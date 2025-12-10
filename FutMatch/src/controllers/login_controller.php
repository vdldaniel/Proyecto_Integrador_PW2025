<?php

/**
 * Login Controller - Procesa el inicio de sesión
 * -----------------------------------------------
 * Este archivo procesa las peticiones de login y establece las sesiones.
 */

require_once '../../src/app/config.php';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si ya está logueado y no viene del formulario, redirigir según tipo de usuario
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToHome();
}

// Procesar el formulario de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Detectar si viene del landing (redirigir) o de un modal (JSON)
    $isFromLanding = isset($_POST['from_landing']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'landing.php') !== false);

    if (!$isFromLanding) {
        header('Content-Type: application/json');
    }

    error_log("[LOGIN] Inicio del proceso de login");
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    error_log("[LOGIN] Email recibido: " . $email);

    // Validaciones básicas
    if (empty($email) || empty($password)) {
        error_log("[LOGIN] Error: Campos vacíos");
        if ($isFromLanding) {
            $_SESSION['login_error'] = 'Por favor, complete todos los campos.';
            header('Location: ' . PAGE_LANDING_PHP);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Por favor, complete todos los campos.'
            ]);
        }
        exit();
    }

    try {
        // Buscar usuario por email
        $query = "SELECT * FROM " . TABLE_USUARIOS . " WHERE email = :email AND id_estado = 1";
        error_log("[LOGIN] Query: " . $query);
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        error_log("[LOGIN] Usuario encontrado: " . ($usuario ? 'Sí (ID: ' . $usuario['id_usuario'] . ')' : 'No'));

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Login exitoso - establecer sesión
            error_log("[LOGIN] Contraseña verificada correctamente con hash");
            $_SESSION['user_id'] = $usuario['id_usuario'];
            $_SESSION['email'] = $usuario['email'];

            // Obtener el rol del usuario desde la tabla usuarios_roles
            $queryRol = "SELECT r.nombre FROM roles r 
                        INNER JOIN usuarios_roles ur ON r.id_rol = ur.id_rol 
                        WHERE ur.id_usuario = :id_usuario";
            error_log("[LOGIN] Query de rol: " . $queryRol);
            $stmtRol = $conn->prepare($queryRol);
            $stmtRol->execute(['id_usuario' => $usuario['id_usuario']]);
            $rol = $stmtRol->fetch();

            if ($rol) {
                $_SESSION['user_type'] = $rol['nombre'];
                error_log("[LOGIN] Rol obtenido de BD: " . $_SESSION['user_type']);
            } else {
                // Fallback si no se encuentra el rol
                $_SESSION['user_type'] = 'jugador';
                error_log("[LOGIN] Rol no encontrado, usando fallback: jugador");
            }

            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['apellido'] = $usuario['apellido'] ?? '';

            error_log("[LOGIN] Sesión establecida para: " . $_SESSION['nombre'] . " (" . $_SESSION['user_type'] . ")");

            // Guardar datos antes de cerrar sesión
            $user_type = $_SESSION['user_type'];
            $nombre = $_SESSION['nombre'];

            // IMPORTANTE: Forzar el guardado de la sesión antes de enviar la respuesta
            session_write_close();

            // Si viene del landing, redirigir al home correspondiente
            if ($isFromLanding) {
                redirectToHome();
            }

            // Login exitoso - devolver respuesta JSON (para modales)
            echo json_encode([
                'success' => true,
                'message' => 'Login exitoso',
                'user_type' => $user_type,
                'nombre' => $nombre
            ]);
            exit();
        } else {
            // Login fallido
            if (!$usuario) {
                error_log("[LOGIN] Error: Usuario no encontrado o inactivo");
            } else {
                error_log("[LOGIN] Error: Contraseña incorrecta para " . $email);
            }

            if ($isFromLanding) {
                $_SESSION['login_error'] = 'Email o contraseña incorrectos.';
                header('Location: ' . PAGE_LANDING_PHP);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Email o contraseña incorrectos.'
                ]);
            }
            exit();
        }
    } catch (PDOException $e) {
        error_log("[LOGIN] Error de base de datos: " . $e->getMessage());

        if ($isFromLanding) {
            $_SESSION['login_error'] = 'Error al procesar el login. Intente nuevamente.';
            header('Location: ' . PAGE_LANDING_PHP);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al procesar el login. Intente nuevamente.'
            ]);
        }
        exit();
    }
}

/**
 * Redirige al home según el tipo de usuario
 */
function redirectToHome()
{
    if (!isset($_SESSION['user_type'])) {
        header('Location: ' . PAGE_LANDING_PHP);
        exit();
    }

    switch ($_SESSION['user_type']) {
        case 'jugador':
            header('Location: ' . PAGE_INICIO_JUGADOR);
            break;
        case 'admin_cancha':
            header('Location: ' . PAGE_INICIO_ADMIN_CANCHA);
            break;
        case 'admin_sistema':
            header('Location: ' . PAGE_INICIO_ADMIN_SISTEMA);
            break;
        default:
            header('Location: ' . PAGE_LANDING_PHP);
    }
    exit();
}
