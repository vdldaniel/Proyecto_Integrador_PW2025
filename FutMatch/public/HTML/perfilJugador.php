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
        <div class="card shadow-lg rounded-3 overflow-hidden">
            <!-- Imagen de banner -->
            <div class="position-relative">
                <img src="<?= IMG_PATH ?>bg3.jpg" class="card-img-top" alt="Banner del jugador" style="height: 250px; object-fit: cover;">
                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white p-4">
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="d-flex align-items-end">
                            <!-- Avatar del jugador -->
                            <div class="position-relative me-3">
                                <img src="<?= IMG_PATH ?>default-avatar.png"
                                    class="rounded-circle border border-3 border-light"
                                    alt="Avatar"
                                    width="100"
                                    height="100"
                                    id="avatarJugador">
                                <?php if ($perfil_jugador_es_propio): ?>
                                    <button class="btn btn-light btn-sm rounded-circle position-absolute bottom-0 end-0"
                                        style="width: 30px; height: 30px; padding: 0;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalCambiarAvatar">
                                        <i class="bi bi-camera-fill"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <!-- Información básica -->
                            <div>
                                <h2 class="mb-1" id="nombreJugador">Carlos Fernández</h2>
                                <p class="mb-1 opacity-75" id="usernameJugador">@carlos_futbol</p>
                                <div class="d-flex align-items-center">
                                    <span class="badge text-bg-dark me-2" id="estadoJugador">Activo</span>
                                    <span class="text-warning me-2" id="calificacionJugador">
                                        ★★★★☆ <small class="text-light">(4.3)</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Estadísticas rápidas -->
                        <div class="text-end d-none d-md-block">
                            <div class="row text-center">
                                <div class="col">
                                    <div class="fw-bold fs-4" id="totalPartidos">127</div>
                                    <small class="text-light">Partidos</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-4" id="golesAnotados">45</div>
                                    <small class="text-light">Goles</small>
                                </div>
                                <div class="col">
                                    <div class="fw-bold fs-4" id="asistencias">32</div>
                                    <small class="text-light">Asistencias</small>
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
        <!-- Sección de Partidos Recientes -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-trophy"></i> <?= $perfil_jugador_titulo_partidos ?></h4>
            </div>
            <div class="card-body p-0">
                <!-- Partido 1 -->
                <div class="border-bottom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge text-bg-dark me-3 p-2">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Victoria 4-2</h5>
                            <p class="text-muted mb-0">Complejo Deportivo Norte • 08/11/2025</p>
                        </div>
                        <div class="text-end">
                            <span class="badge text-bg-dark text-dark">2 goles</span>
                            <span class="badge text-bg-dark text-dark ms-1">1 asistencia</span>
                        </div>
                    </div>
                    <p class="mb-3">Excelente partido donde el equipo mostró una gran coordinación ofensiva.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Equipo: Los Cracks FC</small>
                        </div>
                        <div>
                            <span class="text-warning">★★★★★</span>
                            <small class="text-muted ms-1">Calificación recibida</small>
                        </div>
                    </div>
                </div>

                <!-- Partido 2 -->
                <div class="border-bottom p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge text-bg-dark me-3 p-2">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Derrota 1-3</h5>
                            <p class="text-muted mb-0">Futbol Club Centro • 05/11/2025</p>
                        </div>
                        <div class="text-end">
                            <span class="badge text-bg-dark text-dark">1 gol</span>
                        </div>
                    </div>
                    <p class="mb-3">Partido complicado contra un equipo muy organizado defensivamente.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Equipo: Los Cracks FC</small>
                        </div>
                        <div>
                            <span class="text-warning">★★★☆☆</span>
                            <small class="text-muted ms-1">Calificación recibida</small>
                        </div>
                    </div>
                </div>

                <!-- Partido 3 -->
                <div class="p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="badge text-bg-dark me-3 p-2">
                            <i class="bi bi-dash-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">Empate 2-2</h5>
                            <p class="text-muted mb-0">Cancha Municipal • 02/11/2025</p>
                        </div>
                        <div class="text-end">
                            <span class="badge text-bg-dark text-dark">2 asistencias</span>
                        </div>
                    </div>
                    <p class="mb-3">Partido equilibrado donde ambos equipos tuvieron buenas oportunidades.</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Equipo: Los Cracks FC</small>
                        </div>
                        <div>
                            <span class="text-warning">★★★★☆</span>
                            <small class="text-muted ms-1">Calificación recibida</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de Historial de Equipos (solo para admins o perfil propio) -->
        <?php if ($perfil_jugador_admin_mode || $perfil_jugador_es_propio): ?>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-people"></i> Historial de Equipos</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Los Cracks FC</h6>
                                    <p class="card-text text-muted">Equipo actual • Desde Marzo 2025</p>
                                    <div class="d-flex justify-content-between">
                                        <small>23 partidos jugados</small>
                                        <span class="badge text-bg-dark">Activo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title">Deportivo Unidos</h6>
                                    <p class="card-text text-muted">Enero 2025 - Marzo 2025</p>
                                    <div class="d-flex justify-content-between">
                                        <small>15 partidos jugados</small>
                                        <span class="badge text-bg-dark">Inactivo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar derecha con información -->
    <div class="col-lg-4">
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
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-envelope me-1"></i>Email
                    </label>
                    <p class="mb-0" id="emailJugador">carlos.fernandez@email.com</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-telephone me-1"></i>Teléfono
                    </label>
                    <p class="mb-0" id="telefonoJugador">+54 11 1234-5678</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-calendar me-1"></i>Edad
                    </label>
                    <p class="mb-0" id="edadJugador">28 años</p>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">
                        <i class="bi bi-geo-alt me-1"></i>Ubicación
                    </label>
                    <p class="mb-0" id="ubicacionJugador">CABA, Buenos Aires</p>
                </div>
                <?php if ($perfil_jugador_es_propio): ?>
                    <div class="mb-0">
                        <label class="fw-bold text-muted d-block mb-1">
                            <i class="bi bi-calendar-plus me-1"></i>Miembro desde
                        </label>
                        <p class="mb-0 text-success">Enero 2025</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Posición y habilidades -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-award"></i> Habilidades</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">Posición Preferida</label>
                    <span class="badge text-bg-dark" id="posicionJugador">Mediocampista</span>
                </div>
                <div class="mb-3">
                    <label class="fw-bold text-muted d-block mb-1">Pie Hábil</label>
                    <p class="mb-0" id="pieJugador">Derecho</p>
                </div>
                <div class="mb-0">
                    <label class="fw-bold text-muted d-block mb-2">Especialidades</label>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="badge text-bg-dark">Pases largos</span>
                        <span class="badge text-bg-dark text-dark">Centros</span>
                        <span class="badge text-bg-dark">Visión de juego</span>
                    </div>
                </div>
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
                    <div class="text-warning mb-2" style="font-size: 1.5rem;">
                        ★★★★☆
                    </div>
                    <h3 class="text-warning mb-0">4.3</h3>
                    <small class="text-muted">Basado en 89 calificaciones</small>
                </div>
                <hr class="my-3">

                <!-- Estadísticas de juego -->
                <div class="row text-center mb-3">
                    <div class="col-6 border-end">
                        <div class="fw-bold text-success fs-5">127</div>
                        <small class="text-muted">Partidos</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-primary fs-5">89%</div>
                        <small class="text-muted">Asistencia</small>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-6 border-end">
                        <div class="fw-bold text-warning fs-5">45</div>
                        <small class="text-muted">Goles</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-info fs-5">32</div>
                        <small class="text-muted">Asistencias</small>
                    </div>
                </div>

                <!-- Botón ver estadísticas completas (si no es admin) -->
                <?php if (!$perfil_jugador_admin_mode && $perfil_jugador_es_propio): ?>
                    <div class="mt-3 text-center">
                        <button class="btn btn-sm btn-dark" id="btnVerEstadisticas">
                            <i class="bi bi-graph-up"></i> Ver estadísticas completas
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar avatar (solo perfil propio) -->
<?php if ($perfil_jugador_es_propio): ?>
    <div class="modal fade" id="modalCambiarAvatar" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cambiar Avatar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <img id="previewAvatar" src="<?= IMG_PATH ?>default-avatar.png" class="rounded-circle mb-3" width="120" height="120">
                        <input type="file" class="form-control" id="inputAvatar" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarAvatar">Guardar</button>
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