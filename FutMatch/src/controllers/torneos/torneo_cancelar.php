<?php

require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Solo se acepta POST."]);
    exit;
}

const ETAPA_CANCELADO_ID = 5;


$torneoId = filter_input(INPUT_POST, 'torneo_id', FILTER_VALIDATE_INT);
$idAdminCancha = $_SESSION['user_id'] ?? 1; // Usamos el ID de sesión o el de prueba 7

if (!$torneoId) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de torneo no válido."]);
    exit;
}

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

try {
 
    $sqlVerificar = "SELECT id_torneo FROM torneos WHERE id_torneo = :torneo_id AND id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if ($stmtVerificar->rowCount() === 0) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Permiso denegado o torneo no encontrado."]);
        exit;
    }

    
    $sql = "UPDATE torneos SET id_etapa = :etapa_cancelado_id, fecha_actualizacion = NOW() WHERE id_torneo = :torneo_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':etapa_cancelado_id', ETAPA_CANCELADO_ID, PDO::PARAM_INT);
    $stmt->bindParam(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmt->execute();

    
    
    echo json_encode(["status" => "success", "message" => "Torneo cancelado exitosamente."]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Error al cancelar el torneo: " . $e->getMessage()]);
}