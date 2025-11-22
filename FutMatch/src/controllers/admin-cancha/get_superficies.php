<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

try {
    $sql = "SELECT id_superficie, nombre FROM superficies_canchas";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
