<!-- Componente reutilizable: Perfil de Cancha -->
<!-- Se debe incluir dentro de un <main> container y después de cargar navbar -->


<?php
// Variables por defecto si no están definidas
$perfil_cancha_admin_mode = $perfil_cancha_admin_mode ?? false;
$perfil_cancha_mostrar_selector = $perfil_cancha_mostrar_selector ?? false;
$perfil_cancha_titulo_seccion = $perfil_cancha_titulo_seccion ?? 'Torneos Disponibles';
$perfil_cancha_skip_header = $perfil_cancha_skip_header ?? false;
$perfil_cancha_descripcion = $perfil_cancha_descripcion ?? 'Información detallada de la cancha';
$perfil_cancha_boton_primario = $perfil_cancha_boton_primario ?? [
    'texto' => 'Ver disponibilidad',
    'icono' => 'bi-calendar-plus',
    'url' => '#'
];

// Información básica de la cancha

// Calificaciones temporalmente ocultas
// $perfil_cancha_calificacion = $perfil_cancha_calificacion ?? '4.8';
// $perfil_cancha_total_resenas = $perfil_cancha_total_resenas ?? '127';
$perfil_cancha_total_jugadores = $perfil_cancha_total_jugadores ?? '342';
$perfil_cancha_total_partidos = $perfil_cancha_total_partidos ?? '156';


?>

<?php if (!$perfil_cancha_skip_header): ?>
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="fw-bold mb-1">Perfil de Cancha</h1>
            <p class="text-muted mb-0">
                <?= $perfil_cancha_descripcion ?? 'Información detallada de la cancha' ?>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <!-- Los botones específicos se manejan en cada página individual -->
            <!-- Para admin de cancha, se definen en misPerfiles_AdminCancha.php -->
            <!-- Para admin de sistema, se definen en perfilCancha_AdminSistema.php -->
            <!-- Para jugadores, botones básicos aquí -->
            <?php if (!$perfil_cancha_admin_mode): ?>
                <button type="button" class="btn btn-dark me-2" id="btnVerTorneos">
                    <i class="bi bi-trophy"></i> Ver Torneos
                </button>
                <button type="button" class="btn btn-dark" id="btnCompartirCancha">
                    <i class="bi bi-share"></i> Compartir
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Línea 2: Selector de cancha (solo admin) -->
<?php if ($perfil_cancha_mostrar_selector): ?>
    <div class="row mb-4">
        <div class="col-md-4 ms-auto">
            <div class="dropdown">

                <button id="btnSelectorCanchas" class="btn btn-dark dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-building"></i> Seleccionar cancha...
                </button>

                <ul id="listaCanchas" class="dropdown-menu w-100">
                </ul>

            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Banner de la cancha -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-lg rounded-3 overflow-hidden">

            <div class="position-relative profile-banner-wrapper">


                <div id="bannerCancha"
                    class="profile-banner-image"
                    style="background-image: url('<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>');">
                </div>

                <?php if ($perfil_cancha_admin_mode && !isset($perfil_cancha_es_admin_sistema)): ?>
                    <button class="btn btn-dark btn-sm profile-banner-edit-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCambiarBanner">
                        <i class="bi bi-camera-fill"></i> Editar portada
                    </button>
                <?php endif; ?>

                <div class="profile-banner-overlay">
                    <div class="profile-info-container bg-dark bg-opacity-75 p-4 rounded">

                        <div class="d-flex justify-content-between align-items-end">

                            <div>

                                <h1 id="nombreCancha"><?= $perfil_cancha_nombre ?></h1>
                                <p id="descripcionCancha"><?= $perfil_cancha_descripcion_banner ?></p>
                                <!--
                                <span id="estadoCancha" class="badge bg-info text-light mt-2">
                                    Pendiente
                                </span>-->
                            </div>

                            <div class="text-end">
                                <!-- Calificación oculta temporalmente -->
                                <div class="text-light">
                                    <i class="bi bi-people"></i>
                                    <span id="perfilJugadores">
                                        <?= $perfil_cancha_admin_mode ? 'Admin View' : $perfil_cancha_total_jugadores . ' jugadores' ?>
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>


<!-- Contenido principal -->
<div class="row">
    <div class="col-lg-8">
        <!-- Sección de Torneos -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-trophy"></i> <?= $perfil_cancha_titulo_seccion ?></h4>
            </div>
            <div class="card-body p-0">
                <!-- Torneo 1 -->
                <div class="border-bottom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning rounded-circle p-2 me-3">
                            <i class="bi bi-trophy text-dark"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Copa Verano 2025</h6>
                            <small class="text-muted">Fútbol 5 • <?= $perfil_cancha_admin_mode ? '16' : '12/16' ?> equipos • Inicia: 15 de enero</small>
                        </div>
                        <div>
                            <span class="badge text-bg-dark">Inscripciones Abiertas</span>
                        </div>
                    </div>
                    <p class="mb-3">Torneo de temporada de verano con premios para los 3 primeros puestos. Modalidad todos contra todos + eliminatorias.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if (!$perfil_cancha_admin_mode): ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark btnVerDetalles" data-torneo-id="1">Ver Detalles</button>
                                <button class="btn btn-sm btn-success btnInscribirEquipo" data-torneo-id="1">
                                    <i class="bi bi-trophy"></i> Inscribirse
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark">Ver Detalles</button>
                                <button class="btn btn-sm btn-primary">Gestionar</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Torneo 2 -->
                <div class="border-bottom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info rounded-circle p-2 me-3">
                            <i class="bi bi-trophy text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Liga Amateur - Fecha 8</h6>
                            <small class="text-muted">Fútbol 5 • 12 equipos • En curso</small>
                        </div>
                        <div>
                            <span class="badge text-bg-dark">En Curso</span>
                        </div>
                    </div>
                    <p class="mb-3">Liga amateur semanal. Próxima fecha: Sábado 9 de noviembre a partir de las 14:00.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if (!$perfil_cancha_admin_mode): ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark" data-action="verDetallesTorneo" data-torneo-id="2">Ver Detalles</button>
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="bi bi-lock"></i> Cupos Llenos
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark">Ver Detalles</button>
                                <button class="btn btn-sm btn-primary">Gestionar</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Torneo 3 -->
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success rounded-circle p-2 me-3">
                            <i class="bi bi-trophy text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">Torneo Nocturno Express</h6>
                            <small class="text-muted">Fútbol 5 • <?= $perfil_cancha_admin_mode ? '8' : '0/8' ?> equipos • Viernes por la noche</small>
                        </div>
                        <div>
                            <span class="badge bg-text-dark">Próximamente</span>
                        </div>
                    </div>
                    <p class="mb-3">Torneo express de eliminación directa. Ideal para equipos que quieren competir sin comprometerse toda la temporada.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <?php if (!$perfil_cancha_admin_mode): ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark btnVerDetalles" data-torneo-id="3">Ver Detalles</button>
                                <button class="btn btn-sm btn-warning btnNotificarInicio" data-torneo-id="3">
                                    <i class="bi bi-bell"></i> Notificar Inicio
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-dark">Ver Detalles</button>
                                <button class="btn btn-sm btn-primary">Gestionar</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Sidebar derecha con información -->
    <div class="col-lg-4">
        <!-- Botón principal -->
        <div class="mb-4">
            <?php if ($perfil_cancha_admin_mode): ?>
                <button type="button" class="btn btn-success btn-lg w-100" data-url="<?= PAGE_AGENDA_ADMIN_CANCHA ?>">
                    <i class="bi bi-calendar-check"></i> Ver Disponibilidad
                </button>
            <?php else: ?>
                <a href="<?= $perfil_cancha_boton_primario['url'] ?>" type="button" class="btn btn-success btn-lg w-100">
                    <i class="<?= $perfil_cancha_boton_primario['icono'] ?>"></i> <?= $perfil_cancha_boton_primario['texto'] ?>
                </a>
            <?php endif; ?>
        </div>
        <!-- Panel de Informacion -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-geo-alt"></i> Dirección
                    </label>
                    <p class="mb-0" id="direccionCancha"></p> <?php if (!$perfil_cancha_admin_mode): ?>
                        <button class="btn btn-sm btn-dark mt-1" id="btnVerEnMapa">
                            <i class="bi bi-map"></i> Ver en mapa
                        </button>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-tag"></i> Tipo de Cancha
                    </label>
                    <p class="mb-0" id="tipoCancha"></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-layers"></i> Superficie
                    </label>
                    <p class="mb-0" id="superficieCancha"></p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-people"></i> Capacidad
                    </label>
                    <p class="mb-0" id="capacidadCancha"></p>
                </div>
                <?php if ($perfil_cancha_admin_mode): ?>
                    <div class="mb-0">
                        <label class="fw-bold text-muted d-block mb-1">
                            <i class="bi bi-clipboard-check"></i> Estado
                        </label>
                        <span class="badge text-bg-dark" id="estadoCancha"></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Horarios de atención -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Horarios</h5>
            </div>
            <div class="card-body">
                <small class="text-muted" id="diasAtencion"></small>

                <p class="fw-bold mb-2" id="horarioPrincipal"></p>

                <hr class="my-2">

                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold" id="estadoActual">
                        <i class="bi bi-circle-fill"></i> Cargando...
                    </span>
                    <small class="text-muted" id="horaCierre">Cierra a las N/A</small>
                </div>
            </div>
        </div>

        <!-- Servicios Incluidos
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Servicios y Comodidades</h5>
            </div>
            <div class="card-body">
                <div class="row" id="serviciosContainer">
                    <div class="col-12">
                        <p class="text-muted">Cargando servicios...</p>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Estadísticas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
                <!-- Estadísticas básicas -->
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h4 class="text-primary mb-1"><?= $perfil_cancha_total_partidos ?></h4>
                        <small class="text-muted">Partidos jugados</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info mb-1"><?= $perfil_cancha_total_jugadores ?></h4>
                        <small class="text-muted">Jugadores únicos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>