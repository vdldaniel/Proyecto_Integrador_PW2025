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
          <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    <div id="torneosList" class="row g-3"> </div>

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
          <form id="formCrearTorneo" novalidate>
            <div class="row">
              <!-- Nombre del Torneo -->
              <div class="mb-3 col-12">
                <label for="nombreTorneo" class="form-label">Nombre del Torneo</label>
                <!-- FIX: Añadir name="nombre" -->
                <input type="text" class="form-control" id="nombreTorneo" name="nombre" placeholder="Ej: Copa FutMatch 2025" required />
              </div>
              <!-- Fechas -->
              <div class="mb-3 col-12 col-lg-6">
                <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                <!-- FIX: Añadir name="fechaInicio" -->
                <input type="date" class="form-control" id="fechaInicio" name="fechaInicio" required />
              </div>
              <div class="mb-3 col-12 col-lg-6">
                <label for="fechaFin" class="form-label">Fecha de Fin</label>
                <!-- FIX: Añadir name="fechaFin" -->
                <input type="date" class="form-control" id="fechaFin" name="fechaFin" />
              </div>

              <!-- Descripción (Agregado para la tabla torneos) -->
              <div class="mb-3 col-12">
                <label for="descripcionTorneo" class="form-label">Descripción (Opcional)</label>
                <!-- FIX: Añadir name="descripcion" -->
                <textarea class="form-control" id="descripcionTorneo" name="descripcion" rows="3" placeholder="Ej: Torneo con premios en efectivo."></textarea>
              </div>


              <!-- Abrir Inscripciones -->
              <hr class="my-4">
              <div class="alert alert-success border-0" role="alert">
                <div class="form-check form-switch">
                  <!-- FIX: Añadir name="abrirInscripciones" y value="true" -->
                  <input class="form-check-input fs-5" type="checkbox" id="abrirInscripciones" name="abrirInscripciones" value="true" />
                  <label class="form-check-label fw-bold" for="abrirInscripciones">
                    <i class="bi bi-unlock-fill"></i> Abrir inscripciones inmediatamente
                  </label>
                </div>
                <small class="text-muted">Si no se marca, el torneo se guardará como borrador.</small>
              </div>

              <!-- Fecha cierre inscripciones (aparece si se marca el checkbox) -->
              <div class="mb-3 d-none" id="fechaCierreContainer">
                <label for="fechaCierreInscripciones" class="form-label">Fecha de cierre de inscripciones</label>
                <!-- FIX: Añadir name="fechaCierreInscripciones" -->
                <input type="date" class="form-control" id="fechaCierreInscripciones" name="fechaCierreInscripciones" />
              </div>
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
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form id="formAbrirInscripciones">
            <input type="hidden" id="abrirTorneoId" name="torneo_id" />

            <div class="mb-3">
              <label for="fechaCierreInscripcionesAbrir" class="form-label">Fecha límite</label>
              <input type="date" class="form-control" id="fechaCierreInscripcionesAbrir" name="fecha_cierre" required />
              <small class="text-muted">
                La inscripción se abre ahora y se cerrará en la fecha seleccionada.
              </small>
            </div>
          </form>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button class="btn btn-success" id="btnConfirmarAbrirInscripciones">Abrir Inscripciones</button>
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
                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>" class="btn btn-sm btn-dark">
                      <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Deportivo Fútbol</td>
                  <td>7 jugadores</td>
                  <td>
                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>" class="btn btn-sm btn-dark">
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
  <div class="modal fade" id="modalCancelarTorneo" tabindex="-1" aria-labelledby="modalCancelarTorneoLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog" role="document">
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
            <i class="bi bi-x-circle"></i> Historial de Torneos Cancelados
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
                </tr>
              </thead>

              <tbody id="torneosCanceladosTableBody">
                <!-- Aquí se cargarán los torneos cancelados dinámicamente -->
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

                </tr>
              </thead>
              <tbody id="torneosFinalizadosTableBody">
                <!-- Se completa por JS -->
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
  <script>
    const BASE_URL = "<?= BASE_URL ?>";
  </script>
  <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= JS_MIS_TORNEOS ?>"></script>

  <!-- Contenedor y estructura del Toast (Bootstrap 5) -->
  <div aria-live="polite" aria-atomic="true" class="position-relative">
    <div id="toastContainer" class="toast-container position-fixed bottom-0 end-0 p-3">
      <!-- Toast dinámico, se llenará con JS -->
      <div id="appToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div id="toastBody" class="toast-body">
            <!-- Mensaje aquí -->
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>

</body>

</html>