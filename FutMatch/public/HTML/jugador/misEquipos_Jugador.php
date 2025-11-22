<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once AUTH_REQUIRED_COMPONENT;

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Resalta la página actual en el navbar
$current_page = 'equiposListado';

// Definir título de la página
$page_title = 'Mis Equipos - FutMatch';

$page_css = [CSS_PAGES_EQUIPOS_JUGADOR];

// Cargar head común
require_once HEAD_COMPONENT;
?>

<body>

  <?php
  // Cargar navbar
  require_once NAVBAR_JUGADOR_COMPONENT;
  ?>

  <!-- Contenido Principal -->
  <main class="container mt-4">
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Mis Equipos</h1>
        <p class="text-muted mb-0">Gestiona tus equipos y únete a nuevos</p>
      </div>
      <div class="col-md-6 text-end">
        <!-- 
        <button type="button" class=" hidden btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalUnirseEquipo">
          <i class="bi bi-person-plus"></i> Unirse a un Equipo
        </button>
        -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearEquipo">
          <i class="bi bi-plus-circle"></i> Crear Equipo
        </button>
      </div>
    </div>

    <!-- Línea 2: Filtros y búsqueda 
    <div class="row mb-4">
      <div class="col-12">
        <div class="input-group">
          <span class="input-group-text">
            <i class="bi bi-search"></i>
          </span>
          <input
            type="text"
            id="searchInput"
            class="form-control"
            placeholder="Buscar equipos por nombre..." />
        </div>
      </div>
    </div>
    -->

    <!-- Lista de equipos -->
    <div id="equiposList" class="row g-3">
      <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const GET_USUARIOS = '<?= GET_USUARIOS ?>';
        //const GET_EQUIPOS_JUGADOR = '<?= GET_EQUIPOS_JUGADOR ?>'; Ya está en navbarJugador.php
        //const UPDATE_EQUIPO_JUGADOR = '<?= UPDATE_EQUIPO_JUGADOR ?>';
        const GET_EQUIPO_JUGADOR = '<?= GET_EQUIPO_JUGADOR ?>';
        const POST_EQUIPO_JUGADOR = '<?= POST_EQUIPO_JUGADOR ?>';
        const POST_INVITAR_JUGADOR = '<?= POST_INVITAR_JUGADOR ?>';
        const CURRENT_USER_ID = <?= $_SESSION['user_id'] ?>;
      </script>
    </div>
  </main>

  <!-- Modal: Unirse a un Equipo -->
  <div class="modal fade" id="modalUnirseEquipo" tabindex="-1" aria-labelledby="modalUnirseEquipoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUnirseEquipoLabel">Unirse a un equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="codigoEquipo" class="form-label">Código de equipo</label>
            <input type="text" class="form-control form-control-lg codigo-equipo" id="codigoEquipo"
              placeholder="00000" maxlength="5" pattern="[0-9]{5}">
            <div class="form-text">Ingresa el código de 5 dígitos proporcionado por el equipo</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnSolicitarUnirse">Solicitar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Crear Equipo -->
  <div class="modal fade" id="modalCrearEquipo" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tituloModalEquipo">Crear un equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formCrearEquipo">
            <input type="hidden" id="inputIdEquipo" value="">
            <div class="input foto-perfil mb-3">
              <label for="inputFotoPrincipal" class="form-label">Foto del equipo</label>
              <div class="foto-principal" id="dropZoneFoto">
                <div class="upload-icon">
                  <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                  <span class="upload-text">Click o arrastra una imagen</span>
                </div>
                <input type="file" name="foto" class="d-none" id="inputFotoPrincipal" accept="image/*">
              </div>
            </div>
            <div class="mb-3 input texto-principal">
              <label for="inputNombreEquipo" class="form-label">Nombre del equipo</label>
              <input type="text" name="nombre" id="inputNombreEquipo" class="form-control">
            </div>
            <div class="mb-3 input texto-largo">
              <label for="inputDescripcionEquipo" class="form-label">Descripción del equipo</label>
              <textarea type="text" name="descripcion" id="inputDescripcionEquipo" rows="3" class="form-control"></textarea>
              <div class="form-text">Opcional. Describe brevemente el objetivo o estilo de tu equipo.</div>
            </div>
            <div class="mb-3 input elementos" id="invitarJugadoresSection">
              <!-- Invitar jugadores-->
            </div>

            <!-- Sección de transferir liderazgo (solo visible en modo edición) -->
            <div class="mb-3 d-none" id="seccionTransferirLiderazgo">
              <hr>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Transferir liderazgo del equipo</strong>
              </div>
              <button type="button" class="btn btn-warning w-100 mb-2" id="btnMostrarTransferirLiderazgo">
                <i class="bi bi-arrow-right-circle me-1"></i> Transferir liderazgo
              </button>
              <div class="d-none" id="inputTransferirLiderazgo">
                <div class="input-group">
                  <input type="text" class="form-control" id="inputNuevoLider" placeholder="Username del nuevo líder">
                  <button class="btn btn-success" type="button" id="btnValidarNuevoLider" disabled>
                    <i class="bi bi-check-lg"></i>
                  </button>
                </div>
                <div class="form-text">El nuevo líder debe ser un miembro actual del equipo</div>
              </div>
            </div>
          </form>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="btnCrearEquipoSubmit">Confirmar</button>
          </div>
        </div>
      </div>
    </div>
  </div>


  <!-- Modal: Invitar Jugador -->
  <div class="modal fade" id="modalInvitarJugador" tabindex="-1" aria-labelledby="modalInvitarJugadorLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalInvitarJugadorLabel">Invitar jugadores al equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3" id="invitarJugadoresSectionModal">
            <!-- Los inputs de jugadores se agregarán aquí dinámicamente -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnConfirmarInvitaciones">Invitar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal: Editar Equipo -->
  <div class="modal fade" id="modalEditarEquipo" tabindex="-1" aria-labelledby="modalEditarEquipoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEditarEquipoLabel">Editar equipo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <form id="formEditarEquipo" action="" method="POST">
            <!-- Información básica del equipo -->
            <div class="row mb-3">
              <div class="col-md-8">
                <label for="editNombreEquipo" class="form-label">Nombre del equipo</label>
                <div class="position-relative">
                  <input name="nombreEquipo" type="text" class="form-control" id="editNombreEquipo" disabled
                    data-bs-toggle="tooltip" title="Solo puede modificar el líder del equipo">
                  <i class="bi bi-lock-fill position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
              </div>
              <div class="col-md-4">
                <label for="editFotoEquipo" class="form-label">Foto del equipo</label>
                <div class="position-relative">
                  <input name="fotoEquipo" type="file" class="form-control" id="editFotoEquipo" accept="image/*" disabled
                    data-bs-toggle="tooltip" title="Solo puede modificar el líder del equipo">
                  <i class="bi bi-lock-fill position-absolute top-50 end-0 translate-middle-y me-3 text-muted"></i>
                </div>
              </div>
            </div>

            <!-- Información del líder (solo lectura) -->
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label">Líder del equipo</label>
                <div class="input-group">
                  <span class="input-group-text bg-success text-white">
                    <i class="bi bi-star-fill"></i>
                  </span>
                  <input name="lider" type="text" class="form-control" value="@jugador_lider" readonly>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Fecha de creación</label>
                <input type="text" class="form-control" value="15 de Octubre, 2024" readonly>
              </div>
            </div>

            <!-- Estadísticas del equipo (solo lectura) -->
            <div class="row mb-4">
              <div class="col-md-4">
                <div class="text-center">
                  <div class="bg-primary text-white rounded-3 p-3">
                    <h4 class="mb-0">8</h4>
                    <small>Integrantes</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center">
                  <div class="bg-warning text-dark rounded-3 p-3">
                    <h4 class="mb-0">2</h4>
                    <small>Torneos Activos</small>
                  </div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="text-center">
                  <div class="bg-info text-white rounded-3 p-3">
                    <h4 class="mb-0">12</h4>
                    <small>Partidos Jugados</small>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <!-- Jugadores del equipo (editable si eres el líder) -->
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0">Jugadores del equipo</label>
                <span class="badge text-bg-dark">Puedes agregar/quitar jugadores</span>
              </div>

              <div id="editJugadoresContainer">
                <!-- Jugador líder (no removible) -->
                <div class="input-group mb-2">
                  <span class="input-group-text bg-success text-white">
                    <i class="bi bi-star-fill"></i>
                  </span>
                  <input type="text" class="form-control" value="@jugador_lider" readonly>
                  <button class="btn btn-dark" type="button" disabled>
                    <i class="bi bi-shield-check"></i> Líder
                  </button>
                </div>

                <!-- Jugadores editables -->
                <div class="input-group mb-2">
                  <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                  </span>
                  <input type="text" class="form-control" value="@jugador_miembro1" name="jugadorEdit[]">
                  <button class="btn btn-dark remove-jugador-edit-btn" type="button">
                    <i class="bi bi-dash"></i>
                  </button>
                </div>

                <div class="input-group mb-2">
                  <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                  </span>
                  <input type="text" class="form-control" value="@jugador_miembro2" name="jugadorEdit[]">
                  <button class="btn btn-dark remove-jugador-edit-btn" type="button">
                    <i class="bi bi-dash"></i>
                  </button>
                </div>

                <div class="input-group mb-2">
                  <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                  </span>
                  <input type="text" class="form-control" placeholder="Username del nuevo jugador" name="jugadorEdit[]">
                  <button class="btn btn-dark remove-jugador-edit-btn" type="button">
                    <i class="bi bi-dash"></i>
                  </button>
                </div>
              </div>

              <button type="button" class="btn btn-dark btn-sm" id="btnAgregarJugadorEdit">
                <i class="bi bi-plus"></i> Agregar jugador
              </button>
            </div>

            <!-- Opciones peligrosas -->
            <div class="mt-4">
              <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                  <h6 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Acciones peligrosas</h6>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <button type="button" class="btn btn-dark w-100"
                        data-bs-toggle="tooltip" title="Solo puede modificar el líder del equipo" disabled>
                        <i class="bi bi-arrow-right-circle"></i> Transferir liderazgo
                      </button>
                    </div>
                    <div class="col-md-6">
                      <button type="button" class="btn btn-dark w-100"
                        data-bs-toggle="tooltip" title="Solo puede modificar el líder del equipo" disabled>
                        <i class="bi bi-trash"></i> Disolver equipo
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarEdicionEquipo">
            <i class="bi bi-save"></i> Guardar cambios
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <!--<script src="<?= JS_TOAST_MODULE ?>"></script>-->
  <script src="<?= JS_MIS_EQUIPOS_JUGADOR ?>"></script>
  <script>
    const IMG_EQUIPO_DEFAULT = '<?= IMG_EQUIPO_DEFAULT ?>';
  </script>

  <!-- Estilos adicionales -->
  <style>

  </style>

</body>

</html>