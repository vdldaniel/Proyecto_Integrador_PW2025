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
$torneo_detalle_titulo_header = $torneo_detalle_titulo_header ?? 'Torneo';
$torneo_detalle_subtitulo_header = $torneo_detalle_subtitulo_header ?? '';
$torneo_detalle_botones_header = $torneo_detalle_botones_header ?? [];
$torneo_detalle_mostrar_pestanas = $torneo_detalle_mostrar_pestanas ?? ['bracket', 'equipos'];
$torneo_detalle_datos_torneo = $torneo_detalle_datos_torneo ?? [
    'nombre' => '',
    'fecha_inicio' => '',
    'fecha_fin' => '',
    'estado' => '',
    'equipos_registrados' => 0,
    'formato' => 'Eliminación directa'
];
?>

<!-- Header con título y botones de navegación -->
<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="fw-bold mb-1"><?= $torneo_detalle_titulo_header ?></h1>
        <p class="text-muted mb-0">
            <i class="bi bi-calendar3"></i> <?= $torneo_detalle_subtitulo_header ?>
            <span class="badge text-bg-dark ms-2" id="torneo-estado-badge"><?= $torneo_detalle_datos_torneo['estado'] ?></span>
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
            <div class="col-12 bracket-container" id="bracketContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando bracket...</span>
                    </div>
                    <p class="text-muted mt-3">Cargando bracket del torneo...</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- PESTAÑA: EQUIPOS -->
    <?php if (in_array('equipos', $torneo_detalle_mostrar_pestanas)): ?>
        <div class="tab-pane fade <?= !in_array('bracket', $torneo_detalle_mostrar_pestanas) ? 'show active' : '' ?>" id="equipos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center equipos-header">
                <h3>Equipos Participantes</h3>
                <span class="badge text-bg-dark"><span id="equipos-count">0</span> equipos registrados</span>
            </div>

            <!-- Lista de equipos en formato fila -->
            <div class="row g-3" id="equiposContainer">
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando equipos...</span>
                    </div>
                    <p class="text-muted mt-3">Cargando equipos...</p>
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
                                    <input type="text" class="form-control" id="config-nombre" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de inicio</label>
                                    <input type="date" class="form-control" id="config-fecha-inicio" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha de finalización</label>
                                    <input type="date" class="form-control" id="config-fecha-fin" value="">
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
                                <button class="btn btn-dark">
                                    <i class="bi bi-pencil"></i> Editar Equipos
                                </button>
                                <button class="btn btn-dark">
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