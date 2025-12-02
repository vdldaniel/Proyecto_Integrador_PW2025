<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_admin_cancha = $_SESSION['user_id'];

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$id_cancha = isset($data['id_cancha']) ? intval($data['id_cancha']) : null;
$politicas_reservas = isset($data['politicas_reservas']) ? trim($data['politicas_reservas']) : null;

if (empty($id_cancha)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cancha requerido']);
    exit();
}

try {
    // Verificar que la cancha pertenece al admin
    $sqlVerificar = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha AND id_admin_cancha = :id_admin_cancha";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_admin_cancha', $id_admin_cancha, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if (!$stmtVerificar->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permiso para modificar esta cancha']);
        exit();
    }

    // Actualizar políticas de reservas
    $sql = "UPDATE canchas SET politicas_reservas = :politicas_reservas WHERE id_cancha = :id_cancha";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':politicas_reservas', $politicas_reservas, PDO::PARAM_STR);
    $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Políticas de reservas actualizadas exitosamente'
    ]);
} catch (PDOException $e) {
    error_log("UPDATE_POLITICAS_CANCHA - ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar políticas de reservas']);
}
