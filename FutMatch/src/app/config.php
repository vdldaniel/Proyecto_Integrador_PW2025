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

// Foros
define('TABLE_FOROS', 'foros');
define('TABLE_RESPUESTAS_FOROS', 'respuestas_foros');

// Espacios (Sistema de visibilidad)
define('TABLE_ESPACIOS', 'espacios');
define('TABLE_TIPOS_ESPACIOS', 'tipos_espacios');
define('TABLE_USUARIOS_ESPACIOS', 'usuarios_espacios');

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
define("CSS_PAGES_INICIO_JUGADOR", SRC_PATH . "styles/pages/inicioJugador.css");
define("CSS_PAGES_DASHBOARD_ADMIN_CANCHA", SRC_PATH . "styles/pages/dashboardAdminCancha.css");
define("CSS_PAGES_FOROS_LISTADO", SRC_PATH . "styles/pages/foros-listado.css");
define("CSS_PAGES_MODALES_FOROS", SRC_PATH . "styles/pages/modales-foros.css");
define("CSS_PAGES_PARTIDOS_JUGADOR", SRC_PATH . "styles/pages/partidosJugador.css");
define("CSS_PAGES_PARTIDOS_EXPLORAR", SRC_PATH . "styles/pages/partidos-explorar.css");
define("CSS_PAGES_PARTIDOS_MODALES", SRC_PATH . "styles/pages/partidos-modales.css");
define("CSS_PAGES_DETALLE_TORNEO", SRC_PATH . "styles/pages/torneoDetalle.css");
define("CSS_PAGES_CANCHAS_EXPLORAR", SRC_PATH . "styles/pages/canchas-explorar.css");
define("CSS_PAGES_CANCHAS_ADMIN_SISTEMA", SRC_PATH . "styles/pages/canchasAdminSistema.css");

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
define("JS_CANCHAS_LISTADO", JS_SCRIPTS_PATH . "canchas-listado.js");
define("JS_CUENTA_JUGADOR", JS_SCRIPTS_PATH . "cuenta-jugador.js");
define("JS_DETALLE_TORNEO", JS_SCRIPTS_PATH . "detalle-torneo.js");
define("JS_EQUIPO_CREAR", JS_SCRIPTS_PATH . "equipo-crear.js");
define("JS_EQUIPOS_LISTADO", JS_SCRIPTS_PATH . "equipos-listado.js");
define("JS_FOROS_NUEVO_MODAL", JS_SCRIPTS_PATH . "forosNuevoModal.js");
define("JS_FORGOT", JS_SCRIPTS_PATH . "forgot.js");
define("JS_FOROS_LISTADO", JS_SCRIPTS_PATH . "foros-listado.js");
define("JS_INICIO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "inicioAdminCancha.js");
define("JS_INICIO_ADMIN_SISTEMA", JS_SCRIPTS_PATH . "inicio-admin-sistema.js");
define("JS_INICIO_JUGADOR", JS_SCRIPTS_PATH . "inicioJugador.js");
define("JS_LANDING", JS_SCRIPTS_PATH . "landing.js");
define("JS_PARTIDOS_JUGADOR", JS_SCRIPTS_PATH . "partidosJugador.js");
define("JS_PARTIDOS_EXPLORAR", JS_SCRIPTS_PATH . "partidos-explorar.js");
define("JS_REGISTRO_ADMIN_CANCHA", JS_SCRIPTS_PATH . "registroAdminCancha.js");
define("JS_REGISTRO_JUGADOR", JS_SCRIPTS_PATH . "registroJugador.js");
define("JS_SCRIPT_MAPA", JS_SCRIPTS_PATH . "ScriptMapa.js");
define("JS_TORNEO_LISTADO", JS_SCRIPTS_PATH . "torneo-listado.js");
define("JS_CANCHAS_EXPLORAR", JS_SCRIPTS_PATH . "canchas-explorar.js");
define("JS_MIS_TORNEOS", JS_SCRIPTS_PATH . "mis-torneos.js");
define("JS_TORNEO_DETALLE", JS_SCRIPTS_PATH . "torneo-detalle.js");
define("JS_TORNEOS_JUGADOR", JS_SCRIPTS_PATH . "pages/torneos-jugador.js");
define("JS_CANCHAS_ADMIN_SISTEMA", JS_SCRIPTS_PATH . "canchasAdminSistema.js");

// ===================================
// IMÁGENES
// ===================================
define("IMG_PATH", PUBLIC_PATH . "img/");
define("IMG_LOGO_SINFONDO", IMG_PATH . "logo-sinfondo.svg");
define("IMG_LOGO_FONDOVERDE", IMG_PATH . "logo-fondoverde.svg");
define("IMG_BG2", IMG_PATH . "bg2.jpg");

// ===================================
// COMPONENTES PHP
// ===================================
define("HEAD_COMPONENT", __DIR__ . "/head.php");
define("AUTH_REQUIRED_COMPONENT", __DIR__ . "/auth-required.php");
define("MODAL_LOGIN_COMPONENT", __DIR__ . "/modalLogin.php");
define("MODAL_NUEVO_FORO_COMPONENT", __DIR__ . "/../../public/HTML/forosNuevoModal.php");
define("MODAL_FOROS_BORRADORES", __DIR__ . "/../../public/HTML/forosBorradoresModal.php");
define("FILTRO_EXPLORAR_MODAL", __DIR__ . "/../../public/HTML/jugador/filtroExplorarModal.php");
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
// PÁGINAS - COMUNES
// ===================================
define("PAGE_FOROS_LISTADO", PUBLIC_PATH . "HTML/forosListado.php");

// ===================================
// COMPONENTES - RUTAS LOCALES PARA INCLUDES
// ===================================
define("CALENDARIO_COMPONENT", __DIR__ . "/../../public/HTML/calendario.php");
define("CANCHA_PERFIL_COMPONENT", __DIR__ . "/../../public/HTML/canchaPerfil.php");
define("PERFIL_JUGADOR_COMPONENT", __DIR__ . "/../../public/HTML/perfilJugador.php");

// ===================================
// PÁGINAS - JUGADOR
// ===================================
define("PAGE_INICIO_JUGADOR", PUBLIC_PATH . "HTML/jugador/inicioJugador.php");
define("PAGE_PERFIL_JUGADOR", PUBLIC_PATH . "HTML/jugador/miPerfilJugador.php");

// Equipos
define("PAGE_EQUIPOS_LISTADO", PUBLIC_PATH . "HTML/jugador/equiposListado.php");
define("PAGE_EQUIPO_DETALLE", PUBLIC_PATH . "HTML/jugador/equipoDetalle.php");

// Partidos
define("PAGE_PARTIDOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/partidosJugador.php");
define("PAGE_PARTIDOS_EXPLORAR", PUBLIC_PATH . "HTML/jugador/partidosExplorar.php");

// Canchas
define("PAGE_CANCHAS_EXPLORAR", PUBLIC_PATH . "HTML/jugador/canchasExplorar.php");
define("PAGE_CANCHA_PERFIL_JUGADOR", PUBLIC_PATH . "HTML/jugador/canchaPerfilJugador.php");
define("PAGE_CALENDARIO_CANCHA", PUBLIC_PATH . "HTML/jugador/calendarioCancha.php");

// Torneos
define("PAGE_TORNEOS_EXPLORAR", PUBLIC_PATH . "HTML/jugador/torneosExplorar.php");
define("PAGE_TORNEOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneosJugador.php");
define("PAGE_TORNEO_DETALLE_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneoDetalle.php");


// ===================================
// PÁGINAS - ADMIN CANCHA
// ===================================
define("PAGE_INICIO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/inicioAdminCancha.php");
define("PAGE_AGENDA", PUBLIC_PATH . "HTML/admin-cancha/agenda.php");

// Gestión de canchas
define("PAGE_ADMIN_CANCHAS_LISTADO", PUBLIC_PATH . "HTML/admin-cancha/canchasListado.php");
define("PAGE_ADMIN_CANCHA_CREAR", PUBLIC_PATH . "HTML/admin-cancha/canchaCrear.php");
define("PAGE_ADMIN_PERFILES_CANCHAS", PUBLIC_PATH . "HTML/admin-cancha/perfilesCanchas.php");

// Gestión de torneos
define("PAGE_ADMIN_MIS_TORNEOS", PUBLIC_PATH . "HTML/admin-cancha/misTorneos.php");
define("PAGE_ADMIN_TORNEO_DETALLE", PUBLIC_PATH . "HTML/admin-cancha/torneoDetalle.php");

// Gestión de equipos
define("PAGE_ADMIN_EQUIPO_PERFIL", PUBLIC_PATH . "HTML/equipoPerfil.php");

// ===================================
// PÁGINAS - ADMIN SISTEMA
// ===================================
define("PAGE_LOGIN_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/login-admin-sistema.html");
define("PAGE_INICIO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/inicioAdminSistema.php");
define("PAGE_ESTADISTICAS_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/estadisticasSistema.php");

// Gestión de jugadores
define("PAGE_SISTEMA_JUGADORES_LISTADO", PUBLIC_PATH . "HTML/admin-sistema/jugadoresAdminSistema.php");
define("PAGE_JUGADORES_REPORTADOS_ADMIN", PUBLIC_PATH . "HTML/admin-sistema/jugadoresReportadosAdmin.php");
define("PAGE_SISTEMA_JUGADOR_DETALLE", PUBLIC_PATH . "HTML/admin-sistema/jugador-detalle-sistema.html");

// Gestión de canchas (sistema)
define("PAGE_SISTEMA_CANCHAS_LISTADO", PUBLIC_PATH . "HTML/admin-sistema/canchasAdminSistema.php");
define("PAGE_CANCHAS_REPORTADAS_ADMIN", PUBLIC_PATH . "HTML/admin-sistema/canchasReportadasAdmin.php");
define("PAGE_SISTEMA_CANCHA_DETALLE", PUBLIC_PATH . "HTML/admin-sistema/cancha-detalle-sistema.html");
define("PAGE_SISTEMA_CANCHA_VERIFICAR", PUBLIC_PATH . "HTML/admin-sistema/cancha-verificar-sistema.html");
