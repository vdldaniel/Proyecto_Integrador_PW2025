<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

try {
    $sql = "
        SELECT 
            c.id_cancha,
            c.nombre,
            c.descripcion,
            c.id_superficie,
            c.id_estado,
            c.id_tipo AS tipo_cancha,
            d.direccion_completa,
            s.nombre AS superficie_nombre,
            tc.nombre AS tipo_nombre
        FROM canchas c
        LEFT JOIN direcciones d ON c.id_direccion = d.id_direccion
        LEFT JOIN superficies_canchas s ON c.id_superficie = s.id_superficie
        LEFT JOIN tipo_cancha tc ON c.id_tipo = tc.id_tipo
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $canchas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data'   => $canchas
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

