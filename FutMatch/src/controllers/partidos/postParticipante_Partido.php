<?php
require_once '../../app/config.php';

header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$id_partido = isset($data['id_partido']) ? intval($data['id_partido']) : 0;
$id_jugador = isset($data['id_jugador']) ? intval($data['id_jugador']) : null;
$nombre_invitado = isset($data['nombre_invitado']) ? trim($data['nombre_invitado']) : null;
$equipo = isset($data['equipo']) ? intval($data['equipo']) : 0;

// Validar campos requeridos
if ($id_partido <= 0 || $equipo <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos requeridos']);
    exit();
}

// Validar que sea jugador O nombre_invitado
if (!$id_jugador && !$nombre_invitado) {
    http_response_code(400);
    echo json_encode(['error' => 'Debe proporcionar id_jugador o nombre_invitado']);
    exit();
}

$id_usuario_actual = $_SESSION['user_id'];

try {
    // Verificar que el usuario actual sea el anfitrión del partido
    $queryVerificar = 'SELECT pp.id_partido 
                       FROM participantes_partidos pp 
                       WHERE pp.id_partido = :id_partido 
                       AND pp.id_jugador = :id_usuario 
                       AND pp.id_rol = 1';

    $stmtVerificar = $conn->prepare($queryVerificar);
    $stmtVerificar->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_usuario', $id_usuario_actual, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if (!$stmtVerificar->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permiso para agregar participantes a este partido']);
        exit();
    }

    // Insertar participante invitado (id_rol=2, id_estado=3 confirmado)
    $queryInsert = 'INSERT INTO participantes_partidos (id_partido, id_jugador, nombre_invitado, id_rol, id_estado, equipo) 
                    VALUES (:id_partido, :id_jugador, :nombre_invitado, 2, 3, :equipo)';

    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmtInsert->bindParam(':equipo', $equipo, PDO::PARAM_INT);

    if ($id_jugador) {
        $stmtInsert->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
        $stmtInsert->bindValue(':nombre_invitado', null, PDO::PARAM_NULL);
    } else {
        $stmtInsert->bindValue(':id_jugador', null, PDO::PARAM_NULL);
        $stmtInsert->bindParam(':nombre_invitado', $nombre_invitado, PDO::PARAM_STR);
    }

    $stmtInsert->execute();

    echo json_encode([
        'success' => true,
        'message' => 'Participante agregado exitosamente'
    ]);
} catch (PDOException $e) {
    error_log("POST_PARTICIPANTE_PARTIDO ERROR: " . $e->getMessage());

    // Verificar si es error de duplicado
    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(['error' => 'Este participante ya está en el partido']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al agregar participante']);
    }
}
