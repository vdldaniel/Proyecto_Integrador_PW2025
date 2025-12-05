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
    $sqlVerificar = "SELECT id_organizador, max_equipos FROM torneos WHERE id_torneo = :id_torneo";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $torneo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado"]);
        exit;
    }

    // Si es admin_cancha, verificar propiedad
    if ($userType === 'admin_cancha' && $torneo['id_organizador'] != $userId) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "No tienes permiso para ver este torneo"]);
        exit;
    }

    // Obtener partidos del torneo con información de equipos
    $sqlPartidos = "SELECT 
                        p.id_partido,
                        pt.id_fase,
                        ft.nombre as fase_nombre,
                        pt.orden_en_fase,
                        pt.id_equipo_A,
                        pt.id_equipo_B,
                        eA.nombre as equipo_A_nombre,
                        eB.nombre as equipo_B_nombre,
                        p.goles_equipo_A,
                        p.goles_equipo_B,
                        p.id_reserva,
                        r.fecha as fecha_partido,
                        r.hora_inicio,
                        c.nombre as cancha_nombre
                    FROM partidos_torneos pt
                    INNER JOIN partidos p ON pt.id_partido = p.id_partido
                    INNER JOIN fases_torneo ft ON pt.id_fase = ft.id_fase
                    LEFT JOIN equipos eA ON pt.id_equipo_A = eA.id_equipo
                    LEFT JOIN equipos eB ON pt.id_equipo_B = eB.id_equipo
                    LEFT JOIN reservas r ON p.id_reserva = r.id_reserva
                    LEFT JOIN canchas c ON r.id_cancha = c.id_cancha
                    WHERE pt.id_torneo = :id_torneo
                    ORDER BY pt.id_fase DESC, pt.orden_en_fase ASC";

    $stmtPartidos = $conn->prepare($sqlPartidos);
    $stmtPartidos->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtPartidos->execute();
    $partidos = $stmtPartidos->fetchAll(PDO::FETCH_ASSOC);

    // Organizar partidos por fase
    $partidosPorFase = [];
    foreach ($partidos as $partido) {
        $idFase = $partido['id_fase'];
        if (!isset($partidosPorFase[$idFase])) {
            $partidosPorFase[$idFase] = [
                'fase_nombre' => $partido['fase_nombre'],
                'partidos' => []
            ];
        }
        $partidosPorFase[$idFase]['partidos'][] = $partido;
    }

    echo json_encode([
        "status" => "success",
        "data" => $partidos,
        "max_equipos" => (int)$torneo['max_equipos']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al cargar partidos: " . $e->getMessage()
    ]);
}
