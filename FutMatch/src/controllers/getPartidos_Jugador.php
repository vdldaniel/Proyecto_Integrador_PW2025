<?php
require_once '../app/config.php';

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

// Obtener el id_jugador desde la sesión
$id_jugador = $_SESSION['user_id']; // Asumiendo que el id_usuario es igual al id_jugador

$query = 'SELECT * FROM vista_partidos_jugador WHERE id_jugador = :id ORDER BY fecha_partido ASC';

$stmt = $conn->prepare($query);
$stmt->execute(['id' => $id_jugador]);
$partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($partidos);
