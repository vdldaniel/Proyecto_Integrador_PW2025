<?php
require_once __DIR__ . '/../../app/config.php';

header("Content-Type: application/json");

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

$id_admin_cancha = $_SESSION['user_id'];

// Obtener datos del POST
$data = json_decode(file_get_contents('php://input'), true);

$id_cancha = isset($data['id_cancha']) ? intval($data['id_cancha']) : null;
$horarios = isset($data['horarios']) ? $data['horarios'] : [];

if (empty($id_cancha)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de cancha requerido']);
    exit();
}

if (!is_array($horarios)) {
    http_response_code(400);
    echo json_encode(['error' => 'Formato de horarios inválido']);
    exit();
}

try {
    // Verificar que la cancha pertenece al admin
    $sqlVerificar = "SELECT id_cancha FROM canchas WHERE id_cancha = :id_cancha AND id_admin_cancha = :id_admin_cancha";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtVerificar->bindParam(':id_admin_cancha', $id_admin_cancha, PDO::PARAM_INT);
    $stmtVerificar->execute();

    if (!$stmtVerificar->fetch()) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permiso para modificar esta cancha']);
        exit();
    }

    $conn->beginTransaction();

    // Eliminar todos los horarios existentes de esta cancha
    $sqlDelete = "DELETE FROM horarios_cancha WHERE id_cancha = :id_cancha";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
    $stmtDelete->execute();

    // Insertar los nuevos horarios
    $sqlInsert = "INSERT INTO horarios_cancha (id_cancha, id_dia, hora_apertura, hora_cierre) 
                  VALUES (:id_cancha, :id_dia, :hora_apertura, :hora_cierre)";
    $stmtInsert = $conn->prepare($sqlInsert);

    foreach ($horarios as $horario) {
        // Validar que el horario tiene el id_dia
        if (!isset($horario['id_dia'])) {
            continue;
        }

        $id_dia = intval($horario['id_dia']);
        $hora_apertura = $horario['hora_apertura'] ?? null;
        $hora_cierre = $horario['hora_cierre'] ?? null;

        // Si ambos horarios son NULL, significa que la cancha está cerrada ese día
        if ($hora_apertura === null && $hora_cierre === null) {
            $stmtInsert->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
            $stmtInsert->bindParam(':id_dia', $id_dia, PDO::PARAM_INT);
            $stmtInsert->bindValue(':hora_apertura', null, PDO::PARAM_NULL);
            $stmtInsert->bindValue(':hora_cierre', null, PDO::PARAM_NULL);
            $stmtInsert->execute();
            continue;
        }

        // Validar que si uno tiene valor, el otro también debe tenerlo
        if (($hora_apertura === null && $hora_cierre !== null) || ($hora_apertura !== null && $hora_cierre === null)) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                'error' => 'Ambos horarios deben tener valor o ambos deben ser NULL',
                'dia_id' => $id_dia
            ]);
            exit();
        }

        // Validar que hora_cierre sea posterior a hora_apertura
        if ($hora_apertura !== null && $hora_cierre !== null && $hora_apertura >= $hora_cierre) {
            $conn->rollBack();
            http_response_code(400);
            echo json_encode([
                'error' => 'La hora de cierre debe ser posterior a la hora de apertura',
                'dia_id' => $id_dia
            ]);
            exit();
        }

        $stmtInsert->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
        $stmtInsert->bindParam(':id_dia', $id_dia, PDO::PARAM_INT);
        $stmtInsert->bindParam(':hora_apertura', $hora_apertura, PDO::PARAM_STR);
        $stmtInsert->bindParam(':hora_cierre', $hora_cierre, PDO::PARAM_STR);
        $stmtInsert->execute();
    }

    $conn->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Horarios actualizados exitosamente'
    ]);
} catch (PDOException $e) {
    $conn->rollBack();
    error_log("UPDATE_HORARIOS_CANCHAS - ERROR: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al actualizar horarios de la cancha']);
}
