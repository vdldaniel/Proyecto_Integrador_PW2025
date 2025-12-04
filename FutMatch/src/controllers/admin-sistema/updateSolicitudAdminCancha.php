<?php
require_once '../../app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit();
}

$accion = $_POST['accion'] ?? '';
$id_solicitud = isset($_POST['id_solicitud']) ? intval($_POST['id_solicitud']) : 0;
$id_verificador = $_SESSION['user_id'];

// Debug logging
error_log("=== UPDATE SOLICITUD DEBUG ===");
error_log("Accion: " . $accion);
error_log("ID Solicitud: " . $id_solicitud);
error_log("ID Verificador: " . $id_verificador);
error_log("POST data: " . print_r($_POST, true));

if (!$id_solicitud || !in_array($accion, ['aceptar', 'rechazar', 'reabrir', 'tomar'])) {
    error_log("ERROR: Parámetros inválidos");
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros inválidos']);
    exit();
}

try {
    // Obtener información de la solicitud ANTES de iniciar transacción
    error_log("Consultando solicitud...");
    $querySolicitud = 'SELECT * FROM solicitudes_admin_cancha WHERE id_solicitud = :id_solicitud';
    $stmtSolicitud = $conn->prepare($querySolicitud);
    $stmtSolicitud->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
    $stmtSolicitud->execute();
    $solicitud = $stmtSolicitud->fetch(PDO::FETCH_ASSOC);

    if (!$solicitud) {
        error_log("ERROR: Solicitud no encontrada con ID: " . $id_solicitud);
        throw new Exception('Solicitud no encontrada');
    }

    error_log("Solicitud encontrada: " . print_r($solicitud, true));

    // Iniciar transacción después de validar
    $conn->beginTransaction();

    if ($accion === 'rechazar') {
        // Cambiar estado a rechazada (4)
        $queryUpdate = 'UPDATE solicitudes_admin_cancha 
                       SET id_estado = 4, id_verificador = :id_verificador 
                       WHERE id_solicitud = :id_solicitud';
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_verificador', $id_verificador, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Solicitud rechazada correctamente']);
        exit();
    }

    if ($accion === 'reabrir') {
        // Cambiar estado a pendiente (1)
        $queryUpdate = 'UPDATE solicitudes_admin_cancha
                       SET id_estado = 1 
                       WHERE id_solicitud = :id_solicitud';
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Solicitud reabierta correctamente']);
        exit();
    }

    if ($accion === 'tomar') {
        // Asignar verificador sin cambiar el estado
        $queryUpdate = 'UPDATE solicitudes_admin_cancha 
                       SET id_verificador = :id_verificador 
                       WHERE id_solicitud = :id_solicitud';
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_verificador', $id_verificador, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Caso asignado correctamente']);
        exit();
    }

    if ($accion === 'aceptar') {
        // 1. Crear usuario
        error_log("Creando usuario...");
        $passwordHash = password_hash('password', PASSWORD_DEFAULT);

        $queryUsuario = 'INSERT INTO usuarios
                        (nombre, apellido, email, password, id_estado) 
                        VALUES (:nombre, :apellido, :email, :password, 1)';
        $stmtUsuario = $conn->prepare($queryUsuario);
        $stmtUsuario->bindParam(':nombre', $solicitud['nombre']);
        $stmtUsuario->bindParam(':apellido', $solicitud['apellido']);
        $stmtUsuario->bindParam(':email', $solicitud['email']);
        $stmtUsuario->bindParam(':password', $passwordHash);
        $stmtUsuario->execute();
        error_log("Usuario creado con ID: " . $conn->lastInsertId());

        $id_usuario = $conn->lastInsertId();

        // 2. Crear registro en admin_canchas (solo tiene id_admin_cancha e id_solicitud)
        $queryAdminCancha = 'INSERT INTO admin_canchas
                            (id_admin_cancha, id_solicitud) 
                            VALUES (:id_admin_cancha, :id_solicitud)';
        $stmtAdminCancha = $conn->prepare($queryAdminCancha);
        $stmtAdminCancha->bindParam(':id_admin_cancha', $id_usuario, PDO::PARAM_INT);
        $stmtAdminCancha->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
        $stmtAdminCancha->execute();

        // 3. Crear registro en usuarios_roles
        $queryRol = 'INSERT INTO usuarios_roles
                     (id_usuario, id_rol) 
                     VALUES (:id_usuario, 2)';
        $stmtRol = $conn->prepare($queryRol);
        $stmtRol->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtRol->execute();

        // 4. Crear cancha
        $queryCancha = 'INSERT INTO canchas 
                       (id_admin_cancha, id_direccion, nombre, telefono, id_estado) 
                       VALUES (:id_admin_cancha, :id_direccion, :nombre, :telefono, 3)';
        $stmtCancha = $conn->prepare($queryCancha);
        $stmtCancha->bindParam(':id_admin_cancha', $id_usuario, PDO::PARAM_INT);
        $stmtCancha->bindParam(':id_direccion', $solicitud['id_direccion'], PDO::PARAM_INT);
        $stmtCancha->bindParam(':nombre', $solicitud['nombre_cancha']);
        $stmtCancha->bindParam(':telefono', $solicitud['telefono']);
        $stmtCancha->execute();

        // 5. Actualizar estado de solicitud a aceptada (3)
        $queryUpdate = 'UPDATE solicitudes_admin_cancha
                       SET id_estado = 3, id_verificador = :id_verificador 
                       WHERE id_solicitud = :id_solicitud';
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':id_verificador', $id_verificador, PDO::PARAM_INT);
        $stmtUpdate->bindParam(':id_solicitud', $id_solicitud, PDO::PARAM_INT);
        $stmtUpdate->execute();

        $conn->commit();
        echo json_encode([
            'success' => true,
            'message' => 'Solicitud aceptada. Usuario creado con email: ' . $solicitud['email'] . ' y contraseña: password',
            'id_usuario' => $id_usuario
        ]);
        exit();
    }
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    error_log("Error en updateSolicitudAdminCancha: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar solicitud: ' . $e->getMessage()]);
}
