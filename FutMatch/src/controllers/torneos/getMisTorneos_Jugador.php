<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

try {
    $id_jugador = $_SESSION['user_id'];

    // Filtro opcional para historial (finalizados y cancelados)
    $filtroHistorial = isset($_GET['historial']) ? filter_var($_GET['historial'], FILTER_VALIDATE_BOOLEAN) : false;

    $sql = "
    SELECT 
        t.id_torneo,
        t.nombre,
        t.id_organizador,
        t.fecha_inicio,
        t.fecha_fin,
        t.fin_estimativo,
        t.descripcion,
        t.max_equipos,
        t.id_etapa,
        et.nombre AS etapa,
        etn.id_estado AS id_estado_equipo,
        es.nombre AS estado_solicitud,
        (SELECT COUNT(te.id_equipo) 
            FROM equipos_torneos te 
            WHERE te.id_torneo = t.id_torneo AND te.id_estado = 3) AS total_equipos
    FROM torneos t
    JOIN equipos_torneos etn ON t.id_torneo = etn.id_torneo
    JOIN etapas_torneo et ON t.id_etapa = et.id_etapa
    JOIN estados_solicitudes es ON etn.id_estado = es.id_estado
    WHERE etn.id_equipo IN (
        SELECT je.id_equipo 
        FROM jugadores_equipos je 
        WHERE je.id_jugador = :id_jugador AND je.estado_solicitud = 3
    )";

    // Si es historial, filtrar solo finalizados (4) y cancelados (5) con aprobados (id_estado=3)
    if ($filtroHistorial) {
        $sql .= " AND t.id_etapa IN (4, 5) AND etn.id_estado = 3";
    } else {
        // Si NO es historial, excluir finalizados y cancelados
        $sql .= " AND t.id_etapa NOT IN (4, 5)";
    }

    $sql .= " ORDER BY t.fecha_inicio DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmt->execute();

    $torneos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'success',
        'data' => $torneos
    ]);
} catch (PDOException $e) {
    error_log("GET_MIS_TORNEOS_JUGADOR ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener torneos'
    ]);
}
