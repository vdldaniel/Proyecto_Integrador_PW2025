<?php
require_once '../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

try {
    $id_jugador = $_SESSION['user_id'];
    $filtrarSolicitudes = isset($_GET['filtrar_solicitudes'])
        ? filter_var($_GET['filtrar_solicitudes'], FILTER_VALIDATE_BOOLEAN)
        : false;
    $esLider = isset($_GET['es_lider'])
        ? filter_var($_GET['es_lider'], FILTER_VALIDATE_BOOLEAN)
        : false;

    $query = '
    SELECT * 
    FROM vista_equipos_jugador
    WHERE id_jugador = :id';

    if ($filtrarSolicitudes) {
        $query .= ' AND estado_solicitud = 1';
    }

    if ($esLider) {
        $query .= ' AND id_lider = :id';
    }

    $query .= ' ORDER BY nombre_equipo ASC';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id_jugador);
    $stmt->execute();
    $equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($equipos);
} catch (PDOException $e) {
    error_log("GET_EQUIPOS_JUGADOR ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener equipos']);
    exit();
}
