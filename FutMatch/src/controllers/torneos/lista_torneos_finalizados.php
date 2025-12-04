<?php


require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json; charset=UTF-8");

$idAdminCancha = $_SESSION['user_id'] ?? 1; 

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

try {

    $sql = " SELECT 
            t.id_torneo,
            t.nombre,
            t.fecha_inicio,
            t.fecha_fin
        FROM torneos t
        WHERE t.id_etapa = 4  
        ORDER BY t.fecha_inicio DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $torneos
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);

}

exit;