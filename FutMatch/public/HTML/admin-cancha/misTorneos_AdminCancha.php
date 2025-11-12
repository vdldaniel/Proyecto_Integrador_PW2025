<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'misTorneos';
$page_title = "Torneos - FutMatch";
$page_css = [];


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
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Mis Torneos</h1>
        <p class="text-muted mb-0">Gestiona tus torneos y crea nuevos</p>
      </div>
      <div class="col-md-6 text-end">
        <div class="dropdown d-inline-block me-2">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-clock-history"></i> Historial de Torneos
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalTorneosCancelados">Torneos Cancelados</a></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalTorneosFinalizados">Torneos Finalizados</a></li>
          </ul>
        </div>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearTorneo">
          <i class="bi bi-plus-circle"></i> Crear Torneo
        </button>
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
      <!-- Torneo 1 -->
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
              <div class="col-md-3">
                <h5 class="card-title mb-1">
                  <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="text-decoration-none">
                    Torneo Apertura
                  </a>
                </h5>
                <small class="text-muted">10/10/2025 • 8/16 equipos</small>
              </div>
              <div class="col-md-2">
                <span class="badge bg-secondary">Borrador</span>
              </div>
              <div class="col-md-5 text-end">
                <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalAbrirInscripciones" data-torneo-id="1" title="Abrir inscripciones">
                  <i class="bi bi-unlock"></i>
                  <span class="d-none d-lg-inline ms-1">Abrir inscripciones</span>
                </button>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCancelarTorneo" data-torneo-id="1" title="Cancelar">
                  <i class="bi bi-x-circle"></i>
                  <span class="d-none d-lg-inline ms-1">Cancelar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Torneo 2 -->
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
              <div class="col-md-3">
                <h5 class="card-title mb-1">
                  <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="text-decoration-none">
                    Copa FutMatch
                  </a>
                </h5>
                <small class="text-muted">11/10/2025 • 12/20 equipos</small>
              </div>
              <div class="col-md-2">
                <span class="badge bg-success">Inscripciones abiertas</span>
              </div>
              <div class="col-md-5 text-end">
                <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalSolicitudesTorneo" data-torneo-id="2" title="Ver solicitudes">
                  <i class="bi bi-people"></i>
                  <span class="d-none d-lg-inline ms-1">Solicitudes</span>
                </button>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCancelarTorneo" data-torneo-id="2" title="Cancelar">
                  <i class="bi bi-x-circle"></i>
                  <span class="d-none d-lg-inline ms-1">Cancelar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Torneo 3 -->
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
              <div class="col-md-3">
                <h5 class="card-title mb-1">
                  <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="text-decoration-none">
                    Torneo Interclubes
                  </a>
                </h5>
                <small class="text-muted">10/02/2026 • 16/16 equipos</small>
              </div>
              <div class="col-md-2">
                <span class="badge bg-warning text-dark">Inscripciones cerradas</span>
              </div>
              <div class="col-md-5 text-end">
                <a class="btn btn-outline-info btn-sm me-1" href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" title="Gestionar torneo">
                  <i class="bi bi-gear"></i>
                  <span class="d-none d-lg-inline ms-1">Gestionar</span>
                </a>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCancelarTorneo" data-torneo-id="3" title="Cancelar">
                  <i class="bi bi-x-circle"></i>
                  <span class="d-none d-lg-inline ms-1">Cancelar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Torneo 4 -->
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
              <div class="col-md-3">
                <h5 class="card-title mb-1">
                  <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="text-decoration-none">
                    Liga Primavera
                  </a>
                </h5>
                <small class="text-muted">15/11/2025 • 6/12 equipos</small>
              </div>
              <div class="col-md-2">
                <span class="badge bg-primary">En curso</span>
              </div>
              <div class="col-md-5 text-end">
                <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="btn btn-outline-info btn-sm me-1" title="Gestionar torneo">
                  <i class="bi bi-gear"></i>
                  <span class="d-none d-lg-inline ms-1">Gestionar</span>
                </a>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalCancelarTorneo" data-torneo-id="4" title="Cancelar">
                  <i class="bi bi-x-circle"></i>
                  <span class="d-none d-lg-inline ms-1">Cancelar</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Crear Torneo -->
  <div class="modal fade" id="modalCrearTorneo" tabindex="-1" aria-labelledby="modalCrearTorneoLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCrearTorneoLabel">
            <i class="bi bi-trophy"></i> Crear Torneo
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formCrearTorneo">
            <div class="row">
              <!-- Nombre del Torneo -->
              <div class="mb-3 col-12">
                <label for="nombreTorneo" class="form-label">Nombre del Torneo</label>
                <input type="text" class="form-control" id="nombreTorneo" placeholder="Ej: Copa FutMatch 2025" required />
              </div>
              <!-- Fechas -->
              <div class="mb-3 col-12 col-lg-6">
                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fechaInicio" required />
              </div>
              <div class="mb-3 col-12 col-lg-6">
                <label for="fechaFin" class="form-label">Fecha de Fin</label>
                <input type="date" class="form-control" id="fechaFin" />
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" id="fechaEstimativa" />
                  <label class="form-check-label" for="fechaEstimativa">
                    Fecha estimativa
                  </label>
                </div>
              </div>
              <!-- Cantidad de Equipos -->
              <div class="mb-3 col-12 col-lg-6">
                <label for="cantidadEquipos" class="form-label">Cantidad máxima de equipos (opcional)</label>
                <input type="number" class="form-control" id="cantidadEquipos" min="2" max="64" placeholder="Ej: 16" />
              </div>
            </div>

            <!-- Abrir Inscripciones -->
            <hr class="my-4">
            <div class="alert alert-success border-0" role="alert">
              <div class="form-check form-switch">
                <input class="form-check-input fs-5" type="checkbox" id="abrirInscripciones" />
                <label class="form-check-label fw-bold" for="abrirInscripciones">
                  <i class="bi bi-unlock-fill"></i> Abrir inscripciones inmediatamente
                </label>
              </div>
              <small class="text-muted">Si no se marca, el torneo se guardará como borrador.</small>
            </div>

            <!-- Fecha cierre inscripciones (aparece si se marca el checkbox) -->
            <div class="mb-3 d-none" id="fechaCierreContainer">
              <label for="fechaCierreInscripciones" class="form-label">Fecha de cierre de inscripciones</label>
              <input type="date" class="form-control" id="fechaCierreInscripciones" />
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnCrearTorneo">Crear Torneo</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Abrir Inscripciones -->
  <div class="modal fade" id="modalAbrirInscripciones" tabindex="-1" aria-labelledby="modalAbrirInscripcionesLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalAbrirInscripcionesLabel">
            <i class="bi bi-unlock"></i> Abrir Inscripciones
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formAbrirInscripciones">
            <input type="hidden" id="abrirTorneoId" />
            <div class="mb-3">
              <label for="fechaCierreInscripcionesAbrir" class="form-label">Fecha de cierre de inscripciones</label>
              <input type="date" class="form-control" id="fechaCierreInscripcionesAbrir" required />
              <small class="text-muted">Las inscripciones se abrirán inmediatamente y se cerrarán en la fecha seleccionada.</small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnConfirmarAbrirInscripciones">Abrir Inscripciones</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Solicitudes de Torneo -->
  <div class="modal fade" id="modalSolicitudesTorneo" tabindex="-1" aria-labelledby="modalSolicitudesTorneoLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalSolicitudesTorneoLabel">
            <i class="bi bi-people"></i> Solicitudes de Participación
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="solicitudesTorneoId" />
          <div class="table-responsive">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Nombre Equipo</th>
                  <th>Cantidad de Integrantes</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Los Cracks FC</td>
                  <td>9 jugadores</td>
                  <td>
                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Deportivo Fútbol</td>
                  <td>7 jugadores</td>
                  <td>
                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Racing Club</td>
                  <td>9 jugadores</td>
                  <td>
                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>" class="btn btn-sm btn-info">
                      <i class="bi bi-eye"></i><span class="d-none d-md-inline ms-1">Ver perfil</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Cancelar Torneo -->
  <div class="modal fade" id="modalCancelarTorneo" tabindex="-1" aria-labelledby="modalCancelarTorneoLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalCancelarTorneoLabel">
            <i class="bi bi-exclamation-triangle"></i> ¿Estás seguro?
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="cancelarTorneoId" />
          <h6>Al cancelar este torneo:</h6>
          <ul>
            <li>No aparecerá más en listados para jugadores</li>
            <li>Se notificará a todos los equipos participantes</li>
            <li>El torneo pasará al historial como "Cancelado"</li>
            <li>No se podrán realizar más inscripciones</li>
          </ul>
          <div class="alert alert-warning">
            <strong>Esta acción no se puede deshacer.</strong>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, mantener torneo</button>
          <button type="button" class="btn btn-danger" id="btnConfirmarCancelar">Sí, cancelar torneo</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Torneos Cancelados -->
  <div class="modal fade" id="modalTorneosCancelados" tabindex="-1" aria-labelledby="modalTorneosCanceladosLabel">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTorneosCanceladosLabel">
            <i class="bi bi-x-circle"></i> Torneos Cancelados
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Nombre</th>
                  <th>Fecha Inicio</th>
                  <th>Fecha Fin</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Torneo Federal</td>
                  <td>04/11/2025</td>
                  <td>30/11/2025</td>
                  <td>
                    <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Copa Invierno 2024</td>
                  <td>15/06/2024</td>
                  <td>30/07/2024</td>
                  <td>
                    <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Torneos Finalizados -->
  <div class="modal fade" id="modalTorneosFinalizados" tabindex="-1" aria-labelledby="modalTorneosFinalizadosLabel">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTorneosFinalizadosLabel">
            <i class="bi bi-trophy"></i> Torneos Finalizados
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Nombre</th>
                  <th>Fecha Inicio</th>
                  <th>Fecha Fin</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Copa Verano 2024</td>
                  <td>10/01/2024</td>
                  <td>28/02/2024</td>
                  <td>
                    <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Torneo Clausura 2023</td>
                  <td>15/09/2023</td>
                  <td>15/12/2023</td>
                  <td>
                    <a href="<?= PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-info">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
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
  <script src="<?= JS_MIS_TORNEOS ?>"></script>
</body>

</html>