<?php

// Cargar configuración
require_once("../../../src/app/config.php");
// Definir la página actual para el navbar
$current_page = 'canchasAdminSistema';

$page_title = "Sistema - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];

include HEAD_COMPONENT;

?>


<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>


    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título y botones de navegación -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Verificación de Canchas</h1>
                    <p class="text-muted mb-0">Gestioná solicitudes de canchas</p>
                </div>

                <div class="col-md-6 text-end" style="display: none;">
                    <a type="button" class="btn btn-dark me-2" href="<?= PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA ?>">
                        <i class="bi bi-flag-fill"></i> Ir a Canchas Reportadas
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
                            placeholder="Buscar canchas..." />
                    </div>
                </div>
            </div>

            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs" id="canchasTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes"
                        data-bs-toggle="tooltip" title="Canchas pendientes de verificación" type="button" role="tab">
                        <i class="bi bi-clock-history"></i> Pendientes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="en-revision-tab" data-bs-toggle="tab" data-bs-target="#en-revision"
                        data-bs-toggle="tooltip" title="Canchas en proceso de revisión" type="button" role="tab">
                        <i class="bi bi-hourglass-split"></i> En Revisión
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="habilitadas-tab" data-bs-toggle="tab" data-bs-target="#habilitadas"
                        data-bs-toggle="tooltip" title="Canchas habilitadas y activas" type="button" role="tab">
                        <i class="bi bi-check-circle"></i> Habilitadas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="deshabilitadas-tab" data-bs-toggle="tab" data-bs-target="#deshabilitadas"
                        data-bs-toggle="tooltip" title="Canchas deshabilitadas o rechazadas" type="button" role="tab">
                        <i class="bi bi-x-circle"></i> Deshabilitadas
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content tabla-admin" id="canchasTabsContent">

                <!-- Tabla con estructura mejorada -->
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-bordered tabla-admin-solicitudes">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 8%;">
                                    ID
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="id">
                                </th>
                                <th style="width: 20%;">
                                    Admin Cancha
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="admin">
                                </th>
                                <th style="width: 15%;">
                                    Cancha
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="cancha">
                                </th>
                                <th style="width: 18%;">
                                    Dirección
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="direccion">
                                </th>
                                <th style="width: 12%;">
                                    Verificador
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="verificador">
                                </th>
                                <th style="width: 10%;">
                                    Fecha
                                    <input type="text" class="form-control form-control-sm mt-1 filter-input"
                                        placeholder="Filtrar..." data-column="fecha">
                                </th>
                                <th style="width: 17%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-solicitudes">
                            <!-- Las filas se cargarán dinámicamente aquí -->
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Modal Confirmación -->
        <div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar acción</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalConfirmacionTexto">
                        <!-- Texto de confirmación dinámico -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnConfirmarAccion">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <script>
        const GET_CANCHAS_PENDIENTES_ADMIN_SISTEMA = '<?= GET_CANCHAS_PENDIENTES_ADMIN_SISTEMA ?>';
        const UPDATE_CANCHA_ADMIN_SISTEMA = '<?= UPDATE_CANCHA_ADMIN_SISTEMA ?>';
    </script>
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_CANCHAS_ADMIN_SISTEMA ?>"></script>
</body>

</html>