<?php
require_once '../app/config.php';

header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener el username del parámetro GET
$username = isset($_GET['username']) ? trim($_GET['username']) : '';

if (empty($username)) {
    http_response_code(400);
    echo json_encode(['error' => 'Username requerido']);
    exit();
}

$id_jugador_actual = $_SESSION['user_id'];

try {
    $query = 'SELECT    
        j.id_jugador,
        j.username,
        u.nombre,
        u.apellido,
        j.foto_perfil
    FROM jugadores j
    INNER JOIN usuarios u ON j.id_jugador = u.id_usuario
    WHERE j.username = :username';

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $jugador = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$jugador) {
        http_response_code(404);
        echo json_encode(['error' => 'Usuario no encontrado']);
        exit();
    }

    // Agregar bandera si es el usuario actual
    $jugador['es_usuario_actual'] = ($jugador['id_jugador'] == $id_jugador_actual);

    echo json_encode($jugador);
} catch (PDOException $e) {
    error_log("GET_USUARIOS ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al buscar usuario']);
}
