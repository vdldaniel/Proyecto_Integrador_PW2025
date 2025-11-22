<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id_cancha'];
$fecha = $data['fecha_cierre'];
$indefinido = $data['indefinido'];
$mensaje = $data['mensaje'];

try {
    $sql = "UPDATE canchas 
            SET cerrada = 1,
                fecha_reapertura = :fecha,
                cierre_indefinido = :indef,
                mensaje_cierre = :mensaje
            WHERE id_cancha = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':fecha' => $fecha,
        ':indef' => $indefinido,
        ':mensaje' => $mensaje,
        ':id' => $id
    ]);

    echo json_encode(['ok' => true]);
} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
