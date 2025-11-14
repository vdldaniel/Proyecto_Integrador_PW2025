<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchasExplorar';
$page_title = "Explorar Canchas - FutMatch";
$page_css = [CSS_PAGES_EXPLORAR];

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
        <h1 class="fw-bold mb-1">Explorar Canchas</h1>
        <p class="text-muted mb-0">Descubre y reserva canchas disponibles</p>
      </div>
      <div class="col-md-6 text-end">
        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-dark" id="btnCambiarVista">
            <i class="bi bi-map" id="iconoVista"></i> <span id="textoVista">Mapa</span>
          </button>
          <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalFiltros">
            <i class="bi bi-funnel"></i> Filtros
          </button>
          <div class="input-group" style="width: 300px;">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" id="busquedaCanchas" placeholder="Buscar canchas...">
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
      <!-- Lista de canchas -->
      <div class="row" id="listaCanchas">


      </div>

    </div>

    <!-- Vista de mapa -->
    <div id="vistaMapa" class="d-none">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-0">
              <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-12 text-center">
        <small class="text-muted">
          <i class="bi bi-info-circle me-1"></i>Obtené un mejor resultado utilizando filtros
        </small>
      </div>
    </div>

    <!-- Paginación -->
    <nav aria-label="Paginación de canchas">
      <ul class="pagination justify-content-center pagination-explorar">
        <!-- Se genera dinámicamente con JavaScript -->
      </ul>
    </nav>
  </main>

  <?php require_once FILTRO_EXPLORAR_MODAL; ?>

  <script>
    const GET_CANCHAS_DISPONIBLES_JUGADOR = '<?= GET_CANCHAS_DISPONIBLES_JUGADOR ?>';
    const IMG_CANCHA_DEFAULT = '<?= IMG_CANCHA_DEFAULT ?>';
    const PAGE_PERFIL_CANCHA_JUGADOR = '<?= PAGE_PERFIL_CANCHA_JUGADOR ?>';
  </script>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

  <script src="<?= JS_CANCHAS_EXPLORAR_JUGADOR ?>"></script>
</body>

</html>