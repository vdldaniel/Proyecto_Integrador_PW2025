<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Obtener ID del torneo desde GET
$idTorneo = $_GET['id'] ?? null;

if (!$idTorneo) {
    header("Location: " . PAGE_TORNEOS_EXPLORAR_JUGADOR);
    exit;
}

// Resalta la página actual en el navbar
$current_page = 'torneoDetalle';
$page_title = "Detalle del Torneo - FutMatch";
$page_css = [CSS_PAGES_DETALLE_TORNEO];

// Configuración del componente de torneo detalle - Vista Jugador (solo lectura)
$torneo_detalle_admin_mode = false;
$torneo_detalle_titulo_header = '<span id="torneo-nombre">Cargando...</span>';
$torneo_detalle_subtitulo_header = '<span id="torneo-fechas">Cargando...</span>';
$torneo_detalle_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Info del torneo',
        'clase' => 'btn-dark',
        'icono' => 'bi bi-info-circle',
        'modal' => '#modalDetallesTorneo'
    ]
];
$torneo_detalle_mostrar_pestanas = ['bracket', 'equipos'];
$torneo_detalle_datos_torneo = [
    'id_torneo' => $idTorneo,
    'nombre' => '',
    'fecha_inicio' => '',
    'fecha_fin' => '',
    'estado' => '',
    'equipos_registrados' => 0,
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

    <!-- Modal Detalles del Torneo -->
    <div class="modal fade" id="modalDetallesTorneo" tabindex="-1" aria-labelledby="modalDetallesTorneoLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesTorneoLabel">
                        <i class="bi bi-info-circle"></i> Información del Torneo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre del torneo</label>
                            <p class="form-control-plaintext" id="detalle-nombre">Cargando...</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha de inicio</label>
                            <p class="form-control-plaintext" id="detalle-fecha-inicio">-</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Fecha de finalización</label>
                            <p class="form-control-plaintext" id="detalle-fecha-fin">-</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado</label>
                            <p class="form-control-plaintext"><span class="badge text-bg-dark" id="detalle-estado">-</span></p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Equipos registrados</label>
                            <p class="form-control-plaintext" id="detalle-equipos-registrados">0</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Máximo de equipos</label>
                            <p class="form-control-plaintext" id="detalle-max-equipos">0</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">Descripción</label>
                            <p class="form-control-plaintext" id="detalle-descripcion">Sin descripción</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script>
        // Variable global para el ID del torneo
        const ID_TORNEO = <?= json_encode($idTorneo) ?>;
        const BASE_URL = "<?= BASE_URL ?>";
        const IMG_EQUIPO_DEFAULT = "<?= IMG_EQUIPO_DEFAULT ?>";
    </script>
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_TORNEO_DETALLE_JUGADOR ?>"></script>
</body>

</html>