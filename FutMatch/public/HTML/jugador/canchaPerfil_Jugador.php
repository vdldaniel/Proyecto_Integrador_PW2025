<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfilJugador';
$page_title = "Perfil de Cancha - FutMatch";
$page_css = [CSS_PAGES_PERFILES];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de jugador
    require_once NAVBAR_JUGADOR_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Variables de configuración para el componente perfil de cancha (modo jugador)
        $perfil_cancha_admin_mode = false;
        $perfil_cancha_mostrar_selector = false;
        $perfil_cancha_titulo_seccion = 'Torneos Disponibles';
        $perfil_cancha_descripcion = 'Información detallada y disponibilidad de la cancha';
        $perfil_cancha_boton_primario = [
            'texto' => 'Ver disponibilidad',
            'icono' => 'bi-calendar-plus',
            'url' => PAGE_CALENDARIO_CANCHA_JUGADOR
        ];

        // Información específica de la cancha (normalmente vendría de BD)
        $perfil_cancha_nombre = 'MegaFutbol Cancha A1-F5';
        $perfil_cancha_descripcion_banner = 'Cancha de césped sintético de última generación con iluminación LED profesional. Ideal para partidos de Fútbol 5 con excelente drenaje y superficie antideslizante.';
        $perfil_cancha_direccion = 'Av. Corrientes 1234, CABA, Buenos Aires, Argentina';
        $perfil_cancha_tipo = 'Fútbol 5';
        $perfil_cancha_superficie = 'Césped sintético';
        $perfil_cancha_capacidad = '10 jugadores';
        $perfil_cancha_calificacion = '4.8';
        $perfil_cancha_total_resenas = '127';
        $perfil_cancha_total_jugadores = '342';
        $perfil_cancha_total_partidos = '156';
        $perfil_cancha_dias_atencion = 'Lunes a Domingo';
        $perfil_cancha_horario = '07:00 - 23:00';
        $perfil_cancha_estado_actual = 'Abierto ahora';
        $perfil_cancha_hora_cierre = '23:00';

        // Incluir componente de perfil de cancha
        include PERFIL_CANCHA_COMPONENT;
        ?>
    </main>



    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <!-- Script base del perfil de cancha (debe ir primero) -->
    <script src="<?= JS_PERFIL_CANCHA_BASE ?>"></script>
    <!-- Script específico del jugador (extiende el base) -->
    <script src="<?= BASE_URL ?>src/scripts/pages/cancha-perfil-jugador.js"></script>
</body>

</html>