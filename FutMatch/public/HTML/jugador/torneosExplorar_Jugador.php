<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'torneosExplorar';
$page_title = "Explorar Torneos - FutMatch";
$page_css = [];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar de jugador si está logueado
  if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'jugador') {
    $navbar_jugador_active = true;
    require_once NAVBAR_JUGADOR_COMPONENT;
  } else {
    $navbar_jugador_active = false;
    require_once NAVBAR_GUEST_COMPONENT;
  }

  ?>

  <!-- Contenido Principal -->
  <main class="container mt-4">
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Explorar Torneos</h1>
        <p class="text-muted mb-0">Descubre y únete a torneos disponibles</p>
      </div>
      <div class="col-md-6 text-end">
        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-dark d-none" id="btnCambiarVista">
            <i class="bi bi-map" id="iconoVista"></i> <span id="textoVista">Mapa</span>
          </button>
          <button type="button" class="btn btn-dark d-none" data-bs-toggle="modal" data-bs-target="#modalFiltros">
            <i class="bi bi-funnel"></i> Filtros
          </button>
          <div class="input-group" style="width: 300px;">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="busquedaTorneos" placeholder="Buscar torneos...">
          </div>
        </div>
      </div>
    </div>

    <!-- Filtros activos -->
    <div id="filtrosActivos" class="mb-3 d-none">
      <div class="d-flex gap-2 align-items-center flex-wrap">
        <span class="text-muted">Filtros activos:</span>
        <div id="badgesFiltros" class="d-flex gap-1 flex-wrap"></div>
        <button type="button" class="btn btn-sm btn-dark" id="limpiarFiltros">
          <i class="bi bi-x"></i> Limpiar todo
        </button>
      </div>
    </div>

    <!-- Vista de listado -->
    <div id="vistaListado">
      <!-- Lista de torneos -->
      <div class="row" id="listaTorneos">
        <!-- Los torneos se cargan dinámicamente con JavaScript -->
        <div class="col-12 text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
          <p class="text-muted mt-3">Cargando torneos disponibles...</p>
        </div>
      </div>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación de torneos" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item disabled">
          <a class="page-link" href="#" tabindex="-1">Anterior</a>
        </li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
          <a class="page-link" href="#">Siguiente</a>
        </li>
      </ul>
    </nav>
  </main>

  <!-- Modal Filtros -->
  <div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFiltrosLabel">
            <i class="bi bi-funnel"></i> Filtros de Búsqueda
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formFiltros">
            <!-- Estado del torneo -->
            <div class="mb-3">
              <label class="form-label fw-bold">Estado del torneo</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="inscripciones-abiertas" id="filtroInscripcionesAbiertas">
                <label class="form-check-label" for="filtroInscripcionesAbiertas">
                  Inscripciones abiertas
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="proximamente" id="filtroProximamente">
                <label class="form-check-label" for="filtroProximamente">
                  Próximamente
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="en-curso" id="filtroEnCurso">
                <label class="form-check-label" for="filtroEnCurso">
                  En curso
                </label>
              </div>
            </div>

            <!-- Tipo de fútbol -->
            <div class="mb-3">
              <label class="form-label fw-bold">Tipo de fútbol</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="futbol-5" id="filtroFutbol5">
                <label class="form-check-label" for="filtroFutbol5">
                  Fútbol 5
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="futbol-7" id="filtroFutbol7">
                <label class="form-check-label" for="filtroFutbol7">
                  Fútbol 7
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="futbol-11" id="filtroFutbol11">
                <label class="form-check-label" for="filtroFutbol11">
                  Fútbol 11
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="futbol-sala" id="filtroFutbolSala">
                <label class="form-check-label" for="filtroFutbolSala">
                  Fútbol Sala
                </label>
              </div>
            </div>

            <!-- Rango de premio -->
            <div class="mb-3">
              <label class="form-label fw-bold">Rango de premio</label>
              <div class="row">
                <div class="col-6">
                  <input type="number" class="form-control" placeholder="Mínimo" id="premioMinimo">
                </div>
                <div class="col-6">
                  <input type="number" class="form-control" placeholder="Máximo" id="premioMaximo">
                </div>
              </div>
            </div>

            <!-- Zona -->
            <div class="mb-3">
              <label for="filtroZona" class="form-label fw-bold">Zona</label>
              <select class="form-select" id="filtroZona">
                <option value="">Todas las zonas</option>
                <option value="palermo">Palermo</option>
                <option value="san-telmo">San Telmo</option>
                <option value="recoleta">Recoleta</option>
                <option value="villa-crespo">Villa Crespo</option>
                <option value="belgrano">Belgrano</option>
                <option value="la-boca">La Boca</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-dark" id="btnLimpiarFiltros">Limpiar filtros</button>
          <button type="button" class="btn btn-primary" id="btnAplicarFiltros">Aplicar filtros</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />

  <script>
    const GET_TORNEOS_EXPLORAR = "<?= GET_TORNEOS_EXPLORAR ?>";
    const POST_INSCRIPCION_TORNEO = "<?= POST_INSCRIPCION_TORNEO ?>";
    const GET_LISTA_CANCHAS = "<?= GET_LISTA_CANCHAS ?>";
    const IMG_PATH = "<?= IMG_PATH ?>";
    const PAGE_PERFIL_CANCHA_JUGADOR = "<?= PAGE_PERFIL_CANCHA_JUGADOR ?>";
    const CURRENT_USER_ID = <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>;
  </script>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_TORNEOS_EXPLORAR_JUGADOR ?>?v=<?= time() ?>"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

</body>

</html>