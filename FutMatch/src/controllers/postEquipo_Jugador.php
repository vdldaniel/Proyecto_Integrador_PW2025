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

if (strpos($contentType, 'application/json') !== false) {
    // json en el body
    $rawInput = file_get_contents('php://input');
    $data = json_decode($rawInput, true);
} else {
    // formulario nativo multipart/form-data o application/x-www-form-urlencoded
    $data = $_POST;
}

// Verificar si se recibieron datos
if (empty($data)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode([
        'error' => 'No se recibieron datos',
    ]);
    exit;
}

$id_jugador = $_SESSION['user_id'];

//Inserto nuevo equipo
$queryEquipo =
    'INSERT INTO equipos (id_lider, nombre, foto, abierto, descripcion)
        VALUES (:id, :nombre, :foto, :abierto, :descripcion)';

$stmt = $conn->prepare($queryEquipo);
$stmt->bindParam(':id', $id_jugador);
$stmt->bindParam(':nombre', $data['nombre']);
$stmt->bindParam(':foto', $data['foto']);
$stmt->bindParam(':abierto', $data['abierto']);
$stmt->bindParam(':descripcion', $data['descripcion']);

$stmt->execute();
$equipos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$id_equipo = $conn->lastInsertId();

// Inserto al líder como miembro del equipo
foreach ($data['jugadores'] as $id_jugador) {
    $queryJugadores =
        'INSERT INTO jugadores_equipos (id_jugador, id_equipo)
            VALUES (:id_jugador, :id_equipo)';

    $stmt = $conn->prepare($queryJugadores);
    $stmt->bindParam(':id_jugador', $id_jugador);
    $stmt->bindParam(':id_equipo', $id_equipo);
    $stmt->execute();
}

// header json
header('Content-Type: application/json');
echo json_encode([
    'id' => $id_equipo = $conn->lastInsertId(),
]);
