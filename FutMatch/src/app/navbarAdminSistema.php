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
    <nav id="navbarFutmatchAdmin" class="navbar navbar-expand-lg navbar-dark bg-dark text-white sticky-top border-bottom">
        <div class="container-fluid">

            <div class="d-flex align-items-center">


                <button class="btn btn-dark m-3 d-lg-none"
                    type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                    <i class="bi bi-list text-white"></i>
                </button>
                
                <a class="navbar-brand d-flex align-items-center" href="<?= PAGE_INICIO_JUGADOR ?>">
                    <img class="logo me-3" src="<?= IMG_LOGO_SINFONDO ?>" alt="FutMatch Logo" />
                    <span class="brand-text">FutMatch</span>
                </a>
            

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>"
                                class="btn btn-dark me-2 <?= isActive('inicioAdminSistema', $current_page) ?>"
                                id="adminHome"
                                title="Home">
                                <i class="bi bi-house-door"></i>
                                <span class="d-none d-lg-inline ms-1">Home</span>
                            </a>
                            <a href="<?= PAGE_SISTEMA_CANCHAS_LISTADO ?>"
                                class="btn btn-dark me-2 <?= isActive('canchasListadoSistema', $current_page) ?>"
                                id="botonCanchas"
                                title="Canchas">
                                <span class="d-none d-lg-inline ms-1">Canchas</span>
                            </a>
                            <a href="<?= PAGE_SISTEMA_JUGADORES_LISTADO ?>"
                                class="btn btn-dark me-2 <?= isActive('jugadoresListadoSistema', $current_page) ?>"
                                id="botonAdminJugadores"
                                title="Jugadores">
                                <span class="d-none d-lg-inline ms-1">Jugadores</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-warning position-relative me-2"
                        type="button" data-bs-toggle="modal" data-bs-target="#modalNotificaciones"
                        title="Notificaciones">
                        <i class="bi bi-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            0
                        </span>
                </button>

                <button id="botonConfiguracionAdmin" class="btn btn-dark me-2" type="button"
                        data-bs-toggle="modal" data-bs-target="#modalConfiguracionAdmin" title="Configuración">
                    <i class="bi bi-gear"></i>
                </button>

                <button type="button" class="btn btn-danger">
                    <a href="<?= PAGE_LOGIN_ADMIN_SISTEMA ?>">
                        <i class="bi bi-box-arrow-right text-white"></i>
                    </a>
                </button>
            </div>
        </div>
    </nav>
</header>


<!-- Menú lateral deslizable para pantallas medianas y menores -->
<div class="offcanvas offcanvas-start"
    tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">Menú</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- Navegación principal -->
        <div class="d-grid gap-2 mb-4">
            <a href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('inicioAdminSistema', $current_page) ?>"
                title="Home">
                <i class="bi bi-house-door me-2"></i>Home
            </a>
            <a href="<?= PAGE_SISTEMA_CANCHAS_LISTADO ?>"
                class="btn btn-dark text-start <?= isActive('canchasListadoSistema', $current_page) ?>"
                title="Mis Partidos">
                <i class="bi bi-calendar-event me-2"></i>Canchas
            </a>
            <a href="<?= PAGE_SISTEMA_JUGADORES_LISTADO ?>"
                class="btn btn-dark text-start <?= isActive('jugadoresListadoSistema', $current_page) ?>"
                title="Mis Equipos">
                <i class="bi bi-people me-2"></i>Jugadores
            </a>
        </div>

        <!-- Perfil y configuración -->
        <div class="mt-auto pt-3 border-top">
            <div class="d-grid gap-2">
                <a href="<?= PAGE_PERFIL_JUGADOR_DETALLE ?>"
                    class="btn btn-dark text-start <?= isActive('miPerfil', $current_page) ?>"
                    title="Mi Perfil">
                    <i class="bi bi-person-circle me-2"></i>Mi Perfil
                </a>
                <button class="btn btn-dark text-start" type="button"
                    data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
                    <i class="bi bi-gear me-2"></i>Configuración
                </button>
                <a href="<?= PAGE_LANDING_PHP ?>" class="btn btn-danger text-start"
                    title="Cerrar Sesión">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>
