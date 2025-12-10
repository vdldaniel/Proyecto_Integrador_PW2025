<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'jugadoresReportadosAdmin';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Reportes de Jugadores - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];
$page_js = [JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título y botones de navegación -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Reportes de Jugadores</h1>
                    <p class="text-muted mb-0">Gestioná reportes y sanciones de usuarios</p>
                </div>

                <div class="col-md-6 text-end">
                    <a type="button" class="btn btn-dark me-2" href="<?= PAGE_JUGADORES_LISTADO_ADMIN_SISTEMA ?>">
                        <i class="bi bi-people-fill"></i> Ir a Listado de Jugadores
                    </a>
                </div>
            </div>

            <!-- Línea 2: Filtros y búsqueda -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input
                            type="text"
                            id="searchInput"
                            class="form-control"
                            placeholder="Buscar reportes por usuario o evaluador..." />
                    </div>
                </div>
            </div>

            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs" id="reportesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes"
                        data-bs-toggle="tooltip" title="Reportes pendientes de revisión" type="button" role="tab">
                        <i class="bi bi-clock-fill"></i> Reportes pendientes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="verificacion-tab" data-bs-toggle="tab" data-bs-target="#verificacion"
                        data-bs-toggle="tooltip" title="Reportes en verificación" type="button" role="tab">
                        <i class="bi bi-exclamation-triangle-fill"></i> En verificación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="resueltos-tab" data-bs-toggle="tab" data-bs-target="#resueltos"
                        data-bs-toggle="tooltip" title="Reportes resueltos" type="button" role="tab">
                        <i class="bi bi-check-circle-fill"></i> Resueltos
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content tabla-admin" id="reportesTabsContent">

                <div class="table-header header-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">ID Jugador</div>
                            <div class="col-md-1">Username</div>
                            <div class="col-md-2">Nombre y Apellido</div>
                            <div class="col-md-1">Calificación</div>
                            <div class="col-md-2">ID Evaluador</div>
                            <div class="col-md-2">Verificador</div>
                            <div class="col-md-3">Acciones</div>
                        </div>
                    </div>
                </div>

                <!-- SOLAPA 1: REPORTES PENDIENTES -->
                <div class="tab-pane fade show active" id="pendientes" role="tabpanel">

                    <!-- Reporte 1 - Pendiente -->
                    <div class="card reporte-card estado-pendiente" data-estado="pendientes" data-reporte-id="REP-001">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>JUG-015</strong>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold">agresivo_99</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Alejandro Ruiz</div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★☆☆☆</span>
                                        <small class="text-muted ms-2">2.1</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">JUG-008</span>
                                    <small class="text-muted d-block">carlos_futbol</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark">Sin asignar</span>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-015" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-detalle" data-reporte-id="REP-001" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-tomar" data-reporte-id="REP-001" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender" data-jugador-id="JUG-015" data-jugador-nombre="Alejandro Ruiz" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reporte 2 - Pendiente -->
                    <div class="card reporte-card estado-pendiente" data-estado="pendientes" data-reporte-id="REP-002">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>JUG-023</strong>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold">malcomportado</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Diego Torres</div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★★☆☆</span>
                                        <small class="text-muted ms-2">2.8</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">JUG-012</span>
                                    <small class="text-muted d-block">maria_goals</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark">Sin asignar</span>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-023" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-detalle" data-reporte-id="REP-002" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-tomar" data-reporte-id="REP-002" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender" data-jugador-id="JUG-023" data-jugador-nombre="Diego Torres" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 2: EN VERIFICACIÓN -->
                <div class="tab-pane fade" id="verificacion" role="tabpanel">

                    <!-- Reporte 3 - En verificación -->
                    <div class="card reporte-card estado-verificando" data-estado="verificacion" data-reporte-id="REP-003">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>JUG-031</strong>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold">problematico</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Roberto Silva</div>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★☆☆☆</span>
                                        <small class="text-muted ms-2">2.3</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">JUG-007</span>
                                    <small class="text-muted d-block">futbol_pro</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold text-primary">Admin_Juan</span>
                                    <small class="text-muted d-block">En revisión</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-031" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-detalle" data-reporte-id="REP-003" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender" data-jugador-id="JUG-031" data-jugador-nombre="Roberto Silva" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                        <button class="btn btn-dark btn-reestablecer" data-jugador-id="JUG-031" data-jugador-nombre="Roberto Silva" data-bs-toggle="tooltip" title="Reestablecer cuenta">
                                            <i class="bi bi-person-fill-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 3: RESUELTOS -->
                <div class="tab-pane fade" id="resueltos" role="tabpanel">

                    <!-- Reporte 4 - Resuelto -->
                    <div class="card reporte-card estado-verificado" data-estado="resueltos" data-reporte-id="REP-004">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>JUG-045</strong>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold text-muted">suspendido_user</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold text-muted">Andrés López</div>
                                    <small class="text-danger d-block">Suspendido hasta 25/11/2025</small>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★☆☆☆☆</span>
                                        <small class="text-muted ms-2">1.8</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">JUG-019</span>
                                    <small class="text-muted d-block">fair_player</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold text-success">Admin_Carlos</span>
                                    <small class="text-success d-block">Resuelto - Suspensión aplicada</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-045" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-detalle" data-reporte-id="REP-004" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-reestablecer" data-jugador-id="JUG-045" data-jugador-nombre="Andrés López" data-bs-toggle="tooltip" title="Reestablecer cuenta">
                                            <i class="bi bi-person-fill-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Modal Detalle del Reporte -->
        <div class="modal fade" id="modalDetalleReporte" tabindex="-1" aria-labelledby="modalDetalleReporteLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title text-white" id="modalDetalleReporteLabel">
                            <i class="bi bi-file-text"></i> Detalle del Reporte
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-primary">Información del Partido</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>ID Partido:</strong> <span id="detalle-partido-id">PAR-445</span></p>
                                        <p class="mb-1"><strong>Fecha:</strong> <span id="detalle-partido-fecha">10/11/2025</span></p>
                                        <p class="mb-1"><strong>Cancha:</strong> <span id="detalle-partido-cancha">Complejo Deportivo Centro</span></p>
                                        <p class="mb-0"><strong>Horario:</strong> <span id="detalle-partido-hora">15:30 - 17:00</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger">Jugador Reportado</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Username:</strong> <span id="detalle-reportado-username">agresivo_99</span></p>
                                        <p class="mb-1"><strong>Nombre:</strong> <span id="detalle-reportado-nombre">Alejandro Ruiz</span></p>
                                        <p class="mb-0"><strong>Calificación:</strong> <span id="detalle-reportado-calificacion">★★☆☆☆ (2.1)</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success">Jugador Evaluador</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Username:</strong> <span id="detalle-evaluador-username">carlos_futbol</span></p>
                                        <p class="mb-1"><strong>Nombre:</strong> <span id="detalle-evaluador-nombre">Carlos García</span></p>
                                        <p class="mb-0"><strong>Calificación:</strong> <span id="detalle-evaluador-calificacion">★★★★☆ (4.3)</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning">Puntuación Otorgada</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <div class="text-center">
                                            <span class="fs-1 text-danger fw-bold" id="detalle-puntuacion">1</span>
                                            <p class="mb-0 text-muted">de 5 estrellas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold text-dark">Comentario del Evaluador</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0" id="detalle-comentario">
                                            "Este jugador tuvo un comportamiento muy agresivo durante todo el partido. Hizo varias entradas peligrosas sin intención de jugar la pelota y cuando le dijimos algo respondió de manera muy irrespetuosa. No recomiendo que juegue con otros usuarios."
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-warning btn-tomar-caso">
                            <i class="bi bi-arrow-right"></i> Tomar Caso
                        </button>
                    </div>
                </div>
            </div>
        </div>

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
                            <input type="hidden" id="suspender-jugador-id" name="jugador_id">

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
                            <input type="hidden" id="reestablecer-jugador-id" name="jugador_id">

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
    <script src="<?= JS_JUGADORES_REPORTADOS_ADMIN_SISTEMA ?>"></script>

</body>

</html>