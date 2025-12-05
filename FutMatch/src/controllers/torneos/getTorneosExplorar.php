<?php
require_once '../../app/config.php';

header("Content-Type: application/json; charset=utf-8");

try {
    $sql = "
        SELECT 
            t.id_torneo,
            t.id_organizador,
            t.nombre,
            t.fecha_inicio,
            t.fecha_fin,
            t.id_etapa,
            e.nombre AS etapa_nombre,
            t.descripcion,
            t.max_equipos,
            t.fin_estimativo,
            (
                SELECT COUNT(te.id_equipo) 
                FROM equipos_torneos te 
                WHERE te.id_torneo = t.id_torneo AND te.id_estado = 3
            ) AS total_equipos
        FROM torneos t 
        LEFT JOIN etapas_torneo e ON t.id_etapa = e.id_etapa
        WHERE t.id_etapa = 2
        ORDER BY t.fecha_inicio ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $torneos
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    error_log("getTorneosExplorar ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener torneos'
    ], JSON_UNESCAPED_UNICODE);
}
