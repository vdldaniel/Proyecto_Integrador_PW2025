<?php

/**
 * Controller: getReservas.php
 * Obtiene todas las reservas de una cancha específica
 * Usa la vista vista_reservas para datos completos
 */

require_once '../../app/config.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Validar que haya sesión iniciada
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'No hay sesión iniciada'
    ]);
    exit;
}

try {
    // Validar que se reciba el id_cancha
    if (!isset($_GET['id_cancha']) || empty($_GET['id_cancha'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Falta el parámetro id_cancha'
        ]);
        exit;
    }

    $id_cancha = intval($_GET['id_cancha']);
    $id_usuario = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'] ?? null;

    // Verificar que la cancha existe
    $stmt = $conn->prepare("SELECT id_admin_cancha FROM canchas WHERE id_cancha = :id_cancha");
    $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmt->execute();
    $cancha = $stmt->fetch();

    if (!$cancha) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Cancha no encontrada'
        ]);
        exit;
    }

    // Si es admin de cancha, verificar permisos
    if ($user_type === 'admin_cancha' && $cancha['id_admin_cancha'] != $id_usuario) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para ver las reservas de esta cancha'
        ]);
        exit;
    }

    // Si es jugador, mostrar solo horarios ocupados (reservas confirmadas)
    if ($user_type === 'jugador') {
        // Solo mostrar fecha, hora_inicio, hora_fin de reservas confirmadas (id_estado = 6)
        // Y datos completos si es titular o creador
        $stmt = $conn->prepare("
            SELECT 
                id_reserva,
                fecha,
                fecha_fin,
                hora_inicio,
                hora_fin,
                id_estado,
                estado_reserva,
                id_cancha,
                CASE 
                    WHEN (id_titular_jugador = :id_usuario OR id_creador_usuario = :id_usuario) 
                    THEN titulo
                    ELSE NULL 
                END as titulo,
                CASE 
                    WHEN (id_titular_jugador = :id_usuario OR id_creador_usuario = :id_usuario) 
                    THEN descripcion
                    ELSE NULL 
                END as descripcion,
                CASE 
                    WHEN (id_titular_jugador = :id_usuario OR id_creador_usuario = :id_usuario) 
                    THEN tipo_reserva
                    ELSE NULL 
                END as tipo_reserva,
                CASE 
                    WHEN (id_titular_jugador = :id_usuario OR id_creador_usuario = :id_usuario) 
                    THEN titular_nombre_completo
                    ELSE NULL 
                END as titular_nombre_completo,
                -- Indicador de propiedad
                CASE 
                    WHEN id_titular_jugador = :id_usuario THEN true
                    ELSE false 
                END as es_mi_reserva
            FROM vista_reservas 
            WHERE id_cancha = :id_cancha 
            AND id_estado = 6
            ORDER BY fecha, hora_inicio
        ");
        $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    } else {
        // Admin ve todas las reservas con todos los datos
        $stmt = $conn->prepare("SELECT * FROM vista_reservas WHERE id_cancha = :id_cancha ORDER BY fecha, hora_inicio");
        $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    }

    $stmt->execute();
    $reservas = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'reservas' => $reservas
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener las reservas',
        'error' => $e->getMessage()
    ]);
}
