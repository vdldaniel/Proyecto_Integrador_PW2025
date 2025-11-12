<!-- Componente reutilizable: Perfil de Equipo -->
<!-- Se debe incluir dentro de un <main> container y después de cargar navbar -->
<!-- Variables esperadas:
  $perfil_equipo_admin_mode - Boolean para mostrar funciones de admin
  $perfil_equipo_editar_mode - Boolean para permitir edición (líder del equipo)
  $perfil_equipo_titulo_header - String para título del equipo
  $perfil_equipo_subtitulo_header - String para subtítulo/descripción
  $perfil_equipo_botones_header - Array con botones del header
  $perfil_equipo_mostrar_pestanas - Array con pestañas a mostrar ['info', 'jugadores', 'estadisticas', 'partidos']
  $perfil_equipo_datos_equipo - Array con información del equipo
  $perfil_equipo_jugadores - Array con lista de jugadores
  $perfil_equipo_estadisticas - Array con estadísticas del equipo
-->

<?php
// Variables por defecto si no están definidas
$perfil_equipo_admin_mode = $perfil_equipo_admin_mode ?? false;
$perfil_equipo_editar_mode = $perfil_equipo_editar_mode ?? false;
$perfil_equipo_titulo_header = $perfil_equipo_titulo_header ?? 'Los Tigres FC';
$perfil_equipo_subtitulo_header = $perfil_equipo_subtitulo_header ?? 'Equipo fundado el 15 de Octubre, 2024';
$perfil_equipo_botones_header = $perfil_equipo_botones_header ?? [];
$perfil_equipo_mostrar_pestanas = $perfil_equipo_mostrar_pestanas ?? ['info', 'jugadores', 'estadisticas'];
$perfil_equipo_datos_equipo = $perfil_equipo_datos_equipo ?? [
    'nombre' => 'Los Tigres FC',
    'lider' => 'Carlos Rodríguez (@carlos_lider)',
    'fecha_creacion' => '15 de Octubre, 2024',
    'integrantes' => 8,
    'torneos_activos' => 2,
    'partidos_jugados' => 12,
    'codigo_equipo' => 'TIG2024',
    'estado' => 'Activo'
];
$perfil_equipo_jugadores = $perfil_equipo_jugadores ?? [
    [
        'id' => 1,
        'nombre' => 'Carlos Rodríguez',
        'username' => '@carlos_lider',
        'posicion' => 'Delantero',
        'es_lider' => true,
        'calificacion' => 4.8,
        'partidos' => 45,
        'goles' => 23,
        'estado' => 'Activo'
    ],
    [
        'id' => 2,
        'nombre' => 'Miguel Torres',
        'username' => '@miguel_def',
        'posicion' => 'Defensor',
        'es_lider' => false,
        'calificacion' => 4.5,
        'partidos' => 38,
        'goles' => 3,
        'estado' => 'Activo'
    ],
    [
        'id' => 3,
        'nombre' => 'Juan Pérez',
        'username' => '@juanpe_mid',
        'posicion' => 'Mediocampo',
        'es_lider' => false,
        'calificacion' => 4.3,
        'partidos' => 42,
        'goles' => 8,
        'estado' => 'Activo'
    ]
];
$perfil_equipo_estadisticas = $perfil_equipo_estadisticas ?? [
    'partidos_jugados' => 12,
    'partidos_ganados' => 8,
    'partidos_empatados' => 2,
    'partidos_perdidos' => 2,
    'goles_favor' => 34,
    'goles_contra' => 12,
    'diferencia_goles' => 22,
    'puntos' => 26,
    'racha_actual' => 'G-G-E-G-G'
];
?>

<!-- Header principal del equipo -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white shadow-lg border-0">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <!-- Escudo/Logo del equipo -->
                        <div class="team-logo-container mb-3 mb-md-0">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center mx-auto"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-shield text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h1 class="fw-bold mb-2"><?= $perfil_equipo_titulo_header ?></h1>
                        <p class="mb-1"><?= $perfil_equipo_subtitulo_header ?></p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-success"><i class="bi bi-people"></i> <?= $perfil_equipo_datos_equipo['integrantes'] ?> integrantes</span>
                            <span class="badge bg-warning text-dark"><i class="bi bi-trophy"></i> <?= $perfil_equipo_datos_equipo['torneos_activos'] ?> torneos</span>
                            <span class="badge bg-info"><i class="bi bi-calendar-event"></i> <?= $perfil_equipo_datos_equipo['partidos_jugados'] ?> partidos</span>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                        <?php foreach ($perfil_equipo_botones_header as $boton): ?>
                            <?php if ($boton['tipo'] === 'link'): ?>
                                <a href="<?= $boton['url'] ?>" class="btn <?= $boton['clase'] ?> me-2 mb-2">
                                    <i class="<?= $boton['icono'] ?>"></i> <?= $boton['texto'] ?>
                                </a>
                            <?php else: ?>
                                <button type="button"
                                    class="btn <?= $boton['clase'] ?> me-2 mb-2"
                                    <?= isset($boton['modal']) ? 'data-bs-toggle="modal" data-bs-target="' . $boton['modal'] . '"' : '' ?>
                                    <?= isset($boton['id']) ? 'id="' . $boton['id'] . '"' : '' ?>>
                                    <i class="<?= $boton['icono'] ?>"></i> <?= $boton['texto'] ?>
                                </button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de navegación -->
<ul class="nav nav-tabs mb-4" id="perfilEquipoTabs" role="tablist">
    <?php if (in_array('info', $perfil_equipo_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                <i class="bi bi-info-circle"></i> Información General
            </button>
        </li>
    <?php endif; ?>

    <?php if (in_array('jugadores', $perfil_equipo_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= !in_array('info', $perfil_equipo_mostrar_pestanas) ? 'active' : '' ?>"
                id="jugadores-tab" data-bs-toggle="tab" data-bs-target="#jugadores" type="button" role="tab">
                <i class="bi bi-people"></i> Jugadores (<?= count($perfil_equipo_jugadores) ?>)
            </button>
        </li>
    <?php endif; ?>

    <?php if (in_array('estadisticas', $perfil_equipo_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="estadisticas-tab" data-bs-toggle="tab" data-bs-target="#estadisticas" type="button" role="tab">
                <i class="bi bi-bar-chart"></i> Estadísticas
            </button>
        </li>
    <?php endif; ?>

    <?php if (in_array('partidos', $perfil_equipo_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="partidos-tab" data-bs-toggle="tab" data-bs-target="#partidos" type="button" role="tab">
                <i class="bi bi-calendar-week"></i> Partidos
            </button>
        </li>
    <?php endif; ?>
</ul>

<!-- Contenido de las pestañas -->
<div class="tab-content" id="perfilEquipoTabsContent">

    <!-- PESTAÑA: INFORMACIÓN GENERAL -->
    <?php if (in_array('info', $perfil_equipo_mostrar_pestanas)): ?>
        <div class="tab-pane fade show active" id="info" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Información básica del equipo -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información del Equipo</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted d-block mb-1">
                                        <i class="bi bi-shield"></i> Nombre del Equipo
                                    </label>
                                    <p class="mb-0"><?= $perfil_equipo_datos_equipo['nombre'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted d-block mb-1">
                                        <i class="bi bi-star-fill"></i> Líder del Equipo
                                    </label>
                                    <p class="mb-0"><?= $perfil_equipo_datos_equipo['lider'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted d-block mb-1">
                                        <i class="bi bi-calendar3"></i> Fecha de Creación
                                    </label>
                                    <p class="mb-0"><?= $perfil_equipo_datos_equipo['fecha_creacion'] ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold text-muted d-block mb-1">
                                        <i class="bi bi-key"></i> Código de Equipo
                                    </label>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary"><?= $perfil_equipo_datos_equipo['codigo_equipo'] ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actividad reciente -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="bi bi-clock-history"></i> Actividad Reciente</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Victoria 3-1 vs Real Madrid FC</h6>
                                        <p class="text-muted mb-1">Hace 2 días - Cancha Norte</p>
                                        <small class="text-success">Goles: Carlos R. (2), Miguel T. (1)</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Empate 2-2 vs Barcelona FC</h6>
                                        <p class="text-muted mb-1">Hace 1 semana - Complejo Sur</p>
                                        <small class="text-warning">Goles: Juan P., Miguel T.</small>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="fw-bold">Nuevo miembro: Luis González</h6>
                                        <p class="text-muted mb-1">Hace 2 semanas</p>
                                        <small class="text-info">Se unió al equipo como defensor</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Estadísticas rápidas -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-trophy"></i> Estadísticas Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h3 class="text-success mb-1"><?= $perfil_equipo_estadisticas['partidos_ganados'] ?></h3>
                                    <small class="text-muted">Victorias</small>
                                </div>
                                <div class="col-6">
                                    <h3 class="text-danger mb-1"><?= $perfil_equipo_estadisticas['partidos_perdidos'] ?></h3>
                                    <small class="text-muted">Derrotas</small>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="row text-center">
                                <div class="col-6 border-end">
                                    <h4 class="text-primary mb-1"><?= $perfil_equipo_estadisticas['goles_favor'] ?></h4>
                                    <small class="text-muted">Goles a favor</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-warning mb-1"><?= $perfil_equipo_estadisticas['goles_contra'] ?></h4>
                                    <small class="text-muted">Goles en contra</small>
                                </div>
                            </div>
                            <div class="mt-3 text-center">
                                <span class="badge bg-info">Diferencia: +<?= $perfil_equipo_estadisticas['diferencia_goles'] ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Estado del equipo -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="bi bi-gear"></i> Estado del Equipo</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold text-muted d-block mb-1">Estado</label>
                                <span class="badge bg-success"><?= $perfil_equipo_datos_equipo['estado'] ?></span>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold text-muted d-block mb-1">Racha Actual</label>
                                <div class="racha-container">
                                    <?php
                                    $racha = str_split($perfil_equipo_estadisticas['racha_actual'], 2);
                                    foreach ($racha as $resultado):
                                        $letra = $resultado[0];
                                        $clase = $letra === 'G' ? 'bg-success' : ($letra === 'E' ? 'bg-warning' : 'bg-danger');
                                    ?>
                                        <span class="badge <?= $clase ?> me-1"><?= $letra ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- PESTAÑA: JUGADORES -->
    <?php if (in_array('jugadores', $perfil_equipo_mostrar_pestanas)): ?>
        <div class="tab-pane fade <?= !in_array('info', $perfil_equipo_mostrar_pestanas) ? 'show active' : '' ?>" id="jugadores" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="bi bi-people"></i> Lista de Jugadores</h3>
                <?php if ($perfil_equipo_editar_mode): ?>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarJugador">
                        <i class="bi bi-plus-circle"></i> Agregar Jugador
                    </button>
                <?php endif; ?>
            </div>

            <div class="row g-3">
                <?php foreach ($perfil_equipo_jugadores as $jugador): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm player-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-1 text-center">
                                        <div class="player-avatar rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 50px;">
                                            <i class="bi bi-person-fill text-white fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <h6 class="fw-bold mb-1">
                                            <?= $jugador['nombre'] ?>
                                            <?php if ($jugador['es_lider']): ?>
                                                <i class="bi bi-star-fill text-warning"></i>
                                            <?php endif; ?>
                                        </h6>
                                        <small class="text-muted"><?= $jugador['username'] ?></small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="badge bg-info"><?= $jugador['posicion'] ?></span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <div class="rating">
                                            <span class="text-warning">★★★★★</span>
                                            <small class="d-block text-muted"><?= $jugador['calificacion'] ?></small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <small class="text-muted d-block"><?= $jugador['partidos'] ?> partidos</small>
                                        <small class="text-success"><?= $jugador['goles'] ?> goles</small>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"><i class="bi bi-eye"></i> Ver Perfil</a></li>
                                                <?php if ($perfil_equipo_editar_mode && !$jugador['es_lider']): ?>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-person-x"></i> Remover</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- PESTAÑA: ESTADÍSTICAS -->
    <?php if (in_array('estadisticas', $perfil_equipo_mostrar_pestanas)): ?>
        <div class="tab-pane fade" id="estadisticas" role="tabpanel">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Gráfico de rendimiento -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="bi bi-graph-up"></i> Rendimiento por Mes</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-placeholder bg-light rounded p-4 text-center">
                                <i class="bi bi-bar-chart fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Gráfico de rendimiento mensual</p>
                                <small class="text-muted">Aquí iría un gráfico con Chart.js o similar</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Estadísticas detalladas -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Estadísticas Detalladas</h5>
                        </div>
                        <div class="card-body">
                            <div class="stats-list">
                                <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Partidos Jugados</span>
                                    <span class="fw-bold"><?= $perfil_equipo_estadisticas['partidos_jugados'] ?></span>
                                </div>
                                <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-success">Partidos Ganados</span>
                                    <span class="fw-bold text-success"><?= $perfil_equipo_estadisticas['partidos_ganados'] ?></span>
                                </div>
                                <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-warning">Partidos Empatados</span>
                                    <span class="fw-bold text-warning"><?= $perfil_equipo_estadisticas['partidos_empatados'] ?></span>
                                </div>
                                <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-danger">Partidos Perdidos</span>
                                    <span class="fw-bold text-danger"><?= $perfil_equipo_estadisticas['partidos_perdidos'] ?></span>
                                </div>
                                <div class="stat-item d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted">Puntos</span>
                                    <span class="fw-bold"><?= $perfil_equipo_estadisticas['puntos'] ?></span>
                                </div>
                                <div class="stat-item d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted">Promedio de Goles</span>
                                    <span class="fw-bold"><?= round($perfil_equipo_estadisticas['goles_favor'] / $perfil_equipo_estadisticas['partidos_jugados'], 1) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- PESTAÑA: PARTIDOS -->
    <?php if (in_array('partidos', $perfil_equipo_mostrar_pestanas)): ?>
        <div class="tab-pane fade" id="partidos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="bi bi-calendar-week"></i> Historial de Partidos</h3>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="filtroPartidos" id="todos" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="todos">Todos</label>

                    <input type="radio" class="btn-check" name="filtroPartidos" id="ganados" autocomplete="off">
                    <label class="btn btn-outline-success" for="ganados">Ganados</label>

                    <input type="radio" class="btn-check" name="filtroPartidos" id="perdidos" autocomplete="off">
                    <label class="btn btn-outline-danger" for="perdidos">Perdidos</label>
                </div>
            </div>

            <div class="row g-3" id="partidosContainer">
                <!-- Partido reciente -->
                <div class="col-12 partido-item" data-resultado="ganado">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <h6 class="fw-bold text-success mb-1">VICTORIA</h6>
                                    <small class="text-muted">Hace 2 días</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="match-score">
                                        <span class="fw-bold"><?= $perfil_equipo_datos_equipo['nombre'] ?></span>
                                        <h4 class="my-2">3 - 1</h4>
                                        <span class="text-muted">Real Madrid FC</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block">Cancha Norte</small>
                                    <small class="text-muted">15:30 - 16:30</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver Detalle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Más partidos -->
                <div class="col-12 text-center">
                    <button class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Cargar más partidos
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal para agregar jugador (solo si está en modo edición) -->
<?php if ($perfil_equipo_editar_mode): ?>
    <div class="modal fade" id="modalAgregarJugador" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus"></i> Agregar Jugador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarJugador">
                        <div class="mb-3">
                            <label class="form-label">Username del jugador</label>
                            <div class="input-group">
                                <span class="input-group-text">@</span>
                                <input type="text" class="form-control" placeholder="username_jugador" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Código del equipo</label>
                            <input type="text" class="form-control" value="<?= $perfil_equipo_datos_equipo['codigo_equipo'] ?>" readonly>
                            <small class="form-text text-muted">El jugador debe conocer este código para unirse</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Enviar Invitación</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal para información del equipo -->
<div class="modal fade" id="modalInfoEquipo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-info-circle"></i> Información Detallada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <h6 class="fw-bold">Datos del Equipo</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Nombre completo:</strong></td>
                                    <td><?= $perfil_equipo_datos_equipo['nombre'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Líder:</strong></td>
                                    <td><?= $perfil_equipo_datos_equipo['lider'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de creación:</strong></td>
                                    <td><?= $perfil_equipo_datos_equipo['fecha_creacion'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td><span class="badge bg-success"><?= $perfil_equipo_datos_equipo['estado'] ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Estilos CSS adicionales para el componente -->
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }

    .timeline-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 15px;
        bottom: -20px;
        width: 2px;
        background-color: #e9ecef;
    }

    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .player-card:hover {
        transform: translateY(-2px);
        transition: transform 0.2s ease-in-out;
    }

    .racha-container {
        display: flex;
        gap: 2px;
    }

    .chart-placeholder {
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
</style>