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
              <form id="formRegistroAdmin" class="needs-validation" novalidate>
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
                    />
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
                    />
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
                    />
                    <div class="invalid-feedback">El nombre de la cancha es obligatorio.</div>
                  </div>

                  <!-- DIRECCIÓN - País -->
                  <div class="col-12 col-md-6">
                    <label for="inputPais" class="form-label">País</label>
                    <select
                      class="form-select"
                      id="inputPais"
                      name="pais"
                      required
                    >
                      <option value="" selected disabled>Seleccionar país</option>
                      <option value="argentina">Argentina</option>
                      <option value="uruguay">Uruguay</option>
                      <option value="chile">Chile</option>
                      <option value="paraguay">Paraguay</option>
                      <option value="brasil">Brasil</option>
                    </select>
                    <div class="invalid-feedback">El país es obligatorio.</div>
                  </div>

                  <!-- DIRECCIÓN - Provincia -->
                  <div class="col-12 col-md-6">
                    <label for="inputProvincia" class="form-label">Provincia</label>
                    <select
                      class="form-select"
                      id="inputProvincia"
                      name="provincia"
                      required
                    >
                      <option value="" selected disabled>Seleccionar provincia</option>
                      <option value="buenos-aires">Buenos Aires</option>
                      <option value="caba">CABA</option>
                      <option value="cordoba">Córdoba</option>
                      <option value="santa-fe">Santa Fe</option>
                      <option value="mendoza">Mendoza</option>
                      <option value="otras">Otras</option>
                    </select>
                    <div class="invalid-feedback">La provincia es obligatoria.</div>
                  </div>

                  <!-- DIRECCIÓN - Localidad -->
                  <div class="col-12 col-md-6">
                    <label for="inputLocalidad" class="form-label">Localidad</label>
                    <select
                      class="form-select"
                      id="inputLocalidad"
                      name="localidad"
                      required
                    >
                      <option value="" selected disabled>Seleccionar localidad</option>
                      <option value="la-plata">La Plata</option>
                      <option value="berisso">Berisso</option>
                      <option value="ensenada">Ensenada</option>
                      <option value="city-bell">City Bell</option>
                      <option value="otras">Otras</option>
                    </select>
                    <div class="invalid-feedback">La localidad es obligatoria.</div>
                  </div>

                  <!-- DIRECCIÓN - Calle y Número -->
                  <div class="col-12 col-md-6">
                    <label for="inputCalle" class="form-label">Calle y número</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputCalle"
                      name="calle"
                      placeholder="Ej: Av. 7 N° 1234"
                      required
                    />
                    <div class="invalid-feedback">La calle y número son obligatorios.</div>
                  </div>

                  <!-- DIRECCIÓN - Detalle adicional -->
                  <div class="col-12">
                    <label for="inputDetalle" class="form-label">Detalle adicional (opcional)</label>
                    <input
                      type="text"
                      class="form-control"
                      id="inputDetalle"
                      name="detalle"
                      placeholder="Ej: Entre calles 50 y 51, al lado del polideportivo"
                    />
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
                      required
                    />
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
                      required
                    />
                    <div class="invalid-feedback">Ingresá un email válido.</div>
                  </div>

                  <!-- Preferencia de contacto -->
                  <div class="col-12 col-md-6">
                    <label for="inputContacto" class="form-label">Deseo que me contacten por</label>
                    <select
                      class="form-select"
                      id="inputContacto"
                      name="contacto"
                      required
                    >
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
                      required
                    >
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

                  <!-- Correos promocionales -->
                  <div class="col-12">
                    <div class="form-check">
                      <input
                        class="form-check-input"
                        type="checkbox"
                        id="checkPromociones"
                        name="promociones"
                      />
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
  <script src="<?= JS_REGISTRO_ADMIN_CANCHA ?>"></script>
</body>
</html>
