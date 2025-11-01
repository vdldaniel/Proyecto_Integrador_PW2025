<?php

/**
 * NAVBAR JUGADOR - Componente de navegación para jugadores
 * Incluye: Logo, menú principal, dropdown explorar, notificaciones, perfil
 * Soporte para página activa mediante la variable $current_page
 * Requiere: config.php cargado previamente
 */

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
<!-- Navbar Jugador -->
<header>
    <nav id="navbarFutmatch" class="navbar navbar-expand-lg navbar-dark bg-dark text-white sticky-top border-bottom">
        <div class="container-fluid">
            <!-- Lado izquierdo: menú hamburguesa + título + botones navegación -->
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
                <!--Botones que llevan a otras secciones del programa-->
                <div class="d-flex align-items-center d-none d-md-flex ms-3">
                    <a href="<?= PAGE_INICIO_JUGADOR ?>"
                        class="btn btn-dark me-2 <?= isActive('inicioJugador', $current_page) ?>"
                        id="home"
                        title="Home">
                        <i class="bi bi-house-door"></i>
                        <span class="d-none d-lg-inline ms-1">Home</span>
                    </a>
                    <a href="<?= PAGE_PARTIDOS_JUGADOR ?>"
                        class="btn btn-dark me-2 <?= isActive('partidosJugador', $current_page) ?>"
                        id="botonMisPartidos"
                        title="Mis Partidos">
                        <i class="bi bi-calendar-event"></i>
                        <span class="d-none d-lg-inline ms-1">Mis Partidos</span>
                    </a>
                    <!-- Dropdown Explorar -->
                    <div class="dropdown">
                        <button class="btn btn-dark dropdown-toggle me-2" type="button" id="dropdownExplorar"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Explorar">
                            <i class="bi bi-search"></i>
                            <span class="d-none d-lg-inline ms-1">Explorar</span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownExplorar">
                            <li><a class="dropdown-item" href="<?= PAGE_CANCHAS_EXPLORAR ?>">
                                    <i class="bi bi-geo-alt me-2"></i>Explorar canchas
                                </a>
                            </li>
                            <li><a class="dropdown-item" href="<?= PAGE_PARTIDOS_LISTADO ?>">
                                    <i class="bi bi-people me-2"></i>Explorar partidos
                                </a>
                            </li>
                        </ul>
                    </div>
                    <a href="<?= PAGE_EQUIPOS_LISTADO ?>"
                        class="btn btn-dark me-2 <?= isActive('equiposListado', $current_page) ?>"
                        id="botonMiEquipo"
                        title="Mis Equipos">
                        <i class="bi bi-people"></i>
                        <span class="d-none d-lg-inline ms-1">Mis Equipos</span>
                    </a>
                    <a href="<?= PAGE_FOROS_LISTADO ?>"
                        class="btn btn-dark me-2 <?= isActive('explorarForos', $current_page) ?>"
                        id="botonForos"
                        title="Foros">
                        <i class="bi bi-chat-dots"></i>
                        <span class="d-none d-lg-inline ms-1">Foros</span>
                    </a>
                </div>
            </div>

            <!-- Lado derecho: perfil, notificaciones y configuración -->
            <div class="d-flex align-items-center">
                <!-- Botón Mi Perfil -->
                <a href="<?= PAGE_PERFIL_JUGADOR ?>"
                    class="btn btn-dark me-2 d-none d-md-flex <?= isActive('miPerfil', $current_page) ?>"
                    id="botonMiPerfil"
                    title="Mi Perfil">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-lg-inline ms-1">Mi Perfil</span>
                </a>
                <!-- Campanita de notificaciones -->
                <button class="btn btn-outline-warning position-relative me-2"
                    type="button" data-bs-toggle="modal" data-bs-target="#modalNotificaciones"
                    title="Notificaciones">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        2
                    </span>
                </button>
                <!-- Botones solo en pantallas medianas y grandes -->
                <div class="d-none-custom d-md-flex align-items-center">
                    <button id="botonConfiguracion" class="btn btn-dark me-2" type="button"
                        data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
                        <i class="bi bi-gear"></i>
                    </button>
                    <a href="<?= CONTROLLER_LOGOUT ?>" class="btn btn-danger me-2 d-none d-lg-flex" id="btnCerrarSesion"
                        title="Cerrar Sesión">
                        <i class="bi bi-box-arrow-right text-white"></i>
                    </a>
                </div>
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
            <a href="<?= PAGE_INICIO_JUGADOR ?>"
                class="btn btn-dark text-start <?= isActive('inicioJugador', $current_page) ?>"
                title="Home">
                <i class="bi bi-house-door me-2"></i>Home
            </a>
            <a href="<?= PAGE_PARTIDOS_JUGADOR ?>"
                class="btn btn-dark text-start <?= isActive('MisPartidos', $current_page) ?>"
                title="Mis Partidos">
                <i class="bi bi-calendar-event me-2"></i>Mis Partidos
            </a>
            <a href="<?= PAGE_EQUIPOS_LISTADO ?>"
                class="btn btn-dark text-start <?= isActive('equiposListado', $current_page) ?>"
                title="Mis Equipos">
                <i class="bi bi-people me-2"></i>Mis Equipos
            </a>
        </div>

        <!-- Sección Explorar -->
        <h6 class="offcanvas-section-title mb-2">Explorar</h6>
        <div class="d-grid gap-2 mb-4">
            <a href="<?= PAGE_CANCHAS_EXPLORAR ?>"
                class="btn btn-dark text-start <?= isActive('explorarCanchas', $current_page) ?>">
                <i class="bi bi-geo-alt me-2"></i>Explorar Canchas
            </a>
            <a href="<?= PAGE_PARTIDOS_LISTADO ?>"
                class="btn btn-dark text-start <?= isActive('explorarPartidos', $current_page) ?>">
                <i class="bi bi-people me-2"></i>Explorar Partidos
            </a>
            <a href="<?= PAGE_FOROS_LISTADO ?>"
                class="btn btn-dark text-start <?= isActive('explorarForos', $current_page) ?>">
                <i class="bi bi-chat-dots me-2"></i>Foros
            </a>
        </div>

        <!-- Perfil y configuración -->
        <div class="mt-auto pt-3 border-top">
            <div class="d-grid gap-2">
                <a href="<?= PAGE_PERFIL_JUGADOR ?>"
                    class="btn btn-dark text-start <?= isActive('miPerfil', $current_page) ?>"
                    title="Mi Perfil">
                    <i class="bi bi-person-circle me-2"></i>Mi Perfil
                </a>
                <button class="btn btn-dark text-start" type="button"
                    data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
                    <i class="bi bi-gear me-2"></i>Configuración
                </button>
                <a href="<?= CONTROLLER_LOGOUT ?>" class="btn btn-danger text-start"
                    title="Cerrar Sesión">
                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>