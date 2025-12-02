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
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

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
        <button id="configurarHorarios" class="btn btn-dark me-2" type="button" data-bs-toggle="modal" data-bs-target="#modalConfigurarHorarios">
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

    <!-- Controles adicionales específicos del admin 
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
        Espacio reservado para controles adicionales futuros 
      </div>
    </div>
    -->

  </main>

  <!--MODALES-->

  <!-- Modal de Notificaciones/Solicitudes
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
            Las solicitudes se renderizan dinámicamente desde JavaScript
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
  </div> -->

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
            <!-- Selección de cancha -->
            <div class="mb-4">
              <label for="canchaConfiguracion" class="form-label fw-bold">Cancha</label>
              <select class="form-select" id="canchaConfiguracion" required>
                <option value="">Seleccione una cancha...</option>
              </select>
            </div>

            <!-- Horarios de operación por día -->
            <div class="mb-4">
              <h6 class="fw-bold mb-3">Horarios de Operación</h6>
              <div id="horariosContainer">
                <!-- Los horarios se cargarán dinámicamente con JavaScript -->
                <div class="text-muted text-center py-3">
                  <i class="bi bi-info-circle me-2"></i>
                  Seleccione una cancha para configurar sus horarios
                </div>
              </div>
            </div>

            <!-- Información de la cancha -->
            <div class="mb-4" id="seccionInfoCancha" style="display: none;">
              <h6 class="fw-bold mb-3">Información de la Cancha</h6>
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-2"><strong>Nombre:</strong><br><span id="nombreCancha">-</span></p>
                      <p class="mb-2"><strong>Dirección:</strong><br><span id="direccionCancha">-</span></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-2"><strong>Teléfono:</strong><br><span id="telefonoCancha">-</span></p>
                      <div class="d-grid gap-2">
                        <button type="button" class="btn btn-dark btn-sm" id="botonVerPerfilCancha">
                          <i class="bi bi-building me-2"></i>Ver Perfil Completo
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="botonPoliticasReservas">
                          <i class="bi bi-file-text me-2"></i>Políticas de Reservas
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
                <label for="username" class="form-label">Username del Jugador</label>
                <input type="text" class="form-control" id="username" required>
                <div class="invalid-feedback" id="usernameFeedback">Por favor ingrese un username válido.</div>
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

            <!-- Selección de cancha y tipo de reserva-->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="canchaReserva" class="form-label">Cancha</label>
                <select class="form-select" id="canchaReserva" required>
                  <option value="">Seleccione una cancha...</option>
                  <!-- Traer desde get_canchas.php -->
                </select>
                <div class="invalid-feedback">Por favor seleccione una cancha.</div>
              </div>
              <div class="col-md-6">
                <label for="tipoReserva" class="form-label">Tipo de Reserva</label>
                <select class="form-select" id="tipoReserva" required>
                  <option value="">Seleccione un tipo...</option>
                  <!-- Traer desde getTiposReserva.php -->
                </select>
                <div class="invalid-feedback">Por favor seleccione un tipo de reserva.</div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Detalles de la reserva -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="fechaComienzo" class="form-label">Desde</label>
                <input type="date" class="form-control" id="fechaComienzo" required>
                <div class="invalid-feedback">Por favor seleccione una fecha de inicio.</div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="horaComienzo" class="form-label">Hora</label>
                <input type="time" class="form-control" id="horaComienzo"
                  min="08:00" max="22:00" step="3600" required>
                <div class="invalid-feedback">Por favor seleccione una hora de inicio.</div>
              </div>
              <div class="col-md-6">
                <label for="fechaFin" class="form-label">Hasta</label>
                <input type="date" class="form-control" id="fechaFin" required>
                <div class="invalid-feedback">Por favor seleccione una fecha de fin.</div>
              </div>
              <div class="col-md-6">
                <label for="horaFin" class="form-label">Hora</label>
                <input type="time" class="form-control" id="horaFin"
                  min="08:00" max="22:00" step="3600" required>
                <div class="invalid-feedback">Por favor seleccione una hora de fin.</div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Titulo y comentario -->
            <div class="mb-3">
              <label for="tituloReserva" class="form-label">Título de la reserva</label>
              <input type="text" class="form-control" id="tituloReserva" maxlength="100" placeholder="Partido amistoso, entrenamiento, escuela, etc.">
            </div>
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

  <!-- Modal de Políticas de Reservas -->
  <div class="modal fade" id="modalPoliticasReservas" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-file-text me-2"></i>Políticas de Reservas
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted mb-3">Define las políticas y condiciones para las reservas de esta cancha.</p>
          <div class="mb-3">
            <label for="politicasReservasTexto" class="form-label fw-bold">Políticas de Reservas</label>
            <textarea class="form-control" id="politicasReservasTexto" rows="8" maxlength="2000"
              placeholder="Ejemplo:&#10;- Reserva mínima con 24 horas de anticipación&#10;- Cancelación gratuita hasta 12 horas antes&#10;- Depósito del 50% al confirmar la reserva&#10;- Prohibido el ingreso de bebidas alcohólicas"></textarea>
            <div class="form-text">Máximo 2000 caracteres</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="botonGuardarPoliticas">
            <i class="bi bi-save me-2"></i>Guardar Políticas
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal de Detalle de Reserva -->
  <!-- Modal Detalle Reserva -->
  <div class="modal fade" id="modalDetalleReserva" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-calendar-check me-2"></i>Detalle de Reserva
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="contenidoDetalleReserva">
            <!-- Se llena dinámicamente con JavaScript -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Solicitudes Pendientes -->
  <div class="modal fade" id="modalSolicitudesPendientes" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-clock-history me-2"></i>Solicitudes Pendientes
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="contenidoSolicitudesPendientes">
            <!-- Se llena dinámicamente con JavaScript -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Reservas Históricas -->
  <div class="modal fade" id="modalReservasHistoricas" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-archive me-2"></i>Historial de Reservas
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="contenidoReservasHistoricas">
            <!-- Se llena dinámicamente con JavaScript -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts de JavaScript -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>

  <!-- Constantes PHP para JavaScript -->
  <script>
    const POST_RESERVA = '<?= POST_RESERVA ?>';
    const GET_CANCHAS_ADMIN_CANCHA = '<?= GET_CANCHAS_ADMIN_CANCHA ?>';
    const GET_TIPOS_RESERVA = '<?= GET_TIPOS_RESERVA ?>';
    const GET_USUARIOS = '<?= GET_USUARIOS ?>';
    const GET_HORARIOS_CANCHAS = '<?= GET_HORARIOS_CANCHAS ?>';
    const UPDATE_HORARIOS_CANCHAS = '<?= UPDATE_HORARIOS_CANCHAS ?>';
    const UPDATE_POLITICAS_CANCHA = '<?= UPDATE_POLITICAS_CANCHA ?>';
    const GET_RESERVAS = '<?= GET_RESERVAS ?>';
    const GET_RESERVA_DETALLE = '<?= GET_RESERVA_DETALLE ?>';
    const UPDATE_RESERVA = '<?= UPDATE_RESERVA ?>';
    const PAGE_MIS_PERFILES_ADMIN_CANCHA = '<?= PAGE_MIS_PERFILES_ADMIN_CANCHA ?>';
  </script>

  <!-- Script base del calendario (debe ir primero) -->
  <script src="<?= JS_AGENDA ?>"></script>

  <!-- Script específico del admin (extiende el base) -->
  <script src="<?= JS_AGENDA_ADMIN ?>"></script>
</body>

</html>