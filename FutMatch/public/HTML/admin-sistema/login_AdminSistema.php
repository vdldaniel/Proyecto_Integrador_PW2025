<?php
require_once __DIR__ . '/../../../src/app/config.php';

$page_title = "FutMatch - ADMIN";
$page_css = [SRC_PATH . "styles/pages/landing.css"]; // ajustar si corresponde

include HEAD_COMPONENT;
?>

<head>
  <title><?= $page_title ?></title>
</head>

<body>
  <header class="hero bg-image" style="background-image: url('<?= IMG_PATH ?>bg2.jpg');">
    <div class="hero-overlay"></div>

    <div class="container position-relative">
      <!-- Logo - SVG (opcional) -->

      <!-- Título y lema -->
      <div class="row justify-content-center text-center text-light">
        <div class="col-11 col-lg-9">
          <h1 class="display-3 fw-800 brand-title mb-2">FutMatch - ADMIN</h1>
          <p class="lead brand-tagline mb-5">
            La pasión es la chispa que enciende el fuego del éxito
          </p>
        </div>
      </div>

      <!-- Tarjetas de acción -->
      <div class="row g-4 justify-content-center">


        <!-- Card: Iniciar sesión Admin  -->
        <div class="col-11 col-md-5 col-lg-4">
          <div class="card rounded-4 h-100" id="loginAdminCard" role="button" tabindex="0">
            <div class="card-body py-4">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <h2 class="h4 fw-600 mb-1">Iniciar sesión</h2>
                </div>
              </div>

              <!-- Formulario -->
              <form id="loginForm" novalidate>

                <div class="mb-3">
                  <label for="usuarioAdmin" class="form-label">Email</label>
                  <input type="email" class="form-control" id="usuarioAdmin" name="email"
                    autocomplete="username" required />
                  <div class="invalid-feedback">Ingrese su cuenta de administrador.</div>
                </div>

                <div class="mb-3">
                  <label for="password" class="form-label">Contraseña</label>
                  <input type="password" class="form-control" id="password" name="password"
                    autocomplete="current-password" required />
                  <div class="invalid-feedback">La contraseña es obligatoria.</div>
                </div>

                <div class="d-grid gap-2">
                  <button type="submit" class="btn btn-success">Ingresar</button>
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

  </header>
</body>

<!-- Scripts -->
<script src="<?= JS_BOOTSTRAP ?>"></script>
<!-- Script específico de la página (opcional) -->

</html>