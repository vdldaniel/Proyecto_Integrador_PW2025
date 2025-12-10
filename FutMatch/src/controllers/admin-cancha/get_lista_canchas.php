<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json; charset=utf-8");

// Obtener el id_admin_cancha del parámetro GET
$id_admin_cancha = isset($_GET['id_admin_cancha']) ? intval($_GET['id_admin_cancha']) : 0;

if (empty($id_admin_cancha)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'id_admin_cancha requerido'
    ], JSON_UNESCAPED_UNICODE);
    exit();
}

try {

    $sql = "
        SELECT 
            c.id_cancha,
            c.nombre,
            c.id_estado,
            e.nombre AS estado_nombre,
            d.direccion_completa,
            d.latitud,
            d.longitud,
            d.pais,
            d.localidad,
            d.provincia
        FROM canchas c
        LEFT JOIN estados_canchas e 
            ON c.id_estado = e.id_estado
        LEFT JOIN direcciones d 
            ON c.id_direccion = d.id_direccion
        WHERE c.id_admin_cancha = :id_admin_cancha
        ORDER BY c.nombre ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_admin_cancha', $id_admin_cancha, PDO::PARAM_INT);
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
