<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación y rol
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_cancha') {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

try {
    // Leer datos JSON
    $input = json_decode(file_get_contents('php://input'), true);

    $id_torneo = isset($input['id_torneo']) ? intval($input['id_torneo']) : 0;
    $id_equipo = isset($input['id_equipo']) ? intval($input['id_equipo']) : 0;
    $accion = isset($input['accion']) ? $input['accion'] : ''; // 'aceptar', 'rechazar', 'cancelar'

    if ($id_torneo === 0 || $id_equipo === 0 || empty($accion)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parámetros inválidos: torneo=' . $id_torneo . ', equipo=' . $id_equipo . ', accion=' . $accion]);
        exit();
    }

    // Verificar que el torneo pertenece al admin_cancha actual
    $stmt = $conn->prepare("SELECT id_organizador FROM torneos WHERE id_torneo = :id_torneo");
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->execute();
    $torneo = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$torneo || $torneo['id_organizador'] != $_SESSION['user_id']) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No tienes permisos para modificar este torneo']);
        exit();
    }

    // Determinar el nuevo estado según la acción
    $nuevo_estado = 0;
    $mensaje_exito = '';

    switch ($accion) {
        case 'aceptar':
            $nuevo_estado = 3; // Aprobado
            $mensaje_exito = 'Solicitud aceptada correctamente';
            break;
        case 'rechazar':
            $nuevo_estado = 2; // Rechazado
            $mensaje_exito = 'Solicitud rechazada correctamente';
            break;
        case 'cancelar':
            $nuevo_estado = 4; // Cancelado (por admin después de aprobar)
            $mensaje_exito = 'Participación cancelada correctamente';
            break;
        default:
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
            exit();
    }

    // Actualizar el estado en equipos_torneos
    $stmt = $conn->prepare("
        UPDATE equipos_torneos 
        SET id_estado = :nuevo_estado 
        WHERE id_torneo = :id_torneo AND id_equipo = :id_equipo
    ");
    $stmt->bindParam(':nuevo_estado', $nuevo_estado, PDO::PARAM_INT);
    $stmt->bindParam(':id_torneo', $id_torneo, PDO::PARAM_INT);
    $stmt->bindParam(':id_equipo', $id_equipo, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => $mensaje_exito
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'No se encontró la solicitud o ya fue procesada'
        ]);
    }
} catch (PDOException $e) {
    error_log("UPDATE_SOLICITUD_TORNEO ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al actualizar la solicitud'
    ]);
}
