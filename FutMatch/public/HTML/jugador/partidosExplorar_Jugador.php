<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'partidosExplorar';
$page_title = "Explorar Partidos - FutMatch";
$page_css = [];

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
        <!-- Partido 1 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 partido-item" data-nombre="Partido amistoso en MegaFutbol" data-ubicacion="Llavallol" data-tipo="futbol-5" data-genero="masculino">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg4.jpg" class="card-img-top" alt="Partido" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Partido amistoso - Fútbol 5</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> MegaFutbol Llavallol
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 5</span>
                <span class="badge text-bg-dark me-1">Masculino</span>
                <span class="badge text-bg-dark">4/10 jugadores</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Hoy, 27 de octubre
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-clock"></i> 17:00 - 18:00 hs
              </p>
              <div class="mt-auto">
                <div class="d-grid">
                  <button class="btn btn-primary btn-sm ver-detalle-btn" data-partido-id="1">
                    <i class="bi bi-eye"></i> Solicitar unirse
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Partido 2 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 partido-item" data-nombre="Torneo relámpago" data-ubicacion="Palermo" data-tipo="futbol-7" data-genero="mixto">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg5.jpg" class="card-img-top" alt="Partido" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Torneo relámpago - Fútbol 7</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Deportivo San Lorenzo
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 7</span>
                <span class="badge text-bg-dark me-1">Mixto</span>
                <span class="badge text-bg-dark">Buscando equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Mañana, 28 de octubre
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-clock"></i> 19:00 - 21:00 hs
              </p>
              <div class="mt-auto">
                <div class="d-grid">
                  <button class="btn btn-primary btn-sm ver-detalle-btn" data-partido-id="2" data-tipo-partido="equipo">
                    <i class="bi bi-people"></i> Solicitar unirse como equipo
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Partido 3 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 partido-item" data-nombre="Fútbol femenino competitivo" data-ubicacion="Recoleta" data-tipo="futbol-sala" data-genero="femenino">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>uniserPartido.png" class="card-img-top" alt="Partido" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Fútbol femenino competitivo</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Futsal Elite
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol Sala</span>
                <span class="badge text-bg-dark">Femenino</span>
                <span class="badge text-bg-dark">6/10 jugadoras</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Viernes, 29 de octubre
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-clock"></i> 20:30 - 22:00 hs
              </p>
              <div class="mt-auto">
                <div class="d-grid">
                  <button class="btn btn-primary btn-sm ver-detalle-btn" data-partido-id="3">
                    <i class="bi bi-eye"></i> Solicitar unirse
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div class="col-12 text-center py-5 d-none" id="estadoVacio">
          <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
          <h5 class="text-muted mt-3">No se encontraron partidos</h5>
          <p class="text-muted">Intenta ajustar los filtros de búsqueda.</p>
          <button type="button" class="btn btn-primary" id="limpiarBusqueda">
            <i class="bi bi-arrow-clockwise"></i> Limpiar búsqueda
          </button>
        </div>
      </div>

      <!-- Paginación -->
      <nav aria-label="Paginación de partidos" class="mt-4">
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
    </div>

    <!-- Vista de Mapa -->
    <div id="vistaMapa" class="d-none">
      <div class="row">
        <div class="col-12">
          <div class="card shadow">
            <div class="card-body p-0">
              <div id="map" style="height: 600px; border-radius: 8px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </main>

  <?php require_once FILTRO_EXPLORAR_MODAL; ?>

  <!-- Modal Solicitar Unirse -->
  <div class="modal fade" id="modalSolicitarUnirse" tabindex="-1" aria-labelledby="modalSolicitarUnirseLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalSolicitarUnirseLabel">
            <i class="bi bi-person-plus me-2"></i>Solicitar unirse
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
            Tu número de contacto será compartido con el organizador para facilitar la comunicación.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnSolicitarUnirse">
            <i class="bi bi-send me-2"></i>Solicitar participación
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Unirse como Equipo -->
  <div class="modal fade" id="modalUnirseEquipo" tabindex="-1" aria-labelledby="modalUnirseEquipoLabel">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUnirseEquipoLabel">
            <i class="bi bi-people me-2"></i>Unirse como equipo
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="infoPartidoEquipo">
            <!-- Información del partido se cargará dinámicamente -->
          </div>

          <!-- Información del equipo anfitrión -->
          <div class="mt-4">
            <h6 class="fw-bold text-primary mb-3">
              <i class="bi bi-shield-check me-2"></i>Equipo anfitrión
            </h6>
            <div id="equipoAnfitrionInfo">
              <!-- Información del equipo anfitrión se cargará dinámicamente -->
            </div>
          </div>

          <div class="mt-4">
            <label for="selectorEquipo" class="form-label fw-bold">
              <i class="bi bi-people me-2"></i>Selecciona tu equipo:
            </label>
            <select class="form-select" id="selectorEquipo">
              <option value="">Elige un equipo...</option>
              <option value="1">Los Tigres FC</option>
              <option value="2">Águilas Doradas</option>
              <option value="3">Rayos Azules</option>
            </select>
            <div class="form-text">Solo puedes inscribir equipos donde seas el líder.</div>
          </div>

          <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle me-2"></i>
            Tu solicitud será enviada al organizador del partido. Te notificaremos cuando sea aceptada o rechazada.
            Tu número de contacto será compartido con el organizador para facilitar la comunicación.
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="btnSolicitarEquipo">
            <i class="bi bi-send me-2"></i>Solicitar participación
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">
  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="<?= JS_PARTIDOS_EXPLORAR ?>"></script>
</body>

</html>