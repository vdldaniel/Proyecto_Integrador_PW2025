<?php

/**
 * Controller: getReservaDetalle.php
 * Obtiene el detalle completo de una reserva específica
 * Usa la vista vista_reserva_detalle para datos completos
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
    // Validar que se reciba el id_reserva
    if (!isset($_GET['id_reserva']) || empty($_GET['id_reserva'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Falta el parámetro id_reserva'
        ]);
        exit;
    }

    $id_reserva = intval($_GET['id_reserva']);
    $id_usuario = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'] ?? null;

    // Obtener el detalle de la reserva desde la vista
    $stmt = $conn->prepare("SELECT * FROM vista_reserva_detalle WHERE id_reserva = :id_reserva");
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt->execute();
    $reserva = $stmt->fetch();

    if (!$reserva) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Reserva no encontrada'
        ]);
        exit;
    }

    // Verificar permisos según tipo de usuario
    if ($user_type === 'admin_cancha') {
        // Admin: verificar que la cancha le pertenece
        $stmt = $conn->prepare("SELECT id_admin_cancha FROM canchas WHERE id_cancha = :id_cancha");
        $stmt->bindParam(':id_cancha', $reserva['id_cancha'], PDO::PARAM_INT);
        $stmt->execute();
        $cancha = $stmt->fetch();

        if (!$cancha || $cancha['id_admin_cancha'] != $id_usuario) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para ver esta reserva'
            ]);
            exit;
        }
    } elseif ($user_type === 'jugador') {
        // Jugador: solo puede ver si es titular o creador
        if ($reserva['id_titular_jugador'] != $id_usuario && $reserva['id_creador_usuario'] != $id_usuario) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'No tienes permisos para ver esta reserva'
            ]);
            exit;
        }

        // Verificar que la reserva está confirmada (id_estado = 6)
        if ($reserva['id_estado'] != 6) {
            http_response_code(403);
            echo json_encode([
                'success' => false,
                'message' => 'Solo puedes ver reservas confirmadas'
            ]);
            exit;
        }
    }

    echo json_encode([
        'success' => true,
        'reserva' => $reserva
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener el detalle de la reserva',
        'error' => $e->getMessage()
    ]);
}
