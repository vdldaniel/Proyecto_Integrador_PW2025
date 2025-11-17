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

// Verificar que se recibió el id_equipo
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de equipo no proporcionado']);
    exit();
}

$id_equipo = $_GET['id'];
$id_jugador = $_SESSION['user_id'];

try {
    // Obtener información del equipo
    $queryEquipo = 'SELECT e.*, 
                           (e.id_lider = :id_jugador) as es_lider
                    FROM equipos e
                    WHERE e.id_equipo = :id_equipo';

    $stmtEquipo = $conn->prepare($queryEquipo);
    $stmtEquipo->bindParam(':id_equipo', $id_equipo);
    $stmtEquipo->bindParam(':id_jugador', $id_jugador);
    $stmtEquipo->execute();
    $equipo = $stmtEquipo->fetch(PDO::FETCH_ASSOC);

    if (!$equipo) {
        http_response_code(404);
        echo json_encode(['error' => 'Equipo no encontrado']);
        exit();
    }

    // Obtener jugadores del equipo (estados 1,2,3: pendiente, en revisión, aceptado)
    $queryJugadores = 'SELECT je.id_jugador, j.username, u.nombre, u.apellido,
                              je.estado_solicitud,
                              (je.id_jugador = :id_lider) as es_lider
                       FROM jugadores_equipos je
                       JOIN jugadores j ON je.id_jugador = j.id_jugador
                       JOIN usuarios u ON j.id_jugador = u.id_usuario
                       WHERE je.id_equipo = :id_equipo
                       AND je.estado_solicitud IN (1, 2, 3)
                       ORDER BY es_lider DESC, je.estado_solicitud ASC, j.username ASC';

    $stmtJugadores = $conn->prepare($queryJugadores);
    $stmtJugadores->bindParam(':id_equipo', $id_equipo);
    $stmtJugadores->bindParam(':id_lider', $equipo['id_lider']);
    $stmtJugadores->execute();
    $jugadores = $stmtJugadores->fetchAll(PDO::FETCH_ASSOC);

    // Convertir campos numéricos explícitamente
    foreach ($jugadores as &$jugador) {
        $jugador['id_jugador'] = (int) $jugador['id_jugador'];
        $jugador['estado_solicitud'] = (int) $jugador['estado_solicitud'];
        $jugador['es_lider'] = (int) $jugador['es_lider'];
    }

    $equipo['jugadores'] = $jugadores;

    header('Content-Type: application/json');
    echo json_encode($equipo);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener información del equipo',
        'details' => $e->getMessage()
    ]);
}
