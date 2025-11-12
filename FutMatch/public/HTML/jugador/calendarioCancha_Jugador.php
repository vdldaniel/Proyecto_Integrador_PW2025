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
                    <a class="btn btn-dark me-3" href="<?= PAGE_PERFIL_CANCHA_JUGADOR ?>">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="fw-bold mb-1" id="displayFechaActual">Calendario - Noviembre 2025</h1>
                        <p class="text-muted mb-0">Disponibilidad de MegaFutbol Cancha A1-F5</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información específica de la cancha del jugador -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body py-2">
                        <h6 class="mb-0">
                            <i class="bi bi-building"></i> MegaFutbol Cancha A1-F5
                        </h6>
                        <small class="text-muted">Fútbol 5 • Césped sintético</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="alert alert-info mb-0 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Horarios:</strong> 7:00 AM - 11:00 PM •
                        <strong>Duración:</strong> 1-3 horas
                    </div>
                    <button class="btn btn-sm btn-dark" id="btnVerPoliticas1">
                        <i class="bi bi-file-text"></i> Políticas
                    </button>
                </div>
            </div>
        </div>

        <!-- Incluir el componente de calendario -->
        <?php include CALENDARIO_COMPONENT; ?>

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
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mt-4"><i class="bi bi-calendar-check text-success"></i> Reservas</h6>
                            <ul class="list-unstyled small">
                                <li>• Reservas con 24h de anticipación</li>
                                <li>• Confirmación automática disponible</li>
                                <li>• Máximo 3 reservas simultáneas por usuario</li>
                                <li>• Duración mínima: 1 hora</li>
                                <li>• Duración máxima: 3 horas consecutivas</li>
                            </ul>

                            <h6 class="mt-4"><i class="bi bi-arrow-clockwise text-warning"></i> Cancelaciones</h6>
                            <ul class="list-unstyled small">
                                <li>• Cancelación gratuita hasta 4h antes</li>
                                <li>• Entre 2-4h antes: 50% del costo</li>
                                <li>• Menos de 2h: sin reembolso</li>
                                <li>• Lluvia intensa: reembolso completo</li>
                            </ul>
                        </div>

                        <div class="col-md-6">

                            <h6 class="mt-4"><i class="bi bi-shield-check text-info"></i> Normas de Uso</h6>
                            <ul class="list-unstyled small">
                                <li>• Máximo 10 jugadores por cancha</li>
                                <li>• Prohibido fumar en las instalaciones</li>
                                <li>• Uso obligatorio de botines o zapatillas deportivas</li>
                                <li>• No se permite el ingreso de bebidas alcohólicas</li>
                                <li>• Responsabilidad por daños materiales</li>
                            </ul>

                            <h6 class="mt-4"><i class="bi bi-telephone text-primary"></i> Contacto</h6>
                            <ul class="list-unstyled small">
                                <li>• WhatsApp: +54 11 1234-5678</li>
                                <li>• Email: reservas@megafutbol.com</li>
                                <li>• Atención: Lun-Dom 8:00-22:00</li>
                            </ul>
                        </div>
                    </div>
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
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>MegaFutbol Cancha A1-F5</strong> - Envía tu solicitud y el administrador te contactará para confirmar la disponibilidad.
                    </div>
                    <form id="formReservarCancha">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fechaReserva" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="fechaReserva" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="horaReserva" class="form-label">Hora</label>
                                <select class="form-select" id="horaReserva" required>
                                    <option value="">Seleccionar hora</option>
                                    <option value="07:00">07:00 - 08:00</option>
                                    <option value="08:00">08:00 - 09:00</option>
                                    <option value="09:00">09:00 - 10:00</option>
                                    <option value="10:00">10:00 - 11:00</option>
                                    <option value="11:00">11:00 - 12:00</option>
                                    <option value="12:00">12:00 - 13:00</option>
                                    <option value="13:00">13:00 - 14:00</option>
                                    <option value="14:00">14:00 - 15:00</option>
                                    <option value="15:00">15:00 - 16:00</option>
                                    <option value="16:00">16:00 - 17:00</option>
                                    <option value="17:00">17:00 - 18:00</option>
                                    <option value="18:00">18:00 - 19:00</option>
                                    <option value="19:00">19:00 - 20:00</option>
                                    <option value="20:00">20:00 - 21:00</option>
                                    <option value="21:00">21:00 - 22:00</option>
                                    <option value="22:00">22:00 - 23:00</option>
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
    <!-- Scripts -->
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_AGENDA ?>"></script>
    <script src="<?= JS_CALENDARIO_JUGADOR ?>"></script>

</body>

</html>