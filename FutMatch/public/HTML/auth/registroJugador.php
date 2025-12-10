<!--
Página de registro de usuario (jugador).
Formulario para crear una cuenta con datos básicos:
- Nombre, apellido, username, fecha de nacimiento, género
- Email, teléfono, contraseña
- Checkboxes de términos y condiciones
-->
<?php
require_once __DIR__ . '/../../../src/app/config.php';

$page_title = "FutMatch - Registro";
$page_css = [SRC_PATH . "styles/pages/landing.css"];
include HEAD_COMPONENT;
?>

<title><?= $page_title ?></title>
</head>

<body>
  <header class="hero bg-image" style="background-image: url('<?= IMG_PATH ?>bg2.jpg');">
    <!-- Overlay más oscuro para el formulario de registro -->
    <div class="hero-overlay" style="background: rgba(0, 0, 0, 0.65);"></div>

    <div class="container position-relative">
      <!-- Título -->
      <div class="row justify-content-center text-center text-light mb-4">
        <div class="col-11 col-lg-9">
          <h1 class="display-4 fw-800 brand-title mb-2">FutMatch</h1>
          <p class="lead brand-tagline">¡Crea tu cuenta!</p>
        </div>
      </div>

      <!-- Formulario de registro -->
      <div class="row justify-content-center">
        <div class="col-11 col-md-9 col-lg-7 col-xl-6">
          <!-- Card con efecto glassmorphism -->
          <div class="card card-action" style="cursor: default;">
            <div class="card-body p-4">
              <form id="formRegistro" action="<?= CONTROLLER_REGISTRO_JUGADOR ?>" method="POST" class="needs-validation" novalidate>
                <div class="row g-3">
                  <!-- Nombre -->
                  <div class="col-12 col-md-6">
                    <label for="inputNombre" class="form-label">Nombre</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputNombre"
                      name="nombre"
                      required
                      value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                    />
                    <!--Para agregar una descripcion: -->

                    <div class="invalid-feedback">El nombre es obligatorio.</div>
                  </div>

                  <!-- Apellido -->
                  <div class="col-12 col-md-6">
                    <label for="inputApellido" class="form-label">Apellido</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputApellido"
                      name="apellido"
                      required
                      value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">El apellido es obligatorio.</div>
                  </div>

                  <!-- Nombre de usuario -->
                  <div class="col-12">
                    <label for="inputUsername" class="form-label">Nombre de usuario</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputUsername"
                      name="username"
                      required
                      value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">El nombre de usuario es obligatorio.</div>
                  </div>

                  <!-- Fecha de nacimiento -->
                  <div class="col-12 col-md-6">
                    <label for="inputFechaDeNacimiento" class="form-label">Fecha de nacimiento</label>
                    <input
                      type="date"
                      class="form-control"
                      id="inputFechaDeNacimiento"
                      name="fechaNacimiento"
                      required
                      value="<?= htmlspecialchars($_POST['fechaNacimiento'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">Debes ser mayor de 18 años.</div>
                  </div>

                  <!-- Género -->
                  <div class="col-12 col-md-6">
                    <label for="inputGenero" class="form-label">Género</label>
                    <select
                      class="form-select"
                      id="inputGenero"
                      name="genero"
                      required
                      value="<?= htmlspecialchars($_POST['genero'] ?? '') ?>"
                    >
                      <option value="" selected disabled>Seleccioná tu género</option>
                      <option value="2">Masculino</option>
                      <option value="1">Femenino</option>
                      <option value="3">Prefiero no decir</option>
                    </select>
                    <div class="invalid-feedback">El género es obligatorio.</div>
                  </div>

                  <!-- Correo electrónico -->
                  <div class="col-12">
                    <label for="inputEmail" class="form-label">Correo electrónico</label>
                    <input
                      type="email"
                      class="form-control"
                      id="inputEmail"
                      name="email"
                      placeholder="tu@email.com"
                      required
                      value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">Ingresá un email válido.</div>
                  </div>

                  <!-- Teléfono -->
                  <div class="col-12">
                    <label for="inputTelefono" class="form-label">Teléfono</label>
                    <input
                      type="tel"
                      class="form-control"
                      id="inputTelefono"
                      name="telefono"
                      placeholder="Ej: 11-1234-5678"
                      required
                      value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">Ingresá un teléfono válido (solo números y guiones).</div>
                  </div>

                  <!-- Contraseña -->
                  <div class="col-12 col-md-6">
                    <label for="inputPassword" class="form-label">Contraseña</label>
                    <input
                      type="password"
                      class="form-control"
                      id="inputPassword"
                      name="password"
                      placeholder="Mínimo 8 caracteres"
                      required
                      value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
                    />
                    <div class="invalid-feedback">
                      Mínimo 8 caracteres, una minúscula y un número.
                    </div>
                  </div>

                  <!-- Repetir contraseña -->
                  <div class="col-12 col-md-6">
                    <label for="inputPasswordConfirm" class="form-label">Repetir contraseña</label>
                    <input
                      type="password"
                      class="form-control"
                      id="inputPasswordConfirm"
                      name="passwordConfirm"
                      placeholder="Confirmá tu contraseña"
                      required
                    />
                    <div class="invalid-feedback">Las contraseñas no coinciden.</div>
                  </div>

                  <!-- Términos y condiciones -->
                  <div class="col-12">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="checkTerminos"
                        name="terminos"
                        required
                      />
                      <label class="form-check-label" for="checkTerminos">
                        Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> de FutMatch
                      </label>
                      <div class="invalid-feedback">
                        Debes aceptar los términos y condiciones.
                      </div>
                    </div>
                  </div>

                  <!-- Promociones -->
                  <div class="col-12">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="checkPromociones"
                        name="promociones"
                      />
                      <label class="form-check-label" for="checkPromociones">
                        Acepto recibir correos y promociones de FutMatch
                      </label>
                    </div>
                  </div>

                  <!-- Botón Registrarse -->
                  <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success w-100">
                      Registrarse
                    </button>
                  </div>

                  <!-- Link a login -->
                  <div class="col-12 text-center mt-2">
                    <p class="mb-0">
                      ¿Ya tenés cuenta? 
                      <a href="<?= PAGE_LANDING_PHP ?>" class="text-decoration-none">Iniciá sesión</a>
                    </p>
                  </div>
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
  <script src="<?= JS_REGISTRO_JUGADOR ?>"></script>
</body>
</html>
