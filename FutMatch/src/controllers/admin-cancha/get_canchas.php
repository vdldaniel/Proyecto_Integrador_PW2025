<?php
require_once __DIR__ . '/../../app/config.php';

try {
    $sql = "
        SELECT 
            c.id_cancha,
            c.nombre,
            c.descripcion,
            c.id_superficie,
            c.id_estado,
            d.direccion_completa,
            s.nombre AS superficie_nombre
        FROM canchas c
        INNER JOIN direcciones d ON c.id_direccion = d.id_direccion
        INNER JOIN superficies_canchas s ON c.id_superficie = s.id_superficie
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

