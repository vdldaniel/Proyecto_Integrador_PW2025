<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once AUTH_REQUIRED_COMPONENT;

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verificar si el usuario está logueado
$is_authenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Resalta la página actual en el navbar
$current_page = 'partidosJugador';

// Definir título de la página
$page_title = 'Mis Partidos - FutMatch';

// CSS adicional específico de esta página
$page_css = [CSS_PAGES_PARTIDOS_JUGADOR];

// Cargar head común
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar
  require_once NAVBAR_JUGADOR_COMPONENT;
  ?>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container mt-4">

    <!-- TÍTULO Y FILTROS -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Mis Partidos</h1>
        <p class="text-muted mb-0">Gestiona tus partidos y encuentra nuevos rivales</p>
      </div>
      <div class="col-md-6 text-end pt-3">
        <a href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>" class="btn btn-primary">
          <i class="bi bi-plus-circle me-2"></i>Buscar nuevo partido
        </a>
      </div>
    </div>

    <!-- Filtros 
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex gap-2 flex-wrap align-items-center">
          <button class="btn btn-dark" id="btnFiltros">
            <i class="bi bi-funnel"></i>
          </button>
          <button class="btn btn-sm btn-dark active" data-filter="todos">
            Todos
          </button>
          <button class="btn btn-sm btn-dark" data-filter="confirmados">
            Confirmados
          </button>
          <button class="btn btn-sm btn-dark" data-filter="pendientes">
            Pendientes
          </button>
        </div>
      </div>
    </div>-->

    <!-- LISTA DE PARTIDOS POR SEMANAS -->
    <div id="listaPartidos">

      <!-- ESTA SEMANA -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-week me-2"></i>Esta semana
            </h4>
          </div>
        </div>
        <div id="estaSemana"></div>
      </div>

      <!-- PRÓXIMA SEMANA -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-plus me-2"></i>Próxima semana
            </h4>
          </div>
        </div>
        <div id="proximaSemana"></div>
      </div>

      <!-- MÁS ADELANTE -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-event me-2"></i>Más adelante
            </h4>
          </div>
        </div>
        <div id="masAdelante"></div>
      </div>

    </div>

  </main>

  <!-- Modal Ver Participantes (Solo lectura) -->
  <div class="modal fade" id="modalVerParticipantes" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-people-fill me-2"></i>Participantes del Partido
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Participantes Confirmados -->
          <h6 class="fw-bold mb-3">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Confirmados
          </h6>
          <div class="table-responsive mb-4">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Nombre y Apellido</th>
                  <th>Equipo</th>
                  <th>Rol</th>
                </tr>
              </thead>
              <tbody id="tablaConfirmadosVer">
                <tr>
                  <td colspan="4" class="text-center text-muted">Cargando...</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Gestionar Participantes -->
  <div class="modal fade" id="modalGestionarParticipantes" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            <i class="bi bi-people-fill me-2"></i>Gestionar Participantes
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Participantes Confirmados -->
          <h6 class="fw-bold mb-3">
            <i class="bi bi-check-circle-fill text-success me-2"></i>Confirmados
          </h6>
          <div class="table-responsive mb-4">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Nombre y Apellido</th>
                  <th>Equipo</th>
                  <th>Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="tablaConfirmados">
                <tr>
                  <td colspan="5" class="text-center text-muted">Cargando...</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Agregar Nuevo Participante -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Agregar Participante</h6>
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-4">
                  <label class="form-label">Username</label>
                  <input type="text" class="form-control" id="inputUsername" placeholder="Buscar jugador...">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="checkExterno">
                    <label class="form-check-label" for="checkExterno">
                      Participante externo
                    </label>
                  </div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Nombre y Apellido</label>
                  <input type="text" class="form-control" id="inputNombreApellido" placeholder="Nombre completo" disabled>
                </div>
                <div class="col-md-2">
                  <label class="form-label">Equipo</label>
                  <select class="form-select" id="selectEquipoNuevo">
                    <option value="">Seleccionar</option>
                    <option value="1">Equipo A</option>
                    <option value="2">Equipo B</option>
                  </select>
                  <div class="invalid-feedback">Selecciona un equipo</div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button class="btn btn-success w-100" id="btnAgregarParticipante">
                    <i class="bi bi-plus-lg"></i> Agregar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Solicitudes Pendientes -->
          <h6 class="fw-bold mb-3">
            <i class="bi bi-hourglass-split text-warning me-2"></i>Solicitudes Pendientes
          </h6>
          <div id="alertSinSolicitudes" class="alert alert-info" style="display: none;">
            <i class="bi bi-info-circle me-2"></i>No hay solicitudes pendientes
          </div>
          <div class="table-responsive mb-3" id="contenedorTablaPendientes">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Username</th>
                  <th>Nombre y Apellido</th>
                  <th>Equipo</th>
                  <th>Rol</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody id="tablaPendientes">
                <tr>
                  <td colspan="5" class="text-center text-muted">Cargando...</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Botón Abrir/Cerrar Convocatoria -->
          <div class="d-grid">
            <button class="btn btn-dark" id="btnToggleConvocatoria" data-abierto="0">
              <i class="bi bi-door-open me-2"></i><span id="textoToggleConvocatoria">Abrir convocatoria</span>
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->

  <script src="<?= JS_PARTIDOS_JUGADOR ?>"></script>
  <script>
    const GET_PARTIDOS_JUGADOR = '<?= GET_PARTIDOS_JUGADOR ?>';
    const GET_PARTICIPANTES_PARTIDO = '<?= GET_PARTICIPANTES_PARTIDO ?>';
    const POST_PARTICIPANTE_PARTIDO = '<?= POST_PARTICIPANTE_PARTIDO ?>';
    const UPDATE_PARTIDO = '<?= UPDATE_PARTIDO ?>';
    const GET_USUARIOS = '<?= GET_USUARIOS ?>';
    const UPDATE_RESERVA = '<?= UPDATE_RESERVA ?>';
  </script>
  <script src="<?= JS_BOOTSTRAP ?>"></script>
</body>

</html>