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


$id_jugador = $_SESSION['user_id'];
$query =
    'SELECT * FROM vista_equipos_jugador 
    WHERE id_jugador = :id 
    ORDER BY nombre_equipo ASC';

$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id_jugador);
$stmt->execute();
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($equipos);
