<?php
require_once '../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Establecer header JSON
header('Content-Type: application/json');

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

// Obtener datos del POST
$id_jugador = $_SESSION['user_id'];
$id_equipo = $_POST['id_equipo'] ?? null;
$accion = $_POST['accion'] ?? null;

error_log("POST recibido: id_equipo=$id_equipo, accion=$accion");

// aceptar / rechazar solicitud
if ($accion === 'aceptar_solicitud' || $accion === 'rechazar_solicitud') {
    if ($accion == 'aceptar_solicitud') {
        $nuevo_estado = 3; // aceptado
    } else if ($accion == 'rechazar_solicitud') {
        $nuevo_estado = 4; // rechazado
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
        exit();
    }

    try {
        $queryActualizar = 'UPDATE jugadores_equipos 
                            SET estado_solicitud = :estado_solicitud 
                            WHERE id_equipo = :id_equipo AND id_jugador = :id_jugador';
        $stmtActualizar = $conn->prepare($queryActualizar);
        $stmtActualizar->bindParam(':estado_solicitud', $nuevo_estado);
        $stmtActualizar->bindParam(':id_equipo', $id_equipo);
        $stmtActualizar->bindParam(':id_jugador', $id_jugador);
        $stmtActualizar->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Solicitud ' . ($nuevo_estado == 3 ? 'aceptada' : 'rechazada') . ' exitosamente'
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al actualizar la solicitud', 'details' => $e->getMessage()]);
    }
    exit();
}
