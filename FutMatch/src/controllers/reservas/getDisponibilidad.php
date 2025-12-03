<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener el id_cancha desde GET
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

    // Obtener reservas confirmadas de la cancha
    $sql = "SELECT 
                fecha,
                fecha_fin,
                hora_inicio,
                hora_fin,
                id_estado
            FROM vista_reservas
            WHERE id_cancha = :id_cancha
            AND id_estado = 3
            ORDER BY fecha, hora_inicio";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmt->execute();

    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $reservas
    ]);
} catch (PDOException $e) {
    error_log("GET_DISPONIBILIDAD - ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener disponibilidad de la cancha']);
}
