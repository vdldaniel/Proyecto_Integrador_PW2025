<?php

// Asegurar que config.php está cargado
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/config.php';
}

// Determinar página activa (si no está definida, intentar detectarla)
if (!isset($current_page)) {
    $current_page = basename($_SERVER['PHP_SELF'], '.php');
}

// Helper function para clases activas
function isActive($page_name, $current)
{
    return ($page_name === $current) ? 'active' : '';
}
?>

<!-- Navbar Admin Sistema -->
<header>
    <nav id="navbarFutmatchAdmin" class="navbar navbar-expand-lg navbar-dark bg-dark text-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>">
                <img class="logo" src="<?= BASE_URL ?>public/img/logo-sinfondo.svg" alt="FutMatch Logo" />
                <span class="brand-text">FutMatch</span>
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('inicio-admin-sistema', $current_page) ?>" 
                           aria-current="page" 
                           href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>">Home</a>
                    </li>
                </ul>
                
                <button type="button" class="btn btn-danger">
                    <a href="<?= PAGE_LOGIN_ADMIN_SISTEMA ?>">
                        <i class="bi bi-box-arrow-right text-white"></i>
                    </a>
                </button>
            </div>
        </div>
    </nav>
</header>
