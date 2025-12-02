<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'calendarioCancha';
$page_title = "Calendario de Cancha - FutMatch";
$page_css = [CSS_PAGES_AGENDA];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body class="monthly-view-active">
    <?php
    // Cargar navbar de jugador
    require_once NAVBAR_JUGADOR_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <!-- Línea 1: Header con título y botones de navegación -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <a class="btn btn-dark me-3" href="<?= PAGE_PERFIL_CANCHA_JUGADOR ?>" id="btnVolverPerfil">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="fw-bold mb-1" id="displayFechaActual">Calendario</h1>
                        <p class="text-muted mb-0" id="subtituloCancha">Cargando información de la cancha...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información específica de la cancha del jugador -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body py-2">
                        <h6 class="mb-0" id="nombreCanchaHeader">
                            <i class="bi bi-building"></i> <span id="nombreCanchaTexto">Cargando...</span>
                        </h6>
                        <small class="text-muted" id="detalleCanchaTexto">Cargando detalles...</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info mb-0 d-flex justify-content-between align-items-center">
                    <div id="infoHorarios">
                        <i class="bi bi-info-circle me-2"></i>
                        <span id="textoHorarios">Cargando horarios...</span>
                    </div>
                    <button class="btn btn-sm btn-dark" id="btnVerPoliticas1">
                        <i class="bi bi-file-text"></i> Políticas
                    </button>
                </div>
            </div>
        </div>

        <!-- Incluir el componente de calendario -->
        <?php
        // Configurar calendario sin selector (cancha fija desde URL)
        $calendario_mostrar_selector = false;
        include CALENDARIO_COMPONENT;
        ?>

    </main>



    <!-- Modal de Políticas de Reserva -->
    <div class="modal fade" id="modalPoliticas" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalPoliticasLabel">
                        <i class="bi bi-file-text"></i> Políticas de Reserva
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoPoliticas">
                    <p class="text-center text-muted">Cargando políticas...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reservar Cancha -->
    <div class="modal fade" id="modalReservarCancha" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-send"></i> Solicitar Reserva de Cancha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info" id="alertInfoCancha">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong id="nombreCanchaModal">Cargando...</strong> - Envía tu solicitud y el administrador te contactará para confirmar la disponibilidad.
                    </div>
                    <form id="formReservarCancha">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fechaComienzo" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="fechaComienzo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="horaComienzo" class="form-label">Hora</label>
                                <select class="form-select" id="horaComienzo" required>
                                    <option value="">Seleccionar hora</option>
                                    <!-- Opciones generadas por JS -->
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="comentariosReserva" class="form-label">Comentarios adicionales (opcional)</label>
                            <textarea class="form-control" id="comentariosReserva" rows="3" placeholder="Alguna solicitud especial..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnEnviarSolicitud">
                        <i class="bi bi-send"></i> Solicitar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Constantes JavaScript -->
    <script>
        const GET_INFO_PERFIL = '<?= GET_INFO_PERFIL ?>';
        const GET_HORARIOS_CANCHAS = '<?= GET_HORARIOS_CANCHAS ?>';
        const GET_RESERVAS = '<?= GET_RESERVAS ?>';
        const GET_RESERVA_DETALLE = '<?= GET_RESERVA_DETALLE ?>';
        const POST_RESERVA = '<?= POST_RESERVA ?>';
        const BASE_URL = '<?= BASE_URL ?>';
        const PAGE_PERFIL_CANCHA_JUGADOR = '<?= PAGE_PERFIL_CANCHA_JUGADOR ?>';

        // Obtener id_cancha del query string
        const urlParams = new URLSearchParams(window.location.search);
        const ID_CANCHA = urlParams.get('id') || urlParams.get('id_cancha');

        if (!ID_CANCHA) {
            console.error('No se proporcionó un ID de cancha en la URL');
            alert('Error: No se especificó una cancha');
        }
    </script>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_AGENDA ?>"></script>
    <script src="<?= JS_CALENDARIO_JUGADOR ?>"></script>

</body>

</html>