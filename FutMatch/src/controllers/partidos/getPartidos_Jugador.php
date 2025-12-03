<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

try {
    // Obtener el id_jugador desde GET o sesión
    $id_jugador = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

    $query = 'SELECT * FROM vista_partidos_jugador WHERE id_jugador = :id ORDER BY fecha_partido DESC';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id_jugador, PDO::PARAM_INT);
    $stmt->execute();
    $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($partidos);
} catch (PDOException $e) {
    error_log("GET_PARTIDOS_JUGADOR ERROR: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error al obtener partidos', 'details' => $e->getMessage()]);
    exit();
}
