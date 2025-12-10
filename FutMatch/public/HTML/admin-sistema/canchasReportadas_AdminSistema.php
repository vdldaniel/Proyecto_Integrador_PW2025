<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'canchasReportadasAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Reportes de Canchas - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];
$page_js = [JS_CANCHAS_REPORTADAS_ADMIN_SISTEMA];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título y botones de navegación -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Reportes de Canchas</h1>
                    <p class="text-muted mb-0">Gestioná reportes y sanciones de canchas</p>
                </div>

                <div class="col-md-6 text-end">
                    <a type="button" class="btn btn-dark me-2" href="<?= PAGE_CANCHAS_LISTADO_ADMIN_SISTEMA ?>">
                        <i class="bi bi-building-fill"></i> Ir a Verificación de Canchas
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
                            placeholder="Buscar reportes por cancha o usuario..." />
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
                            <div class="col-md-1">ID Cancha</div>
                            <div class="col-md-2">Nombre Cancha</div>
                            <div class="col-md-2">Administrador</div>
                            <div class="col-md-1">Calificación</div>
                            <div class="col-md-2">Usuario Reportante</div>
                            <div class="col-md-2">Verificador</div>
                            <div class="col-md-2">Acciones</div>
                        </div>
                    </div>
                </div>

                <!-- SOLAPA 1: REPORTES PENDIENTES -->
                <div class="tab-pane fade show active" id="pendientes" role="tabpanel">

                    <!-- Reporte 1 - Pendiente -->
                    <div class="card reporte-card estado-pendiente" data-estado="pendientes" data-reporte-id="REP-C001">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>CAN-025</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">Futbol Club Centro</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Carlos Mendoza</div>
                                    <small class="text-muted d-block">admin_carlos</small>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★☆☆☆</span>
                                        <small class="text-muted ms-2">2.3</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">USR-112</span>
                                    <small class="text-muted d-block">jugador_molesto</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark">Sin asignar</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_CANCHA_ADMIN_SISTEMA ?>?id=CAN-025" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver cancha">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-ver-detalle-reporte" data-cancha-id="CAN-025" data-reporte-id="REP-C001" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark" data-reporte-id="REP-C001" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender-cuenta" data-cancha-id="CAN-025" data-cancha-nombre="Futbol Club Centro" data-bs-toggle="tooltip" title="Suspender cancha">
                                            <i class="bi bi-building-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reporte 2 - Pendiente -->
                    <div class="card reporte-card estado-pendiente" data-estado="pendientes" data-reporte-id="REP-C002">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>CAN-041</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">Complejo Norte</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Laura García</div>
                                    <small class="text-muted d-block">admin_laura</small>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★★☆☆</span>
                                        <small class="text-muted ms-2">3.1</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">USR-089</span>
                                    <small class="text-muted d-block">futbolero_2024</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark">Sin asignar</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_CANCHA_ADMIN_SISTEMA ?>?id=CAN-041" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver cancha">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-ver-detalle-reporte" data-cancha-id="CAN-041" data-reporte-id="REP-C002" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark" data-reporte-id="REP-C002" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender-cuenta" data-cancha-id="CAN-041" data-cancha-nombre="Complejo Norte" data-bs-toggle="tooltip" title="Suspender cancha">
                                            <i class="bi bi-building-x"></i>
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
                    <div class="card reporte-card estado-verificando" data-estado="verificacion" data-reporte-id="REP-C003">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>CAN-055</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">Deportivo San Juan</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Roberto Silva</div>
                                    <small class="text-muted d-block">admin_roberto</small>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★☆☆☆</span>
                                        <small class="text-muted ms-2">2.8</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">USR-234</span>
                                    <small class="text-muted d-block">usuario_quejoso</small>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold text-primary">Admin_Ana</span>
                                    <small class="text-muted d-block">En revisión</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_CANCHA_ADMIN_SISTEMA ?>?id=CAN-055" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver cancha">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-ver-detalle-reporte" data-cancha-id="CAN-055" data-reporte-id="REP-C003" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-suspender-cuenta" data-cancha-id="CAN-055" data-cancha-nombre="Deportivo San Juan" data-bs-toggle="tooltip" title="Suspender cancha">
                                            <i class="bi bi-building-x"></i>
                                        </button>
                                        <button class="btn btn-dark btn-reestablecer-cuenta" data-cancha-id="CAN-055" data-cancha-nombre="Deportivo San Juan" data-bs-toggle="tooltip" title="Reestablecer cancha">
                                            <i class="bi bi-building-check"></i>
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
                    <div class="card reporte-card estado-verificado" data-estado="resueltos" data-reporte-id="REP-C004">
                        <div class="card-body">
                            <div class="row reporte-row">
                                <div class="col-md-1">
                                    <strong>CAN-067</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold text-muted">Cancha Problemática</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold text-muted">Miguel Torres</div>
                                    <small class="text-danger d-block">Suspendido hasta 20/12/2025</small>
                                </div>
                                <div class="col-md-1">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★☆☆☆☆</span>
                                        <small class="text-muted ms-2">1.5</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">USR-156</span>
                                    <small class="text-muted d-block">usuario_honesto</small>
                                </div>
                                <div class="col-md-1">
                                    <span class="fw-bold text-success">Admin_Luis</span>
                                    <small class="text-success d-block">Resuelto - Suspensión aplicada</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_CANCHA_ADMIN_SISTEMA ?>?id=CAN-067" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver cancha">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-dark btn-ver-detalle-reporte" data-cancha-id="CAN-067" data-reporte-id="REP-C004" data-bs-toggle="tooltip" title="Ver detalle del reporte">
                                            <i class="bi bi-file-text"></i>
                                        </button>
                                        <button class="btn btn-dark btn-reestablecer-cuenta" data-cancha-id="CAN-067" data-cancha-nombre="Cancha Problemática" data-bs-toggle="tooltip" title="Reestablecer cancha">
                                            <i class="bi bi-building-check"></i>
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
                                <h6 class="fw-bold text-primary">Información de la Cancha</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Nombre:</strong> <span id="detalle-cancha-nombre">Futbol Club Centro</span></p>
                                        <p class="mb-1"><strong>Dirección:</strong> <span id="detalle-cancha-direccion">Av. Libertador 1234, Centro</span></p>
                                        <p class="mb-1"><strong>Administrador:</strong> <span id="detalle-cancha-admin">Carlos Mendoza</span></p>
                                        <p class="mb-0"><strong>Fecha del incidente:</strong> <span id="detalle-fecha-incidente">08/11/2025</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-danger">Usuario Reportante</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <p class="mb-1"><strong>Username:</strong> <span id="detalle-reportante-username">jugador_molesto</span></p>
                                        <p class="mb-1"><strong>Nombre:</strong> <span id="detalle-reportante-nombre">Pedro Sánchez</span></p>
                                        <p class="mb-0"><strong>Calificación:</strong> <span id="detalle-reportante-calificacion">★★★★☆ (4.1)</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning">Tipo de Problema</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <div class="text-center">
                                            <span class="fs-3 text-danger fw-bold" id="detalle-tipo-problema">Instalaciones deficientes</span>
                                            <p class="mb-0 text-muted">Categoría del reporte</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-info">Calificación Otorgada</h6>
                                <div class="card bg-light mb-3">
                                    <div class="card-body p-3">
                                        <div class="text-center">
                                            <span class="fs-1 text-danger fw-bold" id="detalle-calificacion">2</span>
                                            <p class="mb-0 text-muted">de 5 estrellas</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="fw-bold text-dark">Comentario del Usuario</h6>
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <p class="mb-0" id="detalle-comentario">
                                            "La cancha estaba en muy mal estado, con hoyos en el pasto y sin iluminación adecuada. Los vestuarios estaban sucios y no había agua caliente. Además, el administrador fue muy grosero cuando le hicimos el reclamo. No recomiendo esta cancha para nadie."
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

        <!-- Modal Suspender Cancha -->
        <div class="modal fade" id="modalSuspenderCancha" tabindex="-1" aria-labelledby="modalSuspenderCanchaLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="modalSuspenderCanchaLabel">
                            <i class="bi bi-building-x"></i> <span id="modal-suspender-titulo">Suspender Cancha</span>
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
                                <li>La cancha no aparecerá en las búsquedas de usuarios</li>
                                <li>No se podrán realizar nuevas reservas</li>
                                <li>El administrador recibirá una notificación por email</li>
                                <li>Las reservas existentes serán canceladas</li>
                            </ul>
                        </div>

                        <form id="formSuspenderCancha">
                            <input type="hidden" id="suspender-cancha-id" name="cancha_id">

                            <div class="mb-3">
                                <label for="fecha-suspension" class="form-label">Suspender hasta la fecha:</label>
                                <input type="date" class="form-control" id="fecha-suspension" name="fecha_suspension" required>
                                <div class="form-text">Selecciona hasta qué fecha estará suspendida la cancha</div>
                            </div>

                            <div class="mb-3">
                                <label for="mensaje-suspension" class="form-label">Mensaje personalizado (opcional):</label>
                                <textarea class="form-control" id="mensaje-suspension" name="mensaje_suspension" rows="4"
                                    placeholder="Estimado administrador, debido a reportes recibidos, hemos decidido suspender temporalmente su cancha...">Estimado administrador, debido a reportes recibidos sobre las condiciones de su cancha, hemos decidido suspender temporalmente su servicio mientras revisamos la situación. Durante este período no se podrán realizar nuevas reservas. Le contactaremos cuando la revisión esté completa.</textarea>
                                <div class="form-text">Este mensaje será enviado al email registrado del administrador</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger btn-confirmar-suspension">
                            <i class="bi bi-building-x"></i> Confirmar Suspensión
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Reestablecer Cancha -->
        <div class="modal fade" id="modalRestablecerCancha" tabindex="-1" aria-labelledby="modalRestablecerCanchaLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="modalRestablecerCanchaLabel">
                            <i class="bi bi-building-check"></i> <span id="modal-reestablecer-titulo">Reestablecer Cancha</span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            <strong>¡Importante!</strong> Al reestablecer esta cancha se tomarán las siguientes acciones:
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold">Acciones del restablecimiento:</h6>
                            <ul class="text-muted">
                                <li>La cancha volverá a aparecer en las búsquedas</li>
                                <li>Se podrán realizar nuevas reservas normalmente</li>
                                <li>El administrador recibirá una notificación por email</li>
                                <li>Su estado volverá a "activo"</li>
                            </ul>
                        </div>

                        <form id="formRestablecerCancha">
                            <input type="hidden" id="reestablecer-cancha-id" name="cancha_id">

                            <div class="mb-3">
                                <label for="mensaje-restablecimiento" class="form-label">Mensaje de bienvenida (opcional):</label>
                                <textarea class="form-control" id="mensaje-restablecimiento" name="mensaje_restablecimiento" rows="3"
                                    placeholder="Nos complace informarle que su cancha ha sido restablecida...">Nos complace informarle que su cancha ha sido restablecida y ya está disponible nuevamente para reservas. Agradecemos su colaboración para resolver los inconvenientes reportados. Le recomendamos mantener siempre las mejores condiciones para brindar un excelente servicio.</textarea>
                                <div class="form-text">Este mensaje será enviado al email registrado del administrador</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success btn-confirmar-restablecimiento">
                            <i class="bi bi-building-check"></i> Confirmar Restablecimiento
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_CANCHAS_REPORTADAS_ADMIN_SISTEMA ?>"></script>

</body>

</html>