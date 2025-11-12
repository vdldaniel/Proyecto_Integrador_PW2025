<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'canchasAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

                <div class="col-md-6 text-end">
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
                    <button class="nav-link active" id="solicitudes-tab" data-bs-toggle="tab" data-bs-target="#solicitudes"
                        data-bs-toggle="tooltip" title="Filtrar solicitudes de canchas pendientes" type="button" role="tab">
                        <i class="bi bi-building-fill"></i> Solicitudes
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="verificando-tab" data-bs-toggle="tab" data-bs-target="#verificando"
                        data-bs-toggle="tooltip" title="Filtrar solicitudes de canchas en proceso de verificación" type="button" role="tab">
                        <i class="bi bi-building-fill-exclamation"></i>En verificación
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="verificadas-tab" data-bs-toggle="tab" data-bs-target="#verificadas"
                        data-bs-toggle="tooltip" title="Filtrar solicitudes de canchas verificadas" type="button" role="tab">
                        <i class="bi bi-building-fill-check"></i>Verificadas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rechazadas-tab" data-bs-toggle="tab" data-bs-target="#rechazadas"
                        data-bs-toggle="tooltip" title="Filtrar solicitudes de canchas rechazadas" type="button" role="tab">
                        <i class="bi bi-building-fill-x"></i>Rechazadas
                    </button>
                </li>
                <li class="nav-item" role="administradores">
                    <button class="nav-link" id="administradores-tab" data-bs-toggle="tab" data-bs-target="#administradores"
                        data-bs-toggle="tooltip" title="Filtrar canchas vinculadas a un administrador de canchas" type="button" role="tab">
                        <i class="bi bi-building-gear"></i>Admin. Canchas
                    </button>
                </li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content tabla-admin" id="canchasTabsContent">

                <div class="table-header  header-card">
                    <div class=" card-body">
                        <div class="row">
                            <div class="col-md-1">ID</div>
                            <div class="col-md-2">Usuario</div>
                            <div class="col-md-2">Nombre Cancha</div>
                            <div class="col-md-2">Dirección</div>
                            <div class="col-md-2">Verificador</div>
                            <div class="col-md-1">Fecha</div>
                            <div class="col-md-2">Acciones</div>
                        </div>
                    </div>
                </div>

                <!-- SOLAPA 1: SOLICITUDES PENDIENTES -->
                <div class="tab-pane fade show active" id="solicitudes" role="tabpanel">

                    <!-- Solicitud 1 - Pendiente -->
                    <div class="card row-card-tabla-admin estado-pendiente" data-estado="solicitudes" data-solicitud-id="SOL-001">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>SOL-001</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark usuario-estado">Usuario Nuevo</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Cancha Los Pinos</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Av. Libertador 1234, CABA</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-muted">Sin asignar</span>
                                </div>
                                <div class="col-md-1">
                                    <small>10/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-solicitud-id="SOL-001" data-bs-toggle="tooltip" title="Ver solicitud">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-dark" data-direccion="Av. Libertador 1234, CABA" data-bs-toggle="tooltip" title="Ver en mapa">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                        <button class="btn btn-dark" data-admin-id="ADM-001" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                        <button class="btn btn-dark" data-solicitud-id="SOL-001" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Solicitud 2 - Pendiente -->
                    <div class="card row-card-tabla-admin estado-pendiente" data-estado="solicitudes" data-solicitud-id="SOL-002">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>SOL-002</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark usuario-estado">Usuario Existente</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Deportivo Central</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">San Martín 567, Belgrano</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-muted">Sin asignar</span>
                                </div>
                                <div class="col-md-1">
                                    <small>09/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-solicitud-id="SOL-002" data-bs-toggle="tooltip" title="Ver solicitud">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-dark" data-direccion="San Martín 567, Belgrano" data-bs-toggle="tooltip" title="Ver en mapa">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                        <button class="btn btn-dark" data-admin-id="ADM-002" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                        <button class="btn btn-dark" data-solicitud-id="SOL-002" data-bs-toggle="tooltip" title="Tomar caso">
                                            <i class="bi bi-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 2: EN VERIFICACIÓN -->
                <div class="tab-pane fade" id="verificando" role="tabpanel">

                    <!-- Solicitud 3 - En verificación -->
                    <div class="card row-card-tabla-admin estado-verificando" data-estado="verificando" data-solicitud-id="SOL-003">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>SOL-003</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark usuario-estado">Usuario Nuevo</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Futsal Arena</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Rivadavia 890, Caballito</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-info">Camila Santo</span>
                                </div>
                                <div class="col-md-1">
                                    <small>08/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-solicitud-id="SOL-003" data-bs-toggle="tooltip" title="Ver solicitud">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-dark" data-direccion="Rivadavia 890, Caballito" data-bs-toggle="tooltip" title="Ver en mapa">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                        <button class="btn btn-dark" data-admin-id="ADM-003" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 3: VERIFICADAS -->
                <div class="tab-pane fade" id="verificadas" role="tabpanel">

                    <!-- Solicitud 4 - Verificada -->
                    <div class="card row-card-tabla-admin estado-verificada" data-estado="verificadas" data-solicitud-id="SOL-004">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>SOL-004</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark usuario-estado">Usuario Existente</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Club Atlético Norte</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Corrientes 2345, Centro</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-success">Ana García</span>
                                </div>
                                <div class="col-md-1">
                                    <small>05/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-solicitud-id="SOL-004" data-bs-toggle="tooltip" title="Ver solicitud">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-dark" data-direccion="Corrientes 2345, Centro" data-bs-toggle="tooltip" title="Ver en mapa">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                        <button class="btn btn-dark" data-admin-id="ADM-004" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 4: RECHAZADAS -->
                <div class="tab-pane fade" id="rechazadas" role="tabpanel">

                    <!-- Solicitud 5 - Rechazada -->
                    <div class="card row-card-tabla-admin estado-rechazada" data-estado="rechazadas" data-solicitud-id="SOL-005">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>SOL-005</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark usuario-estado">Usuario Nuevo</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">Cancha Fantasma</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Dirección Inexistente 000</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-danger">Carlos Ruiz</span>
                                </div>
                                <div class="col-md-1">
                                    <small>03/11/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-solicitud-id="SOL-005" data-bs-toggle="tooltip" title="Ver solicitud">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-dark" data-direccion="Dirección Inexistente 000" data-bs-toggle="tooltip" title="Ver en mapa">
                                            <i class="bi bi-geo-alt"></i>
                                        </button>
                                        <button class="btn btn-dark" data-admin-id="ADM-005" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SOLAPA 5: ADMINISTRADORES -->
                <div class="tab-pane fade" id="administradores" role="tabpanel">

                    <!-- Lista de administradores con sus canchas -->
                    <div class="card row-card-tabla-admin" data-estado="administradores">
                        <div class="card-body">
                            <div class="row solicitud-row">
                                <div class="col-md-1">
                                    <strong>ADM-01</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge text-bg-dark">Admin Activo</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="fw-bold">3 Canchas Activas</div>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Juan Pérez - Admin</small>
                                </div>
                                <div class="col-md-2">
                                    <span class="text-success">Camila Santo</span>
                                </div>
                                <div class="col-md-1">
                                    <small>01/10/2025</small>
                                </div>
                                <div class="col-md-2">
                                    <div class="acciones-container">
                                        <button class="btn btn-dark" data-admin-id="ADM-01" data-bs-toggle="tooltip" title="Ver canchas del admin">
                                            <i class="bi bi-building"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <!-- Modal Detalles de Solicitud -->
        <div class="modal fade modal-solicitud" id="modalSolicitudDetalle" tabindex="-1" aria-labelledby="modalSolicitudDetalleLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSolicitudDetalleLabel">
                            <i class="bi bi-file-text"></i> Detalles de Solicitud
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Información de la solicitud -->
                        <div class="info-solicitud">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="label">ID de Solicitud:</div>
                                    <div class="value" id="modal-solicitud-id">SOL-001</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label">Fecha de Solicitud:</div>
                                    <div class="value" id="modal-fecha-solicitud">10/11/2025 14:30</div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del administrador -->
                        <div class="info-solicitud">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person"></i> Información del Administrador</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="label">Nombre y Apellido:</div>
                                    <div class="value" id="modal-nombre-admin">Juan Pérez</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label">Email de Contacto:</div>
                                    <div class="value" id="modal-email-admin">juan.perez@email.com</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label">Teléfono:</div>
                                    <div class="value" id="modal-telefono-admin">+54 11 1234-5678</div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la cancha -->
                        <div class="info-solicitud">
                            <h6 class="fw-bold mb-3"><i class="bi bi-building"></i> Información de la Cancha</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="label">Nombre de la Cancha:</div>
                                    <div class="value" id="modal-nombre-cancha">Cancha Los Pinos</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="label">Dirección:</div>
                                    <div class="value" id="modal-direccion-cancha">Av. Libertador 1234, CABA</div>
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="observaciones-section">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-chat-text"></i> Observaciones</h6>
                                <button class="btn btn-sm btn-primary btn-agregar-observacion">
                                    <i class="bi bi-plus"></i> Agregar observación
                                </button>
                            </div>

                            <!-- Área para nueva observación -->
                            <div class="mb-3">
                                <textarea class="form-control" id="nueva-observacion" rows="3" placeholder="Escribí una nueva observación..."></textarea>
                            </div>

                            <!-- Lista de observaciones existentes -->
                            <div id="observaciones-lista">
                                <div class="observacion-item">
                                    <div class="observacion-fecha">Sistema - 10/11/2025 15:45</div>
                                    <p class="observacion-texto">Solicitud recibida y en proceso de revisión inicial.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-warning">
                            <i class="bi bi-geo-alt"></i> Ver en Mapa
                        </button>
                        <button type="button" class="btn btn-success">
                            <i class="bi bi-arrow-right"></i> Tomar Caso
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_CANCHAS_ADMIN_SISTEMA ?>"></script>
</body>

</html>