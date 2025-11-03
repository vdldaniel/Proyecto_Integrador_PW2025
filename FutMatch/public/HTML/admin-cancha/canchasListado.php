<!--
Listado inicial de canchas al ingresar a "Mis Canchas" en el panel de administración:
- [x] Tabla con listado de canchas (nombre, ubicación, tipo de superficie, capacidad)
- [x] Botón para ver perfil detallado de cada cancha
- [x] Botón para agregar nueva cancha
- Botón para cerrar sesión
-->

<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

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
      <div class="container-fluid mt-4 pt-4 pb-5">
        <!-- Header del body principal -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <button type="button" class="btn btn-outline-secondary" id="btnHistorialCanchas">
            <i class="bi bi-clock-history"></i> Ver historial de canchas
          </button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCancha">
            <i class="bi bi-plus-circle"></i> Agregar cancha
          </button>
        </div>

        <div class="card shadow-lg p-4 rounded-3">
          <div class="row">
            <div class="col-12 table-responsive">
              <h2 class="mb-4">Listado de Canchas</h2>
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Capacidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>1</td>
                    <td>La Previa Cancha C1-F5</td>
                    <td>Futbol 5</td>
                    <td><span class="badge bg-success">Habilitada</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-info" onclick="window.location.href='<?= PAGE_ADMIN_CANCHA_PERFIL ?>'">
                        <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                      </button>
                      <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditarCancha" data-cancha-id="1">
                        <i class="bi bi-pencil"></i><span class="d-none d-lg-inline ms-1">Editar</span>
                      </button>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCerrarCancha" data-cancha-id="1">
                        <i class="bi bi-pause-circle"></i><span class="d-none d-lg-inline ms-1">Cierre temp.</span>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCancha" data-cancha-id="1">
                        <i class="bi bi-trash"></i><span class="d-none d-lg-inline ms-1">Eliminar</span>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>2</td>
                    <td>MegaFutbol Cancha A2-F9</td>
                    <td>Futbol 9</td>
                    <td><span class="badge bg-warning text-dark">En revisión</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-info" onclick="window.location.href='<?= PAGE_ADMIN_CANCHA_PERFIL ?>'">
                        <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                      </button>
                      <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditarCancha" data-cancha-id="2">
                        <i class="bi bi-pencil"></i><span class="d-none d-lg-inline ms-1">Editar</span>
                      </button>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCerrarCancha" data-cancha-id="2">
                        <i class="bi bi-pause-circle"></i><span class="d-none d-lg-inline ms-1">Cierre temp.</span>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCancha" data-cancha-id="2">
                        <i class="bi bi-trash"></i><span class="d-none d-lg-inline ms-1">Eliminar</span>
                      </button>
                    </td>
                  </tr>
                  <tr>
                    <td>3</td>
                    <td>Cancha Delantera</td>
                    <td>Futbol 5</td>
                    <td><span class="badge bg-secondary">Pendiente de verificación</span></td>
                    <td>
                      <button class="btn btn-sm btn-outline-info" onclick="window.location.href='<?= PAGE_ADMIN_CANCHA_PERFIL ?>'">
                        <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver perfil</span>
                      </button>
                      <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditarCancha" data-cancha-id="3">
                        <i class="bi bi-pencil"></i><span class="d-none d-lg-inline ms-1">Editar</span>
                      </button>
                      <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCerrarCancha" data-cancha-id="3">
                        <i class="bi bi-pause-circle"></i><span class="d-none d-lg-inline ms-1">Cierre temp.</span>
                      </button>
                      <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminarCancha" data-cancha-id="3">
                        <i class="bi bi-trash"></i><span class="d-none d-lg-inline ms-1">Eliminar</span>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Agregar Cancha -->
      <div class="modal fade" id="modalAgregarCancha" tabindex="-1" aria-labelledby="modalAgregarCanchaLabel">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalAgregarCanchaLabel">Crear Cancha</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formAgregarCancha">
                <div class="row">
                  <!-- Nombre -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="nombreCancha" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombreCancha" required />
                  </div>
                  <!-- Tipo de superficie -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="tipoSuperficie" class="form-label">Tipo de superficie</label>
                    <select class="form-select" id="tipoSuperficie" required>
                      <option value="">Seleccionar...</option>
                      <option value="1">Sintético</option>
                      <option value="2">Cemento</option>
                      <option value="3">Parquet</option>
                      <option value="4">Césped natural</option>
                    </select>
                  </div>
                  <!-- Ubicación -->
                  <div class="mb-3 col-12">
                    <label for="ubicacionCancha" class="form-label">Ubicación</label>
                    <input type="text" class="form-control" id="ubicacionCancha" required />
                    <div class="alert alert-info mt-2 d-none" role="alert" id="alertUbicacionAgregar">
                      <i class="bi bi-info-circle"></i> La dirección ingresada será verificada por personal de FutMatch para asegurar la integridad de la aplicación.
                    </div>
                  </div>
                  <!-- Descripción -->
                  <div class="mb-3 col-12">
                    <label for="descripcionCancha" class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcionCancha" rows="3" placeholder="Describe las características de la cancha..."></textarea>
                  </div>
                  <!-- Capacidad -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="capacidadCancha" class="form-label">Tipo de cancha</label>
                    <select class="form-select" id="capacidadCancha" required>
                      <option value="">Seleccionar...</option>
                      <option value="1">Fútbol 5</option>
                      <option value="2">Fútbol 7</option>
                      <option value="3">Fútbol 9</option>
                      <option value="4">Fútbol 11</option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btnGuardarCancha">Crear cancha</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Editar Cancha -->
      <div class="modal fade" id="modalEditarCancha" tabindex="-1" aria-labelledby="modalEditarCanchaLabel">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalEditarCanchaLabel">Editar Cancha</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formEditarCancha">
                <input type="hidden" id="editCanchaId" />
                <div class="row">
                  <!-- Nombre -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="editNombreCancha" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="editNombreCancha" required />
                  </div>
                  <!-- Tipo de superficie -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="editTipoSuperficie" class="form-label">Tipo de superficie</label>
                    <select class="form-select" id="editTipoSuperficie" required>
                      <option value="1">Sintético</option>
                      <option value="2">Cemento</option>
                      <option value="3">Parquet</option>
                      <option value="4">Césped natural</option>
                    </select>
                  </div>
                  <!-- Ubicación -->
                  <div class="mb-3 col-12">
                    <label for="editUbicacionCancha" class="form-label">Ubicación</label>
                    <input type="text" class="form-control" id="editUbicacionCancha" required />
                    <div class="alert alert-info mt-2 d-none" role="alert" id="alertUbicacionEditar">
                      <i class="bi bi-info-circle"></i> Al cambiar la dirección, será verificada nuevamente por personal de FutMatch para asegurar la integridad de la aplicación.
                    </div>
                  </div>
                  <!-- Descripción -->
                  <div class="mb-3 col-12">
                    <label for="editDescripcionCancha" class="form-label">Descripción</label>
                    <textarea class="form-control" id="editDescripcionCancha" rows="3" placeholder="Describe las características de la cancha..."></textarea>
                  </div>
                  <!-- Capacidad -->
                  <div class="mb-3 col-12 col-lg-6">
                    <label for="editCapacidadCancha" class="form-label">Tipo de cancha</label>
                    <select class="form-select" id="editCapacidadCancha" required>
                      <option value="1">Fútbol 5</option>
                      <option value="2">Fútbol 7</option>
                      <option value="3">Fútbol 9</option>
                      <option value="4">Fútbol 11</option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-primary" id="btnActualizarCancha">Guardar cambios</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Cerrar Cancha Temporalmente -->
      <div class="modal fade" id="modalCerrarCancha" tabindex="-1" aria-labelledby="modalCerrarCanchaLabel">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCerrarCanchaLabel">Cerrar Cancha Temporalmente</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="formCerrarCancha">
                <input type="hidden" id="cerrarCanchaId" />
                <div class="mb-3">
                  <label for="fechaCierre" class="form-label">Fecha de cierre (hasta)</label>
                  <input type="date" class="form-control" id="fechaCierre" />
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cierreIndefinido" />
                    <label class="form-check-label" for="cierreIndefinido">
                      Cierre indefinido
                    </label>
                  </div>
                </div>
                <div class="mb-3">
                  <label for="mensajeCierre" class="form-label">Mensaje para usuarios (opcional)</label>
                  <textarea class="form-control" id="mensajeCierre" rows="3" maxlength="200" placeholder="Ej: En mantenimiento, regresamos pronto"></textarea>
                  <small class="text-muted">Este mensaje será visible para los usuarios al visualizar la cancha.</small>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-warning" id="btnConfirmarCierre">Cerrar cancha</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Eliminar Cancha -->
      <div class="modal fade" id="modalEliminarCancha" tabindex="-1" aria-labelledby="modalEliminarCanchaLabel">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title" id="modalEliminarCanchaLabel">
                <i class="bi bi-exclamation-triangle"></i> ¿Estás seguro?
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="deleteCanchaId" />
              <h6>Al eliminar esta cancha:</h6>
              <ul>
                <li>No aparecerá más en listados ni mapas</li>
                <li>Los partidos anteriores seguirán mostrando el nombre de la cancha en el historial de jugadores</li>
                <li>No podrás crear nuevas reservas en esta cancha</li>
              </ul>
              <div class="alert alert-warning">
                <strong>¿Prefieres suspenderla temporalmente?</strong>
                <p class="mb-2 mt-2">Si solo necesitas cerrar la cancha por un tiempo, considera suspenderla en lugar de eliminarla.</p>
                <button type="button" class="btn btn-sm btn-warning" id="btnSuspenderEnLugar">
                  <i class="bi bi-pause-circle"></i> Suspender temporalmente
                </button>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">Eliminar definitivamente</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Historial de Canchas -->
      <div class="modal fade" id="modalHistorialCanchas" tabindex="-1" aria-labelledby="modalHistorialCanchasLabel">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalHistorialCanchasLabel">
                <i class="bi bi-clock-history"></i> Historial de Canchas Eliminadas
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="table-responsive">
                <table class="table table-striped">
                  <thead class="table-dark">
                    <tr>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Tipo de Superficie</th>
                      <th>Capacidad</th>
                      <th>Fecha Eliminación</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>4</td>
                      <td>Cancha Eliminada Ejemplo</td>
                      <td>Sintético</td>
                      <td>Fútbol 7</td>
                      <td>15/03/2024</td>
                      <td>
                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalRestaurarCancha" data-cancha-id="4">
                          <i class="bi bi-arrow-clockwise"></i><span class="d-none d-lg-inline ms-1">Restaurar</span>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Restaurar Cancha -->
      <div class="modal fade" id="modalRestaurarCancha" tabindex="-1" aria-labelledby="modalRestaurarCanchaLabel">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="modalRestaurarCanchaLabel">
                <i class="bi bi-arrow-clockwise"></i> Restaurar Cancha
              </h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="restaurarCanchaId" />
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                <strong>La cancha será verificada nuevamente por el equipo de FutMatch</strong>
                <p class="mb-0 mt-2">Una vez restaurada, la cancha pasará a estado "Pendiente de verificación" hasta que nuestro equipo confirme la información.</p>
              </div>
              <p>¿Deseas continuar con la restauración?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-success" id="btnConfirmarRestaurar">Confirmar restauración</button>
            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= CSS_ICONS ?>">
    <!-- Scripts -->
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= JS_CANCHAS_LISTADO ?>"></script>
  </body>
</html>

