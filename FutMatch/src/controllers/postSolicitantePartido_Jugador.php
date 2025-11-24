<?php
require_once '../app/config.php';

header('Content-Type: application/json');

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Verificar que el usuario esté autenticado
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode([
            'error' => 'No autenticado',
            'message' => 'Debe iniciar sesión para solicitar unirse a un partido'
        ]);
        exit;
    }

    // Obtener datos del POST
    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['id_partido'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Datos incompletos',
            'message' => 'Falta el id del partido'
        ]);
        exit;
    }

    $id_partido = (int)$input['id_partido'];
    $id_jugador = $_SESSION['user_id']; // id_usuario = id_jugador
    $equipo = isset($input['equipo']) ? $input['equipo'] : null;

    error_log("POST_SOLICITANTE: id_partido={$id_partido}, id_jugador={$id_jugador}");

    // ============================================
    // VALIDACIÓN: Verificar que el jugador no esté ya en el partido
    // ============================================
    $queryCheck = 'SELECT COUNT(*) as existe FROM participantes_partidos 
                   WHERE id_partido = :id_partido AND id_jugador = :id_jugador';
    $stmtCheck = $conn->prepare($queryCheck);
    $stmtCheck->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmtCheck->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmtCheck->execute();
    $resultado = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if ($resultado['existe'] > 0) {
        http_response_code(400);
        echo json_encode([
            'error' => 'Validación fallida',
            'message' => 'Ya estás registrado en este partido'
        ]);
        exit;
    }

    // ============================================
    // INSERTAR PARTICIPANTE CON ROL SOLICITANTE
    // ============================================
    $queryInsert = 'INSERT INTO participantes_partidos (id_partido, id_jugador, id_rol, id_estado, equipo) 
                    VALUES (:id_partido, :id_jugador, 3, 1, :equipo)';

    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
    $stmtInsert->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
    $stmtInsert->bindParam(':equipo', $equipo, PDO::PARAM_STR);
    $stmtInsert->execute();

    $id_participante = $conn->lastInsertId();

    error_log("POST_SOLICITANTE: Participante insertado con id={$id_participante}");

    echo json_encode([
        'success' => true,
        'message' => 'Solicitud enviada correctamente',
        'id_participante' => $id_participante
    ]);
} catch (PDOException $e) {
    error_log("POST_SOLICITANTE ERROR PDO: " . $e->getMessage());
    error_log("POST_SOLICITANTE ERROR TRACE: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al enviar solicitud',
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ]);
} catch (Exception $e) {
    error_log("POST_SOLICITANTE ERROR GENERAL: " . $e->getMessage());
    error_log("POST_SOLICITANTE ERROR TRACE: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode([
        'error' => 'Error inesperado',
        'message' => $e->getMessage()
    ]);
}
