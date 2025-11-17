<?php
require_once '../app/config.php';


// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* PERFIL JUGADOR
vista_perfil_jugador

PERFIL CANCHA
vista_perfil_cancha
*/

// no requiere iniciar sesión
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$query = '';

try {

    if (isset($_GET['tipo']) && $_GET['tipo'] === 'cancha') {
        // Perfil cancha
        $id_cancha = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id_cancha <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de cancha inválido']);
            exit();
        }

        $query = 'SELECT * FROM vista_perfil_cancha WHERE id_cancha = :id';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id_cancha, PDO::PARAM_INT);
    } else {
        // Perfil jugador
        $id_jugador = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($id_jugador <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de jugador inválido']);
            exit();
        }

        $query = 'SELECT * FROM vista_perfil_jugador WHERE id_jugador = :id';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id_jugador, PDO::PARAM_INT);
    }

    $stmt->execute();
    $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$perfil) {
        http_response_code(404);
        echo json_encode(['error' => 'Perfil no encontrado']);
        exit();
    }
} catch (PDOException $e) {
    error_log("GET_INFO_PERFIL ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al buscar perfil']);
    exit();
};

header('Content-Type: application/json');
echo json_encode($perfil);
