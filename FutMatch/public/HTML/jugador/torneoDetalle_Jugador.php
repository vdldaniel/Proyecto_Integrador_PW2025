<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once AUTH_REQUIRED_COMPONENT;

// Resalta la página actual en el navbar
$current_page = 'torneoDetalle';
$page_title = "Detalle del Torneo - FutMatch";
$page_css = [CSS_PAGES_DETALLE_TORNEO];

// Configuración del componente de torneo detalle - Vista Jugador
$torneo_detalle_admin_mode = false;
$torneo_detalle_titulo_header = 'Copa FutMatch 2025';
$torneo_detalle_subtitulo_header = '11/10/2025 - 15/12/2025';
$torneo_detalle_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Info del torneo',
        'clase' => 'btn-dark',
        'icono' => 'bi bi-info-circle',
        'modal' => '#modalDetallesTorneo'
    ],
    [
        'tipo' => 'link',
        'texto' => 'Inscribir mi equipo',
        'clase' => 'btn-primary',
        'icono' => 'bi bi-plus-circle',
        'url' => 'equipoCrear.php'
    ]
];
$torneo_detalle_mostrar_pestanas = ['bracket', 'equipos'];
$torneo_detalle_datos_torneo = [
    'nombre' => 'Copa FutMatch 2025',
    'fecha_inicio' => '11/10/2025',
    'fecha_fin' => '15/12/2025',
    'estado' => 'En curso',
    'equipos_registrados' => 16,
    'formato' => 'Eliminación directa',
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
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

    <!-- Contenido Principal -->
    <main class="container mt-4">

        <?php include TORNEO_DETALLE_COMPONENT; ?>

    </main>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_TORNEO_DETALLE ?>"></script>
</body>

</html>