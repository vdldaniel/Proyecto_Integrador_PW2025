<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'partidosExplorar';
$page_title = "Explorar Partidos - FutMatch";
$page_css = [CSS_PAGES_EXPLORAR];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
        <h1 class="fw-bold mb-1">Explorar Partidos</h1>
        <p class="text-muted mb-0">Encuentra y únete a partidos disponibles</p>
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
            <input type="text" class="form-control" id="busquedaPartidos" placeholder="Buscar partidos...">
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

    <!-- Vista de Listado -->
    <div id="vistaListado">
      <!-- Lista de partidos -->
      <div class="row" id="listaPartidos">

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
    <nav aria-label="Paginación de partidos">
      <ul class="pagination justify-content-center pagination-explorar">
        <!-- Se genera dinámicamente con JavaScript -->
      </ul>
    </nav>
  </main>

  <?php require_once FILTRO_EXPLORAR_MODAL; ?>

  <!-- Modal Solicitar Unirse a Partido -->
  <div class="modal fade" id="modalSolicitarUnirse" tabindex="-1" aria-labelledby="modalSolicitarUnirseLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalSolicitarUnirseLabel">
            <i class="bi bi-person-plus me-2"></i>Solicitar unirse al partido
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="infoPartidoSolicitar">
            <!-- Información del partido se cargará dinámicamente -->
          </div>

          <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle me-2"></i>
            Tu solicitud será enviada al organizador del partido. Te notificaremos cuando sea aceptada o rechazada.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnConfirmarSolicitud">
            <i class="bi bi-send me-2"></i>Enviar solicitud
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const GET_PARTIDOS_DISPONIBLES_JUGADOR = '<?= GET_PARTIDOS_DISPONIBLES_JUGADOR ?>';
    const IMG_PARTIDO_DEFAULT = '<?= IMG_PARTIDO_DEFAULT ?>';
    const POST_SOLICITANTE_PARTIDO_JUGADOR = '<?= POST_SOLICITANTE_PARTIDO_JUGADOR ?>';
    // id_jugador es igual a id_usuario en la base de datos (FOREIGN KEY)
    const CURRENT_USER_ID = <?= isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 'null' ?>;
  </script>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">
  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="<?= JS_PARTIDOS_EXPLORAR_JUGADOR ?>"></script>
</body>

</html>