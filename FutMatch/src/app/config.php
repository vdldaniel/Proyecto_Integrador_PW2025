<?php

/**
 * Configuración global de rutas y links del proyecto FutMatch
 * ------------------------------------------------------------
 * Define las rutas base, estilos, scripts y páginas.
 * Así evitamos hardcodear paths en distintos archivos.
 */


// ===================================
// CONEXIÓN A LA BASE DE DATOS
// ===================================
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'futmatch_db');

try {
    $conn = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}

// ===================================
// NOMBRES DE TABLAS
// ===================================

// Usuarios y Roles
define('TABLE_USUARIOS', 'usuarios');
define('TABLE_ROLES_USUARIOS', 'roles_usuarios');
define('TABLE_ESTADOS_USUARIOS', 'estados_usuarios');

// Admin Sistema
define('TABLE_ADMIN_SISTEMA', 'admin_sistema');
define('TABLE_PERMISOS', 'permisos');
define('TABLE_SOLICITUDES_ADMIN_CANCHA', 'solicitudes_admin_cancha');

// Admin Cancha
define('TABLE_ADMIN_CANCHAS', 'admin_canchas');
define('TABLE_CANCHAS', 'canchas');
define('TABLE_ESTADOS_CANCHAS', 'estados_canchas');
define('TABLE_HORARIOS_CANCHA', 'horarios_cancha');

// Direcciones
define('TABLE_DIRECCIONES', 'direcciones');

// Días de la semana
define('TABLE_DIAS_SEMANA', 'dias_semana');

// Jugadores
define('TABLE_JUGADORES', 'jugadores');
define('TABLE_SEXO', 'sexo');
define('TABLE_POSICIONES', 'posiciones');

// Equipos
define('TABLE_EQUIPOS', 'equipos');
define('TABLE_JUGADORES_EQUIPOS', 'jugadores_equipos');

// Torneos
define('TABLE_TORNEOS', 'torneos');
define('TABLE_ETAPAS_TORNEO', 'etapas_torneo');
define('TABLE_EQUIPOS_TORNEOS', 'equipos_torneos');

// Reservas y Partidos
define('TABLE_RESERVAS', 'reservas');
define('TABLE_PARTIDOS', 'partidos');
define('TABLE_ROLES_PARTIDOS', 'roles_partidos');
define('TABLE_PARTICIPANTES_PARTIDOS', 'participantes_partidos');
define('TABLE_ESTADISTICAS_PARTIDO', 'estadisticas_partido');

// Estados y Solicitudes
define('TABLE_ESTADOS_SOLICITUDES', 'estados_solicitudes');

// Calificaciones y Reseñas
define('TABLE_CALIFICACIONES_JUGADORES', 'calificaciones_jugadores');
define('TABLE_RESENIAS_CANCHAS', 'resenias_canchas');

// ===================================
// RUTAS BASE
// ===================================
define("BASE_URL", "/Proyecto_Integrador_PW2025/FutMatch/"); // Ajusta según tu configuración de servidor
define("PUBLIC_PATH", BASE_URL . "public/");
define("SRC_PATH", BASE_URL . "src/");
define("ASSETS_PATH", PUBLIC_PATH . "assets/");

// Ruta del archivo de configuración
define("CONFIG_PATH", __DIR__ . "/config.php");

// ===================================
// CSS - ARCHIVOS UNIFICADOS
// ===================================
define("CSS_BOOTSTRAP", ASSETS_PATH . "css/bootstrap.min.css");
define("CSS_BASE", SRC_PATH . "styles/base.css");
define("CSS_LAYOUT", SRC_PATH . "styles/layout.css");
define("CSS_COMPONENTS", SRC_PATH . "styles/components.css");

// CSS de páginas específicas
define("CSS_PAGES_LANDING", SRC_PATH . "styles/pages/landing.css");
define("CSS_PAGES_AGENDA", SRC_PATH . "styles/pages/agenda.css");
define("CSS_PAGES_PARTIDOS_JUGADOR", SRC_PATH . "styles/pages/partidosJugador.css");
define("CSS_PAGES_EXPLORAR", SRC_PATH . "styles/pages/explorar.css");
define("CSS_PAGES_DETALLE_TORNEO", SRC_PATH . "styles/pages/torneoDetalle.css");
define("CSS_PAGES_CANCHAS_ADMIN_SISTEMA", SRC_PATH . "styles/pages/canchasAdminSistema.css");
define("CSS_PAGES_JUGADORES_ADMIN_SISTEMA", SRC_PATH . "styles/pages/jugadoresAdminSistema.css");
define("CSS_PAGES_JUGADORES_REPORTADOS_ADMIN_SISTEMA", SRC_PATH . "styles/pages/jugadoresReportadosAdminSistema.css");
define("CSS_PAGES_TABLAS_ADMIN_SISTEMA", SRC_PATH . "styles/pages/tablasAdminSistema.css");
define("CSS_PAGES_PERFILES", SRC_PATH . "styles/pages/perfiles.css");
define("CSS_PAGES_EQUIPOS_JUGADOR", SRC_PATH . "styles/pages/equiposJugador.css");

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

// Scripts de páginas
define("JS_SCRIPTS_PATH", SRC_PATH . "scripts/pages/");
define("JS_COMPONENTS_PATH", SRC_PATH . "scripts/components/");
define("JS_AGENDA", JS_SCRIPTS_PATH . "agenda.js");

// Scripts de componentes
define("JS_PERFIL_CANCHA_BASE", JS_COMPONENTS_PATH . "perfilCancha.js");
define("JS_AGENDA_ADMIN", JS_SCRIPTS_PATH . "agenda-admin.js");
define("JS_CALENDARIO_JUGADOR", JS_SCRIPTS_PATH . "calendarioJugador.js");
define("JS_ADMIN_INC", JS_SCRIPTS_PATH . "adminInc.js");
define("JS_CANCHA_RESERVAR", JS_SCRIPTS_PATH . "cancha-reservar.js");
define("JS_CANCHA_PERFIL", JS_SCRIPTS_PATH . "canchaPerfil.js");
define("JS_PERFILES_CANCHAS", JS_SCRIPTS_PATH . "perfiles-canchas.js");
define("JS_CUENTA_JUGADOR", JS_SCRIPTS_PATH . "cuenta-jugador.js");
define("JS_DETALLE_TORNEO", JS_SCRIPTS_PATH . "detalle-torneo.js");
define("JS_EQUIPO_CREAR", JS_SCRIPTS_PATH . "equipo-crear.js");
define("JS_EQUIPOS_LISTADO", JS_SCRIPTS_PATH . "equipos-listado.js");
define("JS_FORGOT", JS_SCRIPTS_PATH . "forgot.js");
define("JS_INICIO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "inicioAdminCancha.js");
define("JS_INICIO_ADMIN_SISTEMA", JS_SCRIPTS_PATH . "inicio-admin-sistema.js");
define("JS_INICIO_JUGADOR", JS_SCRIPTS_PATH . "inicioJugador.js");
define("JS_LANDING", JS_SCRIPTS_PATH . "landing.js");
define("JS_SCRIPT_MAPA", JS_SCRIPTS_PATH . "ScriptMapa.js");
define("JS_TORNEO_LISTADO", JS_SCRIPTS_PATH . "torneo-listado.js");
define("JS_MIS_TORNEOS", JS_SCRIPTS_PATH . "mis-torneos.js");
define("JS_TORNEO_DETALLE", JS_SCRIPTS_PATH . "torneo-detalle.js");
define("JS_TORNEOS_JUGADOR", JS_SCRIPTS_PATH . "pages/torneos-jugador.js");
define("JS_PERFIL_JUGADOR", SRC_PATH . "scripts/perfilJugador.js");
define("JS_CANCHAS_ADMIN_SISTEMA", SRC_PATH . "scripts/canchasAdminSistema.js");
define("JS_JUGADORES_ADMIN_SISTEMA", SRC_PATH . "scripts/jugadoresAdminSistema.js");
define("JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA", SRC_PATH . "scripts/jugadoresReportadosAdminSistema.js");
define("JS_CANCHAS_REPORTADAS_ADMIN_SISTEMA", SRC_PATH . "scripts/canchasReportadasAdminSistema.js");

// ==================================
// JS - PAGINAS BACKEND
// ==================================

// Módulos compartidos
define("JS_TOAST_MODULE", SRC_PATH . "scripts/modules/toast.js");

// Jugador
define("JS_REGISTRO_JUGADOR", JS_SCRIPTS_PATH . "registroJugador.js");
define("JS_PARTIDOS_JUGADOR", JS_SCRIPTS_PATH . "partidosJugador.js");
define("JS_CANCHAS_EXPLORAR_JUGADOR", SRC_PATH . "scripts/pages/canchasExplorar_Jugador.js");
define("JS_PARTIDOS_EXPLORAR_JUGADOR", SRC_PATH . "scripts/pages/partidosExplorar_Jugador.js");
define("JS_MIS_EQUIPOS_JUGADOR", JS_SCRIPTS_PATH . "misEquipos_Jugador.js");

// Admin Cancha
define("JS_REGISTRO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "registroAdminCancha.js");

// ===================================
// IMÁGENES
// ===================================
define("IMG_PATH", PUBLIC_PATH . "img/");
define("IMG_LOGO_SINFONDO", IMG_PATH . "logo-sinfondo.svg");
define("IMG_LOGO_FONDOVERDE", IMG_PATH . "logo-fondoverde.svg");
define("IMG_FOTO_PERFIL_JUGADOR", IMG_PATH . "foto_perfil_jugador.png");
define("IMG_BANNER_JUGADOR_DEFAULT", IMG_PATH . "banner_jugador.png");
define("IMG_BANNER_PERFIL_CANCHA_DEFAULT", IMG_PATH . "banner_perfil_cancha.png");
define("IMG_BG2", IMG_PATH . "bg2.jpg");
define("IMG_CANCHA_DEFAULT", IMG_PATH . "foto_cancha_default.png");
define("IMG_PARTIDO_DEFAULT", IMG_PATH . "foto_partido_default.png");
define("IMG_EQUIPO_DEFAULT", IMG_PATH . "foto_perfil_equipo_default.png");

// ===================================
// COMPONENTES PHP
// ===================================
define("HEAD_COMPONENT", __DIR__ . "/head.php");
define("AUTH_REQUIRED_COMPONENT", __DIR__ . "/auth-required.php");
define("MODAL_LOGIN_COMPONENT", __DIR__ . "/modalLogin.php");
define("NAVBAR_JUGADOR_COMPONENT", __DIR__ . "/navbarJugador.php");
define("NAVBAR_GUEST_COMPONENT", __DIR__ . "/navbarGuest.php");
define("NAVBAR_ADMIN_CANCHA_COMPONENT", __DIR__ . "/navbarAdminCancha.php");
define("NAVBAR_ADMIN_SISTEMA_COMPONENT", __DIR__ . "/navbarAdminSistema.php");

// ===================================
// CONTROLADORES
// ===================================
define("CONTROLLER_LOGIN", BASE_URL . "src/controllers/login_controller.php");
define("CONTROLLER_LOGOUT", BASE_URL . "src/controllers/logout.php");
define("CONTROLLER_REGISTRO_JUGADOR", BASE_URL . "src/controllers/registroJugador_controller.php");
define("CONTROLLER_REGISTRO_ADMIN_CANCHA", BASE_URL . "src/controllers/registroAdminCancha_controller.php");
define("GET_PARTIDOS_JUGADOR", BASE_URL . "src/controllers/getPartidos_Jugador.php");
define("GET_CANCHAS_DISPONIBLES_JUGADOR", BASE_URL . "src/controllers/getCanchasDisponibles_Jugador.php");
define("GET_PARTIDOS_DISPONIBLES_JUGADOR", BASE_URL . "src/controllers/getPartidosDisponibles_Jugador.php");
define("POST_SOLICITANTE_PARTIDO_JUGADOR", BASE_URL . "src/controllers/postSolicitantePartido_Jugador.php");

// EQUIPOS
define("GET_EQUIPOS_JUGADOR", BASE_URL . "src/controllers/getEquipos_Jugador.php");
define("GET_EQUIPO_JUGADOR", BASE_URL . "src/controllers/getEquipo_Jugador.php");
define("POST_EQUIPO_JUGADOR", BASE_URL . "src/controllers/postEquipo_Jugador.php");
define("POST_INVITAR_JUGADOR", BASE_URL . "src/controllers/postInvitarJugador_Equipo.php");
define("UPDATE_EQUIPO_JUGADOR", BASE_URL . "src/controllers/updateEquipo_Jugador.php");

//USUARIOS
define("GET_USUARIOS", BASE_URL . "src/controllers/getUsuarios.php");

// ===================================
// PÁGINAS - AUTH
// ===================================
define("PAGE_LANDING_PHP", PUBLIC_PATH . "HTML/auth/landing.php");
define("PAGE_FORGOT", PUBLIC_PATH . "HTML/auth/forgot.html");
define("PAGE_FORGOT_PHP", PUBLIC_PATH . "HTML/auth/forgot.php");
define("PAGE_REGISTER_JUGADOR", PUBLIC_PATH . "HTML/auth/register-jugador.html");
define("PAGE_REGISTRO_JUGADOR_PHP", PUBLIC_PATH . "HTML/auth/registroJugador.php");
define("PAGE_REGISTER_ADMIN_CANCHA", PUBLIC_PATH . "HTML/auth/register-admin-canchas.html");
define("PAGE_REGISTRO_ADMIN_CANCHA_PHP", PUBLIC_PATH . "HTML/auth/registroAdminCancha.php");

// ===================================
// COMPONENTES - RUTAS LOCALES PARA INCLUDES
// ===================================
define("CALENDARIO_COMPONENT", __DIR__ . "/../../public/HTML/calendario.php");
define("FILTRO_EXPLORAR_MODAL", __DIR__ . "/../../public/HTML/filtroExplorarModal.php");
define("PERFIL_CANCHA_COMPONENT", __DIR__ . "/../../public/HTML/perfilCancha.php");
define("PERFIL_EQUIPO_COMPONENT", __DIR__ . "/../../public/HTML/perfilEquipo.php");
define("PERFIL_JUGADOR_COMPONENT", __DIR__ . "/../../public/HTML/perfilJugador.php");
define("TORNEO_DETALLE_COMPONENT", __DIR__ . "/../../public/HTML/torneoDetalle.php");

// ===================================
// PÁGINAS - JUGADOR
// ===================================
define("PAGE_INICIO_JUGADOR", PUBLIC_PATH . "HTML/jugador/inicio_Jugador.php");
define("PAGE_MI_PERFIL_JUGADOR", PUBLIC_PATH . "HTML/jugador/miPerfil_Jugador.php");

// Equipos
define("PAGE_MIS_EQUIPOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misEquipos_Jugador.php");
define("PAGE_PERFIL_EQUIPO_JUGADOR", PUBLIC_PATH . "HTML/jugador/perfilEquipo_Jugador.php");

// Partidos
define("PAGE_MIS_PARTIDOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misPartidos_Jugador.php");
define("PAGE_PARTIDOS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/partidosExplorar_Jugador.php");

// Canchas
define("PAGE_CANCHAS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/canchasExplorar_Jugador.php");
define("PAGE_PERFIL_CANCHA_JUGADOR", PUBLIC_PATH . "HTML/jugador/perfilCancha_Jugador.php");
define("PAGE_CALENDARIO_CANCHA_JUGADOR", PUBLIC_PATH . "HTML/jugador/calendarioCancha_Jugador.php");

// Torneos
define("PAGE_TORNEOS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneosExplorar_Jugador.php");
define("PAGE_MIS_TORNEOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misTorneos_Jugador.php");
define("PAGE_TORNEO_DETALLE_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneoDetalle_Jugador.php");

// Otros Jugadores
define("PAGE_PERFIL_JUGADOR_EXTERNO", PUBLIC_PATH . "HTML/jugador/perfilJugadorExterno_Jugador.php");


// ===================================
// PÁGINAS - ADMIN CANCHA
// ===================================
define("PAGE_INICIO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/inicio_AdminCancha.php");
define("PAGE_AGENDA_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/agenda_AdminCancha.php");

// Gestión de canchas
define("PAGE_MIS_CANCHAS_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misCanchas_AdminCancha.php");
define("PAGE_MIS_PERFILES_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misPerfiles_AdminCancha.php");

// Gestión de torneos
define("PAGE_MIS_TORNEOS_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misTorneos_AdminCancha.php");
define("PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misTorneosDetalle_AdminCancha.php");

// Perfiles de equipos y jugadores
define("PAGE_PERFIL_EQUIPO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/perfilEquipo_AdminCancha.php");
define("PAGE_PERFIL_JUGADOR_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/perfilJugador_AdminCancha.php");

// ===================================
// PÁGINAS - ADMIN SISTEMA
// ===================================
define("PAGE_INICIO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/inicio_AdminSistema.php");
define("PAGE_LOGIN_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/login_AdminSistema.php");

// Gestión de jugadores
define("PAGE_JUGADORES_LISTADO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/jugadores_AdminSistema.php");
define("PAGE_JUGADORES_REPORTADOS_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/jugadoresReportados_AdminSistema.php");
define("PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/perfilJugador_AdminSistema.php");
define("PAGE_PERFIL_EQUIPO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/perfilEquipo_AdminSistema.php");


// Gestión de canchas (sistema)
define("PAGE_CANCHAS_LSITADO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/canchas_AdminSistema.php");
define("PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/canchasReportadas_AdminSistema.php");
define("PAGE_PERFIL_CANCHA_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/perfilCancha_AdminSistema.php");
