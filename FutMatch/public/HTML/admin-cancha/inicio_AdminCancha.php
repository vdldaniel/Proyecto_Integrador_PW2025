<!--
Dashboard del Administrador de Cancha
Incluye:
- Header con título "Dashboard" y botón de solicitudes pendientes
- Card izquierda: Eventos de hoy con link a agenda completa
- Card derecha: Gráfico de resumen de uso de canchas con filtro temporal
-->
<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'inicioAdminCancha';

// Definir título de la página
$page_title = 'Dashboard - FutMatch';

// CSS adicional específico de esta página
$page_css = [
  CSS_PAGES_DASHBOARD_ADMIN_CANCHA
];

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
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Bienvenido!</h1>
        <p class="text-muted mb-0">Aquí podrá gestionar sus recursos y eventos</p>
      </div>
      <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success">
          <i class="bi bi-plus-circle"></i> Nueva Reserva
        </button>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <a href="<?= PAGE_AGENDA ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Agenda</h5>
            <p class="card-text">Ver y gestionar eventos de la agenda.</p>
          </div>
        </a>

        <a href="<?= PAGE_MIS_CANCHAS_ADMIN_CANCHA ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Mis Canchas</h5>
            <p class="card-text">Ver y gestionar canchas vinculadas a tu usuario</p>
          </div>
        </a>

        <a href="<?= PAGE_PERFILES_ADMIN_CANCHA ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Perfiles de Canchas</h5>
            <p class="card-text">Ver y gestionar los perfiles de mis canchas.</p>
          </div>
        </a>

        <a href="<?= PAGE_MIS_TORNEOS_ADMIN_CANCHA ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
          <div class="card-body">
            <h5 class="card-title">Mis Torneos</h5>
            <p class="card-text">Ver y gestionar mis torneos</p>
          </div>
        </a>
      </div>
    </div>

  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_ADMIN_CANCHA ?>"></script>
</body>

</html>