<?php
/**
 * Logout Controller - Cierra la sesión del usuario
 * -------------------------------------------------
 * Destruye la sesión y redirige al landing page.
 */

require_once '../../src/app/config.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Eliminar todas las variables de sesión
$_SESSION = array();

// Destruir la cookie de sesión si existe
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Destruir la sesión
session_destroy();

// Redirigir al landing page
header("Location: " . PAGE_LANDING_PHP);
exit();
?>
