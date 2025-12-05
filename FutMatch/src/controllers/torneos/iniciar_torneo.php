<?php
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

    // Verificar que el torneo pertenece al organizador y obtener max_equipos
    $sqlVerificar = "SELECT id_torneo, id_etapa, max_equipos, id_organizador FROM torneos WHERE id_torneo = :torneo_id AND id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtVerificar->bindValue(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $torneo = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$torneo) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Torneo no encontrado o sin permisos"]);
        exit;
    }

    // Verificar que esté en etapa 2 (Inscripciones abiertas)
    if ($torneo['id_etapa'] != 2) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Solo se pueden iniciar torneos en etapa de inscripciones abiertas"]);
        exit;
    }

    // Obtener equipos participantes (estado = 3, Aprobado)
    $sqlEquipos = "SELECT id_equipo FROM equipos_torneos WHERE id_torneo = :torneo_id AND id_estado = 3 ORDER BY id_equipo";
    $stmtEquipos = $conn->prepare($sqlEquipos);
    $stmtEquipos->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtEquipos->execute();
    $equipos = $stmtEquipos->fetchAll(PDO::FETCH_COLUMN);

    // Verificar que hay equipos suficientes
    if (count($equipos) < 2) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Debe haber al menos 2 equipos aceptados para iniciar el torneo"]);
        exit;
    }

    // Calcular fase inicial y cantidad de partidos según max_equipos
    $maxEquipos = (int)$torneo['max_equipos'];
    $idFase = 0;
    $cantPartidos = 0;

    switch ($maxEquipos) {
        case 16:
            $idFase = 5; // Octavos de final
            $cantPartidos = 8;
            break;
        case 8:
            $idFase = 4; // Cuartos de final
            $cantPartidos = 4;
            break;
        case 4:
            $idFase = 3; // Semifinal
            $cantPartidos = 2;
            break;
        default:
            $conn->rollBack();
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Formato de torneo no válido (max_equipos debe ser 4, 8 o 16)"]);
            exit;
    }

    // Verificar que hay exactamente max_equipos participando
    if (count($equipos) != $maxEquipos) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "El torneo requiere exactamente {$maxEquipos} equipos. Actualmente hay " . count($equipos) . " equipos aceptados."]);
        exit;
    }

    // Generar partidos
    for ($i = 0; $i < $cantPartidos; $i++) {
        // Asignar equipos: equipo A es índice par, equipo B es índice impar
        $idEquipoA = $equipos[$i * 2];
        $idEquipoB = $equipos[$i * 2 + 1];

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
        $stmtPartidoTorneo->bindValue(':id_fase', $idFase, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':orden_en_fase', $i + 1, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_equipo_A', $idEquipoA, PDO::PARAM_INT);
        $stmtPartidoTorneo->bindValue(':id_equipo_B', $idEquipoB, PDO::PARAM_INT);
        $stmtPartidoTorneo->execute();
    }

    // Actualizar etapa a 3 (En curso)
    $sqlUpdate = "UPDATE torneos SET id_etapa = 3 WHERE id_torneo = :torneo_id";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bindValue(':torneo_id', $torneoId, PDO::PARAM_INT);
    $stmtUpdate->execute();

    // Confirmar transacción
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Torneo iniciado correctamente. Se generaron {$cantPartidos} partidos."
    ]);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al iniciar torneo: " . $e->getMessage()
    ]);
}
