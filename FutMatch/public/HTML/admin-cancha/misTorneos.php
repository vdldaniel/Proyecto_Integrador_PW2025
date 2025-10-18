<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'misTorneos';
$page_title = "Torneos - FutMatch";
$page_css = [];


// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
  ?>

  <body>
    <div class="container-fluid pt-5 pb-5">
      <div class="container-fluid mt-5">
        <div class="card shadow-lg p-4 rounded-3">
          <div class="row">
            <div class="col-12 table-responsive">
              <h2 class="mb-4">Torneos activos</h2>
              <table class="table table-dark table-striped align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Fecha Inicio</th>
                    <th>Equipos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody id="torneos-list">
                  <tr>
                    <td>1</td>
                    <td>Torneo Apertura</td>
                    <td>10/10/2025</td>
                    <td>8</td>
                    <td class="estado">Cancelado</td>
                    <td>
                      <a href="" id="crearTorneoBtn" class="btn btn-warning btn-sm me-22">
                        <i class="bi"></i> Ver detalle
                      </a>
                      <button type="button" class="btn btn-danger btn-sm" onclick="cancelarTorneo()">Cancelar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>Copa FutMatch</td>
                    <td>11/10/2025</td>
                    <td>12</td>
                    <td class="estado">Inscripciones abiertas</td>
                    <td>
                      <a href="mis-torneos-detalle.html" id="crearTorneoBtn" class="btn btn-warning btn-sm me-22">
                        <i class="bi"></i> Ver detalle
                      </a>

                      <button type="button" class="btn btn-danger btn-sm" onclick="cancelarTorneo()">Cancelar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Torneo Interclubes</td>
                    <td>10/02/2026</td>
                    <td>10</td>
                    <td class="estado">Inscripciones cerradas</td>
                    <td>
                      <a href="" id="crearTorneoBtn" class="btn btn-warning btn-sm me-22">
                        <i class="bi"></i> Ver detalle
                      </a>
                      <button type="button" class="btn btn-danger btn-sm" onclick="cancelarTorneo()">Cancelar</button>
                    </td>
                  </tr>
                  <tr>
                    <td>1</td>
                    <td>Torneo Federal</td>
                    <td>04/11/2025</td>
                    <td>20</td>
                    <td class="estado">Cancelado</td>
                    <td>
                      <a href="" id="crearTorneoBtn" class="btn btn-warning btn-sm me-22">
                        <i class="bi"></i> Ver detalle
                      </a>
                      <button type="button" class="btn btn-danger btn-sm" onclick="cancelarTorneo()">Cancelar</button>
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="mb-3 d-flex justify-content-end">
                <a href="mis-torneos-crear.html" id="crearTorneoBtn" class="btn btn-success m-2">
                  <i class="bi"></i> Crear Torneo
                </a>
                <button class="btn btn-secondary m-2">Historial de Torneos</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Scripts -->
    <script src="src/scripts/pages/adminInc.js"></script>
    <script src="public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  </html>