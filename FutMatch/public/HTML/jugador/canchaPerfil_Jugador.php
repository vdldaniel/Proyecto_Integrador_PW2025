<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfilJugador';
$page_title = "Perfil de Cancha - FutMatch";
// No necesitamos CSS específico adicional para esta página

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
        $perfil_cancha_boton_primario = [
            'texto' => 'Ver disponibilidad',
            'icono' => 'bi-calendar-plus',
            'url' => PAGE_CALENDARIO_CANCHA_JUGADOR
        ];

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