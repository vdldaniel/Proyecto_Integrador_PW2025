<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json; charset=utf-8");

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
        ORDER BY c.nombre ASC
    ";

    $stmt = $conn->prepare($sql);
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
