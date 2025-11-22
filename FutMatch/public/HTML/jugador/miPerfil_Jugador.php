<?php

// Cargar configuración
require_once("../../../src/app/config.php");
require_once AUTH_REQUIRED_COMPONENT;

// Definir la página actual para el navbar
$current_page = 'miPerfilJugador';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Mi Perfil - FutMatch";

$page_css = [CSS_PAGES_PERFILES, CSS_PAGES_EQUIPOS_JUGADOR];

// Variables para el componente perfilJugador.php
$perfil_jugador_admin_mode = false;
$perfil_jugador_es_propio = true;
$perfil_jugador_titulo_header = 'Mi Perfil';
$perfil_jugador_subtitulo_header = 'Gestiona tu información personal y estadísticas de juego';
$perfil_jugador_titulo_partidos = 'Mis Partidos Recientes';
$perfil_jugador_titulo_estadisticas = 'Mis Estadísticas';
$perfil_jugador_mostrar_reportar = false;
$perfil_jugador_mostrar_equipos = true;

$perfil_jugador_botones_header = [
    [
        'tipo' => 'link',
        'texto' => 'Ver Partidos',
        'icono' => 'bi-calendar-check m-1',
        'clase' => 'btn-dark',
        'url' => PAGE_MIS_PARTIDOS_JUGADOR
    ],
    [
        'tipo' => 'link',
        'texto' => 'Ver Equipos',
        'icono' => 'bi-people m-1',
        'clase' => 'btn-dark',
        'url' => PAGE_MIS_EQUIPOS_JUGADOR
    ],
    [
        'tipo' => 'link',
        'texto' => 'Ver Torneos',
        'icono' => 'bi-trophy m-1',
        'clase' => 'btn-dark',
        'url' => PAGE_MIS_TORNEOS_JUGADOR
    ]/*,
    [
        'tipo' => 'button',
        'texto' => 'Editar Perfil',
        'icono' => 'bi-pencil-square',
        'clase' => 'btn-primary',
        'id' => 'btnEditarPerfil'
    ]*/
];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_JUGADOR_COMPONENT; ?>

    <main>
        <div class="container mt-4">
            <?php include PERFIL_JUGADOR_COMPONENT; ?>
        </div>
    </main>

    <!-- Scripts -->
    <!-- Los principales están en perfilJugador.php -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const CURRENT_USER_ID = '<?= $_SESSION['user_id'] ?>';
        const TIPO_PERFIL = 'jugador';
        const GET_INFO_PERFIL = '<?= GET_INFO_PERFIL ?>';
        const GET_PARTIDOS_JUGADOR = '<?= GET_PARTIDOS_JUGADOR ?>';
        const GET_RESEÑAS_JUGADORES = '<?= GET_RESEÑAS_JUGADORES ?>';
        //const GET_EQUIPOS_JUGADOR = '<?= GET_EQUIPOS_JUGADOR ?>';
        const GET_ESTADISTICAS_JUGADOR = '<?= GET_ESTADISTICAS_JUGADOR ?>';
        const POST_FOTOS_JUGADOR = '<?= POST_FOTOS_JUGADOR ?>';
    </script>

    <!--<script src="<?= JS_TOAST_MODULE ?>"></script>-->
    <script src="<?= JS_PERFIL_JUGADOR_BASE ?>"></script>


</body>

</html>