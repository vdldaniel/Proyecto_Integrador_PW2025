<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once AUTH_REQUIRED_COMPONENT;

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
                <button type="button" class="btn btn-dark me-2" data-bs-toggle="modal" data-bs-target="#modalHistorialTorneos">
                    <i class="bi bi-clock-history"></i> Historial de Torneos
                </button>
                <a href="<?= PAGE_TORNEOS_EXPLORAR_JUGADOR ?>" class="btn btn-primary">
                    <i class="bi bi-search"></i> Explorar Torneos
                </a>
            </div>
        </div>

        <!-- Línea 2: Filtros y búsqueda -->
        <div class="row mb-4 d-none">
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
            <!-- Los torneos se cargan dinámicamente con JavaScript -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-3">Cargando tus torneos...</p>
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
                                        <!-- Los torneos finalizados se cargan dinámicamente con JavaScript -->
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Cargando...</td>
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
                                        <!-- Los torneos cancelados se cargan dinámicamente con JavaScript -->
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Cargando...</td>
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

    <script>
        const BASE_URL = "<?= BASE_URL ?>";
        const GET_MIS_TORNEOS_JUGADOR = "<?= GET_MIS_TORNEOS_JUGADOR ?>";
        const GET_LISTA_CANCHAS = "<?= GET_LISTA_CANCHAS ?>";
        const IMG_PATH = "<?= IMG_PATH ?>";
    </script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_TORNEOS_JUGADOR ?>"></script>

</body>

</html>