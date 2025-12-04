<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'perfilCanchaAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Perfil de Cancha - Admin Sistema - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA, CSS_PAGES_PERFILES];
$page_js = [JS_BOOTSTRAP];

// Variables para el componente perfilCancha.php
$perfil_cancha_admin_mode = true; // Admin de sistema tiene permisos completos
$perfil_cancha_es_admin_sistema = true; // Para distinguir del admin de cancha
$perfil_cancha_mostrar_selector = false; // No necesitamos selector en este contexto
$perfil_cancha_titulo_seccion = 'Historial de Torneos';
$perfil_cancha_skip_header = true; // Usamos nuestro header personalizado
$perfil_cancha_descripcion = 'Revisión y moderación del funcionamiento de la cancha en la plataforma';
$perfil_cancha_boton_primario = [
    'texto' => 'Gestionar Disponibilidad',
    'icono' => 'bi-calendar-check',
    'url' => '#'
];


include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

    <main>
        <div class="container mt-4">
            <!-- Información de moderación -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="alert alert-info">
                        <i class="bi bi-shield-check"></i>
                        <strong>Panel de Moderación:</strong> Tienes acceso completo al perfil de la cancha. Puedes revisar su historial, torneos y tomar acciones administrativas.
                    </div>
                </div>
            </div>

            <!-- Header con botones específicos de admin -->
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-1">Perfil de Cancha</h1>
                    <p class="text-muted mb-0">Revisión y moderación del funcionamiento de la cancha en la plataforma</p>
                </div>
                <div class="col-md-6 text-end">
                    <a href="<?= PAGE_CANCHAS_LISTADO_ADMIN_SISTEMA ?>" class="btn btn-dark me-2">
                        <i class="bi bi-arrow-left"></i> Volver a Canchas
                    </a>
                    <button type="button" class="btn btn-dark me-2" data-bs-toggle="modal" data-bs-target="#modalSuspenderCancha">
                        <i class="bi bi-ban"></i> Suspender
                    </button>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalHabilitarCancha">
                        <i class="bi bi-check-circle"></i> Habilitar
                    </button>
                </div>
            </div>

            <?php include __DIR__ . '/../perfilCancha.php'; ?>

            <!-- Secciones adicionales para admin del sistema -->
            <div class="row mt-4">
                <!-- Historial de reportes sobre la cancha -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-flag"></i> Reportes Recibidos</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Instalaciones en mal estado</h6>
                                            <p class="mb-1 text-muted">Reportado por: @carlos_futbol</p>
                                            <small class="text-muted">08/11/2025 • RES-321</small>
                                        </div>
                                        <span class="badge text-bg-dark">Pendiente</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Horarios incorrectos</h6>
                                            <p class="mb-1 text-muted">Reportado por: @maria_goals</p>
                                            <small class="text-muted">05/11/2025 • RES-318</small>
                                        </div>
                                        <span class="badge text-bg-dark">Resuelto</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Precios excesivos</h6>
                                            <p class="mb-1 text-muted">Reportado por: @equipo_norte</p>
                                            <small class="text-muted">01/11/2025 • RES-310</small>
                                        </div>
                                        <span class="badge text-bg-dark">En revisión</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">Total de reportes: 3 | Ratio: 1.2%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de acciones administrativas -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial Administrativo</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Verificación inicial</h6>
                                            <p class="mb-1 text-muted">Por: Admin_Carlos</p>
                                            <small class="text-muted">15/09/2025</small>
                                        </div>
                                        <span class="badge text-bg-dark">Completada</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Habilitación de servicios</h6>
                                            <p class="mb-1 text-muted">Por: Admin_Maria</p>
                                            <small class="text-muted">20/09/2025</small>
                                        </div>
                                        <span class="badge text-bg-dark">Completada</span>
                                    </div>
                                </div>
                                <div class="list-group-item text-center text-muted py-4">
                                    <i class="bi bi-check-circle fs-3"></i>
                                    <p class="mb-0 mt-2">Estado actual: Habilitada</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de rendimiento -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Análisis de Rendimiento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-success fs-4">95%</div>
                                    <small class="text-muted">Ocupación</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-primary fs-4">4.8★</div>
                                    <small class="text-muted">Calificación</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-warning fs-4">1.2%</div>
                                    <small class="text-muted">Ratio reportes</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-danger fs-4">0</div>
                                    <small class="text-muted">Suspensiones</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-info fs-4">14</div>
                                    <small class="text-muted">Meses activa</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-secondary fs-4">A-</div>
                                    <small class="text-muted">Score general</small>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Indicadores Positivos:</h6>
                                    <ul class="list-unstyled">
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Alta ocupación de canchas</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Excelentes calificaciones de usuarios</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Funcionamiento estable desde apertura</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Pocos reportes de problemas</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Cumplimiento de horarios</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Puntos de Atención:</h6>
                                    <ul class="list-unstyled">
                                        <li class="text-warning"><i class="bi bi-exclamation-triangle"></i> Reporte pendiente sobre instalaciones</li>
                                        <li class="text-info"><i class="bi bi-info-circle"></i> Seguimiento recomendado</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas financieras (solo admin sistema) -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-currency-dollar"></i> Resumen Financiero</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-success fs-4">$45,200</div>
                                    <small class="text-muted">Ingresos mes actual</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-primary fs-4">$2,260</div>
                                    <small class="text-muted">Comisión plataforma</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-warning fs-4">127</div>
                                    <small class="text-muted">Reservas este mes</small>
                                </div>
                                <div class="col-md-3 text-center mb-3">
                                    <div class="fw-bold text-info fs-4">$356</div>
                                    <small class="text-muted">Promedio por reserva</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modales para acciones administrativas -->
        <!-- Modal Suspender Cancha -->
        <div class="modal fade" id="modalSuspenderCancha" tabindex="-1" aria-labelledby="modalSuspenderCanchaLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="modalSuspenderCanchaLabel">
                            <i class="bi bi-ban"></i> <span id="modal-suspender-titulo">Suspender Cancha</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>¡Atención!</strong> Al suspender esta cancha se tomarán las siguientes acciones:
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold">Implicaciones de la suspensión:</h6>
                            <ul class="text-muted">
                                <li>La cancha no aparecerá en búsquedas de disponibilidad</li>
                                <li>Se cancelarán las reservas futuras automáticamente</li>
                                <li>Los usuarios recibirán notificación de la cancelación</li>
                                <li>El administrador de la cancha será notificado</li>
                            </ul>
                        </div>

                        <form id="formSuspenderCancha">
                            <input type="hidden" id="suspender-cancha-id" name="cancha_id" value="CAN-045">

                            <div class="mb-3">
                                <label for="motivo-suspension" class="form-label">Motivo de la suspensión:</label>
                                <select class="form-control" id="motivo-suspension" name="motivo_suspension" required>
                                    <option value="">Selecciona un motivo</option>
                                    <option value="instalaciones_deficientes">Instalaciones deficientes</option>
                                    <option value="incumplimiento_horarios">Incumplimiento de horarios</option>
                                    <option value="quejas_usuarios">Múltiples quejas de usuarios</option>
                                    <option value="documentacion_vencida">Documentación vencida</option>
                                    <option value="revision_administrativa">Revisión administrativa</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="fecha-suspension-cancha" class="form-label">Suspender hasta la fecha:</label>
                                <input type="date" class="form-control" id="fecha-suspension-cancha" name="fecha_suspension" required>
                                <div class="form-text">Selecciona hasta qué fecha estará suspendida la cancha</div>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje-suspension-cancha" class="form-label">Mensaje personalizado (opcional):</label>
                                <textarea class="form-control" id="mensaje-suspension-cancha" name="mensaje_suspension" rows="4"
                                    placeholder="Estimado/a administrador/a, debido a reportes recibidos...">Estimado/a administrador/a, debido a reportes recibidos, hemos decidido suspender temporalmente su cancha mientras revisamos la situación. Durante este período la cancha no estará disponible para reservas. Te contactaremos cuando la revisión esté completa.</textarea>
                                <div class="form-text">Este mensaje será enviado al administrador de la cancha</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-confirmar-suspension-cancha">
                            <i class="bi bi-ban"></i> Confirmar Suspensión
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Habilitar Cancha -->
        <div class="modal fade" id="modalHabilitarCancha" tabindex="-1" aria-labelledby="modalHabilitarCanchaLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="modalHabilitarCanchaLabel">
                            <i class="bi bi-check-circle"></i> <span id="modal-habilitar-titulo">Habilitar Cancha</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            <strong>¡Importante!</strong> Al habilitar esta cancha se tomarán las siguientes acciones:
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold">Acciones de la habilitación:</h6>
                            <ul class="text-muted">
                                <li>La cancha volverá a aparecer en búsquedas</li>
                                <li>Se reactivará la disponibilidad para reservas</li>
                                <li>El administrador recibirá notificación de habilitación</li>
                                <li>Los usuarios podrán volver a reservar horarios</li>
                            </ul>
                        </div>

                        <form id="formHabilitarCancha">
                            <input type="hidden" id="habilitar-cancha-id" name="cancha_id" value="CAN-045">

                            <div class="mb-3">
                                <label for="mensaje-habilitacion" class="form-label">Mensaje de habilitación (opcional):</label>
                                <textarea class="form-control" id="mensaje-habilitacion" name="mensaje_habilitacion" rows="3"
                                    placeholder="Nos complace informarte que tu cancha ha sido habilitada...">Nos complace informarte que tu cancha ha sido habilitada nuevamente. Ya puedes recibir reservas y gestionar tu disponibilidad normalmente. Te recomendamos revisar nuestras políticas de calidad para una mejor experiencia.</textarea>
                                <div class="form-text">Este mensaje será enviado al administrador de la cancha</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-confirmar-habilitacion">
                            <i class="bi bi-check-circle"></i> Confirmar Habilitación
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_PERFILES ?>"></script>

    <script>
        const GET_INFO_PERFIL = '<?= GET_INFO_PERFIL ?>';
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Manejar suspensión de cancha
            document.querySelector('.btn-confirmar-suspension-cancha')?.addEventListener('click', function() {
                const form = document.getElementById('formSuspenderCancha');
                const formData = new FormData(form);

                if (!form.checkValidity()) {
                    form.classList.add('was-validated');
                    return;
                }

                // Aquí iría la llamada AJAX al servidor
                console.log('Suspendiendo cancha:', formData);

                // Simular proceso
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Suspendiendo...';

                setTimeout(() => {
                    alert('Cancha suspendida exitosamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalSuspenderCancha')).hide();
                    // Redirigir o actualizar página
                    location.reload();
                }, 1500);
            });

            // Manejar habilitación de cancha
            document.querySelector('.btn-confirmar-habilitacion')?.addEventListener('click', function() {
                const form = document.getElementById('formHabilitarCancha');
                const formData = new FormData(form);

                // Aquí iría la llamada AJAX al servidor
                console.log('Habilitando cancha:', formData);

                // Simular proceso
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Habilitando...';

                setTimeout(() => {
                    alert('Cancha habilitada exitosamente');
                    bootstrap.Modal.getInstance(document.getElementById('modalHabilitarCancha')).hide();
                    // Redirigir o actualizar página
                    location.reload();
                }, 1500);
            });

            // Establecer fecha mínima para suspensión (hoy)
            const fechaSuspension = document.getElementById('fecha-suspension-cancha');
            if (fechaSuspension) {
                const today = new Date().toISOString().split('T')[0];
                fechaSuspension.min = today;
                fechaSuspension.value = today;
            }
        });

        // CSS para spinner
        const style = document.createElement('style');
        style.textContent = `
            .spin {
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    </script>

</body>

</html>