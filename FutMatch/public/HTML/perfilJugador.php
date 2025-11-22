<!-- Componente reutilizable: Perfil de Jugador -->
<!-- Se debe incluir dentro de un <main> container y después de cargar navbar -->
<!-- Variables esperadas:
  $perfil_jugador_admin_mode - Boolean para mostrar funciones de admin/edición
  $perfil_jugador_es_propio - Boolean para indicar si es el perfil del usuario actual
  $perfil_jugador_titulo_header - String para título del header
  $perfil_jugador_subtitulo_header - String para subtítulo del header
  $perfil_jugador_botones_header - Array con botones del header
  $perfil_jugador_titulo_partidos - String para título de la sección de partidos
  $perfil_jugador_titulo_estadisticas - String para título de la sección de estadísticas
  $perfil_jugador_mostrar_reportar - Boolean para mostrar botón reportar
  $perfil_jugador_mostrar_equipos - Boolean para mostrar sección de equipos
-->

<?php
// Variables por defecto si no están definidas
$perfil_jugador_admin_mode = $perfil_jugador_admin_mode ?? false;
$perfil_jugador_es_propio = $perfil_jugador_es_propio ?? false;
$perfil_jugador_titulo_header = $perfil_jugador_titulo_header ?? 'Mi Perfil';
$perfil_jugador_subtitulo_header = $perfil_jugador_subtitulo_header ?? 'Gestiona tu información personal y estadísticas de juego';
$perfil_jugador_botones_header = $perfil_jugador_botones_header ?? [];
$perfil_jugador_titulo_partidos = $perfil_jugador_titulo_partidos ?? 'Mis Partidos Recientes';
$perfil_jugador_titulo_estadisticas = $perfil_jugador_titulo_estadisticas ?? 'Mis Estadísticas';
$perfil_jugador_mostrar_reportar = $perfil_jugador_mostrar_reportar ?? false;
$perfil_jugador_mostrar_equipos = $perfil_jugador_mostrar_equipos ?? true;
?>

<!-- Línea 1: Header con título y botones de navegación -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="fw-bold mb-1"><?= $perfil_jugador_titulo_header ?></h1>
        <p class="text-muted mb-0"><?= $perfil_jugador_subtitulo_header ?></p>
    </div>
    <div class="col-md-6 text-end">
        <?php foreach ($perfil_jugador_botones_header as $boton): ?>
            <?php if ($boton['tipo'] === 'link'): ?>
                <a href="<?= $boton['url'] ?>" class="btn <?= $boton['clase'] ?> me-2">
                    <i class="<?= $boton['icono'] ?>"></i> <?= $boton['texto'] ?>
                </a>
            <?php else: ?>
                <button type="button"
                    class="btn <?= $boton['clase'] ?> me-2"
                    <?= isset($boton['modal']) ? 'data-bs-toggle="modal" data-bs-target="' . $boton['modal'] . '"' : '' ?>
                    <?= isset($boton['id']) ? 'id="' . $boton['id'] . '"' : '' ?>>
                    <i class="<?= $boton['icono'] ?>"></i> <?= $boton['texto'] ?>
                </button>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Banner del jugador -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-lg rounded-3 overflow-hidden profile-banner-container">
            <!-- Imagen de banner -->
            <div class="position-relative profile-banner-wrapper">
                <div class="profile-banner-image"
                    id="banner">
                </div>

                <!-- Botón editar portada (solo si es perfil propio) -->
                <?php if ($perfil_jugador_es_propio): ?>
                    <button class="btn btn-dark btn-sm profile-banner-edit-btn"
                        data-bs-toggle="modal"
                        data-bs-target="#modalCambiarBanner">
                        <i class="bi bi-camera-fill"></i> Editar portada
                    </button>
                <?php endif; ?>

                <!-- Overlay con información -->
                <div class="profile-banner-overlay">
                    <!-- Avatar del jugador (esquina inferior izquierda) -->
                    <div class="profile-avatar-container">
                        <div class="position-relative">
                            <img class="profile-avatar"
                                alt="Avatar"
                                id="fotoPerfil">
                            <?php if ($perfil_jugador_es_propio): ?>
                                <button class="btn btn-dark profile-avatar-edit-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCambiarFotoPerfil">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="profile-info-container d-flex">

                        <!-- COLUMNA IZQUIERDA -->
                        <div class="profile-info-left flex-grow-1">
                            <h2 class="profile-name mb-1" id="nombreJugador"></h2>
                            <p class="profile-username mb-2" id="usernameJugador"></p>

                            <div class="profile-badges">
                                <span class="badge bg-success me-2" id="estadoJugador"></span>
                                <span class="profile-rating me-2" id="calificacionJugador"></span>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="profile-info-right ms-3">
                            <p class="profile-description mb-0" id="descripcionJugador"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenido principal -->
<div class="row">
    <!-- Sidebar derecha con información (aparece primero en móvil) -->
    <div class="col-lg-4 order-lg-2">
        <!-- Botón de acción principal (si es necesario) -->
        <?php if ($perfil_jugador_mostrar_reportar): ?>
            <div class="mb-4">
                <button type="button" class="btn btn-danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#modalReportarJugador">
                    <i class="bi bi-flag"></i> Reportar Jugador
                </button>
            </div>
        <?php endif; ?>

        <!-- Información personal -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-person"></i> Información Personal</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted d-block mb-1">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <p class="mb-0" id="emailJugador">—</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted d-block mb-1">
                        <i class="bi bi-telephone me-1"></i>Teléfono
                    </label>
                    <p class="mb-0" id="telefonoJugador">—</p>
                </div>
                <div class="mb-3">
                    <label class="text-muted d-block mb-1">
                        <i class="bi bi-calendar me-1"></i>Edad
                    </label>
                    <p class="mb-0" id="edadJugador">—</p>
                </div>
                <?php if ($perfil_jugador_es_propio): ?>
                    <div class="mb-0">
                        <label class="text-muted d-block mb-1">
                            <i class="bi bi-calendar-plus me-1"></i>Miembro desde
                        </label>
                        <p class="mb-0" id="miembroDesdeJugador">—</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Estadísticas detalladas -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> <?= $perfil_jugador_titulo_estadisticas ?></h5>
            </div>
            <div class="card-body">
                <!-- Calificación promedio -->
                <div class="text-center mb-3">
                    <div class="text-warning mb-2" style="font-size: 1.5rem;" id="estrellasCalificacion">
                        ☆☆☆☆☆
                    </div>
                    <h3 class="text-warning mb-0" id="promedioCalificacion">0.0</h3>
                    <small class="text-muted" id="basadoEnReseñas">Basado en 0 calificaciones</small>
                </div>

                <hr class="my-3">

                <!-- Estadísticas de juego -->
                <div class="row text-center mb-3">
                    <div class="col-6 border-end">
                        <div class="fw-bold text-success fs-5" id="totalPartidos">0</div>
                        <small class="text-muted">Partidos</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-primary fs-5" id="porcentajeAsistencia">0%</div>
                        <small class="text-muted">Asistencia</small>
                    </div>
                </div>

                <div class="row text-center mb-3">
                    <div class="col-6 border-end">
                        <div class="fw-bold text-warning fs-5" id="totalGoles">0</div>
                        <small class="text-muted">Goles</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-info fs-5" id="totalAsistencias">0</div>
                        <small class="text-muted">Asistencias</small>
                    </div>
                </div>

                <hr class="my-3">

                <!--<div class="mt-3 text-center">
                    <button class="btn btn-sm btn-dark" id="btnVerEstadisticas">
                        <i class="bi bi-graph-up"></i> Ver estadísticas completas
                    </button>
                </div>-->
            </div>
        </div>
    </div>

    <!-- Columna de partidos (aparece después en móvil, antes en desktop) -->
    <div class="col-lg-8 order-lg-1">
        <!-- Sección de Partidos Recientes -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-trophy"></i> <?= $perfil_jugador_titulo_partidos ?></h4>
            </div>
            <div class="card-body p-0">
                <div id="listaPartidosRecientes">
                    <!-- Los partidos se cargan dinámicamente con JavaScript -->
                </div>
                <!--<div class="p-4 text-center">
                    <button class="btn btn-sm btn-dark" id="btnVerCalificaciones">
                        <i class="bi bi-graph-up"></i> Ver todas las calificaciones
                    </button>
                </div>-->
            </div>
        </div>

        <!-- Sección de Historial de Equipos -->
        <?php if ($perfil_jugador_mostrar_equipos): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-people"></i> Historial de Equipos</h4>
                </div>
                <div class="card-body">
                    <div class="row" id="listaEquiposJugador">
                        <!-- Los equipos se cargan dinámicamente con JavaScript -->
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal para cambiar avatar (solo perfil propio) -->
<?php if ($perfil_jugador_es_propio): ?>
    <div class="modal fade" id="modalCambiarFotoPerfil" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-circle"></i> Cambiar Foto de Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Zona de arrastrar y soltar-->
                    <div class="input mb-3">
                        <label for="inputFotoPrincipal" class="form-label">Foto de Perfil</label>
                        <div class="foto-principal" id="dropZoneFotoPerfil">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <span class="upload-text">Click o arrastra una imagen</span>
                            </div>
                            <input type="file" name="fotoPerfil" class="d-none" id="inputFotoPrincipal" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnEliminarFotoPerfil">
                        <i class="bi bi-trash"></i>Eliminar foto actual</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarFotoPerfil" disabled>Guardar Foto</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar banner -->
    <div class="modal fade" id="modalCambiarBanner" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-image"></i> Cambiar Portada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Zona de arrastrar y soltar-->
                    <div class="input mb-3">
                        <label for="inputBanner" class="form-label">Banner</label>
                        <div class="banner" id="dropZoneBanner">
                            <div class="upload-icon">
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <span class="upload-text">Click o arrastra una imagen</span>
                            </div>
                            <input type="file" name="banner" class="d-none" id="inputBanner" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="btnEliminarBanner">
                        <i class="bi bi-trash"></i>Eliminar foto actual</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarBanner" disabled>Guardar Foto</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal para reportar jugador -->
<?php if ($perfil_jugador_mostrar_reportar): ?>
    <div class="modal fade" id="modalReportarJugador" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-flag"></i> Reportar Jugador
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formReportarJugador">
                        <div class="mb-3">
                            <label class="form-label">Motivo del reporte</label>
                            <select class="form-select" name="motivo" required>
                                <option value="">Selecciona un motivo</option>
                                <option value="comportamiento_agresivo">Comportamiento agresivo</option>
                                <option value="juego_sucio">Juego sucio</option>
                                <option value="falta_respeto">Falta de respeto</option>
                                <option value="abandono_partido">Abandono de partido</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción detallada</label>
                            <textarea class="form-control" name="descripcion" rows="4" placeholder="Describe lo que ocurrió..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnEnviarReporte">Enviar Reporte</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="<?= JS_BOOTSTRAP ?>"></script>