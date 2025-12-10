<?php
require_once '../../app/config.php';

header("Content-Type: application/json; charset=utf-8");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit();
}

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_equipo']) || !isset($data['id_torneo'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit();
}

$id_equipo = intval($data['id_equipo']);
$id_torneo = intval($data['id_torneo']);
$id_jugador = $_SESSION['user_id'];

try {
    // Verificar que el jugador es el líder del equipo
    $sqlCheck = "SELECT id_lider FROM equipos WHERE id_equipo = :id_equipo";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
    $stmtCheck->execute();
    $equipo = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$equipo || $equipo['id_lider'] != $id_jugador) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Solo el líder del equipo puede inscribir al torneo']);
        exit();
    }

    // Verificar que el equipo no esté ya inscrito
    $sqlCheckInscripcion = "SELECT * FROM equipos_torneos WHERE id_equipo = :id_equipo AND id_torneo = :id_torneo";
    $stmtCheckInscripcion = $conn->prepare($sqlCheckInscripcion);
    $stmtCheckInscripcion->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
    $stmtCheckInscripcion->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmtCheckInscripcion->execute();

    if ($stmtCheckInscripcion->rowCount() > 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'El equipo ya está inscrito en este torneo']);
        exit();
    }

    // Insertar inscripción (estado 1 = Pendiente)
    $sql = "INSERT INTO equipos_torneos (id_equipo, id_torneo, id_estado) VALUES (:id_equipo, :id_torneo, 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Inscripción realizada correctamente. Esperando aprobación del organizador.'
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    error_log("postInscripcionTorneo ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al inscribirse al torneo'
    ], JSON_UNESCAPED_UNICODE);
}
