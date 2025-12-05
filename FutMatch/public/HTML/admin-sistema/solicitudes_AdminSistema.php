<?php

// Cargar configuración
require_once("../../../src/app/config.php");


// Definir la página actual para el navbar
$current_page = 'solicitudesAdminSistema';

$page_title = "Solicitudes - FutMatch";

$page_css = [CSS_PAGES_TABLAS_ADMIN_SISTEMA];

include HEAD_COMPONENT;

?>


<body>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>


    <main>
        <div class="container mt-4">

            <!-- Línea 1: Header con título -->
            <div class="row mb-4 align-items-center">
                <div class="col">
                    <h1 class="fw-bold mb-1">Gestión de Solicitudes</h1>
                    <p class="text-muted mb-0">Administrá las solicitudes de nuevos administradores de cancha</p>
                </div>
            </div>

            <!-- Línea 2: Búsqueda general -->
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
                            placeholder="Buscar solicitudes..." />
                    </div>
                </div>
            </div>

            <!-- Pestañas de navegación -->
            <ul class="nav nav-tabs" id="solicitudesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pendientes-tab" data-bs-toggle="tab" data-bs-target="#pendientes"
                        data-bs-toggle="tooltip" title="Solicitudes pendientes de asignación" type="button" role="tab">
                        <i class="bi bi-clock-history"></i> Pendientes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="en-verificacion-tab" data-bs-toggle="tab" data-bs-target="#en-verificacion"
                        data-bs-toggle="tooltip" title="Solicitudes en proceso de verificación" type="button" role="tab">
                        <i class="bi bi-hourglass-split"></i> En Verificación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="aceptadas-tab" data-bs-toggle="tab" data-bs-target="#aceptadas"
                        data-bs-toggle="tooltip" title="Solicitudes aceptadas" type="button" role="tab">
                        <i class="bi bi-check-circle"></i> Aceptadas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rechazadas-tab" data-bs-toggle="tab" data-bs-target="#rechazadas"
                        data-bs-toggle="tooltip" title="Solicitudes rechazadas" type="button" role="tab">
                        <i class="bi bi-x-circle"></i> Rechazadas
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content tabla-admin" id="solicitudesTabsContent">

                <!-- Tabla con estructura -->
                <div class="table-responsive mt-3">
                    <table class="table table-hover table-bordered tabla-admin-solicitudes">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 8%">
                                    ID
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="id">
                                </th>
                                <th style="width: 20%">
                                    Usuario Solicitante
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="usuario">
                                </th>
                                <th style="width: 15%">
                                    Cancha
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="cancha">
                                </th>
                                <th style="width: 18%">
                                    Dirección
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="direccion">
                                </th>
                                <th style="width: 12%">
                                    Verificador
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="verificador">
                                </th>
                                <th style="width: 10%">
                                    Fecha
                                    <input type="text" class="form-control form-control-sm filter-input mt-1"
                                        placeholder="Filtrar..." data-column="fecha">
                                </th>
                                <th style="width: 17%">Acciones</th>
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
        const GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA = '<?= GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA ?>';
        const UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA = '<?= UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA ?>';
    </script>
    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA ?>"></script>
</body>

</html>