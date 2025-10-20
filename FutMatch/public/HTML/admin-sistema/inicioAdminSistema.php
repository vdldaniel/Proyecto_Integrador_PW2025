<?php

// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Definir la página actual para el navbar
$current_page = 'inicioAdminSistema';
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <base href="<?= BASE_URL ?>" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="public/assets/css/bootstrap.min.css" />
    <!-- Bootstrap JS (bundle con Popper incluido) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!--Iconos Bootstrap-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    />

    <!-- Fuente -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap"
      rel="stylesheet"
    />

    <!-- Estilos propios -->
    <link rel="stylesheet" href="src/styles/base.css" />
    <link rel="stylesheet" href="src/styles/layout.css" />
  </head>

  <body>

  <header>
    <?php include NAVBAR_ADMIN_SISTEMA_COMPONENT; ?>
    </header>

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
  <script src="public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="src/scripts/pages/inicio-admin-sistema.js"></script>
  </body>
</html>