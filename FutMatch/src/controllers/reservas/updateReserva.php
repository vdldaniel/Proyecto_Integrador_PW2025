<?php

/**
 * Controller para actualizar reservas
 * Maneja actualización de datos y cambio de estado
 */

// Importar configuración
require_once '../../app/config.php';

// Verificar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar que haya sesión iniciada
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

header('Content-Type: application/json');

// Obtener datos del request
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['id_reserva'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID de reserva no proporcionado']);
    exit;
}

try {
    $id_usuario = $_SESSION['user_id'];

    // Verificar que la reserva pertenece a una cancha del admin o es el creador de la reserva
    $stmtVerificar = $conn->prepare("
        SELECT r.id_reserva, r.id_cancha, r.id_creador_usuario, c.id_admin_cancha
        FROM reservas r
        INNER JOIN canchas c ON r.id_cancha = c.id_cancha
        WHERE r.id_reserva = :id_reserva
    ");
    $stmtVerificar->bindParam(':id_reserva', $input['id_reserva'], PDO::PARAM_INT);
    $stmtVerificar->execute();
    $reserva = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        echo json_encode(['status' => 'error', 'message' => 'Reserva no encontrada']);
        exit;
    }

    // Verificar permisos: el id_admin_cancha O el id_creador_usuario debe coincidir con el user_id de la sesión
    if ($reserva['id_admin_cancha'] != $id_usuario && $reserva['id_creador_usuario'] != $id_usuario) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'No tiene permiso para modificar esta reserva']);
        exit;
    }

    // Determinar qué actualizar según los datos recibidos
    $updates = [];
    $params = ['id_reserva' => $input['id_reserva']];

    // Solo cambio de estado
    if (isset($input['id_estado']) && count($input) == 2) {
        $updates[] = "id_estado = :id_estado";
        $params['id_estado'] = $input['id_estado'];
    } else {
        // Actualización completa de campos
        if (isset($input['fecha'])) {
            $updates[] = "fecha = :fecha";
            $params['fecha'] = $input['fecha'];
        }
        if (isset($input['fecha_fin'])) {
            $updates[] = "fecha_fin = :fecha_fin";
            $params['fecha_fin'] = $input['fecha_fin'];
        }
        if (isset($input['hora_inicio'])) {
            $updates[] = "hora_inicio = :hora_inicio";
            $params['hora_inicio'] = $input['hora_inicio'];
        }
        if (isset($input['hora_fin'])) {
            $updates[] = "hora_fin = :hora_fin";
            $params['hora_fin'] = $input['hora_fin'];
        }
        if (isset($input['id_tipo_reserva'])) {
            $updates[] = "id_tipo_reserva = :id_tipo_reserva";
            $params['id_tipo_reserva'] = $input['id_tipo_reserva'];
        }
        if (isset($input['titulo'])) {
            $updates[] = "titulo = :titulo";
            $params['titulo'] = $input['titulo'];
        }
        if (isset($input['descripcion'])) {
            $updates[] = "descripcion = :descripcion";
            $params['descripcion'] = $input['descripcion'];
        }
    }

    if (empty($updates)) {
        echo json_encode(['status' => 'error', 'message' => 'No hay campos para actualizar']);
        exit;
    }

    // Construir y ejecutar query
    $sql = "UPDATE reservas SET " . implode(', ', $updates) . " WHERE id_reserva = :id_reserva";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }

    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'message' => 'Reserva actualizada correctamente',
        'data' => ['id_reserva' => $input['id_reserva']]
    ]);
} catch (PDOException $e) {
    error_log("Error en updateReserva.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Error en el servidor: ' . $e->getMessage()]);
}
