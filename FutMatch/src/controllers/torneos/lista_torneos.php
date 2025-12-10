<?php

require_once __DIR__ . '/../../app/config.php';
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido. Solo se acepta GET."]);
    exit;
}


$idAdminCancha = $_SESSION['user_id'] ?? 1;

if (!$idAdminCancha) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "Usuario no autenticado."]);
    exit;
}

try {

    $sql = "SELECT 
                t.id_torneo, 
                t.nombre, 
                t.fecha_inicio, 
                t.id_etapa, 
                t.descripcion,
                t.cierre_inscripciones,
                e.nombre AS etapa_nombre
            FROM 
                torneos t
            LEFT JOIN
                etapas_torneo e ON t.id_etapa = e.id_etapa
            WHERE 
                t.id_organizador = :id_organizador
            ORDER BY 
                t.fecha_inicio DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmt->execute();

    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "torneos" => $torneos]);
} catch (Exception $e) {
    http_response_code(500);

    echo json_encode(["status" => "error", "message" => "Error al obtener torneos: " . $e->getMessage()]);
}
