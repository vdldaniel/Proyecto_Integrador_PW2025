<?php

/**
 * NAVBAR ADMIN_CANCHA - Componente de navegación para administradores de canchas
 * Incluye botones: Logo, 'Home', 'Agenda', 'Mis Canchas', 
 *                  'Mis Torneos', Notificaciones, Perfil
 * Soporte para página activa mediante la variable $current_page
 * Requiere: config.php cargado previamente
 */

// Determinar página activa (si no está definida, intentar detectarla)
if (!isset($current_page)) {
	$current_page = basename($_SERVER['PHP_SELF'], '.php');
}

// Helper function para clases activas
function isActive($page_name, $current)
{
	return ($page_name === $current) ? 'active' : '';
}
?>
<!-- Navbar Jugador -->
<header>
	<nav id="navbarFutmatch" class="navbar navbar-expand-lg navbar-dark bg-dark text-white sticky-top border-bottom">
		<div class="container-fluid">
			<!-- Lado izquierdo: menú hamburguesa + título + botones navegación -->
			<div class="d-flex align-items-center">
				<button class="btn btn-dark m-3 d-lg-none"
					type="button" data-bs-toggle="offcanvas"
					data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
					<i class="bi bi-list text-white"></i>
				</button>
				<a class="navbar-brand d-flex align-items-center" href="<?= PAGE_INICIO_ADMIN_CANCHA ?>">
					<img class="logo me-3" src="<?= IMG_LOGO_SINFONDO ?>" alt="FutMatch Logo" />
					<span class="brand-text">FutMatch</span>
				</a>
				<!--Botones que llevan a otras secciones del programa-->
				<div class="d-flex align-items-center d-none d-md-flex ms-3">
					<a href="<?= PAGE_INICIO_ADMIN_CANCHA ?>"
						class="btn btn-dark me-2 <?= isActive('inicioAdminCancha', $current_page) ?>"
						id="home"
						title="Home">
						<i class="bi bi-house-door"></i>
						<span class="d-none d-lg-inline ms-1">Home</span>
					</a>
					<a href="<?= PAGE_AGENDA ?>"
						class="btn btn-dark me-2 <?= isActive('agenda', $current_page) ?>"
						id="botonAgenda"
						title="Agenda">
						<i class="bi bi-calendar-event"></i>
						<span class="d-none d-lg-inline ms-1">Agenda</span>
					</a>
					<a href="<?= PAGE_MIS_CANCHAS_ADMIN_CANCHA ?>"
						class="btn btn-dark me-2 <?= isActive('misCanchas', $current_page) ?>"
						id="botonMisCanchas"
						title="Mis Canchas">
						<i class="bi bi-geo-alt-fill"></i>
						<span class="d-none d-lg-inline ms-1">Mis Canchas</span>
					</a>
					<a href="<?= PAGE_PERFILES_ADMIN_CANCHA ?>"
						class="btn btn-dark me-2 <?= isActive('canchaPerfil', $current_page) ?>"
						id="botonPerfiles"
						title="Perfiles">
						<i class="bi bi-person-circle"></i>
						<span class="d-none d-lg-inline ms-1">Perfiles</span>
					</a>
					<a href="<?= PAGE_MIS_TORNEOS_ADMIN_CANCHA ?>"
						class="btn btn-dark me-2 <?= isActive('misTorneos', $current_page) ?>"
						id="botonMisTorneos"
						title="Mis Torneos">
						<i class="bi bi-trophy-fill"></i>
						<span class="d-none d-lg-inline ms-1">Mis Torneos</span>
					</a>
				</div>
			</div>

			<!-- Lado derecho: perfil, notificaciones y configuración -->
			<div class="d-flex align-items-center">
				<!-- Campanita de notificaciones -->
				<button class="btn btn-outline-warning position-relative me-2"
					type="button" data-bs-toggle="modal" data-bs-target="#modalNotificaciones"
					title="Notificaciones">
					<i class="bi bi-bell"></i>
					<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
						2
					</span>
				</button>
				<!-- Botones solo en pantallas medianas y grandes -->
				<div class="d-none-custom d-md-flex align-items-center">
					<button id="botonConfiguracion" class="btn btn-dark me-2" type="button"
						data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
						<i class="bi bi-gear"></i>
					</button>
					<a href="<?= CONTROLLER_LOGOUT ?>" class="btn btn-danger me-2 d-none d-lg-flex" id="btnCerrarSesion"
						title="Cerrar Sesión">
						<i class="bi bi-box-arrow-right text-white"></i>
					</a>
				</div>
			</div>
		</div>
	</nav>
</header>

<!-- Menú lateral deslizable para pantallas medianas y menores -->
<div class="offcanvas offcanvas-start"
	tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="sidebarMenuLabel">Menú</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body">
		<!-- Navegación principal -->
		<div class="d-grid gap-2 mb-4">
			<a href="<?= PAGE_INICIO_ADMIN_CANCHA ?>"
				class="btn btn-dark text-start <?= isActive('inicioAdminCancha', $current_page) ?>"
				title="Home">
				<i class="bi bi-house-door me-2"></i>Home
			</a>
			<a href="<?= PAGE_AGENDA ?>"
				class="btn btn-dark text-start <?= isActive('agenda', $current_page) ?>"
				title="Agenda">
				<i class="bi bi-calendar-event me-2"></i>Agenda
			</a>
			<a href="<?= PAGE_MIS_CANCHAS_ADMIN_CANCHA ?>"
				class="btn btn-dark text-start <?= isActive('misCanchas', $current_page) ?>"
				title="Mis Canchas">
				<i class="bi bi-geo-alt-fill me-2"></i>Mis Canchas
			</a>
			<a href="<?= PAGE_MIS_TORNEOS_ADMIN_CANCHA ?>"
				class="btn btn-dark text-start <?= isActive('misTorneos', $current_page) ?>"
				title="Mis Torneos">
				<i class="bi bi-trophy-fill me-2"></i>Mis Torneos
			</a>
			<a href="<?= PAGE_PERFILES_ADMIN_CANCHA ?>"
				class="btn btn-dark text-start <?= isActive('canchaPerfil', $current_page) ?>"
				title="Perfiles">
				<i class="bi bi-person-circle me-2"></i>Perfiles
			</a>
		</div>

		<!-- Perfil y configuración -->
		<div class="mt-auto pt-3 border-top">
			<div class="d-grid gap-2">
				<button class="btn btn-dark text-start" type="button"
					data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
					<i class="bi bi-gear me-2"></i>Configuración
				</button>
				<a href="<?= CONTROLLER_LOGOUT ?>" class="btn btn-danger text-start"
					title="Cerrar Sesión">
					<i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
				</a>
			</div>
		</div>
	</div>
</div>
<!-- ============================================ -->
<!-- MODAL: NOTIFICACIONES -->
<!-- ============================================ -->
<div class="modal fade" id="modalNotificaciones" tabindex="-1" aria-labelledby="modalNotificacionesLabel">
	<div class="modal-dialog modal-lg modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalNotificacionesLabel">
					<i class="bi bi-bell me-2"></i>Notificaciones
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Tabs de navegación -->
				<ul class="nav nav-tabs mb-3" id="notificacionesTabs" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab-agenda" data-bs-toggle="tab" data-bs-target="#content-agenda" type="button" role="tab">
							<i class="bi bi-calendar-event me-1"></i>Agenda
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-torneos" data-bs-toggle="tab" data-bs-target="#content-torneos" type="button" role="tab">
							<i class="bi bi-trophy me-1"></i>Torneos
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-futmatch" data-bs-toggle="tab" data-bs-target="#content-futmatch" type="button" role="tab">
							<i class="bi bi-info-circle me-1"></i>FutMatch
						</button>
					</li>
				</ul>

				<!-- Contenido de las tabs -->
				<div class="tab-content" id="notificacionesTabContent">
					<!-- TAB 1: AGENDA -->
					<div class="tab-pane fade show active" id="content-agenda" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Solicitudes de Reserva</h6>
							<a href="<?= PAGE_AGENDA ?>" class="btn btn-sm btn-outline-primary">
								<i class="bi bi-calendar-event me-1"></i>Ver Agenda Completa
							</a>
						</div>

						<!-- Tabla scrolleable de solicitudes -->
						<div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col">Nombre</th>
										<th scope="col">Cancha</th>
										<th scope="col">Fecha</th>
										<th scope="col">Hora</th>
										<th scope="col">Acciones</th>
									</tr>
								</thead>
								<tbody>
									<!-- Solicitud 1 -->
									<tr>
										<td>
											<div>Juan Pérez</div>
											<button class="btn btn-sm btn-link p-0 text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detalle1">
												<small>Ver detalle</small>
											</button>
											<div class="collapse mt-1" id="detalle1">
												<small class="text-muted"><i class="bi bi-phone me-1"></i>+54 9 11 1234-5678</small>
											</div>
										</td>
										<td>Cancha 1</td>
										<td>15/10/2025</td>
										<td>18:00</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
										</td>
									</tr>
									<!-- Solicitud 2 -->
									<tr>
										<td>
											<div>María González</div>
											<button class="btn btn-sm btn-link p-0 text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detalle2">
												<small>Ver detalle</small>
											</button>
											<div class="collapse mt-1" id="detalle2">
												<small class="text-muted"><i class="bi bi-phone me-1"></i>+54 9 11 8765-4321</small>
											</div>
										</td>
										<td>Cancha 2</td>
										<td>16/10/2025</td>
										<td>20:00</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
										</td>
									</tr>
									<!-- Solicitud 3 -->
									<tr>
										<td>
											<div>Carlos Ruiz</div>
											<button class="btn btn-sm btn-link p-0 text-primary" type="button" data-bs-toggle="collapse" data-bs-target="#detalle3">
												<small>Ver detalle</small>
											</button>
											<div class="collapse mt-1" id="detalle3">
												<small class="text-muted"><i class="bi bi-phone me-1"></i>+54 9 11 5555-6666</small>
											</div>
										</td>
										<td>Cancha 3</td>
										<td>17/10/2025</td>
										<td>19:30</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TAB 2: TORNEOS -->
					<div class="tab-pane fade" id="content-torneos" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Solicitudes de Torneos</h6>
							<a href="<?= PAGE_MIS_TORNEOS_ADMIN_CANCHA ?>" class="btn btn-sm btn-outline-primary">
								<i class="bi bi-trophy me-1"></i>Ver Mis Torneos
							</a>
						</div>

						<!-- Tabla de solicitudes de torneos -->
						<div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col">Equipo</th>
										<th scope="col">Torneo</th>
										<th scope="col">Perfil</th>
										<th scope="col">Acciones</th>
									</tr>
								</thead>
								<tbody>
									<!-- Solicitud 1 -->
									<tr>
										<td>Los Tigres FC</td>
										<td>Copa Primavera 2025</td>
										<td>
											<a href="#" class="btn btn-sm btn-outline-info">
												<i class="bi bi-eye me-1"></i>Ver Equipo
											</a>
										</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
										</td>
									</tr>
									<!-- Solicitud 2 -->
									<tr>
										<td>Águilas United</td>
										<td>Liga Nocturna</td>
										<td>
											<a href="#" class="btn btn-sm btn-outline-info">
												<i class="bi bi-eye me-1"></i>Ver Equipo
											</a>
										</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<!-- ============================================ -->
<!-- MODAL: CONFIGURACIÓN DE CUENTA -->
<!-- ============================================ -->
<div class="modal fade" id="modalConfiguracion" tabindex="-1" aria-labelledby="modalConfiguracionLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-config">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalConfiguracionLabel">
					<i class="bi bi-gear me-2"></i>Configuración de Cuenta
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Información de cuenta -->
				<h6 class="border-bottom pb-2 mb-3">Información de Cuenta</h6>

				<!-- Nombre y Apellido -->
				<div class="mb-3">
					<label class="form-label">Nombre y Apellido</label>
					<div class="input-group">
						<input type="text" class="form-control" value="Juan Carlos Administrador" readonly>
					</div>
				</div>

				<!-- Teléfono -->
				<div class="mb-3">
					<label class="form-label">Teléfono</label>
					<div class="input-group">
						<input type="tel" class="form-control" id="inputTelefono" value="+54 9 11 1234-5678" readonly>
						<button class="btn btn-outline-primary" type="button" onclick="editarCampo('inputTelefono')">
							<i class="bi bi-pencil"></i>
						</button>
					</div>
				</div>

				<!-- Email -->
				<div class="mb-3">
					<label class="form-label">Dirección de E-mail</label>
					<div class="input-group">
						<input type="email" class="form-control" id="inputEmail" value="admin@futmatch.com" readonly>
						<button class="btn btn-outline-primary" type="button" onclick="editarCampo('inputEmail')">
							<i class="bi bi-pencil"></i>
						</button>
					</div>
				</div>

				<hr class="my-4">

				<!-- Suspender y Eliminar cuenta en fila -->
				<div class="row g-3">
					<!-- Suspender cuenta -->
					<div class="col-12 col-lg-6">
						<h6 class="mb-2">Suspender Cuenta</h6>
						<button class="btn btn-warning w-100" type="button" data-bs-toggle="collapse" data-bs-target="#suspenderForm">
							<i class="bi bi-pause-circle me-2"></i>Suspender Temporalmente
						</button>

						<!-- Formulario de suspensión -->
						<div class="collapse mt-3" id="suspenderForm">
							<div class="card card-body">
								<p class="small text-muted mb-3">
									Suspende temporalmente su cuenta y la agenda de sus canchas hasta una fecha determinada.
									Las reservas dentro del rango serán canceladas automáticamente y los usuarios serán notificados.
								</p>
								<div class="mb-3">
									<label class="form-label">Fecha de reactivación</label>
									<input type="date" class="form-control" id="fechaReactivacion">
								</div>
								<div class="mb-3">
									<label class="form-label">Hora de reactivación</label>
									<input type="time" class="form-control" id="horaReactivacion">
								</div>
								<button class="btn btn-warning" type="button" onclick="confirmarSuspension()">
									<i class="bi bi-check-lg me-2"></i>Confirmar Suspensión
								</button>
							</div>
						</div>
					</div>

					<!-- Eliminar cuenta -->
					<div class="col-12 col-lg-6">
						<h6 class="mb-2 text-danger">Eliminar Cuenta</h6>
						<button class="btn btn-danger w-100" type="button" data-bs-toggle="collapse" data-bs-target="#eliminarForm">
							<i class="bi bi-trash me-2"></i>Eliminar Permanentemente
						</button>

						<!-- Información de eliminación -->
						<div class="collapse mt-3" id="eliminarForm">
							<div class="card card-body border-danger">
								<p class="small text-muted mb-3">
									Una vez eliminada la cuenta, las reservas próximas serán canceladas.
									Su información de contacto será eliminada y sus canchas dejarán de aparecer en el sistema.
									El historial de partidos y torneos seguirá visible en el historial de los usuarios.
								</p>
								<button class="btn btn-danger" type="button" onclick="confirmarEliminacion()">
									<i class="bi bi-exclamation-triangle me-2"></i>Confirmar Eliminación
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>

<script>
	// Inicializar tooltips de Bootstrap
	document.addEventListener('DOMContentLoaded', function() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
		var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
			return new bootstrap.Tooltip(tooltipTriggerEl);
		});
	});

	// Función para editar campos
	function editarCampo(inputId) {
		const input = document.getElementById(inputId);
		if (input.hasAttribute('readonly')) {
			input.removeAttribute('readonly');
			input.focus();
			input.select();
		} else {
			input.setAttribute('readonly', 'readonly');
			// TODO: Aquí iría la llamada AJAX para guardar los cambios
			alert('Cambios guardados correctamente');
		}
	}

	// Función para confirmar suspensión
	function confirmarSuspension() {
		const fecha = document.getElementById('fechaReactivacion').value;
		const hora = document.getElementById('horaReactivacion').value;

		if (!fecha || !hora) {
			alert('Por favor, complete la fecha y hora de reactivación');
			return;
		}

		if (confirm('¿Está seguro que desea suspender su cuenta hasta el ' + fecha + ' a las ' + hora + '? Las reservas en este período serán canceladas.')) {
			// TODO: Llamada AJAX para suspender cuenta
			alert('Cuenta suspendida exitosamente');
			location.reload();
		}
	}

	// Función para confirmar eliminación
	function confirmarEliminacion() {
		if (confirm('¿Está seguro que desea eliminar su cuenta permanentemente? Esta acción NO se puede deshacer.')) {
			if (confirm('ÚLTIMA CONFIRMACIÓN: ¿Realmente desea eliminar su cuenta? Todas sus canchas serán removidas del sistema.')) {
				// TODO: Llamada AJAX para eliminar cuenta
				alert('Su cuenta ha sido marcada para eliminación. Recibirá un correo de confirmación.');
				window.location.href = '<?= PAGE_LANDING_PHP ?>';
			}
		}
	}

	// Función para eliminar notificación
	function eliminarNotificacion(button) {
		const listItem = button.closest('.list-group-item');
		if (confirm('¿Desea eliminar esta notificación?')) {
			// TODO: Llamada AJAX para eliminar notificación
			listItem.style.transition = 'opacity 0.3s ease';
			listItem.style.opacity = '0';
			setTimeout(() => {
				listItem.remove();
			}, 300);
		}
	}
</script>