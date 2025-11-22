<?php
require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

try {
    $stmt = $conn->prepare("SELECT id_tipo_partido, nombre, min_participantes, max_participantes FROM tipos_partido ORDER BY id_tipo_partido ASC");
    $stmt->execute();
    $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $tipos
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
