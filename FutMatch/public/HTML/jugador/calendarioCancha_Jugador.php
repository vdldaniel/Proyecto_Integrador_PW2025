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
        <!-- Línea 1: Header con título y botones de navegación -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <a class="btn btn-dark me-3" href="<?= PAGE_PERFIL_CANCHA_JUGADOR ?>" id="btnVolverPerfil">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="fw-bold mb-1" id="displayFechaActual">Calendario</h1>
                        <p class="text-muted mb-0" id="subtituloCancha"></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-0 d-flex align-items-center justify-content-end gap-2">
                    <button class="alert alert-info btn" id="btnInfoHorarios" data-bs-toggle="modal" data-bs-target="#modalHorarios">
                        <i class="bi bi-clock me-2"></i> Horarios
                    </button>
                    <button class="alert alert-info btn" id="btnVerPoliticas1" data-bs-toggle="modal" data-bs-target="#modalPoliticas">
                        <i class="bi bi-file-text me-2"></i> Políticas
                    </button>
                </div>
            </div>
        </div>

        <!-- Información específica de la cancha del jugador -->
        <div class="row mb-4 align-items-center">
        </div>

        <!-- Incluir el componente de calendario -->
        <?php
        // Configurar calendario sin selector (cancha fija desde URL)
        $calendario_mostrar_selector = false;
        include CALENDARIO_COMPONENT;
        ?>

    </main>



    <!-- Modal de Horarios -->
    <div class="modal fade" id="modalHorarios" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalHorariosLabel">
                        <i class="bi bi-clock"></i> Horarios de la Cancha
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoHorarios">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

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

    <!-- Modal de Confirmación Simple -->
    <div class="modal fade" id="modalConfirmacion" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalConfirmacionTitulo">Confirmar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalConfirmacionMensaje">
                    ¿Está seguro de realizar esta acción?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarAccion">Confirmar</button>
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
                                <label for="fechaComienzo" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fechaComienzo" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fechaFin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="fechaFin">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="horaInicio" class="form-label">Hora Inicio <span class="text-danger">*</span></label>
                                <select class="form-select" id="horaInicio" required>
                                    <option value="">Seleccionar hora</option>
                                    <!-- Opciones generadas por JS -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="horaFin" class="form-label">Hora Fin <span class="text-danger">*</span></label>
                                <select class="form-select" id="horaFin" required>
                                    <option value="">Seleccionar hora</option>
                                    <!-- Opciones generadas por JS -->
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tituloReserva" class="form-label">Título de la Reserva</label>
                            <input type="text" class="form-control" id="tituloReserva" placeholder="Ej: Partido amistoso, Entrenamiento...">
                        </div>
                        <div class="mb-3">
                            <label for="comentariosReserva" class="form-label">Descripción / Comentarios</label>
                            <textarea class="form-control" id="comentariosReserva" rows="3" placeholder="Descripción de la reserva o comentarios adicionales..."></textarea>
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
        const GET_DISPONIBILIDAD = '<?= GET_DISPONIBILIDAD ?>';
        const GET_RESERVAS = '<?= GET_RESERVAS ?>';
        const GET_RESERVA_DETALLE = '<?= GET_RESERVA_DETALLE ?>';
        const POST_RESERVA = '<?= POST_RESERVA ?>';
        const BASE_URL = '<?= BASE_URL ?>';
        const PAGE_PERFIL_CANCHA_JUGADOR = '<?= PAGE_PERFIL_CANCHA_JUGADOR ?>';
        const USUARIO_LOGUEADO = <?= isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'jugador' ? 'true' : 'false' ?>;

        // Obtener id_cancha del query string
        const urlParams = new URLSearchParams(window.location.search);
        const ID_CANCHA = urlParams.get('id') || urlParams.get('id_cancha');

        if (!ID_CANCHA) {
            console.error('No se proporcionó un ID de cancha en la URL');
            showToast("Error: No se especificó una cancha", "error");
        }
    </script>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_AGENDA ?>"></script>
    <script src="<?= JS_CALENDARIO_JUGADOR ?>"></script>

    <?php
    // Incluir modal de login si no está logueado
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'jugador') {
        require_once MODAL_LOGIN_COMPONENT;
    }
    ?>

</body>

</html>