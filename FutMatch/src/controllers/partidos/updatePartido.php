<?php
require_once '../../app/config.php';

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

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$id_partido = isset($data['id_partido']) ? intval($data['id_partido']) : 0;
$accion = isset($data['accion']) ? trim($data['accion']) : '';

// Campos opcionales según la acción
$abierto = isset($data['abierto']) ? intval($data['abierto']) : null;
$id_jugador = isset($data['id_jugador']) ? intval($data['id_jugador']) : null;
$id_participante = isset($data['id_participante']) ? intval($data['id_participante']) : null;
$id_estado = isset($data['id_estado']) ? intval($data['id_estado']) : null;
$equipo = isset($data['equipo']) ? intval($data['equipo']) : null;

// Validar campos requeridos
if ($id_partido <= 0 || empty($accion)) {
    http_response_code(400);
    echo json_encode(['error' => 'Faltan campos requeridos']);
    exit();
}

$id_usuario_actual = $_SESSION['user_id'];

try {
    // Caso especial: cancelar_participacion no requiere ser anfitrión
    if ($accion === 'cancelar_participacion') {
        // Verificar que el usuario actual sea el participante
        $queryVerificarParticipante = 'SELECT id_participante 
                                       FROM participantes_partidos 
                                       WHERE id_partido = :id_partido 
                                       AND id_jugador = :id_usuario';

        $stmtVerificar = $conn->prepare($queryVerificarParticipante);
        $stmtVerificar->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':id_usuario', $id_usuario_actual, PDO::PARAM_INT);
        $stmtVerificar->execute();

        $participante = $stmtVerificar->fetch();

        if (!$participante) {
            http_response_code(403);
            echo json_encode(['error' => 'No eres participante de este partido']);
            exit();
        }

        // Cambiar estado a cancelado (5)
        $queryUpdate = 'UPDATE participantes_partidos 
                       SET id_estado = 5 
                       WHERE id_partido = :id_partido 
                       AND id_jugador = :id_usuario 
                       AND id_rol != 1';

        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_usuario', $id_usuario_actual, PDO::PARAM_INT);
        $stmtUpdate->execute();

        if ($stmtUpdate->rowCount() === 0) {
            http_response_code(403);
            echo json_encode(['error' => 'No puedes cancelar tu participación (podrías ser el anfitrión)']);
            exit();
        }

        $mensaje = 'Participación cancelada';
    } else {
        // Para todas las demás acciones, verificar que el usuario sea el anfitrión
        $queryVerificar = 'SELECT pp.id_partido 
                           FROM participantes_partidos pp 
                           WHERE pp.id_partido = :id_partido 
                           AND pp.id_jugador = :id_usuario 
                           AND pp.id_rol = 1';

        $stmtVerificar = $conn->prepare($queryVerificar);
        $stmtVerificar->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
        $stmtVerificar->bindParam(':id_usuario', $id_usuario_actual, PDO::PARAM_INT);
        $stmtVerificar->execute();

        if (!$stmtVerificar->fetch()) {
            http_response_code(403);
            echo json_encode(['error' => 'No tienes permiso para modificar este partido']);
            exit();
        }

        // Ejecutar las acciones que requieren ser anfitrión
        switch ($accion) {
            case 'toggle_convocatoria':
                if ($abierto === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Campo "abierto" requerido']);
                    exit();
                }

                $queryUpdate = 'UPDATE partidos SET abierto = :abierto WHERE id_partido = :id_partido';
                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':abierto', $abierto, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $mensaje = $abierto ? 'Convocatoria abierta' : 'Convocatoria cerrada';
                break;

            case 'aceptar_solicitante':
                if ($id_jugador === null || $equipo === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Campos "id_jugador" y "equipo" requeridos']);
                    exit();
                }

                // Cambiar estado a confirmado (3) y asignar equipo
                $queryUpdate = 'UPDATE participantes_partidos 
                           SET id_estado = 3, equipo = :equipo 
                           WHERE id_partido = :id_partido 
                           AND id_jugador = :id_jugador 
                           AND id_rol = 3';

                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':equipo', $equipo, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $mensaje = 'Solicitante aceptado';
                break;

            case 'rechazar_solicitante':
                if ($id_jugador === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Campo "id_jugador" requerido']);
                    exit();
                }

                // Cambiar estado a rechazado (2)
                $queryUpdate = 'UPDATE participantes_partidos 
                           SET id_estado = 2 
                           WHERE id_partido = :id_partido 
                           AND id_jugador = :id_jugador 
                           AND id_rol = 3';

                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_jugador', $id_jugador, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $mensaje = 'Solicitante rechazado';
                break;

            case 'eliminar_participante':
                if ($id_participante === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Campo "id_participante" requerido']);
                    exit();
                }

                // Eliminar participante (solo si no es anfitrión)
                $queryDelete = 'DELETE FROM participantes_partidos 
                           WHERE id_partido = :id_partido 
                           AND id_participante = :id_participante 
                           AND id_rol != 1';

                $stmtDelete = $conn->prepare($queryDelete);
                $stmtDelete->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
                $stmtDelete->bindParam(':id_participante', $id_participante, PDO::PARAM_INT);
                $stmtDelete->execute();

                $mensaje = 'Participante eliminado';
                break;

            case 'cambiar_equipo':
                if ($id_participante === null || $equipo === null) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Campos "id_participante" y "equipo" requeridos']);
                    exit();
                }

                $queryUpdate = 'UPDATE participantes_partidos 
                           SET equipo = :equipo 
                           WHERE id_partido = :id_partido 
                           AND id_participante = :id_participante';

                $stmtUpdate = $conn->prepare($queryUpdate);
                $stmtUpdate->bindParam(':equipo', $equipo, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_partido', $id_partido, PDO::PARAM_INT);
                $stmtUpdate->bindParam(':id_participante', $id_participante, PDO::PARAM_INT);
                $stmtUpdate->execute();

                $mensaje = 'Equipo actualizado';
                break;

            default:
                http_response_code(400);
                echo json_encode(['error' => 'Acción no válida']);
                exit();
        }
    }

    echo json_encode([
        'success' => true,
        'message' => $mensaje
    ]);
} catch (PDOException $e) {
    error_log("UPDATE_PARTIDO ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar partido']);
}
