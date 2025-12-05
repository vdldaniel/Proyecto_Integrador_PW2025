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
$perfil_cancha_nombre = $perfil_cancha_nombre ?? 'Nombre de Cancha';
$perfil_cancha_descripcion_banner = $perfil_cancha_descripcion_banner ?? 'Descripción breve de la cancha';

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
            <div id="selectorCanchas" class="dropdown">

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
                        onclick="abrirModalCambiarImagen()">
                        <i class="bi bi-camera-fill"></i> Editar portada
                    </button>
                <?php endif; ?>

                <div class="profile-banner-overlay">
                    <div class="profile-info-container bg-dark bg-opacity-75 p-4 rounded">

                        <div class="d-flex justify-content-between align-items-end">

                            <div>

                                <h1 id="nombreCancha"><?= $perfil_cancha_nombre ?></h1>
                                <p id="descripcionCancha"><?= $perfil_cancha_descripcion_banner ?></p>
                                <div id="tiposPartidoCancha" class="mt-2">
                                    <!-- Se llenará dinámicamente con JavaScript -->
                                </div>
                                <!--
                                <span id="estadoCancha" class="badge bg-info text-light mt-2">
                                    Pendiente
                                </span>-->
                            </div>

                            <!-- TIPO PARTIDO -->
                            <div class="text-end">
                                <div class="mb-2">
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<!-- Modal Cambiar Imagen -->
<div class="modal fade" id="modalCambiarImagen" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Cambiar imagen de la cancha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="imgCanchaId">

                <div class="mb-3 text-center">
                    <img id="previewNuevaImagen" src="" class="img-fluid rounded shadow" style="max-height:180px;">
                </div>

                <input type="file" id="inputNuevaImagen" class="form-control" accept="image/*">
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="btnSubirImagen">Guardar</button>
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

                <!-- LOADER -->
                <div id="loaderTorneos" class="text-center p-4">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">Cargando torneos...</p>
                </div>

                <!-- LISTA DINÁMICA -->
                <div id="listaTorneos"></div>

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
                <button type="button" class="btn btn-success btn-lg w-100" id="btnVerDisponibilidad">
                    <i class="<?= $perfil_cancha_boton_primario['icono'] ?>"></i> <?= $perfil_cancha_boton_primario['texto'] ?>
                </button>
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
                <!--<div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-people"></i> Capacidad
                    </label>
                    <p class="mb-0" id="capacidadCancha"></p>
                </div>-->
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
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-clock"></i> Horarios</h5>
                <?php if ($perfil_cancha_admin_mode): ?>
                    <a href="<?= PAGE_AGENDA_ADMIN_CANCHA ?>?openModal=configurarHorarios" class="btn btn-sm btn-dark">
                        <i class="bi bi-gear"></i> Cambiar horarios
                    </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <!-- Estado actual -->
                <div class="mb-3 p-2 rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold" id="estadoActual">
                            <i class="bi bi-circle-fill"></i> Cargando...
                        </span>
                        <small class="text-muted" id="horaCierre"></small>
                    </div>
                </div>

                <!-- Horarios detallados por día -->
                <div id="horariosDetallados" class="mt-3">
                    <p class="text-muted">Cargando horarios...</p>
                </div>
            </div>
        </div>

        <!-- Servicios Incluidos-->
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
        </div> 

        <!-- Estadísticas (Ocultas temporalmente) -->
        <?php if (false): ?>
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
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
        <?php endif; ?>
    </div>
</div>