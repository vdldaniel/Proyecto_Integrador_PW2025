<!--
Listado inicial de canchas al ingresar a "Mis Canchas" en el panel de administración:
- [x] Tabla con listado de canchas (nombre, ubicación, tipo de superficie, capacidad)
- [x] Botón para ver perfil detallado de cada cancha
- [x] Botón para agregar nueva cancha
- Botón para cerrar sesión
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'misCanchas'; 
$page_title = "Listado de canchas - FutMatch";
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
      <div class="container-fluid mt-5 pt-5 pb-5">
        <div class="card shadow-lg p-4 rounded-3">
          <div class="row">
            <div class="col-12 table-responsive">
              <h2 class="mb-4">Listado de Canchas</h2>
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Tipo de Superficie</th>
                    <th>Capacidad</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>La Previa Cancha C1-F5</td>
                    <td>Cemento</td>
                    <td>Futbol 5</td>
                    <td>
                      <button class="btn btn-sm btn-info">Ver</button>
                      <button class="btn btn-sm btn-warning">Editar</button>
                      <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>MegaFutbol Cancha A2-F9</td>
                    <td>Sintetico</td>
                    <td>Futbol 9</td>
                    <td>
                      <button class="btn btn-sm btn-info">Ver</button>
                      <button class="btn btn-sm btn-warning">Editar</button>
                      <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Cancha Delantera</td>
                    <td>Parquet</td>
                    <td>Futbol 5</td>
                    <td>
                      <button class="btn btn-sm btn-info">Ver</button>
                      <button class="btn btn-sm btn-warning">Editar</button>
                      <button class="btn btn-sm btn-danger">Eliminar</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="d-flex gap-2 justify-content-end">
                <a href="<?= PAGE_ADMIN_CANCHA_CREAR ?>">
                  <button type="button" class="btn btn-primary" id="">
                    Agregar cancha
                  </button></a
                >
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Scripts -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
  </body>
</html>

