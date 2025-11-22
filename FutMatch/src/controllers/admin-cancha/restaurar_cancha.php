<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/config.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id_cancha'];

try {
    // Estado 1 = "Pendiente de verificación"
    $sql = "UPDATE canchas SET id_estado = 1 WHERE id_cancha = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([ ':id' => $id ]);

    echo json_encode(['ok' => true]);

} catch (Exception $e) {
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
