<?php
require_once __DIR__ . '/../../app/config.php';

header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

try {
    $query = 'SELECT 
        id_tipo_reserva,
        nombre,
        descripcion
    FROM tipos_reserva
    ORDER BY nombre ASC';

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $tiposReserva = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($tiposReserva);
} catch (PDOException $e) {
    error_log("GET_TIPOS_RESERVA ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los tipos de reserva']);
    exit();
}
