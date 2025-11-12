<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfil';
$page_title = "Perfil de Cancha - FutMatch";
$page_css = [CSS_PAGES_PERFILES];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
  ?>

  <!-- Contenido Principal -->
  <main class="container mt-4">
    <?php
    // Variables de configuración para el componente perfil de cancha (modo admin)
    $perfil_cancha_admin_mode = true;
    $perfil_cancha_mostrar_selector = true;
    $perfil_cancha_titulo_seccion = 'Torneos Activos';
    $perfil_cancha_skip_header = true; // Usamos nuestro header personalizado
    $perfil_cancha_descripcion = 'Gestiona la información y configuración de tu cancha';
    $perfil_cancha_boton_primario = [
      'texto' => 'Ver Disponibilidad',
      'icono' => 'bi-calendar-check',
      'url' => PAGE_AGENDA_ADMIN_CANCHA
    ];

    // Información específica de la cancha (normalmente vendría de BD del admin logueado)
    $perfil_cancha_nombre = 'MegaFutbol Cancha A1-F5';
    $perfil_cancha_descripcion_banner = 'Cancha de césped sintético de última generación con iluminación LED profesional. Ideal para partidos de Fútbol 5 con excelente drenaje y superficie antideslizante.';
    $perfil_cancha_direccion = 'Av. Corrientes 1234, CABA, Buenos Aires, Argentina';
    $perfil_cancha_tipo = 'Fútbol 5';
    $perfil_cancha_superficie = 'Césped sintético';
    $perfil_cancha_capacidad = '10 jugadores';
    $perfil_cancha_calificacion = '4.8';
    $perfil_cancha_total_resenas = '127';
    $perfil_cancha_total_jugadores = '342';
    $perfil_cancha_total_partidos = '156';
    $perfil_cancha_dias_atencion = 'Lunes a Domingo';
    $perfil_cancha_horario = '07:00 - 23:00';
    $perfil_cancha_estado_actual = 'Abierto ahora';
    $perfil_cancha_hora_cierre = '23:00';
    ?>

    <!-- Header específico para admin de cancha -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Perfil de Cancha</h1>
        <p class="text-muted mb-0">Gestiona la información y configuración de tu cancha</p>
      </div>
      <div class="col-md-6 text-end">
        <a type="button" class="btn btn-success me-2" href="<?= PAGE_AGENDA_ADMIN_CANCHA ?>">
          <i class="bi bi-calendar-week"></i> Ir a Agenda
        </a>
        <button type="button" class="btn btn-dark me-2" data-bs-toggle="modal" data-bs-target="#modalEditarCancha">
          <i class="bi bi-gear"></i> Configuración
        </button>
        <button type="button" class="btn btn-primary" id="btnEditarPerfil">
          <i class="bi bi-pencil-square"></i> Editar Perfil
        </button>
      </div>
    </div>

    <?php
    // Incluir componente de perfil de cancha (sin header, ya lo definimos arriba)
    include PERFIL_CANCHA_COMPONENT;
    ?>
  </main>

  <!-- Modal Editar Cancha (reutilizado de misCanchas_AdminCancha.php) -->
  <div class="modal fade" id="modalEditarCancha" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Cancha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formEditarCancha">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editNombreCancha" class="form-label">Nombre de la cancha</label>
                <input type="text" class="form-control" id="editNombreCancha" value="MegaFutbol Cancha A1-F5" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editTipoCancha" class="form-label">Tipo de cancha</label>
                <select class="form-select" id="editTipoCancha" required>
                  <option value="futbol5" selected>Fútbol 5</option>
                  <option value="futbol7">Fútbol 7</option>
                  <option value="futbol11">Fútbol 11</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editSuperficie" class="form-label">Tipo de superficie</label>
                <select class="form-select" id="editSuperficie" required>
                  <option value="cesped_natural">Césped natural</option>
                  <option value="cesped_sintetico" selected>Césped sintético</option>
                  <option value="parquet">Parquet</option>
                  <option value="cemento">Cemento</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editCapacidad" class="form-label">Capacidad</label>
                <input type="number" class="form-control" id="editCapacidad" value="10" min="4" max="22" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="editDescripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="editDescripcion" rows="3" required>Cancha de césped sintético de última generación con iluminación LED profesional. Ideal para partidos de Fútbol 5 con excelente drenaje y superficie antideslizante.</textarea>
            </div>
            <div class="mb-3">
              <label for="editDireccion" class="form-label">Dirección completa</label>
              <input type="text" class="form-control" id="editDireccion" value="Av. Corrientes 1234, CABA, Buenos Aires, Argentina" required>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editHoraApertura" class="form-label">Hora de apertura</label>
                <input type="time" class="form-control" id="editHoraApertura" value="07:00" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editHoraCierre" class="form-label">Hora de cierre</label>
                <input type="time" class="form-control" id="editHoraCierre" value="23:00" required>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para cambiar banner de cancha -->
  <div class="modal fade" id="modalCambiarBanner" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-image"></i> Cambiar Portada de Cancha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Zona de arrastrar y soltar -->
          <div class="banner-upload-zone" id="bannerUploadZone">
            <div class="text-center p-4">
              <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
              <h5>Arrastra tu imagen aquí</h5>
              <p class="text-muted">o haz clic para seleccionar un archivo</p>
              <input type="file" class="d-none" id="inputBanner" accept="image/*">
            </div>
          </div>

          <!-- Preview del banner -->
          <div class="mt-3" id="bannerPreviewContainer" style="display: none;">
            <label class="form-label">Vista previa:</label>
            <div class="banner-preview-wrapper">
              <div class="banner-preview" id="bannerPreview"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" id="btnGuardarBanner" disabled>Guardar Portada</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">
  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <!-- Script base del perfil de cancha (debe ir primero) -->
  <script src="<?= JS_PERFIL_CANCHA_BASE ?>"></script>
  <!-- Script específico del admin (extiende el base) -->
  <script src="<?= JS_PERFILES_CANCHAS ?>"></script>
</body>

</html>