<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'torneos';
$page_title = "Mis Torneos - FutMatch";
$page_css = [];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
    <?php
    // Cargar navbar de jugador
    require_once NAVBAR_JUGADOR_COMPONENT;
    ?>

    <!-- Contenido Principal -->
    <main class="container mt-4">
        <!-- Línea 1: Header con título y botones de navegación -->
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold mb-1">Mis Torneos</h1>
                <p class="text-muted mb-0">Ver torneos activos y explorar nuevos</p>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#modalHistorialTorneos">
                    <i class="bi bi-clock-history"></i> Historial de Torneos
                </button>
                <a href="<?= PAGE_TORNEOS_EXPLORAR ?>" class="btn btn-primary">
                    <i class="bi bi-search"></i> Explorar Torneos
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
                        placeholder="Buscar torneos por nombre, fecha o estado..." />
                </div>
            </div>
        </div>

        <!-- Lista de torneos -->
        <div id="torneosList" class="row g-3">
            <!-- Torneo 1 - Participando -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-trophy text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    Copa FutMatch 2025
                                </h5>
                                <small class="text-muted">11/10/2025 - 15/12/2025 • 16 equipos</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-success fs-6">Participando</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Torneo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Torneo 2 - En curso -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-trophy text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    Liga Primavera Amateur
                                </h5>
                                <small class="text-muted">01/09/2025 - 30/11/2025 • 12 equipos</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-primary fs-6">En curso</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Torneo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Torneo 3 - Inscripciones abiertas -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-trophy text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    Torneo Interclubes Verano
                                </h5>
                                <small class="text-muted">15/01/2026 - 28/02/2026 • 8/20 equipos</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-warning text-dark fs-6">Inscripciones abiertas</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Torneo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Torneo 4 - Próximo a comenzar -->
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-trophy text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    Copa Navideña Express
                                </h5>
                                <small class="text-muted">20/12/2025 - 22/12/2025 • 8 equipos</small>
                            </div>
                            <div class="col-md-3">
                                <span class="badge bg-info fs-6">Próximamente</span>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i> Ver Torneo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal Historial de Torneos -->
    <div class="modal fade" id="modalHistorialTorneos" tabindex="-1" aria-labelledby="modalHistorialTorneosLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHistorialTorneosLabel">
                        <i class="bi bi-clock-history"></i> Historial de Torneos
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Tabs para separar finalizados y cancelados -->
                    <ul class="nav nav-tabs" id="historialTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="finalizados-tab" data-bs-toggle="tab" data-bs-target="#finalizados" type="button" role="tab">
                                <i class="bi bi-trophy"></i> Torneos Finalizados
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cancelados-tab" data-bs-toggle="tab" data-bs-target="#cancelados" type="button" role="tab">
                                <i class="bi bi-x-circle"></i> Torneos Cancelados
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="historialTabContent">
                        <!-- Tab Torneos Finalizados -->
                        <div class="tab-pane fade show active" id="finalizados" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha</th>
                                            <th>Resultado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Copa Verano 2024</td>
                                            <td>10/01/2024 - 28/02/2024</td>
                                            <td><span class="badge bg-success">Campeón</span></td>
                                            <td>
                                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Torneo Clausura 2023</td>
                                            <td>15/09/2023 - 15/12/2023</td>
                                            <td><span class="badge bg-secondary">3° Puesto</span></td>
                                            <td>
                                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Tab Torneos Cancelados -->
                        <div class="tab-pane fade" id="cancelados" role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha Programada</th>
                                            <th>Motivo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Torneo Federal</td>
                                            <td>04/11/2025 - 30/11/2025</td>
                                            <td><span class="text-muted">Cancelado por organizador</span></td>
                                            <td>
                                                <a href="<?= PAGE_TORNEO_DETALLE_JUGADOR ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_TORNEOS_JUGADOR ?>"></script>
</body>

</html>