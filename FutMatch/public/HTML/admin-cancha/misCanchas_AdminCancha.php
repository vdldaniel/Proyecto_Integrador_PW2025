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
$page_css = [];


// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<body>
	<?php
	// Cargar navbar de admin cancha
	require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
	?>
	<!-- Contenido Principal -->
	<main class="container mt-4">
		<!-- Línea 1: Header con título y botones de navegación -->
		<div class="row mb-4 align-items-center">
			<div class="col-md-6">
				<h1 class="fw-bold mb-1">Mis Canchas</h1>
				<p class="text-muted mb-0">Gestiona tus canchas y agrega nuevas</p>
			</div>
			<div class="col-md-6 text-end">
				<button type="button" class="btn btn-dark me-2" id="btnHistorialCanchas" data-bs-toggle="modal" data-bs-target="#modalHistorialCanchas">
					<i class="bi bi-clock-history"></i> Historial de Canchas
				</button>
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarCancha">
					<i class="bi bi-plus-circle"></i> Agregar Cancha
				</button>
			</div>
		</div>

		<!-- Línea 2: Filtros y búsqueda -->
		<div class="row mb-4">
			<div class="col-12">
				<div class="input-group">
					<span class="input-group-text">
						<i class="bi bi-search"></i>
					</span>
					<input
						type="text"
						id="searchInput"
						class="form-control"
						placeholder="Buscar canchas por nombre, tipo o capacidad..." />
				</div>
			</div>
		</div>

		<!-- Lista de canchas -->
		<div id="canchasList" class="row g-3">
			<div class="row" id="canchasList"></div>
		</div>
	</main>

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
								<select class="form-select" id="tipoSuperficie" required></select>
							</div>
							<!-- Ubicación -->
							<div class="mb-3 col-12">
								<label for="ubicacionCancha" class="form-label">Ubicación de la cancha</label>
								<p class="text-muted small mb-2">
									Buscá la dirección o arrastrá el marcador en el mapa para indicar la ubicación exacta.
								</p>

								<!-- Buscador de dirección -->
								<div class="input-group mb-3">
									<input
										type="text"
										class="form-control"
										id="inputBuscadorDireccionAgregar"
										placeholder="Ej: Av. 7 1234, La Plata, Buenos Aires" />
									<button class="btn btn-dark" type="button" id="btnBuscarDireccionAgregar">
										<i class="bi bi-search"></i> Buscar
									</button>
								</div>

								<!-- Mapa -->
								<div id="mapAgregar" style="height: 400px; border-radius: 8px; margin-bottom: 1rem;"></div>

								<!-- Campo oculto para la dirección completa -->
								<input type="hidden" id="ubicacionCancha" name="direccion" required />

								<!-- Campos ocultos para coordenadas -->
								<input type="hidden" id="inputLatitudAgregar" name="latitud" required />
								<input type="hidden" id="inputLongitudAgregar" name="longitud" required />

								<!-- Dirección seleccionada (visible para el usuario) -->
								<div id="direccionSeleccionadaAgregar" class="alert alert-info d-none mt-2">
									<strong>Dirección seleccionada:</strong> <span id="textoDireccionAgregar"></span>
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
								<select class="form-select" id="capacidadCancha" required></select>
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
								<label for="editUbicacionCancha" class="form-label">Ubicación de la cancha</label>
								<p class="text-muted small mb-2">
									Buscá la dirección o arrastrá el marcador en el mapa para indicar la ubicación exacta.
								</p>

								<!-- Buscador de dirección -->
								<div class="input-group mb-3">
									<input
										type="text"
										class="form-control"
										id="inputBuscadorDireccionEditar"
										placeholder="Ej: Av. 7 1234, La Plata, Buenos Aires" />
									<button class="btn btn-dark" type="button" id="btnBuscarDireccionEditar">
										<i class="bi bi-search"></i> Buscar
									</button>
								</div>

								<!-- Mapa -->
								<div id="mapEditar" style="height: 400px; border-radius: 8px; margin-bottom: 1rem;"></div>

								<!-- Campo oculto para la dirección completa -->
								<input type="hidden" id="editUbicacionCancha" name="direccion" required />

								<!-- Campos ocultos para coordenadas -->
								<input type="hidden" id="inputLatitudEditar" name="latitud" required />
								<input type="hidden" id="inputLongitudEditar" name="longitud" required />

								<!-- Dirección seleccionada (visible para el usuario) -->
								<div id="direccionSeleccionadaEditar" class="alert alert-info d-none mt-2">
									<strong>Dirección seleccionada:</strong> <span id="textoDireccionEditar"></span>
								</div>
								<div class="alert alert-warning mt-2">
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
								<select class="form-select" id="editCapacidadCancha" required></select>
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
						<!-- <div class="mb-3">
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
						</div> -->
						<!-- <div class="mb-3">
							<label for="mensajeCierre" class="form-label">Mensaje para usuarios (opcional)</label>
							<textarea class="form-control" id="mensajeCierre" rows="3" maxlength="200" placeholder="Ej: En mantenimiento, regresamos pronto"></textarea>
							<small class="text-muted">Este mensaje será visible para los usuarios al visualizar la cancha.</small>
						</div> -->
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
							<tbody></tbody>
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
	<script>
		const BASE_URL = "<?= BASE_URL ?>";
		const GEOCODING_PROXY = '<?= CONTROLLER_GEOCODING_PROXY ?>';
	</script>
	<!-- Leaflet JS -->
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	<script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?= JS_CANCHAS_LISTADO ?>"></script>
	<div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

</body>

</html>