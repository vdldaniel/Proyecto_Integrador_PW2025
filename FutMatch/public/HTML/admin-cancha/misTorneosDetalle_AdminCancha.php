<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Verificar autenticación
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin_cancha') {
    header("Location: " . BASE_URL . "public/HTML/auth/login.php");
    exit;
}

// Obtener ID del torneo desde GET
$idTorneo = $_GET['id'] ?? null;

if (!$idTorneo) {
    header("Location: " . PAGE_MIS_TORNEOS_ADMIN_CANCHA);
    exit;
}

// Resalta la página actual en el navbar
$current_page = 'torneoDetalle';
$page_title = "Detalle del Torneo - FutMatch";
$page_css = [CSS_PAGES_DETALLE_TORNEO];

// Configuración del componente de torneo detalle - Vista Admin Cancha
$torneo_detalle_admin_mode = true;
$torneo_detalle_titulo_header = '<span id="torneo-nombre">Cargando...</span>';
$torneo_detalle_subtitulo_header = '<span id="torneo-fechas"></span>';
$torneo_detalle_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Detalles del torneo',
        'clase' => 'btn-dark',
        'icono' => 'bi bi-info-circle',
        'modal' => '#modalDetallesTorneo'
    ],
    [
        'tipo' => 'button',
        'texto' => 'Avanzar de fase',
        'clase' => 'btn-success',
        'icono' => 'bi bi-arrow-right-circle',
        'id' => 'btnAvanzarFaseTorneo'
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
    'max_equipos' => 0,
    'formato' => 'Eliminación directa',
    'descripcion' => ''
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de admin cancha
    require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Incluir componente de detalle de torneo
        include __DIR__ . '/../torneoDetalle.php';
        ?>
    </main>

    <!-- Modal Crear Reserva Partido -->
    <div class="modal fade" id="modalCrearReservaPartido" tabindex="-1" aria-labelledby="modalCrearReservaPartidoLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCrearReservaPartidoLabel">
                        <i class="bi bi-calendar-plus"></i> Programar Partido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearReservaPartido" novalidate>
                        <input type="hidden" id="idPartido" name="id_partido">
                        <input type="hidden" id="idTorneo" name="id_torneo">
                        <input type="hidden" id="idTipoReserva" name="id_tipo_reserva" value="2">

                        <div class="row">
                            <!-- Selección de Cancha -->
                            <div class="mb-3 col-12">
                                <label for="selectCancha" class="form-label">
                                    <i class="bi bi-geo-alt"></i> Cancha
                                </label>
                                <select class="form-select" id="selectCancha" name="id_cancha" required>
                                    <option value="">Seleccione una cancha...</option>
                                </select>
                                <div class="invalid-feedback">Por favor seleccione una cancha.</div>
                            </div>

                            <!-- Fecha -->
                            <div class="mb-3 col-12 col-lg-6">
                                <label for="fechaPartido" class="form-label">
                                    <i class="bi bi-calendar3"></i> Fecha
                                </label>
                                <input type="date" class="form-control" id="fechaPartido" name="fecha" required>
                                <div class="invalid-feedback">Seleccione una fecha válida.</div>
                                <small class="text-muted" id="rangoFechasTorneo"></small>
                            </div>

                            <!-- Hora Inicio -->
                            <div class="mb-3 col-6 col-lg-3">
                                <label for="horaInicio" class="form-label">
                                    <i class="bi bi-clock"></i> Hora Inicio
                                </label>
                                <input type="time" class="form-control" id="horaInicio" name="hora_inicio" required>
                                <div class="invalid-feedback">Hora requerida.</div>
                            </div>

                            <!-- Hora Fin -->
                            <div class="mb-3 col-6 col-lg-3">
                                <label for="horaFin" class="form-label">
                                    <i class="bi bi-clock-fill"></i> Hora Fin
                                </label>
                                <input type="time" class="form-control" id="horaFin" name="hora_fin" required>
                                <div class="invalid-feedback">Hora requerida.</div>
                            </div>

                            <!-- Título -->
                            <div class="mb-3 col-12">
                                <label for="tituloPartido" class="form-label">
                                    <i class="bi bi-card-text"></i> Título
                                </label>
                                <input type="text" class="form-control" id="tituloPartido" name="titulo" required>
                                <div class="invalid-feedback">El título es requerido.</div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-3 col-12">
                                <label for="descripcionPartido" class="form-label">
                                    <i class="bi bi-text-paragraph"></i> Descripción
                                </label>
                                <textarea class="form-control" id="descripcionPartido" name="descripcion" rows="2"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarReservaPartido">
                        <i class="bi bi-check-circle"></i> Programar Partido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Avanzar Fase -->
    <div class="modal fade" id="modalConfirmarAvanzarFase" tabindex="-1" aria-labelledby="modalConfirmarAvanzarFaseLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmarAvanzarFaseLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Confirmar Avance de Fase
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">¿Está seguro que desea avanzar a la siguiente fase?</p>
                    <p class="text-muted small mt-2">Esto creará los partidos de la siguiente ronda con los ganadores actuales. Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarAvanzarFase">
                        <i class="bi bi-arrow-right-circle"></i> Sí, avanzar fase
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ganador del Partido -->
    <div class="modal fade" id="modalGanadorPartido" tabindex="-1" aria-labelledby="modalGanadorPartidoLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGanadorPartidoLabel">
                        <i class="bi bi-trophy-fill"></i> Registrar Resultado del Partido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formGanadorPartido" novalidate>
                        <input type="hidden" id="idPartidoResultado" name="id_partido">

                        <div class="text-center mb-3">
                            <small class="text-muted" id="fasePartidoResultado"></small>
                        </div>

                        <!-- Equipo A -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h6 class="mb-0" id="equipoANombreResultado">Equipo A</h6>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label mb-1 small">Goles</label>
                                        <input type="number" class="form-control" id="golesEquipoA" name="goles_equipo_A" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- VS -->
                        <div class="text-center mb-3">
                            <span class="badge text-bg-secondary">VS</span>
                        </div>

                        <!-- Equipo B -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h6 class="mb-0" id="equipoBNombreResultado">Equipo B</h6>
                                    </div>
                                    <div class="col-4">
                                        <label class="form-label mb-1 small">Goles</label>
                                        <input type="number" class="form-control" id="golesEquipoB" name="goles_equipo_B" min="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="invalid-feedback d-block" id="errorResultado" style="display: none !important;"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarResultado">
                        <i class="bi bi-check-circle"></i> Guardar Resultado
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalles del Torneo -->
    <div class="modal fade" id="modalDetallesTorneo" tabindex="-1" aria-labelledby="modalDetallesTorneoLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesTorneoLabel">
                        <i class="bi bi-trophy"></i> Detalles del Torneo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Información General -->
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Información General</h6>
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Nombre:</td>
                                        <td id="detalle-nombre">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Formato:</td>
                                        <td>Eliminación directa</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Fecha de Inicio:</td>
                                        <td id="detalle-fecha-inicio">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Fecha de Fin:</td>
                                        <td id="detalle-fecha-fin">-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Estado:</td>
                                        <td><span class="badge text-bg-dark" id="detalle-estado">-</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas del Torneo -->
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold">Estadísticas</h6>
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Equipos Participantes:</td>
                                        <td><span id="detalle-equipos-registrados">0</span> / <span id="detalle-max-equipos">0</span> equipos</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Cierre de Inscripciones:</td>
                                        <td id="detalle-cierre-inscripciones">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Descripción -->
                        <div class="col-12 mb-3">
                            <h6 class="fw-bold">Descripción</h6>
                            <p class="text-muted" id="detalle-descripcion">-</p>
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
        const BASE_URL = "<?= BASE_URL ?>";
        const ID_TORNEO = <?= $idTorneo ?>;
        const GET_DETALLE_TORNEO = "<?= BASE_URL ?>src/controllers/torneos/getDetalleTorneo.php";
        const GET_EQUIPOS_TORNEO = "<?= BASE_URL ?>src/controllers/torneos/getEquiposTorneo.php";
        const GET_PARTIDOS_TORNEO = "<?= BASE_URL ?>src/controllers/torneos/getPartidosTorneo.php";
        const GET_CANCHAS_ADMIN_CANCHA = "<?= GET_CANCHAS_ADMIN_CANCHA ?>";
        const ENDPOINT_CREAR_RESERVA_PARTIDO = "<?= BASE_URL ?>src/controllers/torneos/crear_reserva_partido.php";
        const ENDPOINT_ACTUALIZAR_RESULTADO_PARTIDO = "<?= BASE_URL ?>src/controllers/torneos/actualizarPartidoTorneo.php";
        const ENDPOINT_AVANZAR_FASE_TORNEO = "<?= BASE_URL ?>src/controllers/torneos/avanzarFaseTorneo.php";
    </script>
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_TORNEO_DETALLE ?>"></script>
</body>

</html>