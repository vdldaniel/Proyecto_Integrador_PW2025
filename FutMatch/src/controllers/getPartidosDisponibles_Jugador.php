<?php
// Habilitar display de errores para debugging (QUITAR EN PRODUCCIÓN)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../app/config.php';

// Configurar salida JSON desde el inicio
header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Verificar si hay usuario autenticado
    $id_jugador = null;
    if (isset($_SESSION['user_id'])) {
        $id_jugador = $_SESSION['user_id'];
        error_log("GET_PARTIDOS: id_jugador de sesión: " . $id_jugador);
    } else {
        error_log("GET_PARTIDOS: Modo guest - sin sesión activa");
    }

    // Construir query según si hay usuario autenticado
    if ($id_jugador) {
        // Excluir partidos en los que ya participa el jugador
        $query = 'SELECT * FROM vista_explorar_partidos 
                  WHERE id_partido NOT IN (
                      SELECT id_partido FROM participantes_partidos WHERE id_jugador = :id_jugador
                  )';
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    } else {
        // Modo guest: mostrar todos los partidos disponibles
        $query = 'SELECT * FROM vista_explorar_partidos';
        $stmt = $conn->prepare($query);
    }

    $stmt->execute();
    $partidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("GET_PARTIDOS: Partidos obtenidos: " . count($partidos));

    echo json_encode($partidos);
} catch (PDOException $e) {
    error_log("GET_PARTIDOS ERROR PDO: " . $e->getMessage());
    error_log("GET_PARTIDOS ERROR TRACE: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al obtener partidos',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    error_log("GET_PARTIDOS ERROR GENERAL: " . $e->getMessage());
    error_log("GET_PARTIDOS ERROR TRACE: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error inesperado',
        'message' => $e->getMessage()
    ]);
}
