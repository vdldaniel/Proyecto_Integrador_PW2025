<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once '../../../src/app/auth-required.php';

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

    <!-- Filtros -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex gap-2 flex-wrap align-items-center">
          <!-- Botón Filtro -->
          <button class="btn btn-dark" id="btnFiltros">
            <i class="bi bi-funnel"></i>
          </button>
          <!-- Filtros de estado -->
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
    </div>

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

  <!-- Scripts -->
  <script>
    const API_PARTIDOS = '<?= GET_PARTIDOS_JUGADOR ?>';
  </script>
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_PARTIDOS_JUGADOR ?>"></script>
</body>

</html>