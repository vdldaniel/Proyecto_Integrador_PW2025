<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Público: Los horarios son visibles para todos (jugadores y admins)
$id_cancha = isset($_GET['id_cancha']) ? intval($_GET['id_cancha']) : null;

if (empty($id_cancha)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cancha requerido']);
    exit();
}

try {
    // Verificar que la cancha existe
    $sqlVerificar = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if (!$stmtVerificar->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Cancha no encontrada']);
        exit();
    }

    // Obtener horarios de la cancha
    $sql = "SELECT 
                hc.id_horario,
                hc.id_cancha,
                hc.id_dia,
                d.nombre AS dia_nombre,
                hc.hora_apertura,
                hc.hora_cierre
            FROM horarios_cancha hc
            INNER JOIN dias_semana d ON hc.id_dia = d.id_dia
            WHERE hc.id_cancha = :id_cancha
            ORDER BY d.id_dia ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmt->execute();

    $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si no hay horarios, devolver array vacío
    echo json_encode([
        'status' => 'success',
        'data' => $horarios
    ]);
} catch (PDOException $e) {
    error_log("GET_HORARIOS_CANCHAS - ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener horarios de la cancha']);
}
