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

$id_usuario = $_SESSION['user_id'];
$accion = $_POST['accion'] ?? '';

try {
    if ($accion === 'actualizar_datos') {
        // Actualizar nombre, apellido, email, teléfono
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');

        // Validaciones básicas
        if (empty($nombre) || empty($apellido) || empty($email)) {
            throw new Exception('Nombre, apellido y email son obligatorios');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Email inválido');
        }

        // Verificar si el email ya existe en otro usuario
        $queryCheckEmail = 'SELECT id_usuario FROM usuarios WHERE email = :email AND id_usuario != :id_usuario';
        $stmtCheckEmail = $conn->prepare($queryCheckEmail);
        $stmtCheckEmail->bindParam(':email', $email);
        $stmtCheckEmail->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtCheckEmail->execute();

        if ($stmtCheckEmail->fetch()) {
            throw new Exception('El email ya está registrado por otro usuario');
        }

        // Actualizar tabla usuarios
        $queryUsuario = 'UPDATE usuarios 
                        SET nombre = :nombre, apellido = :apellido, email = :email 
                        WHERE id_usuario = :id_usuario';
        $stmtUsuario = $conn->prepare($queryUsuario);
        $stmtUsuario->bindParam(':nombre', $nombre);
        $stmtUsuario->bindParam(':apellido', $apellido);
        $stmtUsuario->bindParam(':email', $email);
        $stmtUsuario->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtUsuario->execute();

        // Actualizar teléfono según el rol del usuario
        if (!empty($telefono)) {
            // Verificar si es jugador
            $queryJugador = 'SELECT id_jugador FROM jugadores WHERE id_jugador = :id_usuario';
            $stmtJugador = $conn->prepare($queryJugador);
            $stmtJugador->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtJugador->execute();

            if ($stmtJugador->fetch()) {
                $queryUpdateJugador = 'UPDATE jugadores SET telefono = :telefono WHERE id_jugador = :id_usuario';
                $stmtUpdateJugador = $conn->prepare($queryUpdateJugador);
                $stmtUpdateJugador->bindParam(':telefono', $telefono);
                $stmtUpdateJugador->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtUpdateJugador->execute();
            }

            // Verificar si es admin cancha
            $queryAdminCancha = 'SELECT id_admin_cancha FROM admin_canchas WHERE id_admin_cancha = :id_usuario';
            $stmtAdminCancha = $conn->prepare($queryAdminCancha);
            $stmtAdminCancha->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmtAdminCancha->execute();

            if ($stmtAdminCancha->fetch()) {
                $queryUpdateAdmin = 'UPDATE admin_canchas SET telefono = :telefono WHERE id_admin_cancha = :id_usuario';
                $stmtUpdateAdmin = $conn->prepare($queryUpdateAdmin);
                $stmtUpdateAdmin->bindParam(':telefono', $telefono);
                $stmtUpdateAdmin->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmtUpdateAdmin->execute();
            }
        }

        echo json_encode([
            'success' => true,
            'message' => 'Datos actualizados correctamente'
        ]);
    } elseif ($accion === 'cambiar_password') {
        // Cambiar contraseña
        $password_actual = $_POST['password_actual'] ?? '';
        $password_nueva = $_POST['password_nueva'] ?? '';
        $password_confirmar = $_POST['password_confirmar'] ?? '';

        // Validaciones
        if (empty($password_actual) || empty($password_nueva) || empty($password_confirmar)) {
            throw new Exception('Todos los campos de contraseña son obligatorios');
        }

        if ($password_nueva !== $password_confirmar) {
            throw new Exception('Las contraseñas nuevas no coinciden');
        }

        if (strlen($password_nueva) < 6) {
            throw new Exception('La nueva contraseña debe tener al menos 6 caracteres');
        }

        // Verificar contraseña actual
        $queryPassword = 'SELECT password FROM usuarios WHERE id_usuario = :id_usuario';
        $stmtPassword = $conn->prepare($queryPassword);
        $stmtPassword->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtPassword->execute();
        $usuario = $stmtPassword->fetch();

        if (!$usuario || !password_verify($password_actual, $usuario['password'])) {
            throw new Exception('La contraseña actual es incorrecta');
        }

        // Actualizar contraseña
        $passwordHash = password_hash($password_nueva, PASSWORD_DEFAULT);
        $queryUpdate = 'UPDATE usuarios SET password = :password WHERE id_usuario = :id_usuario';
        $stmtUpdate = $conn->prepare($queryUpdate);
        $stmtUpdate->bindParam(':password', $passwordHash);
        $stmtUpdate->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmtUpdate->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    } elseif ($accion === 'obtener_datos') {
        // Obtener datos del usuario
        $query = 'SELECT u.nombre, u.apellido, u.email,
                        COALESCE(j.telefono, ac.telefono) as telefono
                 FROM usuarios u
                 LEFT JOIN jugadores j ON u.id_usuario = j.id_jugador
                 LEFT JOIN admin_canchas ac ON u.id_usuario = ac.id_admin_cancha
                 WHERE u.id_usuario = :id_usuario';

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        $datos = $stmt->fetch();

        if ($datos) {
            echo json_encode([
                'success' => true,
                'data' => $datos
            ]);
        } else {
            throw new Exception('Usuario no encontrado');
        }
    } else {
        throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (PDOException $e) {
    error_log("Error en updateUsuario: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Error al procesar la solicitud']);
}
