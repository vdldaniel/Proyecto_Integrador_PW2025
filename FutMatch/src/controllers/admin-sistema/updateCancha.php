<?php
require_once(__DIR__ . "/../../app/config.php");

header('Content-Type: application/json');

try {

    // Validar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
        exit;
    }

    // Obtener datos del POST
    $action = $_POST['action'] ?? null;
    $id_cancha = isset($_POST['id_cancha']) ? intval($_POST['id_cancha']) : null;

    // Validar datos requeridos
    if (!$action || !$id_cancha) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        exit;
    }

    $conn->beginTransaction();

    switch ($action) {
        case 'tomar_caso':
            // Asignar verificador (admin sistema actual) y cambiar estado a "En revisión" (2)
            $sql = "UPDATE canchas 
                    SET id_verificador = :id_verificador, 
                        id_estado = 2
                    WHERE id_cancha = :id_cancha 
                    AND id_estado = 1"; // Solo si está en estado pendiente

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_verificador', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('No se pudo tomar el caso. Verificá que la cancha esté en estado pendiente.');
            }

            $response = [
                'success' => true,
                'message' => 'Caso tomado exitosamente'
            ];
            break;

        case 'habilitar':
            // Cambiar estado a "Habilitada" (3)
            // Verificar que el usuario actual sea el verificador asignado
            $sql = "UPDATE canchas 
                    SET id_estado = 3
                    WHERE id_cancha = :id_cancha 
                    AND (id_verificador = :id_verificador OR id_verificador IS NULL)
                    AND id_estado IN (1, 2)"; // Pendiente o En revisión

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
            $stmt->bindParam(':id_verificador', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('No se pudo habilitar la cancha. Verificá que tengas permisos y que la cancha esté en estado pendiente o en revisión.');
            }

            $response = [
                'success' => true,
                'message' => 'Cancha habilitada exitosamente'
            ];
            break;

        case 'rechazar':
            // Cambiar estado a "Deshabilitada" (4)
            $motivo = $_POST['motivo'] ?? null;

            $sql = "UPDATE canchas 
                    SET id_estado = 4
                    WHERE id_cancha = :id_cancha 
                    AND (id_verificador = :id_verificador OR id_verificador IS NULL)
                    AND id_estado IN (1, 2)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
            $stmt->bindParam(':id_verificador', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('No se pudo rechazar la cancha. Verificá que tengas permisos y que la cancha esté en estado pendiente o en revisión.');
            }

            $response = [
                'success' => true,
                'message' => 'Cancha rechazada'
            ];
            break;

        case 'reabrir':
            // Cambiar estado de "Deshabilitada" (4) a "Pendiente" (1)
            // Limpiar el verificador para que pueda ser tomado nuevamente
            $sql = "UPDATE canchas 
                    SET id_estado = 1, 
                        id_verificador = NULL
                    WHERE id_cancha = :id_cancha 
                    AND id_estado = 4"; // Solo si está deshabilitada

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_cancha', $id_cancha, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() === 0) {
                throw new Exception('No se pudo reabrir la cancha. Verificá que la cancha esté en estado deshabilitada.');
            }

            $response = [
                'success' => true,
                'message' => 'Cancha reabierta exitosamente'
            ];
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Acción no válida']);
            exit;
    }

    $conn->commit();
    echo json_encode($response);
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'error' => 'Error al procesar la solicitud',
        'message' => $e->getMessage()
    ]);
}
