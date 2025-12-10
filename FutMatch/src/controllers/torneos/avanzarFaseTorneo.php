<?php

/**
 * Controller: Avanzar Fase de Torneo
 * 
 * Avanza el torneo a la siguiente fase, creando los partidos correspondientes
 * con los ganadores de la fase anterior
 * 
 * Método: POST
 * Body JSON: {
 *   torneo_id: number
 * }
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../../app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_cancha') {
    http_response_code(401);
    echo json_encode(["status" => "error", "message" => "No autorizado"]);
    exit;
}

$idAdminCancha = $_SESSION['user_id'];

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
    exit;
}

// Obtener datos
$input = json_decode(file_get_contents('php://input'), true);
$torneoId = $input['torneo_id'] ?? null;

if (!$torneoId) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "ID de torneo requerido"]);
    exit;
}

try {
    // Iniciar transacción
    $conn->beginTransaction();

    // Verificar que el torneo pertenece al organizador y obtener fase actual
    $sqlVerificar = "SELECT t.id_torneo, t.id_etapa, t.max_equipos, t.id_organizador,
                            (SELECT MIN(pt.id_fase) FROM partidos_torneos pt WHERE pt.id_torneo = t.id_torneo) as fase_actual
                     FROM torneos t 
                     WHERE t.id_torneo = :torneo_id AND t.id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtVerificar->bindValue(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $torneo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        $conn->rollBack();
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado o sin permisos"]);
        exit;
    }

    // Verificar que esté en etapa 3 (En curso)
    if ($torneo['id_etapa'] != 3) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Solo se pueden avanzar torneos en curso"]);
        exit;
    }

    $faseActual = (int)$torneo['fase_actual'];

    // Verificar que no esté ya terminado (id_fase = 1 o menor no existe)
    if ($faseActual < 2) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "El torneo ya ha terminado"]);
        exit;
    }

    // Obtener partidos de la fase actual
    $sqlPartidosFase = "SELECT pt.id_partido, pt.orden_en_fase, pt.id_equipo_A, pt.id_equipo_B, 
                               p.goles_equipo_A, p.goles_equipo_B, p.id_reserva
                        FROM partidos_torneos pt
                        INNER JOIN partidos p ON pt.id_partido = p.id_partido
                        WHERE pt.id_torneo = :torneo_id AND pt.id_fase = :id_fase
                        ORDER BY pt.orden_en_fase ASC";
    $stmtPartidosFase = $conn->prepare($sqlPartidosFase);
    $stmtPartidosFase->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtPartidosFase->bindValue(':id_fase', $faseActual, PDO::PARAM_INT);
    $stmtPartidosFase->execute();
    $partidos = $stmtPartidosFase->fetchAll(PDO::FETCH_ASSOC);

    // Validar que todos los partidos tengan reserva y resultados
    $ganadores = [];
    foreach ($partidos as $partido) {
        // Verificar que el partido tenga reserva
        if (!$partido['id_reserva']) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Todos los partidos de la fase actual deben estar programados antes de avanzar"
            ]);
            exit;
        }

        // Verificar que el partido tenga resultados
        if ($partido['goles_equipo_A'] === null || $partido['goles_equipo_B'] === null) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Todos los partidos de la fase actual deben tener resultados registrados antes de avanzar"
            ]);
            exit;
        }

        // Determinar ganador
        $golesA = (int)$partido['goles_equipo_A'];
        $golesB = (int)$partido['goles_equipo_B'];

        if ($golesA === $golesB) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "No puede haber empates en torneos de eliminación directa. Partido {$partido['orden_en_fase']} está empatado."
            ]);
            exit;
        }

        // Agregar ganador en el orden correspondiente
        if ($golesA > $golesB) {
            $ganadores[] = $partido['id_equipo_A'];
        } else {
            $ganadores[] = $partido['id_equipo_B'];
        }
    }

    // Si estamos en la Final (id_fase = 2), finalizar el torneo
    if ($faseActual == 2) {
        // Debe haber exactamente 1 ganador
        if (count($ganadores) !== 1) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Error al determinar el ganador de la final"
            ]);
            exit;
        }

        // Actualizar etapa a 4 (Finalizado)
        $sqlUpdate = "UPDATE torneos SET id_etapa = 4 WHERE id_torneo = :torneo_id";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode([
            "status" => "success",
            "message" => "¡Torneo finalizado! El equipo ganador ha sido determinado.",
            "finalizado" => true,
            "id_equipo_ganador" => $ganadores[0]
        ]);
        exit;
    }

    // Calcular siguiente fase y cantidad de partidos
    $siguienteFase = $faseActual - 1;
    $cantPartidosNuevos = 0;

    switch ($siguienteFase) {
        case 4: // Cuartos de final
            $cantPartidosNuevos = 4;
            break;
        case 3: // Semifinal
            $cantPartidosNuevos = 2;
            break;
        case 2: // Final
            $cantPartidosNuevos = 1;
            break;
        default:
            $conn->rollBack();
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Fase no válida"]);
            exit;
    }

    // Verificar que hay suficientes ganadores
    if (count($ganadores) < $cantPartidosNuevos * 2) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "No hay suficientes equipos para crear la siguiente fase"
        ]);
        exit;
    }

    // Crear partidos de la siguiente fase
    // Los ganadores se emparejan: ganador del partido 1 vs ganador del partido 2, etc.
    for ($i = 0; $i < $cantPartidosNuevos; $i++) {
        $idEquipoA = $ganadores[$i * 2];
        $idEquipoB = $ganadores[$i * 2 + 1];

        // Insertar en tabla partidos
        $sqlPartido = "INSERT INTO partidos (id_anfitrion, id_tipo_partido, abierto, goles_equipo_A, goles_equipo_B, id_reserva) 
                       VALUES (:id_anfitrion, NULL, 0, NULL, NULL, NULL)";
        $stmtPartido = $conn->prepare($sqlPartido);
        $stmtPartido->bindValue(':id_anfitrion', $torneo['id_organizador'], PDO::PARAM_INT);
        $stmtPartido->execute();

        $idPartido = $conn->lastInsertId();

        // Insertar en tabla partidos_torneos
        $sqlPartidoTorneo = "INSERT INTO partidos_torneos (id_partido, id_torneo, id_fase, orden_en_fase, id_equipo_A, id_equipo_B) 
                             VALUES (:id_partido, :id_torneo, :id_fase, :orden_en_fase, :id_equipo_A, :id_equipo_B)";
        $stmtPartidoTorneo = $conn->prepare($sqlPartidoTorneo);
        $stmtPartidoTorneo->bindValue(':id_partido', $idPartido, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_torneo', $torneoId, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_fase', $siguienteFase, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':orden_en_fase', $i + 1, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_equipo_A', $idEquipoA, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_equipo_B', $idEquipoB, PDO::PARAM_INT);
        $stmtPartidoTorneo->execute();
    }

    // Confirmar transacción
    $conn->commit();

    // Obtener nombre de la fase
    $sqlFase = "SELECT nombre FROM fases_torneo WHERE id_fase = :id_fase";
    $stmtFase = $conn->prepare($sqlFase);
    $stmtFase->bindValue(':id_fase', $siguienteFase, PDO::PARAM_INT);
    $stmtFase->execute();
    $nombreFase = $stmtFase->fetchColumn();

    echo json_encode([
        "status" => "success",
        "message" => "Fase avanzada correctamente. Se generaron {$cantPartidosNuevos} partidos para {$nombreFase}.",
        "nueva_fase" => $siguienteFase,
        "nombre_fase" => $nombreFase,
        "partidos_creados" => $cantPartidosNuevos
    ]);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al avanzar fase: " . $e->getMessage()
    ]);
}
