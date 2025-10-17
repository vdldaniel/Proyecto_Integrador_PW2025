<!--Muy parecido a canchas-listado, pero filtrando po      <nav class="navbar navbar-expand-lg sticky-top border-bottom"> partidos abiertos disponibles
Página para explorar partidos disponibles para unirse
- Debe mostrar 2 pestañas:
  - (por defecto) Listado de partidos cercanos (tarjetas) con:
    - [x] Nombre de la cancha
    - [x] Ubicación (dirección)
    - [x] Botón de "Ver detalles" que redirija a partido-detalle.html
    - [x] Botón de "Solicitar Unirse" que redirija a partido-unirse.html
      + Si el usuario no está logueado, redirigir a login-jugador.html
  - Mapa con marcadores de los partidos cercanos:
    - Al hacer clic en un marcador, mostrar un tooltip con el nombre de la cancha 
      y un botón de "Ver detalles" que redirija a partido-detalle.html
  - Filtro de búsqueda por:
    - [x] Genero
    - [x] Ubicación (ciudad/barrio)
    - [x] Cantidad de jugadores que se unirían (para filtrar partidos según cupos)
    - Servicios (iluminación, vestuarios, etc.)
    - Botón de "Aplicar filtros" que actualice el listado y el mapa según los criterios seleccionados
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'dropdownExplorar';

// CSS adicional específico de esta página
$page_title = "Explorar partidos - FutMatch";
$page_css = [
  CSS_PAGES_CANCHAS_EXPLORAR
];


// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar de admin cancha
  require_once NAVBAR_JUGADOR_COMPONENT;
  ?>

  <main>
    <div class="bg-body-secondary">
      <div class="container-fluid py-5">
        <div class="busqueda-container">
          <!--Barra de busqueda-->
          <div class="busqueda-wrapper">
            <div class="busqueda-header">
              <div class="barra-de-busqueda">
                <input
                  type="text"
                  class="input-busqueda form-control"
                  placeholder="Buscar partidos..." />
                <i class="bi bi-search busqueda-icon"></i>
              </div>
            </div>
            <!--Filtros de búsqueda-->
            <div class="container-fluid filtro-de-busquedas">
              <form id="filtrosForm">
                <div class="row g-3">
                  <!--Ubicación-->
                  <div class="col-md-4">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input
                      type="text"
                      class="form-control"
                      id="ubicacion"
                      placeholder="Ingrese una ubicacion..." />
                  </div>
                  <!--Tipo de cancha-->
                  <div class="col-md-2">
                    <label for="jugadores" class="form-label">Tipo de cancha</label>
                    <select class="form-select" id="jugadores">
                      <option selected>Seleccione tipo de cancha...</option>
                      <option value="5">Futbol 5</option>
                      <option value="7">Futbol 7</option>
                      <option value="11">Futbol 11</option>
                    </select>
                  </div>
                  <!--Genero-->
                  <div class="col-md-2">
                    <label for="genero" class="form-label"> Genero </label>
                    <select class="form-select" id="genero">
                      <option selected>Seleccione el genero...</option>
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                      <option value="MIX">Mixto</option>
                    </select>
                  </div>
                  <!--Fecha-->
                  <!--AGREGAR MAS FILTROS DE BUSQUEDA-->
                </div>
                <!--Boton aplicar filtros-->
                <div class="mt-4 d-flex justify-content-end">
                  <button type="submit" class="btn btn-primary">
                    Aplicar filtros
                  </button>
                </div>
              </form>
            </div>

            <!--Listado de canchas GRID VIEW-->
            <div class="listado-grid border-top">
              <div
                class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-1">
                <div class="col">
                  <div class="card h-100 shadow-sm">
                    <img
                      src="https://www.megafutbol.com.ar/fotos/foto040.jpg"
                      class="card-img"
                      alt="Imagen de la cancha" />
                    <div class="card-body">
                      <h5 class="card-title">MegaFutbol Llavallol</h5>
                      <p class="d-flex gap-1">
                        <i class="bi bi-geo-alt"></i>
                        Antártida Argentina 2340, B1833CDN Llavallol,
                        Provincia de Buenos Aires
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-calendar-event"></i> Sabado 27/9
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-clock"></i>17:00 a 18:00 hs
                      </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                      <!--Botones-->
                      <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-primary btn-sm">
                          Ver detalles
                        </button>
                        <button type="button" class="btn btn-primary btn-sm btn-unirse">
                          Unirse
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card h-100 shadow-sm">
                    <img
                      src="https://www.megafutbol.com.ar/fotos/foto040.jpg"
                      class="card-img"
                      alt="Imagen de la cancha" />
                    <div class="card-body">
                      <h5 class="card-title">MegaFutbol Llavallol</h5>
                      <p class="d-flex gap-1">
                        <i class="bi bi-geo-alt"></i>
                        Antártida Argentina 2340, B1833CDN Llavallol,
                        Provincia de Buenos Aires
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-calendar-event"></i> Sabado 27/9
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-clock"></i>17:00 a 18:00 hs
                      </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                      <!--Botones-->
                      <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-primary btn-sm">
                          Ver detalles
                        </button>
                        <button type="button" class="btn btn-primary btn-sm btn-unirse">
                          Unirse
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col">
                  <div class="card h-100 shadow-sm">
                    <img
                      src="https://www.megafutbol.com.ar/fotos/foto040.jpg"
                      class="card-img"
                      alt="Imagen de la cancha" />
                    <div class="card-body">
                      <h5 class="card-title">MegaFutbol Llavallol</h5>
                      <p class="d-flex gap-1">
                        <i class="bi bi-geo-alt"></i>
                        Antártida Argentina 2340, B1833CDN Llavallol,
                        Provincia de Buenos Aires
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-calendar-event"></i> Sabado 27/9
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-clock"></i>17:00 a 18:00 hs
                      </p>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                      <!--Botones-->
                      <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-primary btn-sm">
                          Ver detalles
                        </button>
                        <button type="button" class="btn btn-primary btn-sm btn-unirse">
                          Unirse
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Modales -->


  <!-- Modal -->
  <div class="modal fade" id="modalUnirse" tabindex="-1" aria-labelledby="modalUnirse" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="modalUnirse">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Scripts -->
  <script src="public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="src/scripts/pages/partidos-listado.js"></script>
</body>

</html>