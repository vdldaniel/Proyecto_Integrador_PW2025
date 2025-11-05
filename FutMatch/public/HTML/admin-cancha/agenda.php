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
$page_css = [
  CSS_PAGES_AGENDA
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body class="monthly-view-active">
  <?php
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
  ?>

  <!-- Contenido Principal -->
  <main class="container mt-4">
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-8">
        <h1 class="fw-bold mb-1" id="displayFechaActual">Noviembre 2025</h1>
        <p class="text-muted mb-0">Gestiona las reservas de tus canchas</p>
      </div>
      <div class="col-md-4 text-end">
        <button id="configurarHorarios" class="btn btn-outline-secondary me-2" type="button" data-bs-toggle="modal" data-bs-target="#modalConfigurarHorarios">
          <i class="bi bi-gear"></i> Configuración
        </button>
        <button id="botonCrearReserva" class="btn btn-success" type="button">
          <i class="bi bi-plus-circle"></i> Crear Reserva
        </button>
      </div>
    </div>

    <!-- Incluir componente calendario con funcionalidades específicas del admin -->
    <?php
    // Cargar el componente calendario base
    $calendario_admin_mode = true; // Flag para identificar que está en modo admin
    include CALENDARIO_COMPONENT;
    ?>

    <!-- Controles adicionales específicos del admin -->
    <div class="row mb-3">
      <div class="col-md-8">
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            id="searchInput"
            class="form-control"
            placeholder="Buscar reservas..." />
        </div>
      </div>
      <div class="col-md-4">
        <!-- Espacio reservado para controles adicionales futuros -->
      </div>
    </div>
  </main>

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
  <div class="modal fade" id="modalConfigurarHorarios">
    <div class="modal-dialog modal-lg">
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
                        <a href="<?= PAGE_ADMIN_PERFILES_CANCHAS ?>" type="button" class="btn btn-outline-info" id="botonVerPerfilCancha">
                          <i class="bi bi-building me-2"></i>Ver Perfil Completo
                        </a>
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
  <script src="<?= JS_BOOTSTRAP ?>"></script>

  <!-- Script base del calendario (debe ir primero) -->
  <script src="<?= JS_AGENDA ?>"></script>

  <!-- Script específico del admin (extiende el base) -->
  <script src="<?= JS_AGENDA_ADMIN ?>"></script>
</body>

</html>