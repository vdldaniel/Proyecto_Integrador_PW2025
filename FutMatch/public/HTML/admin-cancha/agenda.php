<!--
Manejo de la agenda del Admin de Cancha:
Consiste en una vista de calendario (mensual, semanal y diaria) donde el admin puede:
- Filtrar por cancha (debe seleccionar una cancha para ver su agenda)
- Ver la agenda de reservas de la cancha
- Crear una reserva (redirige a modal de creación)
- Ver/Editar/Eliminar reservas existentes (clic en reserva abre modal de edición)
- Ver/Aceptar/Rechazar las solicitudes de reservas de la canchas
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'agenda'; 

// CSS adicional específico de esta página
$page_title = "Agenda - FutMatch";
$page_css = [CSS_PAGES_AGENDA
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>
<body class="monthly-view-active">
  <?php 
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT; 
  ?>

  <!-- Barra superior de controles -->
  <div class="container-fluid my-3">
    <div class="row align-items-center g-2 justify-content-center justify-content-md-start">
      <!-- Selector de cancha -->
      <div class="col-auto">
        <select class="form-select" id="selectorCancha" style="width: auto; min-width: 200px;">
          <option selected>Seleccionar cancha</option>
          <option value="1">Cancha A - Fútbol 11</option>
          <option value="2">Cancha B - Fútbol 7</option>
          <option value="3">Cancha C - Fútbol 5</option>
        </select>
      </div>

      <!-- Botón crear reserva -->
      <div class="col-auto">
        <button id="botonCrearReserva" class="btn btn-success d-flex align-items-center justify-content-center" type="button" style="min-width: 38px;">
          <i class="bi bi-plus-circle"></i><span class="d-none d-lg-inline ms-2">Crear reserva</span>
        </button>
      </div>

      <!-- Botón Hoy -->
      <div class="col-auto">
        <button id="botonHoy" class="btn btn-primary" type="button">Hoy</button>
      </div>

      <!-- Selector de fecha -->
      <div class="col-auto">
        <div class="position-relative">
          <input type="date" class="form-control d-none d-lg-block" id="selectorFecha">
          <input type="date" class="form-control d-lg-none position-absolute opacity-0" id="selectorFechaOculto" style="width: 40px; pointer-events: none;">
          <button class="btn btn-outline-secondary d-lg-none" type="button" id="botonAbrirSelectorFecha">
            <i class="bi bi-calendar3"></i>
          </button>
        </div>
      </div>

      <!-- Selector de vista -->
      <div class="col-auto ms-md-auto">
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownVista" data-bs-toggle="dropdown" aria-expanded="false">
            <span id="vistaActual">Mes</span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownVista">
            <li><a class="dropdown-item selector-vista" href="#" data-vista="mes">Mes</a></li>
            <li><a class="dropdown-item selector-vista" href="#" data-vista="semana">Semana</a></li>
            <li><a class="dropdown-item selector-vista" href="#" data-vista="dia">Día</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Área principal del calendario -->
  <div class="main-container">
    <div class="container-fluid h-100">
      <div class="row h-100 p-3">
        <main class="col-12 d-flex flex-column">
          <!-- Encabezado con fecha y navegación -->
          <div class="d-flex justify-content-between align-items-center mb-3 date-header">
            <h3 id="displayFechaActual" class="mb-0">Septiembre 2025</h3> <!-- Se actualiza con actualizarDisplayFecha() en agenda.js -->
            <div class="btn-group navegacion-calendario" role="group" aria-label="Navigate calendar">
              <button type="button" class="btn btn-outline-secondary" id="botonAnterior">
                <i class="bi bi-chevron-left"></i> <!-- Ir al período anterior -->
              </button>
              <button type="button" class="btn btn-outline-secondary" id="botonSiguiente">
                <i class="bi bi-chevron-right"></i> <!-- Ir al período siguiente -->
              </button>
            </div>
          </div>

        <!-- Contenido del calendario -->
        <div id="contenidoCalendario" class="d-flex flex-column flex-grow-1">
          <!-- Vista mensual -->
          <div id="vistaMensual" class="vista-calendario d-flex flex-column">
            <div class="table-responsive flex-grow-1">
              <table class="table table-bordered h-100">
                <thead class="table-light">
                  <tr>
                    <th scope="col">Dom</th>
                    <th scope="col">Lun</th>
                    <th scope="col">Mar</th>
                    <th scope="col">Mié</th>
                    <th scope="col">Jue</th>
                    <th scope="col">Vie</th>
                    <th scope="col">Sáb</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Se llena con renderizarVistaMensual() en agenda.js -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Vista semanal -->
          <div id="vistaSemanal" class="vista-calendario d-none">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead class="table-light">
                  <tr>
                    <th scope="col" width="100">Hora</th>
                    <th scope="col">Dom</th>
                    <th scope="col">Lun</th>
                    <th scope="col">Mar</th>
                    <th scope="col">Mié</th>
                    <th scope="col">Jue</th>
                    <th scope="col">Vie</th>
                    <th scope="col">Sáb</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Se llena con renderizarVistaSemanal() en agenda.js -->
                </tbody>
              </table>
            </div>
          </div>

          <!-- Vista diaria -->
          <div id="vistaDiaria" class="vista-calendario d-none">
            <div class="row">
              <div class="col-md-8">
                <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead class="table-light">
                      <tr>
                        <th scope="col" width="100">Hora</th>
                        <th scope="col" id="encabezadoVistaDiaria">Día</th>
                      </tr>
                    </thead>
                    <tbody>
                      <!-- Se llena con renderizarVistaDiaria() en agenda.js -->
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-4">
                <div class="card">
                  <div class="card-header">
                    <h6 class="mb-0">Resumen del día</h6>
                  </div>
                  <div class="card-body">
                    <!-- Se llena con actualizarResumenDia() en agenda.js -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!--MODALES-->
  
  <!-- Modal de Notificaciones/Solicitudes -->
  <div class="modal fade" id="modalNotificaciones" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-bell me-2"></i>Solicitudes de Reserva
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="listaSolicitudes">
            <!-- Las solicitudes se renderizan dinámicamente desde JavaScript -->
          </div>
          
          <div id="sinSolicitudes" class="text-center d-none">
            <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">No hay solicitudes pendientes</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal de Configuración -->
  <div class="modal fade" id="modalConfiguracion" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-gear me-2"></i>Configuración de Cancha
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="configuracionForm">
            <!-- Horarios de operación -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Horarios de Operación</h6>
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="horaApertura" class="form-label">Hora de apertura</label>
                  <input type="time" class="form-control" id="horaApertura" 
                         value="08:00" min="06:00" max="23:00" required>
                  <div class="form-text">Horario mínimo: 06:00</div>
                </div>
                <div class="col-md-6">
                  <label for="horaCierre" class="form-label">Hora de cierre</label>
                  <input type="time" class="form-control" id="horaCierre" 
                         value="22:00" min="07:00" max="24:00" required>
                  <div class="form-text">Horario máximo: 24:00</div>
                </div>
              </div>
              <div class="alert alert-info d-flex align-items-center">
                <i class="bi bi-info-circle me-2"></i>
                <small>El horario de cierre debe ser posterior al de apertura</small>
              </div>
            </div>
            
            <!-- Días de operación -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Días de Operación</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="lunes" checked>
                    <label class="form-check-label" for="lunes">Lunes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="martes" checked>
                    <label class="form-check-label" for="martes">Martes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="miercoles" checked>
                    <label class="form-check-label" for="miercoles">Miércoles</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="jueves" checked>
                    <label class="form-check-label" for="jueves">Jueves</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="viernes" checked>
                    <label class="form-check-label" for="viernes">Viernes</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="sabado" checked>
                    <label class="form-check-label" for="sabado">Sábado</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="domingo" checked>
                    <label class="form-check-label" for="domingo">Domingo</label>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Información de la cancha -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Información del Complejo</h6>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-2"><strong>Nombre:</strong><br><span id="nombreComplejo">Complejo Deportivo Central</span></p>
                      <p class="mb-0"><strong>Dirección:</strong><br><span id="direccionComplejo">Av. Principal 123, Buenos Aires</span></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-2"><strong>Teléfono:</strong><br><span id="telefonoComplejo">+54 11 1234-5678</span></p>
                      <div class="d-grid">
                        <button type="button" class="btn btn-outline-info" id="botonVerPerfilCancha">
                          <i class="bi bi-building me-2"></i>Ver Perfil Completo
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="botonGuardarConfiguracion">
            <i class="bi bi-save me-2"></i>Guardar Configuración
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalGestionReserva" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModal">Gestionar Reserva</h5>
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
  <script src="src/scripts/pages/agenda.js"></script>
</body>
</html>



