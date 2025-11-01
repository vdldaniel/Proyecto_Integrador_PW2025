<!--
Página principal (home).
Muestra el título, lema y las tarjetas para:
- Entrar como invitado
- Iniciar sesión o registrarse
También enlaza al formulario para inscribirse como admin. de canchas.
-->
<?php
require_once __DIR__ . '/../../../src/app/config.php';

// Iniciar sesión para mostrar errores de login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "FutMatch";

$page_css = [CSS_PAGES_LANDING];

include HEAD_COMPONENT;

?>

<body>
    <header class="hero bg-image" style="background-image: url('<?= IMG_BG2 ?>');">
        <div class="hero-overlay"></div>

        <div class="container position-relative">
            <!-- Logo - SVG -->
            <!-- <div class="text-center mb-3">[SVG LOGO]</div> -->

            <!-- Título y lema -->
            <div class="row justify-content-center text-center text-light">
                <div class="col-11 col-lg-9">
                    <h1 class="display-3 fw-800 brand-title mb-2">FutMatch</h1>
                    <p class="lead brand-tagline mb-5">
                        La pasión es la chispa que enciende el fuego del éxito
                    </p>
                </div>
            </div>

            <!-- Tarjetas de acción -->
            <div class="row g-4 justify-content-center">
                <!-- Card: Invitado -->
                <div class="col-11 col-md-5 col-lg-4">
                    <a href="<?= PAGE_INICIO_JUGADOR ?>" class="text-decoration-none">
                        <div class="card card-action h-100" role="button" tabindex="0" aria-label="Entrar como invitado">
                            <div class="card-body py-4">
                                <h2 class="h4 fw-600 mb-2">Entrar como invitado</h2>
                                <p class="text-muted mb-0">
                                    Explorá canchas y partidos sin registrarte. Ideal para una primera mirada rápida.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Card: Iniciar sesión / Registrarse (expandible) -->
                <div class="col-11 col-md-5 col-lg-4">
                    <div class="guest card card-action h-100" id="loginCard" role="button" tabindex="0"
                        aria-controls="loginCollapse" aria-expanded="false">
                        <div class="card-body py-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h2 class="h4 fw-600 mb-1">Iniciar sesión</h2>
                                    <p class="text-muted mb-0">O registrate en segundos para guardar tus partidos.</p>
                                </div>
                                <span class="chevron" aria-hidden="true">▾</span>
                            </div>

                            <!-- Mostrar error si existe -->
                            <?php if (isset($_SESSION['login_error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <?= htmlspecialchars($_SESSION['login_error']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <!-- Contenido expandible -->
                            <div class="collapse mt-3 <?= isset($_SESSION['login_error']) ? 'show' : '' ?>" id="loginCollapse">
                                <form id="loginForm" action="<?= CONTROLLER_LOGIN ?>" method="POST" novalidate>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            autocomplete="username" required 
                                            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
                                        <div class="invalid-feedback">Ingresá un email válido.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            autocomplete="current-password" required />
                                        <div class="invalid-feedback">La contraseña es obligatoria.</div>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
                                        </button>
                                        <a class="btn btn-outline-secondary"
                                            href="<?= PAGE_REGISTRO_JUGADOR_PHP ?>">
                                            <i class="bi bi-person-plus me-2"></i>Registrarme
                                        </a>
                                    </div>

                                    <div class="text-center mt-3">
                                        <a href="<?= PAGE_FORGOT_PHP ?>"
                                            class="small">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enlace discreto para gestores de canchas -->
                <div class="col-12 text-center">
                    <a href="<?= PAGE_REGISTRO_ADMIN_CANCHA_PHP ?>"
                        class="link-cancha">¿Sos dueño de una cancha? Te ayudamos a gestionarla</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_LANDING ?>"></script>
    
    <script>
        // Auto-expandir formulario si hay error de login
        <?php if (isset($_SESSION['login_error'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const loginCard = document.getElementById('loginCard');
                if (loginCard) {
                    loginCard.setAttribute('aria-expanded', 'true');
                }
                // Focus en el campo de email
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    emailInput.focus();
                }
            });
            <?php 
            // Limpiar el error después de mostrarlo
            unset($_SESSION['login_error']); 
            ?>
        <?php endif; ?>
    </script>
</body>

</html>