<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'perfilJugadorAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Perfil de Jugador - Admin Sistema - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];
$page_js = [JS_PERFIL_JUGADOR, JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA];

// Variables para el componente perfilJugador.php
$perfil_jugador_admin_mode = true; // Admin de sistema tiene permisos completos
$perfil_jugador_es_propio = false;
$perfil_jugador_titulo_header = 'Perfil de Jugador - Moderación';
$perfil_jugador_subtitulo_header = 'Revisión y moderación del comportamiento del jugador en la plataforma';
$perfil_jugador_titulo_partidos = 'Historial de Partidos';
$perfil_jugador_titulo_estadisticas = 'Estadísticas y Comportamiento';
$perfil_jugador_mostrar_reportar = false; // Admin no puede reportar

$perfil_jugador_botones_header = [
    [
        'tipo' => 'link',
        'texto' => 'Volver a Reportes',
        'icono' => 'bi-arrow-left',
        'clase' => 'btn-outline-secondary',
        'url' => PAGE_JUGADORES_REPORTADOS_ADMIN
    ],
    [
        'tipo' => 'button',
        'texto' => 'Suspender',
        'icono' => 'bi-person-fill-x',
        'clase' => 'btn-outline-danger',
        'modal' => '#modalSuspenderJugador'
    ],
    [
        'tipo' => 'button',
        'texto' => 'Reestablecer',
        'icono' => 'bi-person-fill-check',
        'clase' => 'btn-outline-success',
        'modal' => '#modalRestablecerJugador'
    ]
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
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-check"></i>
                        <strong>Panel de Moderación:</strong> Tienes acceso completo al historial del jugador. Revisa cuidadosamente antes de tomar acciones disciplinarias.
                    </div>
                </div>
            </div>

            <?php include PERFIL_JUGADOR_COMPONENT; ?>

            <!-- Secciones adicionales para admin del sistema -->
            <div class="row mt-4">
                <!-- Historial de reportes -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="bi bi-flag"></i> Historial de Reportes</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Comportamiento agresivo</h6>
                                            <p class="mb-1 text-muted">Reportado por: @carlos_futbol</p>
                                            <small class="text-muted">10/11/2025 • PAR-445</small>
                                        </div>
                                        <span class="badge bg-warning">Pendiente</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Falta de respeto</h6>
                                            <p class="mb-1 text-muted">Reportado por: @maria_goals</p>
                                            <small class="text-muted">08/11/2025 • PAR-458</small>
                                        </div>
                                        <span class="badge bg-success">Resuelto</span>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Abandono de partido</h6>
                                            <p class="mb-1 text-muted">Reportado por: @futbol_pro</p>
                                            <small class="text-muted">05/11/2025 • PAR-423</small>
                                        </div>
                                        <span class="badge bg-danger">Rechazado</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">Total de reportes: 3 | Ratio: 2.4%</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial de sanciones -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Sanciones</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Advertencia</h6>
                                            <p class="mb-1 text-muted">Por: Admin_Carlos</p>
                                            <small class="text-muted">01/10/2025</small>
                                        </div>
                                        <span class="badge bg-info">Cumplida</span>
                                    </div>
                                </div>
                                <div class="list-group-item text-center text-muted py-4">
                                    <i class="bi bi-check-circle fs-3"></i>
                                    <p class="mb-0 mt-2">No hay sanciones activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Análisis de comportamiento -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Análisis de Comportamiento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-success fs-4">89%</div>
                                    <small class="text-muted">Asistencia</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-primary fs-4">4.3★</div>
                                    <small class="text-muted">Calificación</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-warning fs-4">2.4%</div>
                                    <small class="text-muted">Ratio reportes</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-danger fs-4">0</div>
                                    <small class="text-muted">Suspensiones</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-info fs-4">8</div>
                                    <small class="text-muted">Meses activo</small>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="fw-bold text-secondary fs-4">B+</div>
                                    <small class="text-muted">Score general</small>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Indicadores Positivos:</h6>
                                    <ul class="list-unstyled">
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Alta asistencia a partidos</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Buenas calificaciones de otros jugadores</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Usuario activo desde hace tiempo</li>
                                        <li class="text-success"><i class="bi bi-check-circle"></i> Pocas infracciones reportadas</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6>Puntos de Atención:</h6>
                                    <ul class="list-unstyled">
                                        <li class="text-warning"><i class="bi bi-exclamation-triangle"></i> Reportes recientes por agresividad</li>
                                        <li class="text-info"><i class="bi bi-info-circle"></i> Seguimiento recomendado</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modales heredados del sistema de reportes -->
        <!-- Modal Suspender Jugador -->
        <div class="modal fade" id="modalSuspenderJugador" tabindex="-1" aria-labelledby="modalSuspenderJugadorLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="modalSuspenderJugadorLabel">
                            <i class="bi bi-person-fill-x"></i> <span id="modal-suspender-titulo">Suspender Jugador</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>¡Atención!</strong> Al suspender este jugador se tomarán las siguientes acciones:
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold">Implicaciones de la suspensión:</h6>
                            <ul class="text-muted">
                                <li>El jugador no podrá iniciar sesión en su cuenta</li>
                                <li>No podrá participar en partidos ni torneos</li>
                                <li>Recibirá una notificación por email explicando la situación</li>
                                <li>Su perfil será marcado como "suspendido" temporalmente</li>
                            </ul>
                        </div>

                        <form id="formSuspenderJugador">
                            <input type="hidden" id="suspender-jugador-id" name="jugador_id" value="JUG-015">

                            <div class="mb-3">
                                <label for="fecha-suspension" class="form-label">Suspender hasta la fecha:</label>
                                <input type="date" class="form-control" id="fecha-suspension" name="fecha_suspension" required>
                                <div class="form-text">Selecciona hasta qué fecha estará suspendido el jugador</div>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje-suspension" class="form-label">Mensaje personalizado (opcional):</label>
                                <textarea class="form-control" id="mensaje-suspension" name="mensaje_suspension" rows="4"
                                    placeholder="Estimado/a jugador/a, debido a reportes recibidos, hemos decidido suspender temporalmente tu cuenta mientras revisamos la situación.">Estimado/a jugador/a, debido a reportes recibidos, hemos decidido suspender temporalmente tu cuenta mientras revisamos la situación. Durante este período no podrás acceder a los servicios de la plataforma. Te contactaremos cuando la revisión esté completa.</textarea>
                                <div class="form-text">Este mensaje será enviado al email registrado del jugador</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-confirmar-suspension">
                            <i class="bi bi-person-fill-x"></i> Confirmar Suspensión
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Reestablecer Jugador -->
        <div class="modal fade" id="modalRestablecerJugador" tabindex="-1" aria-labelledby="modalRestablecerJugadorLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="modalRestablecerJugadorLabel">
                            <i class="bi bi-person-fill-check"></i> <span id="modal-reestablecer-titulo">Reestablecer Cuenta</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            <strong>¡Importante!</strong> Al reestablecer esta cuenta se tomarán las siguientes acciones:
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold">Acciones del restablecimiento:</h6>
                            <ul class="text-muted">
                                <li>El jugador recuperará acceso completo a su cuenta</li>
                                <li>Podrá participar nuevamente en partidos y torneos</li>
                                <li>Recibirá una notificación por email confirmando el restablecimiento</li>
                                <li>Su perfil volverá al estado "activo"</li>
                            </ul>
                        </div>

                        <form id="formRestablecerJugador">
                            <input type="hidden" id="reestablecer-jugador-id" name="jugador_id" value="JUG-015">

                            <div class="mb-3">
                                <label for="mensaje-restablecimiento" class="form-label">Mensaje de bienvenida (opcional):</label>
                                <textarea class="form-control" id="mensaje-restablecimiento" name="mensaje_restablecimiento" rows="3"
                                    placeholder="Nos complace informarte que tu cuenta ha sido restablecida...">Nos complace informarte que tu cuenta ha sido restablecida. Ya puedes volver a disfrutar de todos los servicios de FutMatch. Te recomendamos revisar nuestras normas de convivencia para una mejor experiencia.</textarea>
                                <div class="form-text">Este mensaje será enviado al email registrado del jugador</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-confirmar-restablecimiento">
                            <i class="bi bi-person-fill-check"></i> Confirmar Restablecimiento
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_PERFIL_JUGADOR ?>"></script>
    <script src="<?= JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA ?>"></script>

</body>

</html>