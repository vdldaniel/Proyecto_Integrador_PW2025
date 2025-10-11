<!--
Página de recuperación de contraseña.
Formulario para solicitar restablecer la contraseña enviando un email.
Incluye enlace para volver al inicio de sesión.
-->
<?php
require_once __DIR__ . '/../../../src/app/config.php';

$page_title = "Restablecer contraseña | FutMatch";
$page_css = [SRC_PATH . "styles/pages/landing.css"];
include HEAD_COMPONENT;
?>

<title><?= $page_title ?></title>
</head>

<body>
  <header class="hero bg-image" style="background-image: url('<?= IMG_PATH ?>bg2.jpg');">
    <!-- Overlay más oscuro -->
    <div class="hero-overlay" style="background: rgba(0, 0, 0, 0.65);"></div>

    <div class="container position-relative">
      <!-- Título -->
      <div class="row justify-content-center text-center text-light mb-4">
        <div class="col-11 col-lg-9">
          <h1 class="display-4 fw-800 brand-title mb-2">FutMatch</h1>
          <p class="lead brand-tagline">Recuperá tu cuenta</p>
        </div>
      </div>

      <!-- Formulario de recuperación -->
      <div class="row justify-content-center">
        <div class="col-11 col-md-8 col-lg-5 col-xl-4">
          <!-- Card con efecto glassmorphism -->
          <div class="card card-action" style="cursor: default;">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <i class="bi bi-lock fs-1 text-warning"></i>
                <h4 class="mt-3 mb-2">¿Olvidaste tu contraseña?</h4>
                <p class="text-muted mb-0">
                  Ingresá tu correo electrónico y te enviaremos un enlace para que recuperes el acceso a tu cuenta.
                </p>
              </div>

              <form id="formForgot" class="needs-validation" novalidate>
                <!-- Correo electrónico -->
                <div class="mb-3">
                  <label for="inputEmail" class="form-label">Correo electrónico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="inputEmail"
                    name="email"
                    placeholder="tu@email.com"
                    required
                  />
                  <div class="invalid-feedback">Ingresá un email válido.</div>
                </div>

                <!-- Botón Recuperar -->
                <div class="d-grid mb-3">
                  <button type="submit" class="btn btn-success">
                    <i class="bi bi-envelope me-2"></i>Enviar enlace de recuperación
                  </button>
                </div>

                <!-- Link a login -->
                <div class="text-center">
                  <a href="<?= PAGE_LANDING_PHP ?>" class="text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i>Volver al inicio de sesión
                  </a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_FORGOT ?>"></script>
</body>
</html>