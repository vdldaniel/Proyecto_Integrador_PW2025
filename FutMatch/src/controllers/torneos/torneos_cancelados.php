<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/config.php';

$idAdminCancha = $_SESSION['user_id'] ?? 1;

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

try {
    // OBTENER SOLO TORNEOS CANCELADOS id_etapa = 5 del admin actual

    $sql = "
        SELECT 
            t.id_torneo,
            t.nombre,
            t.fecha_inicio,
            t.fecha_fin
        FROM torneos t
        WHERE t.id_etapa = 5 AND t.id_organizador = :id_organizador
        ORDER BY t.fecha_inicio DESC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "torneos" => $torneos
    ]);
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Error en la consulta: " . $e->getMessage()
    ]);
}
