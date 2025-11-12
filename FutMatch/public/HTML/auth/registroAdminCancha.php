<!--
Formulario de inscripción para gestores de canchas.
Solicitud de registro que será revisada por administradores del sistema.
Incluye datos personales, de la cancha, dirección detallada y preferencias de contacto.
-->
<?php
require_once __DIR__ . '/../../../src/app/config.php';

$page_title = "FutMatch - Registro Admin Cancha";
$page_css = [SRC_PATH . "styles/pages/landing.css"];
include HEAD_COMPONENT;
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
          <p class="lead brand-tagline">Registrá tu cancha</p>
        </div>
      </div>

      <!-- Formulario de registro -->
      <div class="row justify-content-center">
        <div class="col-11 col-md-10 col-lg-8 col-xl-7">
          <!-- Card con efecto glassmorphism -->
          <div class="card card-action" style="cursor: default;">
            <div class="card-body p-4">
              <form id="formRegistroAdmin" action="<?= CONTROLLER_REGISTRO_ADMIN_CANCHA ?>" method="POST" class="needs-validation" novalidate>
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
                      value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" />
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
                      value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" />
                    <div class="invalid-feedback">El apellido es obligatorio.</div>
                  </div>

                  <!-- Divisor -->
                  <div class="col-12">
                    <hr class="form-divider my-4">
                  </div>

                  <!-- Sección: Información de la cancha -->
                  <div class="col-12">
                    <h5 class="mb-2">Información de la cancha</h5>
                    <p class="text-muted fst-italic small mb-3">
                      Requerimos esta información para verificar tu cancha. Si administrás más de una sede,
                      podrás registrarlas una vez que tu cuenta sea verificada.
                    </p>
                  </div>

                  <!-- Nombre de la cancha/predio -->
                  <div class="col-12">
                    <label for="inputNombreCancha" class="form-label">Nombre de la cancha o predio</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputNombreCancha"
                      name="nombreCancha"
                      placeholder="Ej: Complejo Deportivo Las Palmeras"
                      required
                      value="<?= htmlspecialchars($_POST['nombreCancha'] ?? '') ?>" />
                    <div class="invalid-feedback">El nombre de la cancha es obligatorio.</div>
                  </div>

                  <!-- UBICACIÓN CON MAPA -->
                  <div class="col-12">
                    <label for="inputDireccion" class="form-label">Ubicación de la cancha</label>
                    <p class="text-muted small mb-2">
                      Buscá la dirección o arrastrá el marcador en el mapa para indicar la ubicación exacta.
                    </p>

                    <!-- Buscador de dirección -->
                    <div class="input-group mb-3">
                      <input
                        type="text"
                        class="form-control"
                        id="inputBuscadorDireccion"
                        placeholder="Ej: Av. 7 1234, La Plata, Buenos Aires" />
                      <button class="btn btn-dark" type="button" id="btnBuscarDireccion">
                        <i class="bi bi-search"></i> Buscar
                      </button>
                    </div>

                    <!-- Mapa -->
                    <div id="map" style="height: 400px; border-radius: 8px; margin-bottom: 1rem;"></div>

                    <!-- Campo oculto para la dirección completa -->
                    <input type="hidden" id="inputDireccion" name="direccion" required />

                    <!-- Campos ocultos para coordenadas -->
                    <input type="hidden" id="inputLatitud" name="latitud" required />
                    <input type="hidden" id="inputLongitud" name="longitud" required />

                    <!-- Campos opcionales para país, provincia, localidad (extraídos del geocoding) -->
                    <input type="hidden" id="inputPais" name="pais" />
                    <input type="hidden" id="inputProvincia" name="provincia" />
                    <input type="hidden" id="inputLocalidad" name="localidad" />

                    <!-- Mensaje de validación -->
                    <div class="invalid-feedback" id="errorDireccion">
                      Debes seleccionar una ubicación en el mapa.
                    </div>

                    <!-- Dirección seleccionada (visible para el usuario) -->
                    <div id="direccionSeleccionada" class="alert alert-info d-none mt-2">
                      <strong>Dirección seleccionada:</strong> <span id="textoDireccion"></span>
                    </div>
                  </div>

                  <!-- Divisor -->
                  <div class="col-12">
                    <hr class="form-divider my-4">
                  </div>

                  <!-- Sección: Información de contacto -->
                  <div class="col-12">
                    <h5 class="mb-2">Información de contacto</h5>
                    <p class="text-muted fst-italic small mb-3">
                      Te contactaremos para verificar tu cuenta e introducirte en la propuesta de FutMatch.
                    </p>
                  </div>

                  <!-- Teléfono -->
                  <div class="col-12 col-md-6">
                    <label for="inputTelefono" class="form-label">Teléfono</label>
                    <input
                      type="tel"
                      class="form-control"
                      id="inputTelefono"
                      name="telefono"
                      placeholder="Ej: 221-456-7890"
                      required />
                    <div class="invalid-feedback">Ingresá un teléfono válido (solo números y guiones).</div>
                  </div>

                  <!-- Correo electrónico -->
                  <div class="col-12 col-md-6">
                    <label for="inputEmail" class="form-label">Correo electrónico</label>
                    <input
                      type="email"
                      class="form-control"
                      id="inputEmail"
                      name="email"
                      placeholder="tu@email.com"
                      required />
                    <div class="invalid-feedback">Ingresá un email válido.</div>
                  </div>

                  <!-- Preferencia de contacto -->
                  <div class="col-12 col-md-6">
                    <label for="inputContacto" class="form-label">Deseo que me contacten por</label>
                    <select
                      class="form-select"
                      id="inputContacto"
                      name="contacto"
                      required>
                      <option value="" selected disabled>Seleccionar método</option>
                      <option value="whatsapp">WhatsApp</option>
                      <option value="mail">Mail</option>
                      <option value="telefono">Teléfono</option>
                    </select>
                    <div class="invalid-feedback">Seleccioná un método de contacto.</div>
                  </div>

                  <!-- Horario de preferencia -->
                  <div class="col-12 col-md-6">
                    <label for="inputHorario" class="form-label">Horario de preferencia</label>
                    <select
                      class="form-select"
                      id="inputHorario"
                      name="horario"
                      required>
                      <option value="" selected disabled>Seleccionar horario</option>
                      <option value="manana">Mañana (08:00 - 12:00)</option>
                      <option value="tarde">Tarde (12:00 - 18:00)</option>
                    </select>
                    <div class="invalid-feedback">Seleccioná un horario de preferencia.</div>
                  </div>

                  <!-- Divisor -->
                  <div class="col-12">
                    <hr class="form-divider my-4">
                  </div>

                  <!-- Términos y condiciones -->
                  <div class="col-12">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="checkTerminos"
                        name="terminos"
                        required />
                      <label class="form-check-label" for="checkTerminos">
                        Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> de FutMatch
                      </label>
                      <div class="invalid-feedback">
                        Debes aceptar los términos y condiciones.
                      </div>
                    </div>
                  </div>

                  <!-- Correos promocionales -->
                  <div class="col-12">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="checkPromociones"
                        name="promociones" />
                      <label class="form-check-label" for="checkPromociones">
                        Acepto recibir correos promocionales de FutMatch
                      </label>
                    </div>
                  </div>

                  <!-- Botón Solicitar Registro -->
                  <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-success w-100">
                      Solicitar Registro
                    </button>
                  </div>

                  <!-- Link a landing -->
                  <div class="col-12 text-center mt-2">
                    <p class="mb-0">
                      <a href="<?= PAGE_LANDING_PHP ?>" class="text-decoration-none">Volver al inicio</a>
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
  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="<?= JS_REGISTRO_ADMIN_CANCHA ?>"></script>
</body>

</html>