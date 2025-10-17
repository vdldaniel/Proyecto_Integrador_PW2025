<!--
Página para explorar canchas disponibles para reservar
- Debe mostrar 2 pestañas:
  - (por defecto) Listado de canchas cercanas (tarjetas) con:
    - [x] Nombre de la cancha
    - [x] Ubicación (dirección)
    - [x] Botón de "Ver detalles" que redirija a cancha-detalle.html
    - [x] Botón de "Reservar" que redirija a cancha-reservar.html
      + Si el usuario no está logueado, redirigir a login-jugador.html
  - Mapa con marcadores de las canchas cercanas:
    - Al hacer clic en un marcador, mostrar un tooltip con el nombre de la cancha 
      y un botón de "Ver detalles" que redirija a cancha-detalle.html
  - Filtro de búsqueda por:
    - [x] Ubicación (ciudad/barrio)
    - [x] Cantidad de jugadores que participarían (para filtrar canchas según tamaño)
    - [] Servicios (iluminación, vestuarios, etc.)
    - [x] Botón de "Aplicar filtros" que actualice el listado y el mapa según los criterios seleccionados
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'dropdownExplorar'; 

// CSS adicional específico de esta página
$page_title = "Explorar canchas - FutMatch";
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
                    placeholder="Buscar canchas..."
                  />
                  <i class="bi bi-search busqueda-icon"></i>
                </div>
              </div>
              <!--Filtros de búsqueda-->
              <div class="container-fluid filtro-de-busquedas">
                <form id="filtrosForm">
                  <div class="row g-3">
                    <!--Ubicación-->
                    <div class="col-md-4">
                      <label for="ubicacion" class="form-label"
                        >Ubicación</label
                      >
                      <input
                        type="text"
                        class="form-control"
                        id="ubicacion"
                        placeholder="Ingrese una ubicacion..."
                      />
                    </div>
                    <!--Tipo de cancha-->
                    <div class="col-md-4">
                      <label for="jugadores" class="form-label"
                        >Tipo de cancha</label
                      >
                      <select class="form-select" id="jugadores">
                        <option selected>Seleccione tipo de cancha...</option>
                        <option value="5">Futbol 5</option>
                        <option value="7">Futbol 7</option>
                        <option value="11">Futbol 11</option>
                      </select>
                    </div>
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
                  class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-1"
                >
                  <div class="col">
                    <div class="card h-100 shadow-sm">
                      <img
                        src="https://www.megafutbol.com.ar/fotos/foto040.jpg"
                        class="card-img"
                        alt="Imagen de la cancha"
                      />
                      <div class="card-body">
                        <h5 class="card-title">MegaFutbol Llavallol</h5>
                        <p class="d-flex gap-1">
                          <i class="bi bi-geo-alt"></i>
                          Antártida Argentina 2340, B1833CDN Llavallol,
                          Provincia de Buenos Aires
                        </p>
                      </div>
                      <div class="card-footer bg-transparent border-top-0">
                        <!--Botones-->
                        <div class="d-flex gap-2 align-items-center">
                          <button type="button" class="btn btn-primary btn-sm">
                            Ver detalles
                          </button>
                          <button type="button" class="btn btn-primary btn-sm btn-crear-reserva">
                            Reservar
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
                        alt="Imagen de la cancha"
                      />
                      <div class="card-body">
                        <h5 class="card-title">MegaFutbol Llavallol</h5>
                        <p class="card-text">
                          Antártida Argentina 2340, B1833CDN Llavallol,
                          Provincia de Buenos Aires
                        </p>
                      </div>
                      <div class="card-footer bg-transparent border-top-0">
                        <!--Botones-->
                        <div class="d-flex gap-2 align-items-center">
                          <button type="button" class="btn btn-primary btn-sm">
                            Ver detalles
                          </button>
                          <button type="button" class="btn btn-primary btn-sm btn-crear-reserva">
                            Reservar
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
                        alt="Imagen de la cancha"
                      />
                      <div class="card-body">
                        <h5 class="card-title">MegaFutbol Llavallol</h5>
                        <p class="card-text">
                          Antártida Argentina 2340, B1833CDN Llavallol,
                          Provincia de Buenos Aires
                        </p>
                      </div>
                      <div class="card-footer bg-transparent border-top-0">
                        <!--Botones-->
                        <div class="d-flex gap-2 align-items-center">
                          <button type="button" class="btn btn-primary btn-sm">
                            Ver detalles
                          </button>
                          <button type="button" class="btn btn-primary btn-sm btn-crear-reserva">
                            Reservar
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
                        alt="Imagen de la cancha"
                      />
                      <div class="card-body">
                        <h5 class="card-title">MegaFutbol Llavallol</h5>
                        <p class="card-text">
                          Antártida Argentina 2340, B1833CDN Llavallol,
                          Provincia de Buenos Aires
                        </p>
                      </div>
                      <div class="card-footer bg-transparent border-top-0">
                        <!--Botones-->
                        <div class="d-flex gap-2 align-items-center">
                          <button type="button" class="btn btn-primary btn-sm btn-crear-reserva">
                            Ver detalles
                          </button>
                          <button type="button" class="btn btn-primary btn-sm">
                            Reservar
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
    <!-------------------------------MODALES----------------------------------------------------->
    <div class="modal fade" id="modalGestionReserva" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal">Crear Reserva</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- ID oculto para edición -->
          <input type="hidden" id="idReserva">
          
          <form id="formularioGestionReserva">
            <!-- Estado de la reserva (solo visible en modo edición) -->
            <div id="seccionEstado" class="row mb-3 d-none">
              <div class="col-md-6">
                <label for="estadoReserva" class="form-label">Estado</label>
                <select class="form-select" id="estadoReserva">
                  <option value="pending">Pendiente</option>
                  <option value="confirmed">Confirmada</option>
                  <option value="cancelled">Cancelada</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">Fecha de creación</label>
                <input type="text" class="form-control" id="fechaCreacion" readonly>
              </div>
            </div>
            
            <!-- Datos del jugador -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="idJugador" class="form-label">ID # Jugador</label>
                <input type="number" class="form-control" id="idJugador" min="1" required>
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="reservaExterna">
                  <label class="form-check-label" for="reservaExterna">
                    Reserva externa a la App
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Datos adicionales (deshabilitados hasta marcar reserva externa) -->
            <div id="datosExternos">
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="nombreExterno" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="nombreExterno" maxlength="100" disabled>
                </div>
                <div class="col-md-6">
                  <label for="telefonoExterno" class="form-label">Teléfono</label>
                  <input type="tel" class="form-control" id="telefonoExterno" disabled>
                </div>
              </div>
            </div>
            
            <hr class="my-4">
            
            <!-- Detalles de la reserva -->
            <div class="row mb-3">
              <div class="col-md-4">
                <label for="canchaReserva" class="form-label">Cancha</label>
                <select class="form-select" id="canchaReserva" required>
                  <option value="">Seleccione una cancha...</option>
                  <option value="1">Cancha A - Fútbol 11</option>
                  <option value="2">Cancha B - Fútbol 7</option>
                  <option value="3">Cancha C - Fútbol 5</option>
                </select>
              </div>
              <div class="col-md-4">
                <label for="fechaReserva" class="form-label">Fecha</label>
                <input type="date" class="form-control" id="fechaReserva" required>
              </div>
              <div class="col-md-4">
                <label for="horaReserva" class="form-label">Hora</label>
                <input type="time" class="form-control" id="horaReserva" 
                       min="08:00" max="22:00" step="3600" required>
                <div class="form-text">Horario disponible: 8:00 AM - 10:00 PM</div>
              </div>
            </div>
            
            <hr class="my-4">
            
            <!-- Comentario -->
            <div class="mb-3">
              <label for="comentarioReserva" class="form-label">Comentario</label>
              <textarea class="form-control" id="comentarioReserva" rows="3" maxlength="500" placeholder="Información adicional sobre la reserva..."></textarea>
              <div class="form-text">Máximo 500 caracteres</div>
            </div>
          </form>
        </div>
        <div class="modal-footer" id="piePaginaModal">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success" id="botonGuardarReserva">
            <i class="bi bi-check-circle me-2"></i><span id="textoBoton">Crear Reserva</span>
          </button>
          <button type="button" class="btn btn-danger d-none" id="botonEliminarReserva">
            <i class="bi bi-trash me-2"></i>Eliminar Reserva
          </button>
        </div>
      </div>
    </div>
  </div>
    <!-- Scripts de JavaScript -->
  <script src="public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="src/scripts/pages/canchas-listado.js"></script>
  </body>
</html>
