<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_type'], ['admin_cancha', 'jugador'])) {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$userId = $_SESSION['user_id'];
$userType = $_SESSION['user_type'];

// Obtener ID del torneo desde GET
$idTorneo = $_GET['id_torneo'] ?? null;

if (!$idTorneo) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de torneo requerido"]);
    exit;
}

try {
    // Verificar existencia del torneo
    $sqlVerificar = "SELECT id_organizador FROM torneos WHERE id_torneo = :id_torneo";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $verificar = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$verificar) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado"]);
        exit;
    }

    // Si es admin_cancha, verificar propiedad
    if ($userType === 'admin_cancha' && $verificar['id_organizador'] != $userId) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "No tienes permiso para ver este torneo"]);
        exit;
    }

    // Obtener la fase mínima actual del torneo (la más avanzada)
    $sqlFaseActual = "SELECT MIN(pt.id_fase) as fase_actual 
                      FROM partidos_torneos pt 
                      WHERE pt.id_torneo = :id_torneo";
    $stmtFaseActual = $conn->prepare($sqlFaseActual);
    $stmtFaseActual->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtFaseActual->execute();
    $faseActual = $stmtFaseActual->fetchColumn() ?: 999;

    // Consultar equipos del torneo con estadísticas
    $sqlEquipos = "SELECT 
                    e.id_equipo,
                    e.nombre as nombre_equipo,
                    e.foto,
                    CONCAT('" . UPLOADS_EQUIPOS_PATH . "', e.foto) as logo_url,
                    (SELECT COUNT(*) FROM jugadores_equipos WHERE id_equipo = e.id_equipo AND estado_solicitud = 3) as total_integrantes,
                    u.nombre as lider_nombre,
                    u.apellido as lider_apellido,
                    -- Goles a favor (como equipo A)
                    COALESCE((SELECT SUM(p.goles_equipo_A) 
                              FROM partidos_torneos pt 
                              INNER JOIN partidos p ON pt.id_partido = p.id_partido
                              WHERE pt.id_torneo = :id_torneo 
                              AND pt.id_equipo_A = e.id_equipo 
                              AND p.goles_equipo_A IS NOT NULL), 0) +
                    -- Goles a favor (como equipo B)
                    COALESCE((SELECT SUM(p.goles_equipo_B) 
                              FROM partidos_torneos pt 
                              INNER JOIN partidos p ON pt.id_partido = p.id_partido
                              WHERE pt.id_torneo = :id_torneo 
                              AND pt.id_equipo_B = e.id_equipo 
                              AND p.goles_equipo_B IS NOT NULL), 0) as goles_favor,
                    -- Goles en contra (como equipo A)
                    COALESCE((SELECT SUM(p.goles_equipo_B) 
                              FROM partidos_torneos pt 
                              INNER JOIN partidos p ON pt.id_partido = p.id_partido
                              WHERE pt.id_torneo = :id_torneo 
                              AND pt.id_equipo_A = e.id_equipo 
                              AND p.goles_equipo_B IS NOT NULL), 0) +
                    -- Goles en contra (como equipo B)
                    COALESCE((SELECT SUM(p.goles_equipo_A) 
                              FROM partidos_torneos pt 
                              INNER JOIN partidos p ON pt.id_partido = p.id_partido
                              WHERE pt.id_torneo = :id_torneo 
                              AND pt.id_equipo_B = e.id_equipo 
                              AND p.goles_equipo_A IS NOT NULL), 0) as goles_contra,
                    -- Verificar si es ganador, continúa, o fue eliminado
                    CASE 
                        -- Si el torneo está finalizado (no hay partidos en fase >= 2), verificar si ganó la final
                        WHEN NOT EXISTS (SELECT 1 FROM partidos_torneos WHERE id_torneo = :id_torneo AND id_fase >= 2)
                        AND EXISTS (
                            SELECT 1 FROM partidos_torneos pt 
                            INNER JOIN partidos p ON pt.id_partido = p.id_partido
                            WHERE pt.id_torneo = :id_torneo 
                            AND pt.id_fase = 2
                            AND (
                                (pt.id_equipo_A = e.id_equipo AND p.goles_equipo_A > p.goles_equipo_B)
                                OR (pt.id_equipo_B = e.id_equipo AND p.goles_equipo_B > p.goles_equipo_A)
                            )
                        ) THEN 'ganador'
                        -- Si está en la fase actual, continúa
                        WHEN EXISTS (
                            SELECT 1 FROM partidos_torneos pt 
                            WHERE pt.id_torneo = :id_torneo 
                            AND pt.id_fase = :fase_actual
                            AND (pt.id_equipo_A = e.id_equipo OR pt.id_equipo_B = e.id_equipo)
                        ) THEN 'continua'
                        ELSE 'eliminado'
                    END as estado_equipo
                   FROM equipos e
                   INNER JOIN equipos_torneos et ON e.id_equipo = et.id_equipo
                   INNER JOIN usuarios u ON e.id_lider = u.id_usuario
                   WHERE et.id_torneo = :id_torneo 
                   AND et.id_estado = 3
                   ORDER BY estado_equipo ASC, e.nombre ASC";

    $stmtEquipos = $conn->prepare($sqlEquipos);
    $stmtEquipos->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtEquipos->bindValue(':fase_actual', $faseActual, PDO::PARAM_INT);
    $stmtEquipos->execute();
    $equipos = $stmtEquipos->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $equipos
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al cargar equipos: " . $e->getMessage()
    ]);
}
