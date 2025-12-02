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

    // Verificar que la cancha pertenece al admin
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

    if ($cancha['id_admin_cancha'] != $id_usuario) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'No tienes permisos para ver las reservas de esta cancha'
        ]);
        exit;
    }

    // Obtener las reservas desde la vista
    $stmt = $conn->prepare("SELECT * FROM vista_reservas WHERE id_cancha = :id_cancha ORDER BY fecha, hora_inicio");
    $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
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
