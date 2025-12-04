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
// SESIÓN
// ===================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===================================
// RUTAS BASE
// ===================================
define("BASE_URL", "/Proyecto_Integrador_PW2025/FutMatch/"); // Ajusta según tu configuración de servidor
define("PUBLIC_PATH", BASE_URL . "public/");
define("SRC_PATH", BASE_URL . "src/");

// Ruta del archivo de configuración
define("CONFIG_PATH", __DIR__ . "/config.php");

// ASSETS
define("ASSETS_PATH", PUBLIC_PATH . "assets/");
define("CSS_BOOTSTRAP", ASSETS_PATH . "css/bootstrap.min.css");
define("JS_BOOTSTRAP", ASSETS_PATH . "js/bootstrap.bundle.min.js");
define("CSS_ICONS", "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css");
define("FONT_MONTSERRAT", "https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap");

// ===================================
// PAGINAS - FRONT
// ===================================

// admin-cancha
define("PAGE_AGENDA_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/agenda_AdminCancha.php");
define("PAGE_INICIO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/inicio_AdminCancha.php");
define("PAGE_MIS_CANCHAS_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misCanchas_AdminCancha.php");
define("PAGE_MIS_PERFILES_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misPerfiles_AdminCancha.php");
define("PAGE_MIS_TORNEOS_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misTorneos_AdminCancha.php");
define("PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/misTorneosDetalle_AdminCancha.php");
define("PAGE_PERFIL_EQUIPO_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/perfilEquipo_AdminCancha.php");
define("PAGE_PERFIL_JUGADOR_ADMIN_CANCHA", PUBLIC_PATH . "HTML/admin-cancha/perfilJugador_AdminCancha.php");

// admin-sistema
define("PAGE_SOLICITUDES_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/solicitudes_AdminSistema.php");
define("PAGE_CANCHAS_LISTADO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/canchas_AdminSistema.php");
define("PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/canchasReportadas_AdminSistema.php");
define("PAGE_INICIO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/inicio_AdminSistema.php");
define("PAGE_JUGADORES_LISTADO_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/jugadores_AdminSistema.php");
define("PAGE_JUGADORES_REPORTADOS_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/jugadoresReportados_AdminSistema.php");
define("PAGE_PERFIL_CANCHA_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/perfilCancha_AdminSistema.php");
define("PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA", PUBLIC_PATH . "HTML/admin-sistema/perfilJugador_AdminSistema.php");

// auth
define("PAGE_FORGOT_PHP", PUBLIC_PATH . "HTML/auth/forgot.php");
define("PAGE_LANDING_PHP", PUBLIC_PATH . "HTML/auth/landing.php");
define("PAGE_REGISTRO_JUGADOR_PHP", PUBLIC_PATH . "HTML/auth/registroJugador.php");
define("PAGE_REGISTRO_ADMIN_CANCHA_PHP", PUBLIC_PATH . "HTML/auth/registroAdminCancha.php");

// jugador
define("PAGE_CALENDARIO_CANCHA_JUGADOR", PUBLIC_PATH . "HTML/jugador/calendarioCancha_Jugador.php");
define("PAGE_CANCHAS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/canchasExplorar_Jugador.php");
define("PAGE_INICIO_JUGADOR", PUBLIC_PATH . "HTML/jugador/inicio_Jugador.php");
define("PAGE_MI_PERFIL_JUGADOR", PUBLIC_PATH . "HTML/jugador/miPerfil_Jugador.php");
define("PAGE_MIS_EQUIPOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misEquipos_Jugador.php");
define("PAGE_MIS_PARTIDOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misPartidos_Jugador.php");
define("PAGE_MIS_TORNEOS_JUGADOR", PUBLIC_PATH . "HTML/jugador/misTorneos_Jugador.php");
define("PAGE_PARTIDOS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/partidosExplorar_Jugador.php");
define("PAGE_PERFIL_CANCHA_JUGADOR", PUBLIC_PATH . "HTML/jugador/perfilCancha_Jugador.php");
define("PAGE_PERFIL_EQUIPO_JUGADOR", PUBLIC_PATH . "HTML/jugador/perfilEquipo_Jugador.php");
define("PAGE_PERFIL_JUGADOR_EXTERNO", PUBLIC_PATH . "HTML/jugador/perfilJugadorExterno_Jugador.php");
define("PAGE_TORNEO_DETALLE_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneoDetalle_Jugador.php");
define("PAGE_TORNEOS_EXPLORAR_JUGADOR", PUBLIC_PATH . "HTML/jugador/torneosExplorar_Jugador.php");


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
// IMAGENES Y UPLOADS
// ===================================
define("IMG_PATH", PUBLIC_PATH . "img/");
define("IMG_BANNER_PERFIL_CANCHA_DEFAULT", IMG_PATH . "banner_perfil_cancha.png");
define("IMG_BANNER_PERFIL_JUGADOR_DEFAULT", IMG_PATH . "banner_perfil_jugador.png");
define("IMG_CANCHA_DEFAULT", IMG_PATH . "foto_cancha_default.png");
define("IMG_PARTIDO_DEFAULT", IMG_PATH . "foto_partido_default.png");
define("IMG_EQUIPO_DEFAULT", IMG_PATH . "foto_perfil_equipo_default.png");
define("IMG_FOTO_PERFIL_JUGADOR", IMG_PATH . "foto_perfil_jugador.png");
define("IMG_LANDING", IMG_PATH . "landing.jpg");
define("IMG_LOGO_FONDOVERDE", IMG_PATH . "logo-fondoverde.svg");
define("IMG_LOGO_SINFONDO", IMG_PATH . "logo-sinfondo.svg");

define("UPLOADS_PATH", PUBLIC_PATH . "uploads/");
define("UPLOADS_CANCHAS_PATH", UPLOADS_PATH . "canchas/");
define("UPLOADS_JUGADORES_PATH", UPLOADS_PATH . "jugadores/");
define("UPLOADS_EQUIPOS_PATH", UPLOADS_PATH . "equipos/");


// ===================================
// SRC - CONTROLLERS, SCRIPTS Y ESTILOS
// ===================================

// APP - MODULOS REUTILIZABLES
define("AUTH_REQUIRED_COMPONENT", __DIR__ . "/auth-required.php");
define("HEAD_COMPONENT", __DIR__ . "/head.php");
define("MODAL_LOGIN_COMPONENT", __DIR__ . "/modalLogin.php");
define("NAVBAR_ADMIN_CANCHA_COMPONENT", __DIR__ . "/navbarAdminCancha.php");
define("NAVBAR_ADMIN_SISTEMA_COMPONENT", __DIR__ . "/navbarAdminSistema.php");
define("NAVBAR_GUEST_COMPONENT", __DIR__ . "/navbarGuest.php");
define("NAVBAR_JUGADOR_COMPONENT", __DIR__ . "/navbarJugador.php");

// ===================================
// CONTROLLERS
// ===================================

// admin-cancha
define("GET_CANCHAS_ADMIN_CANCHA", BASE_URL . "src/controllers/admin-cancha/get_canchas.php");


// admin-sistema
define("GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA", BASE_URL . "src/controllers/admin-sistema/getSolicitudesAdminCancha.php");
define("UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA", BASE_URL . "src/controllers/admin-sistema/updateSolicitudAdminCancha.php");
define("GET_CANCHAS_PENDIENTES_ADMIN_SISTEMA", BASE_URL . "src/controllers/admin-sistema/getCanchasPendientes.php");
define("UPDATE_CANCHA_ADMIN_SISTEMA", BASE_URL . "src/controllers/admin-sistema/updateCancha.php");

// partidos
define("GET_PARTICIPANTES_PARTIDO", BASE_URL . "src/controllers/partidos/getParticipantesPartido.php");
define("GET_PARTIDOS_JUGADOR", BASE_URL . "src/controllers/partidos/getPartidos_Jugador.php");
define("POST_PARTICIPANTE_PARTIDO", BASE_URL . "src/controllers/partidos/postParticipante_Partido.php");
define("UPDATE_PARTIDO", BASE_URL . "src/controllers/partidos/updatePartido.php");

// reservas
define("GET_DISPONIBILIDAD", BASE_URL . "src/controllers/reservas/getDisponibilidad.php");
define("GET_HORARIOS_CANCHAS", BASE_URL . "src/controllers/reservas/getHorariosCanchas.php");
define("GET_RESERVA_DETALLE", BASE_URL . "src/controllers/reservas/getReservaDetalle.php");
define("GET_RESERVAS", BASE_URL . "src/controllers/reservas/getReservas.php");
define("GET_TIPOS_RESERVA", BASE_URL . "src/controllers/reservas/getTiposReserva.php");
define("POST_RESERVA", BASE_URL . "src/controllers/reservas/postReserva.php");
define("UPDATE_HORARIOS_CANCHAS", BASE_URL . "src/controllers/reservas/updateHorariosCanchas.php");
define("UPDATE_RESERVA", BASE_URL . "src/controllers/reservas/updateReserva.php");
define("UPDATE_POLITICAS_CANCHA", BASE_URL . "src/controllers/reservas/updatePoliticasCancha.php");

// usuarios
define("UPDATE_USUARIO", BASE_URL . "src/controllers/usuarios/updateUsuario.php");

// resto
define("CONTROLLER_GEOCODING_PROXY", BASE_URL . "src/controllers/geocoding_proxy.php");
define("GET_CANCHAS_DISPONIBLES_JUGADOR", BASE_URL . "src/controllers/getCanchasDisponibles_Jugador.php"); //EXPLORAR
define("GET_EQUIPO_JUGADOR", BASE_URL . "src/controllers/getEquipo_Jugador.php");
define("GET_EQUIPOS_JUGADOR", BASE_URL . "src/controllers/getEquipos_Jugador.php");
define("GET_ESTADISTICAS_JUGADOR", BASE_URL . "src/controllers/getEstadisticas_Jugador.php");
define("GET_INFO_PERFIL", BASE_URL . "src/controllers/getInfoPerfil.php");                     //MIS PARTIDOS
define("GET_PARTIDOS_DISPONIBLES_JUGADOR", BASE_URL . "src/controllers/getPartidosDisponibles_Jugador.php"); //EXPLORAR
define("GET_RESEÑAS_JUGADORES", BASE_URL . "src/controllers/getReseñas_Jugadores.php");
define("GET_USUARIOS", BASE_URL . "src/controllers/getUsuarios.php");
define("CONTROLLER_LOGIN", BASE_URL . "src/controllers/login_controller.php");
define("CONTROLLER_LOGOUT", BASE_URL . "src/controllers/logout.php");
define("POST_EQUIPO_JUGADOR", BASE_URL . "src/controllers/postEquipo_Jugador.php");
define("POST_FOTOS_JUGADOR", BASE_URL . "src/controllers/postFotos_Jugador.php");
define("POST_INVITAR_JUGADOR", BASE_URL . "src/controllers/postInvitarJugador_Equipo.php");
define("POST_SOLICITANTE_PARTIDO_JUGADOR", BASE_URL . "src/controllers/postSolicitantePartido_Jugador.php"); //SOLICITAR PARTICIPACIÓN
define("CONTROLLER_REGISTRO_ADMIN_CANCHA", BASE_URL . "src/controllers/registroAdminCancha_controller.php");
define("CONTROLLER_REGISTRO_JUGADOR", BASE_URL . "src/controllers/registroJugador_controller.php");
define("UPDATE_EQUIPO_JUGADOR", BASE_URL . "src/controllers/updateEquipo_Jugador.php");
define("UPDATE_JUGADORES_EQUIPOS", BASE_URL . "src/controllers/updateJugadores_Equipos.php");


// ===================================
// JS - RUTAS DE SCRIPTS
// ===================================
define("JS_SCRIPTS_PATH", SRC_PATH . "scripts/pages/");

//=================================
// COMPONENTES - REUTILIZABLES
//=================================
define("JS_COMPONENTS_PATH", SRC_PATH . "scripts/components/");

define("JS_PERFILES", JS_COMPONENTS_PATH . "perfiles.js");
define("JS_NAVBAR_JUGADOR", JS_COMPONENTS_PATH . "navbar_Jugador.js");
define("JS_NAVBAR_ADMIN_CANCHA", JS_COMPONENTS_PATH . "navbarAdminCancha.js");
define("JS_NAVBAR_ADMIN_SISTEMA", JS_COMPONENTS_PATH . "navbar_AdminSistema.js");
define("JS_NOTIFICACIONES_JUGADOR", JS_COMPONENTS_PATH . "notificaciones_Jugador.js");
define("JS_PERFIL_CANCHA_BASE", JS_COMPONENTS_PATH . "perfilCancha.js");
define("JS_PERFIL_JUGADOR_BASE", JS_COMPONENTS_PATH . "perfilJugador.js");
define("JS_TOAST_MODULE", JS_COMPONENTS_PATH . "toast.js");
define("JS_AGENDA", JS_COMPONENTS_PATH . "agenda.js");

//=================================
// PAGES
//=================================
define("JS_PAGES_PATH", SRC_PATH . "scripts/pages/");

// Usuarios
define("JS_UPDATE_USUARIO", SRC_PATH . "scripts/usuarios/updateUsuario.js");

// admin-cancha
define("JS_CANCHAS_LISTADO", JS_PAGES_PATH . "admin-cancha/canchasListado.js");
define("JS_PERFILES_CANCHA", JS_PAGES_PATH . "admin-cancha/perfilAdmin.js");
define("JS_AGENDA_ADMIN_CANCHA", JS_PAGES_PATH . "admin-cancha/agenda_AdminCancha.js");
define("JS_AGENDA_ADMIN", JS_PAGES_PATH . "admin-cancha/agenda-admin.js");

// admin-sistema
define("JS_CANCHAS_ADMIN_SISTEMA", JS_PAGES_PATH . "admin-sistema/canchasAdminSistema.js");
define("JS_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA", JS_PAGES_PATH . "admin-sistema/solicitudesAdminCancha_AdminSistema.js");

// resto
define("JS_CALENDARIO_JUGADOR", JS_PAGES_PATH . "calendarioJugador.js");
define("JS_CACHA_PERFIL_JUGADOR", JS_PAGES_PATH . "cancha-perfil-jugador.js");
define("JS_CANCHAS_EXPLORAR_JUGADOR", JS_PAGES_PATH . "canchasExplorar_Jugador.js");
define("JS_FORGOT", JS_PAGES_PATH . "forgot.js");
define("JS_INICIO_ADMIN_SISTEMA", JS_PAGES_PATH . "inicio-admin-sistema.js");
define("JS_INICIO_ADMIN_CANCHA", JS_PAGES_PATH . "inicioAdminCancha.js");
define("JS_INICIO_JUGADOR", JS_PAGES_PATH . "inicioJugador.js");
define("JS_JUGADORES_ADMIN_SISTEMA", JS_PAGES_PATH . "jugadoresAdminSistema.js");
define("JS_LANDING", JS_PAGES_PATH . "landing.js");
define("JS_MIS_TORNEOS", JS_PAGES_PATH . "mis-torneos.js");
define("JS_MIS_EQUIPOS_JUGADOR", JS_PAGES_PATH . "misEquipos_Jugador.js");
define("JS_PARTIDOS_EXPLORAR_JUGADOR", JS_PAGES_PATH . "partidosExplorar_Jugador.js");
define("JS_PARTIDOS_JUGADOR", JS_PAGES_PATH . "partidosJugador.js");
define("JS_PERFILES_CANCHAS", JS_PAGES_PATH . "perfiles-canchas.js");
define("JS_REGISTRO_ADMIN_CANCHA", JS_PAGES_PATH . "registroAdminCancha.js");
define("JS_REGISTRO_JUGADOR", JS_PAGES_PATH . "registroJugador.js");
define("JS_TORNEO_DETALLE", JS_PAGES_PATH . "torneo-detalle.js");
define("JS_TORNEOS_JUGADOR", JS_PAGES_PATH . "torneos-jugador.js");


//RESTO FUERA DE PAGES
define("JS_CANCHAS_REPORTADAS_ADMIN_SISTEMA", SRC_PATH . "scripts/canchasReportadasAdminSistema.js");
define("JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA", SRC_PATH . "scripts/jugadoresReportadosAdminSistema.js");
define("JS_PERFIL_JUGADOR", SRC_PATH . "scripts/perfilJugador.js");



//A eliminar:
//define("JS_ADMIN_INC", JS_SCRIPTS_PATH . "adminInc.js");
//define("JS_CANCHA_RESERVAR", JS_SCRIPTS_PATH . "cancha-reservar.js");
//define("JS_CANCHA_PERFIL", JS_SCRIPTS_PATH . "canchaPerfil.js");
//define("JS_CUENTA_JUGADOR", JS_SCRIPTS_PATH . "cuenta-jugador.js");
//define("JS_DETALLE_TORNEO", JS_SCRIPTS_PATH . "detalle-torneo.js");
//define("JS_EQUIPO_CREAR", JS_SCRIPTS_PATH . "equipo-crear.js");
//define("JS_EQUIPOS_LISTADO", JS_SCRIPTS_PATH . "equipos-listado.js");
//define("JS_SCRIPT_MAPA", JS_SCRIPTS_PATH . "ScriptMapa.js");
//define("JS_TORNEO_LISTADO", JS_SCRIPTS_PATH . "torneo-listado.js");
// 
// torneos-exporar.js


// ===================================
// CSS - ARCHIVOS UNIFICADOS
// ===================================
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


// ===================================
// NOMBRES DE TABLAS -- dinamitar
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
