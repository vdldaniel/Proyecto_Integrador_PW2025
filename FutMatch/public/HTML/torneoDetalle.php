<!-- Componente reutilizable: Detalle de Torneo -->
<!-- Se debe incluir dentro de un <main> container y después de cargar navbar -->
<!-- Variables esperadas:
  $torneo_detalle_admin_mode - Boolean para mostrar funciones de admin
  $torneo_detalle_titulo_header - String para título del header
  $torneo_detalle_subtitulo_header - String para subtítulo del header
  $torneo_detalle_botones_header - Array con botones del header
  $torneo_detalle_mostrar_pestanas - Array con pestañas a mostrar ['bracket', 'equipos', 'configuracion']
  $torneo_detalle_datos_torneo - Array con información del torneo
-->

<?php
// Variables por defecto si no están definidas
$torneo_detalle_admin_mode = $torneo_detalle_admin_mode ?? false;
$torneo_detalle_titulo_header = $torneo_detalle_titulo_header ?? 'Copa FutMatch 2025';
$torneo_detalle_subtitulo_header = $torneo_detalle_subtitulo_header ?? '11/10/2025 - 15/12/2025';
$torneo_detalle_botones_header = $torneo_detalle_botones_header ?? [];
$torneo_detalle_mostrar_pestanas = $torneo_detalle_mostrar_pestanas ?? ['bracket', 'equipos'];
$torneo_detalle_datos_torneo = $torneo_detalle_datos_torneo ?? [
    'nombre' => 'Copa FutMatch 2025',
    'fecha_inicio' => '11/10/2025',
    'fecha_fin' => '15/12/2025',
    'estado' => 'En curso',
    'equipos_registrados' => 16,
    'formato' => 'Eliminación directa',
    'premio' => '$50,000'
];
?>

<!-- Header con título y botones de navegación -->
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fw-bold mb-1"><?= $torneo_detalle_titulo_header ?></h1>
        <p class="text-muted mb-0">
            <i class="bi bi-calendar3"></i> <?= $torneo_detalle_subtitulo_header ?>
            <span class="badge bg-primary ms-2"><?= $torneo_detalle_datos_torneo['estado'] ?></span>
        </p>
    </div>
    <div class="col-md-4 text-end">
        <?php foreach ($torneo_detalle_botones_header as $boton): ?>
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

<!-- Pestañas de navegación -->
<ul class="nav nav-tabs" id="torneoTabs" role="tablist">
    <?php if (in_array('bracket', $torneo_detalle_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="partidos-tab" data-bs-toggle="tab" data-bs-target="#partidos" type="button" role="tab">
                <i class="bi bi-diagram-3"></i> Bracket del Torneo
            </button>
        </li>
    <?php endif; ?>

    <?php if (in_array('equipos', $torneo_detalle_mostrar_pestanas)): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link <?= !in_array('bracket', $torneo_detalle_mostrar_pestanas) ? 'active' : '' ?>"
                id="equipos-tab" data-bs-toggle="tab" data-bs-target="#equipos" type="button" role="tab">
                <i class="bi bi-people"></i> Equipos
            </button>
        </li>
    <?php endif; ?>

    <?php if (in_array('configuracion', $torneo_detalle_mostrar_pestanas) && $torneo_detalle_admin_mode): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="configuracion-tab" data-bs-toggle="tab" data-bs-target="#configuracion" type="button" role="tab">
                <i class="bi bi-gear"></i> Configuración
            </button>
        </li>
    <?php endif; ?>
</ul>

<!-- Contenido de las pestañas -->
<div class="tab-content" id="torneoTabsContent">

    <!-- PESTAÑA: BRACKET DEL TORNEO -->
    <?php if (in_array('bracket', $torneo_detalle_mostrar_pestanas)): ?>
        <div class="tab-pane fade show active" id="partidos" role="tabpanel">
            <div class="col-12 bracket-container">
                <!-- Octavos de Final -->
                <div class="col-3 bracket-branch octavos">
                    <div class="branch-header">
                        <h5 class="branch-title">Octavos de Final</h5>
                    </div>
                    <div class="branch-body">
                        <div class="branch-block">
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Los Cracks FC</span>
                                    <span class="team-score">3</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Deportivo Fútbol</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Tigres FC</span>
                                    <span class="team-score">2</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Real Unidos</span>
                                    <span class="team-score">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="branch-block">
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Atlético Norte</span>
                                    <span class="team-score">1</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Club Central</span>
                                    <span class="team-score">3</span>
                                </div>
                            </div>
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Estudiantes FC</span>
                                    <span class="team-score">4</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Racing Club</span>
                                    <span class="team-score">2</span>
                                </div>
                            </div>
                        </div>
                        <div class="branch-block">
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Boca Unidos</span>
                                    <span class="team-score">1</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">River Plate FC</span>
                                    <span class="team-score">2</span>
                                </div>
                            </div>
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Independiente</span>
                                    <span class="team-score">3</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">San Lorenzo</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                        </div>
                        <div class="branch-block">
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Vélez FC</span>
                                    <span class="team-score">0</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Huracán</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                            <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                <div class="match-team">
                                    <span class="team-name">Lanús FC</span>
                                    <span class="team-score">2</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Banfield</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cuartos de Final -->
                <div class="col-3 bracket-branch cuartos">
                    <div class="branch-header">
                        <h5 class="branch-title">Cuartos de Final</h5>
                    </div>
                    <div class="branch-body">
                        <div class="branch-block">
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">Los Cracks FC</span>
                                        <span class="team-score">2</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Tigres FC</span>
                                        <span class="team-score">1</span>
                                    </div>
                                </div>
                            </div>
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">Club Central</span>
                                        <span class="team-score">0</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Estudiantes FC</span>
                                        <span class="team-score">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="branch-block">
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">River Plate FC</span>
                                        <span class="team-score">1</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Independiente</span>
                                        <span class="team-score">2</span>
                                    </div>
                                </div>
                            </div>
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">Huracán</span>
                                        <span class="team-score">1</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Lanús FC</span>
                                        <span class="team-score">3</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Semifinal -->
                <div class="col-3 bracket-branch semis">
                    <div class="branch-header">
                        <h5 class="branch-title">Semifinal</h5>
                    </div>
                    <div class="branch-body">
                        <div class="branch-block">
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">Los Cracks FC</span>
                                        <span class="team-score">1</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Estudiantes FC</span>
                                        <span class="team-score">2</span>
                                    </div>
                                </div>
                            </div>
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team">
                                        <span class="team-name">Independiente</span>
                                        <span class="team-score">0</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Lanús FC</span>
                                        <span class="team-score">1</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Final -->
                <div class="col-3 bracket-branch final">
                    <div class="branch-header">
                        <h5 class="branch-title">Final</h5>
                    </div>
                    <div class="branch-body">
                        <div class="branch-block">
                            <div class="branch-pair">
                                <div class="branch-match" data-bs-toggle="modal" data-bs-target="#modalPartidoDetalle">
                                    <div class="match-team winner">
                                        <span class="team-name">Estudiantes FC</span>
                                        <span class="team-score">3</span>
                                    </div>
                                    <div class="match-team">
                                        <span class="team-name">Lanús FC</span>
                                        <span class="team-score">2</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Cierre del bracket-container -->
        </div> <!-- Cierre del tab-pane partidos -->
    <?php endif; ?>

    <!-- PESTAÑA: EQUIPOS -->
    <?php if (in_array('equipos', $torneo_detalle_mostrar_pestanas)): ?>
        <div class="tab-pane fade <?= !in_array('bracket', $torneo_detalle_mostrar_pestanas) ? 'show active' : '' ?>" id="equipos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center equipos-header">
                <h3>Equipos Participantes</h3>
                <span class="badge bg-primary"><?= $torneo_detalle_datos_torneo['equipos_registrados'] ?> equipos registrados</span>
            </div>

            <!-- Lista de equipos en formato fila -->
            <div class="row g-3">
                <!-- Equipo 1 - Campeón -->
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card champion">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <i class="bi bi-trophy-fill text-warning fs-2"></i>
                                </div>
                                <div class="col-md-2 text-center">
                                    <img src="<?= IMG_PATH ?>team-placeholder.png" alt="Escudo" class="team-logo" width="50" height="50">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1 text-warning">🏆 Estudiantes FC</h5>
                                    <p class="text-muted mb-0">Campeón del torneo</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-success">Campeón</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">7 partidos</small>
                                    <br>
                                    <small class="text-success">6G - 1E - 0P</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if ($torneo_detalle_admin_mode): ?>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipo 2 - Subcampeón -->
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card runner-up">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <i class="bi bi-award-fill text-secondary fs-2"></i>
                                </div>
                                <div class="col-md-2 text-center">
                                    <img src="<?= IMG_PATH ?>team-placeholder.png" alt="Escudo" class="team-logo" width="50" height="50">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">🥈 Lanús FC</h5>
                                    <p class="text-muted mb-0">Subcampeón del torneo</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-secondary">Subcampeón</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">7 partidos</small>
                                    <br>
                                    <small class="text-info">5G - 1E - 1P</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if ($torneo_detalle_admin_mode): ?>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipo 3 - Semifinalista -->
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="badge bg-warning text-dark">3°</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <img src="<?= IMG_PATH ?>team-placeholder.png" alt="Escudo" class="team-logo" width="50" height="50">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">Los Cracks FC</h5>
                                    <p class="text-muted mb-0">Semifinalista</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-warning text-dark">Semifinal</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">6 partidos</small>
                                    <br>
                                    <small class="text-warning">4G - 1E - 1P</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if ($torneo_detalle_admin_mode): ?>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipo 4 - Semifinalista -->
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="badge bg-warning text-dark">4°</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <img src="<?= IMG_PATH ?>team-placeholder.png" alt="Escudo" class="team-logo" width="50" height="50">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1">Independiente</h5>
                                    <p class="text-muted mb-0">Semifinalista</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-warning text-dark">Semifinal</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">6 partidos</small>
                                    <br>
                                    <small class="text-warning">4G - 0E - 2P</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if ($torneo_detalle_admin_mode): ?>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipos eliminados en cuartos -->
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card eliminated">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="text-muted">5°-8°</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <img src="<?= IMG_PATH ?>team-placeholder.png" alt="Escudo" class="team-logo" width="50" height="50">
                                </div>
                                <div class="col-md-3">
                                    <h5 class="mb-1 text-muted">Tigres FC</h5>
                                    <p class="text-muted mb-0">Eliminado en cuartos</p>
                                </div>
                                <div class="col-md-2 text-center">
                                    <span class="badge bg-danger">Eliminado</span>
                                </div>
                                <div class="col-md-2 text-center">
                                    <small class="text-muted">5 partidos</small>
                                    <br>
                                    <small class="text-muted">3G - 0E - 2P</small>
                                </div>
                                <div class="col-md-2 text-end">
                                    <?php if ($torneo_detalle_admin_mode): ?>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i> Ver
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Más equipos... -->
                <div class="col-12 text-center mt-3">
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i> Mostrando los primeros 5 equipos.
                        <a href="#" class="text-decoration-none">Ver todos los equipos registrados</a>
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- PESTAÑA: CONFIGURACIÓN (solo para admin) -->
    <?php if (in_array('configuracion', $torneo_detalle_mostrar_pestanas) && $torneo_detalle_admin_mode): ?>
        <div class="tab-pane fade" id="configuracion" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-gear"></i> Configuración General</h5>
                        </div>
                        <div class="card-body">
                            <form id="formConfigTorneo">
                                <div class="mb-3">
                                    <label class="form-label">Nombre del torneo</label>
                                    <input type="text" class="form-control" value="<?= $torneo_detalle_datos_torneo['nombre'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" value="2025-10-11">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de finalización</label>
                                    <input type="date" class="form-control" value="2025-12-15">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Premio total</label>
                                    <input type="text" class="form-control" value="<?= $torneo_detalle_datos_torneo['premio'] ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-people"></i> Gestión de Equipos</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Agregar Equipo
                                </button>
                                <button class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Editar Equipos
                                </button>
                                <button class="btn btn-outline-danger">
                                    <i class="bi bi-trash"></i> Eliminar Equipo
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Detalles del Partido -->
<div class="modal fade" id="modalPartidoDetalle" tabindex="-1" aria-labelledby="modalPartidoDetalleLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPartidoDetalleLabel">
                    <i class="bi bi-diagram-3"></i> Detalles del Partido
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información del partido -->
                    <div class="col-12 mb-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h4 id="modal-fase" class="text-primary mb-3">Octavos de Final</h4>
                                <div class="row align-items-center">
                                    <div class="col-4">
                                        <img src="<?= IMG_PATH ?>team-placeholder.png" class="img-fluid mb-2" width="60">
                                        <h6 id="modal-equipo1">Los Cracks FC</h6>
                                    </div>
                                    <div class="col-4">
                                        <h2 class="text-primary mb-0">
                                            <span id="modal-score1">3</span> - <span id="modal-score2">1</span>
                                        </h2>
                                        <small class="text-muted">Resultado Final</small>
                                    </div>
                                    <div class="col-4">
                                        <img src="<?= IMG_PATH ?>team-placeholder.png" class="img-fluid mb-2" width="60">
                                        <h6 id="modal-equipo2">Deportivo Fútbol</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional del partido -->
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold">Información del Partido</h6>
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td><strong>Fecha:</strong></td>
                                    <td>15/11/2025</td>
                                </tr>
                                <tr>
                                    <td><strong>Hora:</strong></td>
                                    <td>18:00</td>
                                </tr>
                                <tr>
                                    <td><strong>Cancha:</strong></td>
                                    <td>Complejo Deportivo Norte</td>
                                </tr>
                                <tr>
                                    <td><strong>Árbitro:</strong></td>
                                    <td>Carlos Mendoza</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
                <a href="#" id="modal-btn-ver-completo" class="btn btn-primary">
                    <i class="bi bi-eye"></i> Ver Partido Completo
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles del Torneo -->
<div class="modal fade" id="modalDetallesTorneo" tabindex="-1" aria-labelledby="modalDetallesTorneoLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetallesTorneoLabel">
                    <i class="bi bi-info-circle"></i> Detalles del Torneo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Información básica -->
                    <div class="col-12 mb-4">
                        <h6 class="fw-bold">Información General</h6>
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td><strong>Nombre:</strong></td>
                                    <td><?= $torneo_detalle_datos_torneo['nombre'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de inicio:</strong></td>
                                    <td><?= $torneo_detalle_datos_torneo['fecha_inicio'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha de finalización:</strong></td>
                                    <td><?= $torneo_detalle_datos_torneo['fecha_fin'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Formato:</strong></td>
                                    <td><?= $torneo_detalle_datos_torneo['formato'] ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Premio total:</strong></td>
                                    <td><?= $torneo_detalle_datos_torneo['premio'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Estado de inscripciones -->
                    <div class="col-12">
                        <h6 class="fw-bold">Estado de Inscripciones</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Las inscripciones se cerraron el <strong>25/10/2025</strong> con un total de <strong><?= $torneo_detalle_datos_torneo['equipos_registrados'] ?> equipos</strong> registrados.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>