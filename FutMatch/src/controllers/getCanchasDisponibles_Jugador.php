<?php
require_once '../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// No es necesario autenticar porque se puede ver desde modo "Guest"
/*
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}
*/

$query = 'SELECT * FROM vista_explorar_canchas';

$stmt = $conn->prepare($query);
$stmt->execute();
$canchas = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($canchas);
