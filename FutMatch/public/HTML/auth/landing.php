<!--
Página principal (home).
Muestra el título, lema y las tarjetas para:
- Entrar como invitado
- Iniciar sesión o registrarse
También enlaza al formulario para inscribirse como admin. de canchas.
-->
<?php
require_once __DIR__ . '/../../../src/app/config.php';

$page_title = "FutMatch";
$page_css = [SRC_PATH . "styles/pages/landing.css"];
include HEAD_COMPONENT;
?>

<title><?= $page_title ?></title>
</head>

<body>
    <header class="hero bg-image" style="background-image: url('<?= IMG_PATH ?>bg2.jpg');">
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
                    <div class="card card-action h-100" role="button" tabindex="0" aria-label="Entrar como invitado"
                        onclick="location.href='<?= PAGE_INICIO_GUEST ?>'"
                        onkeydown="if(event.key==='Enter'){location.href='<?= PAGE_INICIO_GUEST ?>'}">
                        <div class="card-body py-4">
                            <h2 class="h4 fw-600 mb-2">Entrar como invitado</h2>
                            <p class="text-muted mb-0">
                                Explorá canchas y partidos sin registrarte. Ideal para una primera mirada rápida.
                            </p>
                        </div>
                    </div>
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

                            <!-- Contenido expandible -->
                            <div class="collapse mt-3" id="loginCollapse">
                                <form id="loginForm" novalidate>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            autocomplete="username" required />
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
                                            Ingresar</button>
                                        <a class="btn btn-outline-secondary"
                                            href="<?= PAGE_REGISTRO_JUGADOR_PHP ?>">Registrarme</a>
                                    </div>

                                    <div class="text-center mt-3">
                                        <a href="<?= PAGE_FORGOT ?>"
                                            class="small">¿Olvidaste tu contraseña?</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Enlace discreto para gestores de canchas -->
                <div class="col-12 text-center">
                    <a href="<?= PAGE_REGISTER_ADMIN_CANCHA ?>"
                        class="link-cancha">¿Sos dueño de una cancha? Te ayudamos a gestionarla</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Scripts -->
    <script src="<?= JS_BOOTSTRAP ?>"></script>
    <script src="<?= JS_LANDING ?>"></script>
</body>

</html>