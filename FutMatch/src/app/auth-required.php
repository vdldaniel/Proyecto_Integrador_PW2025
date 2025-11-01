<?php
/**
 * Componente de Autenticación - FutMatch
 * ----------------------------------------
 * Maneja la sesión y verifica que el usuario esté autenticado.
 * Incluir este archivo en páginas que requieren autenticación.
 * 
 * Uso:
 * require_once '../../../src/app/auth-required.php';
 * 
 * Variables disponibles después de incluir:
 * - $_SESSION['user_id'] - ID del usuario
 * - $_SESSION['email'] - Email del usuario
 * - $_SESSION['user_type'] - Tipo de usuario (jugador, admin_cancha, admin_sistema)
 * - $_SESSION['nombre'] - Nombre del usuario
 */

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica si el usuario está autenticado
 * @return bool
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['email']);
}

/**
 * Requiere autenticación - redirige al login si no está autenticado
 * @param string $redirectTo URL de redirección después del login
 */
function requireAuth($redirectTo = null) {
    if (!isLoggedIn()) {
        // Guardar la URL actual para redirigir después del login
        if ($redirectTo === null) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        }
        
        // Redirigir al landing/login
        header("Location: " . PAGE_LANDING_PHP);
        exit();
    }
}

/**
 * Verifica si el usuario tiene un tipo específico
 * @param string $type Tipo de usuario (jugador, admin_cancha, admin_sistema)
 * @return bool
 */
function isUserType($type) {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === $type;
}

/**
 * Requiere un tipo de usuario específico
 * @param string $type Tipo de usuario requerido
 */
function requireUserType($type) {
    if (!isUserType($type)) {
        // Redirigir según el tipo de usuario actual
        if (isset($_SESSION['user_type'])) {
            switch ($_SESSION['user_type']) {
                case 'jugador':
                    header("Location: " . PAGE_INICIO_JUGADOR);
                    break;
                case 'admin_cancha':
                    header("Location: " . PAGE_INICIO_ADMIN_CANCHA);
                    break;
                case 'admin_sistema':
                    header("Location: " . PAGE_INICIO_ADMIN_SISTEMA);
                    break;
                default:
                    header("Location: " . PAGE_LANDING_PHP);
            }
        } else {
            header("Location: " . PAGE_LANDING_PHP);
        }
        exit();
    }
}

/**
 * Obtiene información del usuario actual
 * @return array|null
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'email' => $_SESSION['email'] ?? null,
        'nombre' => $_SESSION['nombre'] ?? null,
        'apellido' => $_SESSION['apellido'] ?? null,
        'user_type' => $_SESSION['user_type'] ?? null
    ];
}
?>
