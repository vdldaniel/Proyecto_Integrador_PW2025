<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'torneoDetalle';
$page_title = "Detalle del Torneo - FutMatch";
$page_css = [CSS_PAGES_DETALLE_TORNEO];

// Configuración del componente de torneo detalle - Vista Admin Cancha
$torneo_detalle_admin_mode = true;
$torneo_detalle_titulo_header = 'Copa FutMatch 2025';
$torneo_detalle_subtitulo_header = '11/10/2025 - 15/12/2025';
$torneo_detalle_botones_header = [
    [
        'tipo' => 'button',
        'texto' => 'Detalles del torneo',
        'clase' => 'btn-outline-info',
        'icono' => 'bi bi-info-circle',
        'modal' => '#modalDetallesTorneo'
    ]
];
$torneo_detalle_mostrar_pestanas = ['bracket', 'equipos'];
$torneo_detalle_datos_torneo = [
    'nombre' => 'Copa FutMatch 2025',
    'fecha_inicio' => '11/10/2025',
    'fecha_fin' => '15/12/2025',
    'estado' => 'En curso',
    'equipos_registrados' => 16,
    'formato' => 'Eliminación directa',
    'premio' => '$50,000'
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de admin cancha
    require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <?php
        // Incluir componente de detalle de torneo
        include __DIR__ . '/../torneoDetalle.php';
        ?>
    </main>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_TORNEO_DETALLE ?>"></script>
</body>

</html>

<!-- Contenido de las pestañas -->
<div class="tab-content" id="torneoTabsContent">
    <!-- SOLAPA 1: PARTIDOS -->
    <div class="tab-pane fade show active" id="partidos" role="tabpanel">
        <div class="col-12 bracket-container">
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
                                <span class="team-name">Los Cracks FC</span>
                                <span class="team-score">3</span>
                            </div>
                            <div class="match-team">
                                <span class="team-name">Deportivo Fútbol</span>
                                <span class="team-score">1</span>
                            </div>
                        </div>
                    </div>
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
                                <span class="team-name">Los Cracks FC</span>
                                <span class="team-score">3</span>
                            </div>
                            <div class="match-team">
                                <span class="team-name">Deportivo Fútbol</span>
                                <span class="team-score">1</span>
                            </div>
                        </div>
                    </div>
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
                                <span class="team-name">Los Cracks FC</span>
                                <span class="team-score">3</span>
                            </div>
                            <div class="match-team">
                                <span class="team-name">Deportivo Fútbol</span>
                                <span class="team-score">1</span>
                            </div>
                        </div>
                    </div>
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
                                <span class="team-name">Los Cracks FC</span>
                                <span class="team-score">3</span>
                            </div>
                            <div class="match-team">
                                <span class="team-name">Deportivo Fútbol</span>
                                <span class="team-score">1</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <span class="team-score">3</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Deportivo Fútbol</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                        </div>
                        <div class="branch-pair">
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
                        </div>
                    </div>
                    <div class="branch-block">
                        <div class="branch-pair">
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
                        </div>
                        <div class="branch-pair">
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
                        </div>
                    </div>
                </div>
            </div>

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
                                    <span class="team-score">3</span>
                                </div>
                                <div class="match-team">
                                    <span class="team-name">Deportivo Fútbol</span>
                                    <span class="team-score">1</span>
                                </div>
                            </div>
                        </div>
                        <div class="branch-pair">
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-3 bracket-branch final">
                <div class="branch-header">
                    <h5 class="branch-title">Final</h5>
                </div>
                <div class="branch-body">
                    <div class="branch-block">
                        <div class="branch-pair">
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
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- Cierre del bracket-container -->
    </div> <!-- Cierre del tab-pane partidos -->
    <!-- SOLAPA 2: EQUIPOS -->
    <div class="tab-pane fade" id="equipos" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center equipos-header">
            <h3>Equipos Participantes</h3>
            <span class="badge bg-primary">16 equipos registrados</span>
        </div>

        <!-- Lista de equipos en formato fila -->
        <div class="row g-3">
            <!-- Equipo 1 -->
            <div class="col-12">
                <div class="card border-0 mb-2 team-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span class="badge bg-success fs-6 team-position-badge">1°</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar first-place">
                                    <i class="bi bi-people text-success"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">Los Cracks FC</h5>
                                <small class="text-muted d-block"><i class="bi bi-people"></i> 11 integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-trophy"></i> 2 partidos ganados</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-success d-block"><i class="bi bi-plus-circle"></i> 5 goles a favor</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-danger d-block"><i class="bi bi-dash-circle"></i> 1 goles en contra</small>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="tooltip" title="Ver perfil del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipo 2 -->
            <div class="col-12">
                <div class="card border-0 mb-2 team-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span class="badge bg-success fs-6 team-position-badge">2°</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar second-place">
                                    <i class="bi bi-people text-info"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">Racing Club</h5>
                                <small class="text-muted d-block"><i class="bi bi-people"></i> 10 integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-trophy"></i> 1 partido ganado</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-success d-block"><i class="bi bi-plus-circle"></i> 2 goles a favor</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-danger d-block"><i class="bi bi-dash-circle"></i> 0 goles en contra</small>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="tooltip" title="Ver perfil del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipo 3 -->
            <div class="col-12">
                <div class="card border-0 mb-2 team-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span class="badge bg-warning fs-6 team-position-badge">3°</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar third-place">
                                    <i class="bi bi-people text-warning"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">River Plate</h5>
                                <small class="text-muted d-block"><i class="bi bi-people"></i> 9 integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-trophy"></i> 1 partido ganado</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-success d-block"><i class="bi bi-plus-circle"></i> 4 goles a favor</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-danger d-block"><i class="bi bi-dash-circle"></i> 2 goles en contra</small>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="tooltip" title="Ver perfil del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipo 4 -->
            <div class="col-12">
                <div class="card border-0 mb-2 team-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span class="badge bg-warning fs-6 team-position-badge">4°</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar default-place">
                                    <i class="bi bi-people text-secondary"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">San Lorenzo</h5>
                                <small class="text-muted d-block"><i class="bi bi-people"></i> 8 integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-trophy"></i> 1 partido ganado</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-success d-block"><i class="bi bi-plus-circle"></i> 1 goles a favor</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-danger d-block"><i class="bi bi-dash-circle"></i> 0 goles en contra</small>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-primary btn-sm"
                                    data-bs-toggle="tooltip" title="Ver perfil del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipos restantes (5-16) con estado de espera -->
            <div class="col-12">
                <div class="card border-0 mb-2 team-card eliminated">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <span class="badge bg-secondary fs-6 team-position-badge">-</span>
                            </div>
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar default-place">
                                    <i class="bi bi-people text-muted"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1 text-muted">Deportivo Fútbol</h5>
                                <small class="text-muted d-block"><i class="bi bi-people"></i> 7 integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-clock"></i> Sin partidos</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-dash"></i> - goles</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-dash"></i> - goles</small>
                            </div>
                            <div class="col-md-1 text-end">
                                <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-secondary btn-sm"
                                    data-bs-toggle="tooltip" title="Ver perfil del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Se pueden agregar más equipos siguiendo el mismo patrón -->
            <div class="col-12 text-center mt-3">
                <p class="text-muted">
                    <i class="bi bi-info-circle"></i> Mostrando los primeros 5 equipos.
                    <a href="#" class="text-decoration-none">Ver todos los equipos registrados</a>
                </p>
            </div>
        </div>
    </div>
</div>
</main>

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
                                    <div class="col-4 text-end">
                                        <h3 id="modal-equipo1" class="mb-1">Los Cracks FC</h3>
                                        <small class="text-muted">Local</small>
                                    </div>
                                    <div class="col-3 text-center">
                                        <h2 class="mb-1">
                                            <span id="modal-goles1" class="badge bg-success fs-4">3</span>
                                            <span class="text-muted mx-2">-</span>
                                            <span id="modal-goles2" class="badge bg-secondary fs-4">1</span>
                                        </h2>
                                    </div>
                                    <div class="col-4 text-start">
                                        <h3 id="modal-equipo2" class="mb-1">Deportivo Fútbol</h3>
                                        <small class="text-muted">Visitante</small>
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
                                    <td class="fw-bold">Fecha:</td>
                                    <td id="modal-fecha">28/10/2025</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Hora:</td>
                                    <td id="modal-hora">15:30</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Cancha:</td>
                                    <td id="modal-cancha">Cancha Principal A</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td><span id="modal-estado" class="badge bg-success">Finalizado</span></td>
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
                                    <td class="fw-bold">Nombre:</td>
                                    <td>Copa FutMatch 2025</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de Inicio:</td>
                                    <td>11/10/2025</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de Fin:</td>
                                    <td>15/12/2025 <span class="badge bg-warning text-dark">Estimativa</span></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Equipos Máximos:</td>
                                    <td>16</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td><span class="badge bg-primary">En curso</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Estado de inscripciones -->
                    <div class="col-12">
                        <h6 class="fw-bold">Estado de Inscripciones</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Las inscripciones se cerraron el <strong>25/10/2025</strong> con un total de <strong>12 equipos</strong> registrados.
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


</main>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="<?= CSS_ICONS ?>">

<!-- Scripts -->
<script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= JS_TORNEO_DETALLE ?>"></script>
</body>

</html>