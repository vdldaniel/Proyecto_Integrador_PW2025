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

// Obtener datos JSON
$input = json_decode(file_get_contents('php://input'), true);

// Validar datos requeridos
$idPartido = $input['id_partido'] ?? null;
$idTorneo = $input['id_torneo'] ?? null;
$idCancha = $input['id_cancha'] ?? null;
$fecha = $input['fecha'] ?? null;
$horaInicio = $input['hora_inicio'] ?? null;
$horaFin = $input['hora_fin'] ?? null;
$titulo = $input['titulo'] ?? null;
$descripcion = $input['descripcion'] ?? null;

if (!$idPartido || !$idTorneo || !$idCancha || !$fecha || !$horaInicio || !$horaFin || !$titulo) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Faltan datos requeridos"]);
    exit;
}

try {
    // Iniciar transacción
    $conn->beginTransaction();

    // Verificar que el partido pertenece al torneo y que el torneo es del admin
    $sqlVerificar = "SELECT pt.id_partido, t.id_organizador, t.fecha_inicio, t.fecha_fin
                     FROM partidos_torneos pt
                     INNER JOIN torneos t ON pt.id_torneo = t.id_torneo
                     WHERE pt.id_partido = :id_partido 
                     AND pt.id_torneo = :id_torneo 
                     AND t.id_organizador = :id_organizador";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindValue(':id_partido', $idPartido, PDO::PARAM_INT);
    $stmtVerificar->bindValue(':id_torneo', $idTorneo, PDO::PARAM_INT);
    $stmtVerificar->bindValue(':id_organizador', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificar->execute();
    $partido = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$partido) {
        $conn->rollBack();
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "Partido no encontrado o sin permisos"]);
        exit;
    }

    // Verificar que la fecha esté dentro del rango del torneo
    if ($fecha < $partido['fecha_inicio'] || $fecha > $partido['fecha_fin']) {
        $conn->rollBack();
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "La fecha debe estar entre " . $partido['fecha_inicio'] . " y " . $partido['fecha_fin']]);
        exit;
    }

    // Verificar que la cancha pertenece al admin
    $sqlVerificarCancha = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha AND id_admin_cancha = :id_admin";
    $stmtVerificarCancha = $conn->prepare($sqlVerificarCancha);
    $stmtVerificarCancha->bindValue(':id_cancha', $idCancha, PDO::PARAM_INT);
    $stmtVerificarCancha->bindValue(':id_admin', $idAdminCancha, PDO::PARAM_INT);
    $stmtVerificarCancha->execute();

    if ($stmtVerificarCancha->rowCount() === 0) {
        $conn->rollBack();
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "La cancha no pertenece a este administrador"]);
        exit;
    }

    // Crear la reserva
    $sqlReserva = "INSERT INTO reservas (id_cancha, id_tipo_reserva, fecha, fecha_fin, hora_inicio, hora_fin, titulo, descripcion, id_estado, id_creador_usuario, id_titular_jugador, id_titular_externo) 
                   VALUES (:id_cancha, 2, :fecha, :fecha, :hora_inicio, :hora_fin, :titulo, :descripcion, 3, :id_creador, NULL, NULL)";
    $stmtReserva = $conn->prepare($sqlReserva);
    $stmtReserva->bindValue(':id_cancha', $idCancha, PDO::PARAM_INT);
    $stmtReserva->bindValue(':fecha', $fecha, PDO::PARAM_STR);
    $stmtReserva->bindValue(':hora_inicio', $horaInicio, PDO::PARAM_STR);
    $stmtReserva->bindValue(':hora_fin', $horaFin, PDO::PARAM_STR);
    $stmtReserva->bindValue(':titulo', $titulo, PDO::PARAM_STR);
    $stmtReserva->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmtReserva->bindValue(':id_creador', $idAdminCancha, PDO::PARAM_INT);
    $stmtReserva->execute();

    $idReserva = $conn->lastInsertId();

    // Obtener id_tipo_partido de la cancha
    $sqlTipoPartido = "SELECT id_tipo_partido FROM canchas_tipos_partido WHERE id_cancha = :id_cancha LIMIT 1";
    $stmtTipoPartido = $conn->prepare($sqlTipoPartido);
    $stmtTipoPartido->bindValue(':id_cancha', $idCancha, PDO::PARAM_INT);
    $stmtTipoPartido->execute();
    $tipoPartido = $stmtTipoPartido->fetch(PDO::FETCH_ASSOC);

    $idTipoPartido = $tipoPartido ? $tipoPartido['id_tipo_partido'] : null;

    // Actualizar el partido con id_tipo_partido y id_reserva
    $sqlUpdatePartido = "UPDATE partidos 
                         SET id_tipo_partido = :id_tipo_partido, id_reserva = :id_reserva 
                         WHERE id_partido = :id_partido";
    $stmtUpdatePartido = $conn->prepare($sqlUpdatePartido);
    $stmtUpdatePartido->bindValue(':id_tipo_partido', $idTipoPartido, PDO::PARAM_INT);
    $stmtUpdatePartido->bindValue(':id_reserva', $idReserva, PDO::PARAM_INT);
    $stmtUpdatePartido->bindValue(':id_partido', $idPartido, PDO::PARAM_INT);
    $stmtUpdatePartido->execute();

    // Confirmar transacción
    $conn->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Reserva creada exitosamente",
        "id_reserva" => $idReserva
    ]);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al crear reserva: " . $e->getMessage()
    ]);
}
