<?php

// Cargar configuración
require_once '../../../src/app/config.php';

// Definir la página actual para el navbar
$current_page = 'perfilJugadorExterno';

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Perfil de Jugador - FutMatch";

$page_css = [CSS_PAGES_PERFILES];
$page_js = [JS_PERFIL_JUGADOR];

// Variables para el componente perfilJugador.php
$perfil_jugador_admin_mode = false;
$perfil_jugador_es_propio = false;
$perfil_jugador_titulo_header = 'Perfil de Jugador';
$perfil_jugador_subtitulo_header = 'Información y estadísticas del jugador';
$perfil_jugador_titulo_partidos = 'Partidos Recientes';
$perfil_jugador_titulo_estadisticas = 'Estadísticas del Jugador';
$perfil_jugador_mostrar_reportar = true;
$perfil_jugador_mostrar_equipos = true;

$perfil_jugador_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Volver',
        'icono' => 'bi-arrow-left',
        'clase' => 'btn-dark',
        'id' => 'btnVolver'
    ],
    [
        'tipo' => 'button',
        'texto' => 'Reportar',
        'icono' => 'bi-flag',
        'clase' => 'btn-dark',
        'modal' => '#modalReportarJugador'
    ]
];

include HEAD_COMPONENT;

?>

<body>
    <?php
    // Cargar navbar de jugador si está logueado
    if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'jugador') {
        $navbar_jugador_active = true;
        require_once NAVBAR_JUGADOR_COMPONENT;
    } else {
        $navbar_jugador_active = false;
        require_once NAVBAR_GUEST_COMPONENT;
    }
    ?>

    <main>
        <div class="container mt-4">
            <?php include PERFIL_JUGADOR_COMPONENT; ?>
        </div>
    </main>


    <script>
        // Funcionalidad específica para perfil externo
        document.addEventListener('DOMContentLoaded', function() {
            // Botón volver
            const btnVolver = document.getElementById('btnVolver');
            if (btnVolver) {
                btnVolver.addEventListener('click', function() {
                    // Volver a la página anterior
                    if (document.referrer) {
                        window.history.back();
                    } else {
                        // Fallback si no hay referrer
                        window.location.href = '<?= PAGE_MIS_PARTIDOS_JUGADOR ?>';
                    }
                });
            }
        });
    </script>

    <!-- Scripts -->
    <!-- Están en perfilJugador.php -->

</body>

</html>