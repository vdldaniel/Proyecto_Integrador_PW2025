<?php
/**
 * Configuración global de rutas y links del proyecto FutMatch
 * ------------------------------------------------------------
 * Define las rutas base, estilos, scripts y páginas.
 * Así evitamos hardcodear paths en distintos archivos.
 */

// ===================================
// RUTAS BASE
// ===================================
define("BASE_URL", "/Proyecto_Integrador_PW2025/FutMatch/");
define("PUBLIC_PATH", BASE_URL . "public/");
define("SRC_PATH", BASE_URL . "src/");
define("ASSETS_PATH", PUBLIC_PATH . "assets/");

// ===================================
// CSS - ARCHIVOS UNIFICADOS
// ===================================
define("CSS_BOOTSTRAP", ASSETS_PATH . "css/bootstrap.min.css");
define("CSS_BASE", SRC_PATH . "styles/base.css");
define("CSS_LAYOUT", SRC_PATH . "styles/layout.css");
define("CSS_COMPONENTS", SRC_PATH . "styles/components.css");

// CSS de páginas específicas
define("CSS_PAGES_AGENDA", SRC_PATH . "styles/pages/agenda.css");
define("CSS_PAGES_INICIO_JUGADOR", SRC_PATH . "styles/pages/inicioJugador.css");
define("CSS_PAGES_DASHBOARD_ADMIN_CANCHA", SRC_PATH . "styles/pages/dashboardAdminCancha.css");
define("CSS_PAGES_FOROS_LISTADO", SRC_PATH . "styles/pages/foros-listado.css");
define("CSS_PAGES_PARTIDOS_JUGADOR", SRC_PATH . "styles/pages/partidosJugador.css");

// CDN Externos
define("CSS_ICONS", "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css");

// ===================================
// FUENTES
// ===================================
define("FONT_MONTSERRAT", "https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap");

// ===================================
// ⚙️ JAVASCRIPT
// ===================================
define("JS_BOOTSTRAP", ASSETS_PATH . "js/bootstrap.bundle.min.js");
define("JS_BOOTSTRAP_CDN", "https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js");

// Scripts de páginas
define("JS_SCRIPTS_PATH", SRC_PATH . "scripts/pages/");
define("JS_AGENDA", JS_SCRIPTS_PATH . "agenda.js");
define("JS_ADMIN_INC", JS_SCRIPTS_PATH . "adminInc.js");
define("JS_CANCHA_RESERVAR", JS_SCRIPTS_PATH . "cancha-reservar.js");
define("JS_CANCHAS_LISTADO", JS_SCRIPTS_PATH . "canchas-listado.js");
define("JS_CUENTA_JUGADOR", JS_SCRIPTS_PATH . "cuenta-jugador.js");
define("JS_DETALLE_TORNEO", JS_SCRIPTS_PATH . "detalle-torneo.js");
define("JS_EQUIPO_CREAR", JS_SCRIPTS_PATH . "equipo-crear.js");
define("JS_EQUIPOS_LISTADO", JS_SCRIPTS_PATH . "equipos-listado.js");
define("JS_FORGOT", JS_SCRIPTS_PATH . "forgot.js");
define("JS_FOROS_LISTADO", JS_SCRIPTS_PATH . "foros-listado.js");
define("JS_INICIO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "inicioAdminCancha.js");
define("JS_INICIO_ADMIN_SISTEMA", JS_SCRIPTS_PATH . "inicio-admin-sistema.js");
define("JS_INICIO_JUGADOR", JS_SCRIPTS_PATH . "inicioJugador.js");
define("JS_LANDING", JS_SCRIPTS_PATH . "landing.js");
define("JS_PARTIDOS_JUGADOR", JS_SCRIPTS_PATH . "partidosJugador.js");
define("JS_REGISTRO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "registroAdminCancha.js");
define("JS_REGISTRO_JUGADOR", JS_SCRIPTS_PATH . "registroJugador.js");
define("JS_SCRIPT_MAPA", JS_SCRIPTS_PATH . "ScriptMapa.js");
define("JS_TORNEO_LISTADO", JS_SCRIPTS_PATH . "torneo-listado.js");

// ===================================
// IMÁGENES
// ===================================
define("IMG_PATH", PUBLIC_PATH . "img/");
define("IMG_LOGO_SINFONDO", IMG_PATH . "logo-sinfondo.svg");
define("IMG_LOGO_FONDOVERDE", IMG_PATH . "logo-fondoverde.svg");

// ===================================
// COMPONENTES PHP
// ===================================
define("HEAD_COMPONENT", __DIR__ . "/head.php");
define("NAVBAR_JUGADOR_COMPONENT", __DIR__ . "/navbarJugador.php");
define("NAVBAR_GUEST_COMPONENT", __DIR__ . "/navbarGuest.php");
define("NAVBAR_ADMIN_CANCHA_COMPONENT", __DIR__ . "/navbarAdminCancha.php");
define("NAVBAR_ADMIN_SISTEMA_COMPONENT", __DIR__ . "/navbarAdminSistema.php");

// ===================================
// PÁGINAS - AUTH
// ===================================
define("PAGE_LANDING", PUBLIC_PATH . "HTML/auth/landing.html");
define("PAGE_LANDING_PHP", PUBLIC_PATH . "HTML/auth/landing.php");
define("PAGE_FORGOT", PUBLIC_PATH . "HTML/auth/forgot.html");
define("PAGE_FORGOT_PHP", PUBLIC_PATH . "HTML/auth/forgot.php");
define("PAGE_REGISTER_JUGADOR", PUBLIC_PATH . "HTML/auth/register-jugador.html");
define("PAGE_REGISTRO_JUGADOR_PHP", PUBLIC_PATH . "HTML/auth/registroJugador.php");
define("PAGE_REGISTER_ADMIN_CANCHA", PUBLIC_PATH . "HTML/auth/register-admin-canchas.html");
define("PAGE_REGISTRO_ADMIN_CANCHA_PHP", PUBLIC_PATH . "HTML/auth/registroAdminCancha.php");

// ===================================
// PÁGINAS - COMUNES
// ===================================
define("PAGE_FOROS_LISTADO", PUBLIC_PATH . "HTML/foros-listado.php");
define("PAGE_FOROS_DETALLE", PUBLIC_PATH . "HTML/forosDetalle.html");

// ===================================
// PÁGINAS - JUGADOR
// ===================================
define("PAGE_INICIO_JUGADOR", PUBLIC_PATH . "HTML/jugador/inicioJugador.php");
define("PAGE_PERFIL_JUGADOR", PUBLIC_PATH . "HTML/jugador/perfilJugador.html");
define("PAGE_CUENTA_JUGADOR", PUBLIC_PATH . "HTML/jugador/cuenta-jugador (-MODAL navbar).html");

// Equipos
define("PAGE_EQUIPOS_LISTADO", PUBLIC_PATH . "HTML/jugador/equiposListado.html");
define("PAGE_EQUIPO_CREAR", PUBLIC_PATH . "HTML/jugador/equipo-crear (-MODAL).html");

// Partidos
define("PAGE_PARTIDOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/partidosJugador.php");
define("PAGE_PARTIDOS_LISTADO", PUBLIC_PATH . "HTML/jugador/partidosListado.html");
define("PAGE_PARTIDO_DETALLE", PUBLIC_PATH . "HTML/jugador/partidoDetalle.html");
define("PAGE_PARTIDO_UNIRSE", PUBLIC_PATH . "HTML/jugador/partido-unirse (-MODAL).html");

// Canchas
define("PAGE_CANCHAS_EXPLORAR", PUBLIC_PATH . "HTML/jugador/canchasExplorar.html");
define("PAGE_CANCHA_PERFIL", PUBLIC_PATH . "HTML/jugador/canchaPerfil.html");
define("PAGE_CANCHA_RESERVAR", PUBLIC_PATH . "HTML/jugador/cancha-reservar (-MODAL).html");

// Torneos
define("PAGE_TORNEOS_LISTADO", PUBLIC_PATH . "HTML/jugador/torneosListado (reciclar de mis-torneos).html");
define("PAGE_TORNEO_DETALLE", PUBLIC_PATH . "HTML/jugador/torneosDetalle (dejar a Cami) .html");

// ===================================
// PÁGINAS - ADMIN CANCHA
// ===================================
define("PAGE_INICIO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/inicioAdminCancha.php");
define("PAGE_AGENDA", PUBLIC_PATH . "HTML/admin-cancha/agenda.php");

// Gestión de canchas
define("PAGE_ADMIN_CANCHAS_LISTADO", PUBLIC_PATH . "HTML/admin-cancha/canchas-listado.php");
define("PAGE_ADMIN_CANCHA_CREAR", PUBLIC_PATH . "HTML/admin-cancha/cancha-crear.php");
define("PAGE_ADMIN_CANCHA_PERFIL", PUBLIC_PATH . "HTML/admin-cancha/canchaPerfil.html");
define("PAGE_ADMIN_CANCHA_EDITAR", PUBLIC_PATH . "HTML/admin-cancha/(pasar a MODAL) cancha-editar.html");
define("PAGE_ADMIN_CANCHA_PERFIL_EDITAR", PUBLIC_PATH . "HTML/admin-cancha/(UNIFICAR CON canchaPerfil) cancha-perfil-editar.html");

// Gestión de torneos
define("PAGE_ADMIN_MIS_TORNEOS", PUBLIC_PATH . "HTML/admin-cancha/mis-torneos.php");
define("PAGE_ADMIN_MIS_TORNEOS_CREAR", PUBLIC_PATH . "HTML/admin-cancha/(pasar a MODAL) mis-torneos-crear.html");
define("PAGE_ADMIN_MIS_TORNEOS_DETALLE", PUBLIC_PATH . "HTML/admin-cancha/(dejar a Cami) mis-torneos-detalle.html");

// ===================================
// PÁGINAS - ADMIN SISTEMA
// ===================================
define("PAGE_LOGIN_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/login-admin-sistema.html");
define("PAGE_INICIO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/inicio-admin-sistema.html");

// Gestión de jugadores
define("PAGE_SISTEMA_JUGADORES_LISTADO", PUBLIC_PATH . "HTML/admin-sistema/jugadores-listado-sistema.html");
define("PAGE_SISTEMA_JUGADOR_DETALLE", PUBLIC_PATH . "HTML/admin-sistema/jugador-detalle-sistema.html");

// Gestión de canchas (sistema)
define("PAGE_SISTEMA_CANCHAS_LISTADO", PUBLIC_PATH . "HTML/admin-sistema/canchas-listado-sistema.html");
define("PAGE_SISTEMA_CANCHA_DETALLE", PUBLIC_PATH . "HTML/admin-sistema/cancha-detalle-sistema.html");
define("PAGE_SISTEMA_CANCHA_VERIFICAR", PUBLIC_PATH . "HTML/admin-sistema/cancha-verificar-sistema.html");
?>
