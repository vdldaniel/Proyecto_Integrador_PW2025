<?php

// Cargar configuración
require_once("../../../src/app/config.php");

// Definir la página actual para el navbar
$current_page = 'inicioAdminSistema';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$page_title = "Sistema - FutMatch";

$page_css = [];

include HEAD_COMPONENT;

?>

<body>
  <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>

  <main>
    <div class="container mt-4">

      <!-- Línea 1: Header con título y botones de navegación -->
      <div class="row mb-4 align-items-center">
        <div class="col">
          <h1 class="fw-bold mb-1">Administración</h1>
          <p class="text-muted mb-0">Gestioná usuarios y canchas</p>
        </div>
      </div>

      <div class="row">
        <div class="col">
          <a href="<?= PAGE_SISTEMA_CANCHAS_LISTADO ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
            <div class="card-body">
              <h5 class="card-title">Listado de Canchas</h5>
              <p class="card-text">Ver y gestionar las canchas disponibles.</p>
            </div>
          </a>

          <a href="<?= PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
            <div class="card-body">
              <h5 class="card-title">Canchas reportadas</h5>
              <p class="card-text">Ver y gestionar los reportes recibidos de los usuarios</p>
            </div>
          </a>

          <a href="<?= PAGE_SISTEMA_JUGADORES_LISTADO ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
            <div class="card-body">
              <h5 class="card-title">Listado de Usuarios</h5>
              <p class="card-text">Ver y gestionar los usuarios registrados.</p>
            </div>
          </a>

          <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
            <div class="card-body">
              <h5 class="card-title">Listado de Usuarios</h5>
              <p class="card-text">Ver y gestionar los reportes recibidos de los usuairos</p>
            </div>
          </a>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_ADMIN_SISTEMA ?>"></script>
</body>

</html>