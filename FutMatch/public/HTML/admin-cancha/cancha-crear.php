<!--
- [x] Formulario para ingresar datos de la cancha (nombre, ubicación, tipo de superficie, capacidad)
- [] Validación de datos
- [x] Botón para guardar la nueva cancha
- [x] Botón para cancelar y volver al panel de administración
- [] Botón para cerrar sesión
- [] Mostrar mensaje de éxito o error al guardar la cancha (MODAL)
- [] Redirige al panel de administración tras guardar la cancha
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'misCanchas'; 

// CSS adicional específico de esta página
$page_title = "Crear Cancha - FutMatch";
$page_css = [

];


// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>
<body>
  <?php 
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT; 
  ?>
    <main>
      <div class="container py-5">
        <div class="card shadow border-0 rounded-4">
          <div class="card-body p-4">
            <h3 class="card-title mb-5 d-flex">Crear cancha</h3>
            <form class="formCanchaNueva" id="formCanchaNueva" action="">
              <div class="row">
                <!--Nombre-->
                <div class="mb-3 col-12 col-lg-6">
                  <label for="inputNombre" class="form-label">Nombre</label>
                  <input
                    type="text"
                    class="form-control"
                    id="inputNombre"
                    required
                  />
                  <!--Muestra el error-->
                  <p class="text-danger" id="errorNombre"></p>
                </div>
                <!--Tipo de superficie-->
                <div class="mb-3 col-12 col-lg-6">
                  <label for="inputSuperficie" class="form-label"
                    >Tipo de superficie</label
                  >
                  <select class="form-select" id="inputSuperficie" required>
                    <option selected>Seleccionar...</option>
                    <option value="1">Sintetico</option>
                    <option value="2">Cemento</option>
                    <option value="3">Parquet</option>
                    <option value="4">Cesped natural</option>
                  </select>
                  <!--Muestra el error-->
                  <p class="text-danger" id="errorSuperficie"></p>
                </div>
                <!--Ubicacion-->
                <div class="mb-3 col-12 col-lg-6">
                  <label for="inputUbicacion" class="form-label"
                    >Ubicacion</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    id="inputUbicacion"
                    required
                  />
                  <!--Muestra el error-->
                  <p class="text-danger" id="errorUbicacion"></p>
                </div>
                <!--Capacidad-->
                <div class="mb-3 col-12 col-lg-6">
                  <label for="inputCapacidad" class="form-label"
                    >Tipo de cancha</label
                  >
                  <select class="form-select" id="inputCapacidad" required>
                    <option selected>Seleccionar...</option>
                    <option value="1">Futbol 5</option>
                    <option value="2">Futbol 7</option>
                    <option value="3">Futbol 9</option>
                    <option value="4">Futbol 11</option>
                  </select>
                  <!--Muestra el error-->
                  <p class="text-danger" id="errorCapacidad"></p>
                </div>
                <!--Botones-->
                <div class="d-flex gap-2 justify-content-end">
                  <button type="reset" class="btn btn-danger">Cancelar</button>
                  <button type="submit" class="btn btn-primary" id="">
                    Crear cancha
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
    <!-- Modal exito o error -->
    <div
      class="modal fade"
      id="resultadoModal"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Formulario enviado</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Cerrar"
            ></button>
          </div>
          <div class="modal-body">
            <p id="resultadoTexto">¡Tu cancha fue creada correctamente!</p>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>