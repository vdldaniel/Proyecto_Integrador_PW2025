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
        <h1 class="text-center mb-5">FUTMATCH - Administración</h1>

        <div class="row justify-content-center">
          <div class="col-12 col-lg-8">
            <a href="<?= PAGE_SISTEMA_CANCHAS_LISTADO ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none" >
              <div class="card-body">
                <h5 class="card-title">Listado de Canchas</h5>
                <p class="card-text">Ver y gestionar las canchas disponibles.</p>
              </div>
            </a>

            <a href="<?= PAGE_SISTEMA_JUGADORES_LISTADO ?>" class="card shadow-border-0 rounded-4 mb-5 text-decoration-none">
              <div class="card-body">
                <h5 class="card-title">Listado de Usuarios</h5>
                <p class="card-text">Ver y gestionar los usuarios registrados.</p>
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