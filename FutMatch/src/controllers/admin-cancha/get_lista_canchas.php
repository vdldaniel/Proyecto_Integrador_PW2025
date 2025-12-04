<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json; charset=utf-8");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener el id_admin_cancha del parámetro GET
$id_admin_cancha = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';

if (empty($id_admin_cancha)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username requerido']);
    exit();
}

try {

    $sql = "
        SELECT 
            c.id_cancha,
            c.nombre,
            c.id_estado,
            e.nombre AS estado_nombre
        FROM canchas c
        LEFT JOIN estados_canchas e 
            ON c.id_estado = e.id_estado
        WHERE id_admin_cancha = :id_admin_cancha
        ORDER BY c.nombre ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_admin_cancha', $id_admin_cancha, PDO::PARAM_STR);
    $stmt->execute();

    $canchas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data'   => $canchas
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
