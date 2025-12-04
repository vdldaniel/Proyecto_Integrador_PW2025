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
        <!-- Torneo 1 - Inscripciones abiertas -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Copa FutMatch Verano 2025" data-ubicacion="Palermo" data-tipo="futbol-5" data-estado="inscripciones-abiertas">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg1.jpg" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Copa FutMatch Verano 2025</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Mega Fútbol Central, Palermo
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 5</span>
                <span class="badge text-bg-dark">12/20 equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Inicio: 15 de enero 2026
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-calendar-x"></i> Cierre inscripciones: 10 de enero
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$50,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Inscripción:</small>
                  <span class="fw-bold">$2,500 por equipo</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2 d-md-flex">
                  <button class="btn btn-dark btn-sm flex-fill" onclick="verDetalleTorneo(1)">
                    <i class="bi bi-eye"></i> Ver detalles
                  </button>
                  <button class="btn btn-success btn-sm flex-fill" onclick="inscribirseTorneo(1)">
                    <i class="bi bi-trophy"></i> Inscribirse
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Torneo 2 - Próximamente -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Liga Amateur Primavera" data-ubicacion="San Telmo" data-tipo="futbol-7" data-estado="proximamente">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg2.jpg" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Liga Amateur Primavera</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Deportivo San Lorenzo, San Telmo
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 7</span>
                <span class="badge text-bg-dark">0/16 equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Inicio: 1 de marzo 2026
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-calendar-plus"></i> Inscripciones abren: 15 de enero
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$80,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Inscripción:</small>
                  <span class="fw-bold">$3,200 por equipo</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <button class="btn btn-dark" disabled>
                    <i class="bi bi-clock"></i> Próximamente
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Torneo 3 - Inscripciones cerradas -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Torneo Express Navideño" data-ubicacion="Recoleta" data-tipo="futbol-sala" data-estado="inscripciones-cerradas">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg3.jpg" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Torneo Express Navideño</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Futsal Elite, Recoleta
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol Sala</span>
                <span class="badge text-bg-dark">8/8 equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Inicio: 20 de diciembre 2025
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-calendar-check"></i> Torneo express de 3 días
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$25,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Inscripción:</small>
                  <span class="fw-bold">$1,500 por equipo</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <button class="btn btn-dark" onclick="verDetalleTorneo(3)">
                    <i class="bi bi-eye"></i> Ver detalles
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Torneo 4 - En curso -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Copa Clausura 2025" data-ubicacion="Villa Crespo" data-tipo="futbol-11" data-estado="en-curso">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg4.jpg" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Copa Clausura 2025</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Complejo Deportivo Norte
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 11</span>
                <span class="badge text-bg-dark">En curso - Fecha 6/10</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Finaliza: 15 de diciembre 2025
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-people"></i> 16 equipos participando
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$120,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Próxima fecha:</small>
                  <span class="fw-bold">Domingo 10/11</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2">
                  <button class="btn btn-primary" onclick="verDetalleTorneo(4)">
                    <i class="bi bi-trophy"></i> Ver torneo
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Torneo 5 - Femenino -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Liga Femenina Metropolitana" data-ubicacion="Belgrano" data-tipo="futbol-5" data-estado="inscripciones-abiertas">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>bg5.jpg" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Liga Femenina Metropolitana</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Complejo Belgrano Sport
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 5</span>
                <span class="badge text-bg-dark">Femenino</span>
                <span class="badge text-bg-dark">6/12 equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Inicio: 5 de febrero 2026
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-calendar-x"></i> Cierre inscripciones: 25 de enero
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$40,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Inscripción:</small>
                  <span class="fw-bold">$2,000 por equipo</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2 d-md-flex">
                  <button class="btn btn-dark btn-sm flex-fill" onclick="verDetalleTorneo(5)">
                    <i class="bi bi-eye"></i> Ver detalles
                  </button>
                  <button class="btn btn-success btn-sm flex-fill" onclick="inscribirseTorneo(5)">
                    <i class="bi bi-trophy"></i> Inscribirse
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Torneo 6 - Juvenil -->
        <div class="col-12 col-md-6 col-lg-4 mb-4 torneo-item" data-nombre="Copa Juvenil Sub-21" data-ubicacion="La Boca" data-tipo="futbol-7" data-estado="inscripciones-abiertas">
          <div class="card h-100 shadow">
            <img src="<?= IMG_PATH ?>uniserPartido.png" class="card-img-top" alt="Torneo" style="height: 200px; object-fit: cover;">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title">Copa Juvenil Sub-21</h5>
              <p class="card-text text-muted mb-2">
                <i class="bi bi-geo-alt"></i> Cancha La Bombonera
              </p>
              <div class="mb-2">
                <span class="badge text-bg-dark me-1">Fútbol 7</span>
                <span class="badge text-bg-dark me-1">Sub-21</span>
                <span class="badge text-bg-dark">8/16 equipos</span>
              </div>
              <p class="card-text small text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Inicio: 20 de febrero 2026
              </p>
              <p class="card-text small text-muted mb-3">
                <i class="bi bi-calendar-x"></i> Cierre inscripciones: 10 de febrero
              </p>
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <small class="text-muted">Premio total:</small>
                  <span class="fw-bold text-success">$30,000</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">Inscripción:</small>
                  <span class="fw-bold">$1,800 por equipo</span>
                </div>
              </div>
              <div class="mt-auto">
                <div class="d-grid gap-2 d-md-flex">
                  <button class="btn btn-dark btn-sm flex-fill" onclick="verDetalleTorneo(6)">
                    <i class="bi bi-eye"></i> Ver detalles
                  </button>
                  <button class="btn btn-success btn-sm flex-fill" onclick="inscribirseTorneo(6)">
                    <i class="bi bi-trophy"></i> Inscribirse
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Estado vacío -->
        <div class="col-12 text-center py-5 d-none" id="estadoVacio">
          <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
          <h5 class="text-muted mt-3">No se encontraron torneos</h5>
          <p class="text-muted">Intenta ajustar los filtros de búsqueda.</p>
          <button type="button" class="btn btn-primary" id="limpiarBusqueda">
            <i class="bi bi-arrow-clockwise"></i> Limpiar búsqueda
          </button>
        </div>
      </div>
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

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />

  <!-- Scripts -->
  <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>

  <script src="<?= BASE_URL ?>src/scripts/pages/torneos-explorar.js"></script>
</body>

</html>