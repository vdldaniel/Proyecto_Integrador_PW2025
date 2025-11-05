<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfil';
$page_title = "Perfil de Cancha - FutMatch";
$page_css = [];

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
    $perfil_cancha_boton_primario = [
      'texto' => 'Ver Disponibilidad',
      'icono' => 'bi-calendar-check',
      'url' => PAGE_AGENDA
    ];

    // Incluir componente de perfil de cancha
    include CANCHA_PERFIL_COMPONENT;
    ?>
  </main>

  <!-- Modal Editar Cancha (reutilizado de canchasListado.php) -->
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