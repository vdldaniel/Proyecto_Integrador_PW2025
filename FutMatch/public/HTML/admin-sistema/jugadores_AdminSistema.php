<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'jugadoresAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Sistema - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];

include HEAD_COMPONENT;

?>

<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título y botones de navegación -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Listado de Jugadores</h1>
                    <p class="text-muted mb-0">Gestioná usuarios</p>
                </div>

                <div class="col-md-6 text-end">
                    <a type="button" class="btn btn-outline-secondary me-2" href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>">
                        <i class="bi bi-flag-fill"></i> Ir a Jugadores Reportados
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
                            placeholder="Buscar jugadores..." />
                    </div>
                </div>
            </div>


            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs" id="jugadoresTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="jugadores-tab" data-bs-toggle="tab" data-bs-target="#jugadores"
                        data-bs-toggle="tooltip" title="Listado completo de jugadores" type="button" role="tab">
                        <i class="bi bi-people-fill"></i> Jugadores
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="suspendidos-tab" data-bs-toggle="tab" data-bs-target="#suspendidos"
                        data-bs-toggle="tooltip" title="Listado de jugadores suspendidos" type="button" role="tab">
                        <i class="bi bi-person-fill-exclamation"></i> Suspendidos
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content tabla-admin" id="jugadoresTabsContent">

                <div class="table-header header-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">ID</div>
                            <div class="col-md-2">Username</div>
                            <div class="col-md-2">Nombre y Apellido</div>
                            <div class="col-md-2">Calificación</div>
                            <div class="col-md-2">Partidos jugados</div>
                            <div class="col-md-1">Fecha</div>
                            <div class="col-md-2">Acciones</div>
                        </div>
                    </div>
                </div>

                <!-- SOLAPA 1: JUGADORES -->
                <div class="tab-pane fade show active" id="jugadores" role="tabpanel">

                    <!-- Jugador 1 - Activo -->
                    <div class="card jugador-card estado-activo" data-estado="jugadores" data-jugador-id="JUG-001">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>JUG-001</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">carlitos_10</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Carlos Rodríguez</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★★★☆</span>
                                        <small class="text-muted ms-2">4.2</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-info">12 partidos</span>
                                </div>
                                <div class="col-md-1">
                                    <small>15/08/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-001" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver reportes">
                                            <i class="bi bi-flag"></i>
                                        </a>
                                        <button class="btn btn-dark" data-jugador-id="JUG-001" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jugador 2 - Activo -->
                    <div class="card jugador-card estado-activo" data-estado="jugadores" data-jugador-id="JUG-002">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>JUG-002</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">marigamer</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">María González</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★★★★</span>
                                        <small class="text-muted ms-2">4.8</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-info">25 partidos</span>
                                </div>
                                <div class="col-md-1">
                                    <small>02/09/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-002" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver reportes">
                                            <i class="bi bi-flag"></i>
                                        </a>
                                        <button class="btn btn-dark" data-jugador-id="JUG-002" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jugador 3 - Activo -->
                    <div class="card jugador-card estado-activo" data-estado="jugadores" data-jugador-id="JUG-003">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>JUG-003</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">futbol_lover</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Luis Martínez</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★★☆☆</span>
                                        <small class="text-muted ms-2">3.6</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-info">8 partidos</span>
                                </div>
                                <div class="col-md-1">
                                    <small>20/10/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-003" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver reportes">
                                            <i class="bi bi-flag"></i>
                                        </a>
                                        <button class="btn btn-dark" data-jugador-id="JUG-003" data-bs-toggle="tooltip" title="Suspender cuenta">
                                            <i class="bi bi-person-fill-x"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 2: SUSPENDIDOS -->
                <div class="tab-pane fade" id="suspendidos" role="tabpanel">

                    <!-- Jugador 4 - Suspendido -->
                    <div class="card jugador-card estado-suspendido" data-estado="suspendidos" data-jugador-id="JUG-004">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>JUG-004</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold text-muted">bad_player</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold text-muted">Pedro Sánchez</div>
                                    <small class="text-danger d-block">Suspendido hasta 30/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="d-flex align-items-center">
                                        <span class="text-warning">★★☆☆☆</span>
                                        <small class="text-muted ms-2">2.1</small>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-secondary">5 partidos</span>
                                </div>
                                <div class="col-md-1">
                                    <small>05/07/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <a href="<?= PAGE_PERFIL_JUGADOR_ADMIN_SISTEMA ?>?id=JUG-004" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>" class="btn btn-dark" data-bs-toggle="tooltip" title="Ver reportes">
                                            <i class="bi bi-flag"></i>
                                        </a>
                                        <button class="btn btn-dark" data-jugador-id="JUG-004" data-bs-toggle="tooltip" title="Reestablecer cuenta">
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

        <!-- Modal Suspender Jugador -->
        <div class="modal fade" id="modalSuspenderJugador" tabindex="-1" aria-labelledby="modalSuspenderJugadorLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-dark" id="modalSuspenderJugadorLabel">
                            <i class="bi bi-person-fill-x"></i> <span id="modal-suspender-titulo">Suspender Jugador</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    placeholder="Estimado/a jugador/a, debido a reportes recibidos, hemos decidido suspender temporalmente tu cuenta mientras revisamos la situación. Durante este período no podrás acceder a los servicios de la plataforma. Te contactaremos cuando la revisión esté completa.">Estimado/a jugador/a, debido a reportes recibidos, hemos decidido suspender temporalmente tu cuenta mientras revisamos la situación. Durante este período no podrás acceder a los servicios de la plataforma. Te contactaremos cuando la revisión esté completa.</textarea>
                                <div class="form-text">Este mensaje será enviado al email registrado del jugador</div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning btn-confirmar-suspension">
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
                            <h6 class="fw-bold">Implicaciones del restablecimiento:</h6>
                            <ul class="text-muted">
                                <li>El jugador podrá volver a iniciar sesión normalmente</li>
                                <li>Recuperará el acceso completo a partidos y torneos</li>
                                <li>Recibirá una notificación por email confirmando el restablecimiento</li>
                                <li>Su perfil volverá al estado "activo"</li>
                                <li>Se eliminará la marca de "suspendido"</li>
                            </ul>
                        </div>

                        <form id="formRestablecerJugador">
                            <input type="hidden" id="reestablecer-jugador-id" name="jugador_id">

                            <div class="mb-3">
                                <label for="mensaje-restablecimiento" class="form-label">Mensaje personalizado (opcional):</label>
                                <textarea class="form-control" id="mensaje-restablecimiento" name="mensaje_restablecimiento" rows="4"
                                    placeholder="Estimado/a jugador/a, nos complace informarte que tu cuenta ha sido restablecida. Ya puedes acceder nuevamente a todos los servicios de la plataforma. Agradecemos tu paciencia durante el proceso de revisión.">Estimado/a jugador/a, nos complace informarte que tu cuenta ha sido restablecida. Ya puedes acceder nuevamente a todos los servicios de la plataforma. Agradecemos tu paciencia durante el proceso de revisión.</textarea>
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_JUGADORES_ADMIN_SISTEMA ?>"></script>
</body>

</html>