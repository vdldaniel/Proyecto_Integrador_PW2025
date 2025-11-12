<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchasExplorar';
$page_title = "Explorar Canchas - FutMatch";
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
        <h1 class="fw-bold mb-1">Explorar Canchas</h1>
        <p class="text-muted mb-0">Descubre y reserva canchas disponibles</p>
      </div>
      <div class="col-md-6 text-end">
        <div class="d-flex gap-2 justify-content-end">
          <button type="button" class="btn btn-outline-secondary" id="btnCambiarVista">
            <i class="bi bi-map" id="iconoVista"></i> <span id="textoVista">Mapa</span>
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFiltros">
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
        <button type="button" class="btn btn-sm btn-outline-secondary" id="limpiarFiltros">
          <i class="bi bi-x"></i> Limpiar todo
        </button>
      </div>
    </div>

    <!-- Vista de listado -->
    <div id="vistaListado">
      <!-- Lista de canchas -->
      <div class="row" id="listaCanchas">
        <!-- Cancha 1 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 cancha-item" data-nombre="Mega Fútbol Central" data-ubicacion="Palermo" data-tipo="futbol-5" data-superficie="sintetico">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg1.jpg" class="card-img-top" alt="Cancha" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Mega Fútbol Central</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Palermo, Buenos Aires
              </p>
              <div class="mb-2">
                <span class="badge bg-success me-1">Fútbol 5</span>
                <span class="badge bg-info me-1">Sintético</span>
                <span class="badge bg-warning text-dark">Disponible</span>
              </div>
              <p class="card-text small text-muted mb-3">
                Cancha de fútbol 5 con césped sintético de última generación. Vestuarios, duchas y estacionamiento incluido.
              </p>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <small class="text-muted">(4.2)</small>
                  </div>
                </div>
                <div class="text-end">
                  <span class="h6 mb-0">$2500/h</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <a class="btn btn-primary" href=<? PAGE_PERFIL_CANCHA_JUGADOR . '?id=1' ?>>
                    <i class="bi bi-eye"></i> Ver detalles
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cancha 2 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 cancha-item" data-nombre="Deportivo San Lorenzo" data-ubicacion="San Telmo" data-tipo="futbol-7" data-superficie="cesped-natural">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg2.jpg" class="card-img-top" alt="Cancha" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Deportivo San Lorenzo</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> San Telmo, Buenos Aires
              </p>
              <div class="mb-2">
                <span class="badge bg-success me-1">Fútbol 7</span>
                <span class="badge bg-success me-1">Césped natural</span>
                <span class="badge bg-danger">Ocupada</span>
              </div>
              <p class="card-text small text-muted mb-3">
                Cancha de fútbol 7 con césped natural. Ideal para partidos más competitivos con mayor espacio de juego.
              </p>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <small class="text-muted">(4.8)</small>
                  </div>
                </div>
                <div class="text-end">
                  <span class="h6 mb-0">$3200/h</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <button class="btn btn-outline-primary" onclick="verDetalleCancha(2)" disabled>
                    <i class="bi bi-clock"></i> No disponible
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cancha 3 -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 cancha-item" data-nombre="Futsal Elite" data-ubicacion="Recoleta" data-tipo="futbol-sala" data-superficie="parquet">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg3.jpg" class="card-img-top" alt="Cancha" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Futsal Elite</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Recoleta, Buenos Aires
              </p>
              <div class="mb-2">
                <span class="badge bg-success me-1">Fútbol Sala</span>
                <span class="badge bg-secondary me-1">Parquet</span>
                <span class="badge bg-warning text-dark">Disponible</span>
              </div>
              <p class="card-text small text-muted mb-3">
                Cancha de futsal profesional con piso de parquet. Climatizada con aire acondicionado y sonido profesional.
              </p>
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                  <div class="text-warning">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                    <small class="text-muted">(4.5)</small>
                  </div>
                </div>
                <div class="text-end">
                  <span class="h6 mb-0">$2800/h</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <button class="btn btn-primary" onclick="verDetalleCancha(3)">
                    <i class="bi bi-eye"></i> Ver detalles
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div class="col-12 text-center py-5 d-none" id="estadoVacio">
          <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
          <h5 class="text-muted mt-3">No se encontraron canchas</h5>
          <p class="text-muted">Intenta ajustar los filtros de búsqueda.</p>
          <button type="button" class="btn btn-primary" id="limpiarBusqueda">
            <i class="bi bi-arrow-clockwise"></i> Limpiar búsqueda
          </button>
        </div>
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

    <!-- Paginación -->
    <nav aria-label="Paginación de canchas" class="mt-4">
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
  </main>

  <?php require_once FILTRO_EXPLORAR_MODAL; ?>

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

  <script src="<?= JS_CANCHAS_EXPLORAR ?>"></script>
</body>

</html>