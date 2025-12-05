<?php

/**
 * NAVBAR JUGADOR - Componente de navegación para jugadores
 * Incluye: Logo, menú principal, dropdown explorar, notificaciones, perfil
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

<script>
	const GET_EQUIPOS_JUGADOR = '<?= GET_EQUIPOS_JUGADOR ?>';
	const UPDATE_EQUIPO_JUGADOR = '<?= UPDATE_EQUIPO_JUGADOR ?>';
	const UPDATE_JUGADORES_EQUIPOS = '<?= UPDATE_JUGADORES_EQUIPOS ?>';
</script>
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
				<a class="navbar-brand d-flex align-items-center" href="<?= PAGE_INICIO_JUGADOR ?>">
					<img class="logo me-3" src="<?= IMG_LOGO_SINFONDO ?>" alt="FutMatch Logo" />
					<span class="brand-text">FutMatch</span>
				</a>
				<!--Botones que llevan a otras secciones del programa-->
				<div class="d-flex align-items-center d-none d-md-flex ms-3">
					<a href="<?= PAGE_INICIO_JUGADOR ?>"
						class="btn btn-dark me-2 <?= isActive('inicioJugador', $current_page) ?>"
						id="home"
						title="Home">
						<i class="bi bi-house-door"></i>
						<span class="d-none d-lg-inline ms-1">Home</span>
					</a>
					<a href="<?= PAGE_MIS_PARTIDOS_JUGADOR ?>"
						class="btn btn-dark me-2 <?= isActive('partidosJugador', $current_page) ?>"
						id="botonMisPartidos"
						title="Mis Partidos">
						<i class="bi bi-calendar-event"></i>
						<span class="d-none d-lg-inline ms-1">Mis Partidos</span>
					</a>
					<!-- Dropdown Explorar -->
					<div class="dropdown">
						<button class="btn btn-dark dropdown-toggle me-2" type="button" id="dropdownExplorar"
							data-bs-toggle="dropdown" aria-expanded="false" title="Explorar">
							<i class="bi bi-search"></i>
							<span class="d-none d-lg-inline ms-1">Explorar</span>
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownExplorar">
							<li><a class="dropdown-item" href="<?= PAGE_CANCHAS_EXPLORAR_JUGADOR ?>">
									<i class="bi bi-geo-alt me-2"></i>Explorar canchas
								</a>
							</li>
							<li><a class="dropdown-item" href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>">
									<i class="bi bi-people me-2"></i>Explorar partidos
								</a>
							</li>
							<li><a class="dropdown-item" href="<?= PAGE_TORNEOS_EXPLORAR_JUGADOR ?>">
									<i class="bi bi-trophy me-2"></i>Explorar torneos
								</a>
							</li>
						</ul>
					</div>
					<a href="<?= PAGE_MIS_EQUIPOS_JUGADOR ?>"
						class="btn btn-dark me-2 <?= isActive('equiposListado', $current_page) ?>"
						id="botonMiEquipo"
						title="Mis Equipos">
						<i class="bi bi-people"></i>
						<span class="d-none d-lg-inline ms-1">Mis Equipos</span>
					</a>
					<a href="<?= PAGE_MIS_TORNEOS_JUGADOR ?>"
						class="btn btn-dark me-2 <?= isActive('torneosJugador', $current_page) ?>"
						id="botonMisTorneos"
						title="Mis Torneos">
						<i class="bi bi-trophy"></i>
						<span class="d-none d-lg-inline ms-1">Mis Torneos</span>
					</a>
				</div>
			</div>

			<!-- Lado derecho: perfil, notificaciones y configuración -->
			<div class="d-flex align-items-center">
				<!-- Botón Mi Perfil -->
				<a href="<?= PAGE_MI_PERFIL_JUGADOR ?>"
					class="btn btn-dark me-2 d-none d-md-flex <?= isActive('miPerfil', $current_page) ?>"
					id="botonMiPerfil"
					title="Mi Perfil">
					<i class="bi bi-person-circle"></i>
					<span class="d-none d-lg-inline ms-1">Mi Perfil</span>
				</a>
				<!-- Campanita de notificaciones -->
				<button class="btn btn-dark position-relative me-2"
					type="button" data-bs-toggle="modal" data-bs-target="#modalNotificaciones"
					title="Notificaciones">
					<i class="bi bi-bell"></i>
					<!--<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
						2
					</span>-->
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
			<a href="<?= PAGE_INICIO_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('inicioJugador', $current_page) ?>"
				title="Home">
				<i class="bi bi-house-door me-2"></i>Home
			</a>
			<a href="<?= PAGE_MIS_PARTIDOS_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('MisPartidos', $current_page) ?>"
				title="Mis Partidos">
				<i class="bi bi-calendar-event me-2"></i>Mis Partidos
			</a>
			<a href="<?= PAGE_MIS_EQUIPOS_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('equiposListado', $current_page) ?>"
				title="Mis Equipos">
				<i class="bi bi-people me-2"></i>Mis Equipos
			</a>
		</div>

		<!-- Sección Explorar -->
		<h6 class="offcanvas-section-title mb-2">Explorar</h6>
		<div class="d-grid gap-2 mb-4">
			<a href="<?= PAGE_CANCHAS_EXPLORAR_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('explorarCanchas', $current_page) ?>">
				<i class="bi bi-geo-alt me-2"></i>Explorar Canchas
			</a>
			<a href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('explorarPartidos', $current_page) ?>">
				<i class="bi bi-people me-2"></i>Explorar Partidos
			</a>
			<a href="<?= PAGE_TORNEOS_EXPLORAR_JUGADOR ?>"
				class="btn btn-dark text-start <?= isActive('explorarTorneos', $current_page) ?>">
				<i class="bi bi-trophy me-2"></i>Explorar Torneos
			</a>
		</div>

		<!-- Perfil y configuración -->
		<div class="mt-auto pt-3 border-top">
			<div class="d-grid gap-2">
				<a href="<?= PAGE_MI_PERFIL_JUGADOR ?>"
					class="btn btn-dark text-start <?= isActive('miPerfil', $current_page) ?>"
					title="Mi Perfil">
					<i class="bi bi-person-circle me-2"></i>Mi Perfil
				</a>
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
					<!--<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab-reservas" data-bs-toggle="tab" data-bs-target="#content-reservas" type="button" role="tab">
							<i class="bi bi-calendar-check me-1"></i>Reservas
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-solicitantes" data-bs-toggle="tab" data-bs-target="#content-solicitantes" type="button" role="tab">
							<i class="bi bi-person-plus me-1"></i>Solicitantes
						</button>
					</li>-->
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="tab-equipos" data-bs-toggle="tab" data-bs-target="#content-equipos" type="button" role="tab">
							<i class="bi bi-people me-1"></i>Equipos
						</button>
					</li>
					<!--<li class="nav-item" role="presentation">
						<button class="nav-link" id="tab-torneos" data-bs-toggle="tab" data-bs-target="#content-torneos" type="button" role="tab">
							<i class="bi bi-trophy me-1"></i>Torneos
						</button>
					</li>-->
				</ul>

				<!-- Contenido de las tabs -->
				<div class="tab-content" id="notificacionesTabContent">
					<!-- TAB 1: RESERVAS
					<div class="tab-pane fade show active" id="content-reservas" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Estado de Reservas</h6>
							<a href="<?= PAGE_MIS_PARTIDOS_JUGADOR ?>" class="btn btn-sm btn-dark">
								<i class="bi bi-calendar-event me-1"></i>Ver Mis Partidos
							</a>
						</div> -->

					<!-- Lista de reservas
						<div>
							<div class="list-group"> -->
					<!-- Reserva aceptada
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<div class="d-flex align-items-center mb-1">
											<i class="bi bi-check-circle-fill text-success me-2"></i>
											<h6 class="mb-0">Reserva Aceptada</h6>
										</div>
										<p class="mb-1">Cancha Los Pinos - Cancha 1</p>
										<small class="text-muted">Sábado 16/11/2025 - 18:00 hs</small>
									</div>
									<div>
										<button class="btn btn-sm btn-dark">
											<i class="bi bi-check-lg me-1"></i>Ver Detalle
										</button>
									</div>
								</div> -->
					<!-- Reserva rechazada
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<div class="d-flex align-items-center mb-1">
											<i class="bi bi-x-circle-fill text-danger me-2"></i>
											<h6 class="mb-0">Reserva Rechazada</h6>
										</div>
										<p class="mb-1">Club Atlético River - Cancha Principal</p>
										<small class="text-muted">Domingo 17/11/2025 - 20:00 hs</small>
									</div>
									<div>
										<button class="btn btn-sm btn-dark">
											<i class="bi bi-search me-1"></i>Buscar Alternativas
										</button>
									</div>
								</div> -->
					<!-- Reserva pendiente
								<div class="list-group-item d-flex justify-content-between align-items-center">
									<div>
										<div class="d-flex align-items-center mb-1">
											<i class="bi bi-clock-fill text-warning me-2"></i>
											<h6 class="mb-0">Reserva Pendiente</h6>
										</div>
										<p class="mb-1">Complejo Deportivo Norte - Cancha 3</p>
										<small class="text-muted">Viernes 22/11/2025 - 19:30 hs</small>
									</div>
									<div>
										<button class="btn btn-sm btn-dark">
											<i class="bi bi-hourglass-split me-1"></i>Esperando
										</button>
									</div>
								</div>
							</div>
						</div>
					</div> -->

					<!-- TAB 2: SOLICITANTES
					<div class="tab-pane fade" id="content-solicitantes" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Solicitudes para tus Partidos</h6>
							<a href="<?= PAGE_MIS_PARTIDOS_JUGADOR ?>" class="btn btn-sm btn-dark">
								<i class="bi bi-people me-1"></i>Gestionar Partidos
							</a>
						</div> -->

					<!-- Tabla de solicitudes
						<div>
							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col">Jugador</th>
										<th scope="col">Partido</th>
										<th scope="col">Fecha</th>
										<th scope="col">Acciones</th>
									</tr>
								</thead>
								<tbody> -->
					<!-- Solicitud 1 
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
												<div>
													<div>Carlos Mendez</div>
													<small class="text-muted">Delantero - 4.2★</small>
												</div>
											</div>
										</td>
										<td>
											<div>Fútbol 5 - Los Pinos</div>
											<small class="text-muted">Cancha 1</small>
										</td>
										<td>
											<div>16/11/2025</div>
											<small class="text-muted">18:00 hs</small>
										</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
											<button class="btn btn-sm btn-dark" title="Ver perfil">
												<i class="bi bi-eye"></i>
											</button>
										</td>
									</tr>-->
					<!-- Solicitud 2 
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
												<div>
													<div>Ana Rodriguez</div>
													<small class="text-muted">Mediocampo - 4.7★</small>
												</div>
											</div>
										</td>
										<td>
											<div>Fútbol 11 - River</div>
											<small class="text-muted">Cancha Principal</small>
										</td>
										<td>
											<div>18/11/2025</div>
											<small class="text-muted">20:30 hs</small>
										</td>
										<td>
											<button class="btn btn-sm btn-success me-1" title="Aceptar">
												<i class="bi bi-check-lg"></i>
											</button>
											<button class="btn btn-sm btn-danger" title="Rechazar">
												<i class="bi bi-x-lg"></i>
											</button>
											<button class="btn btn-sm btn-dark" title="Ver perfil">
												<i class="bi bi-eye"></i>
											</button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>-->

					<!-- TAB 3: EQUIPOS -->
					<div class="tab-pane fade show active" id="content-equipos" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Solicitudes de Equipos</h6>
							<a href="<?= PAGE_MIS_EQUIPOS_JUGADOR ?>" class="btn btn-sm btn-dark">
								<i class="bi bi-people me-1"></i>Ver Mis Equipos
							</a>
						</div>

						<!-- Lista de solicitudes de equipos -->
						<div>
							<div class="list-group" id="solicitudesEquiposContainer">

								<!-- Ejemplos comentados:
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-person-plus-fill text-success me-2"></i>
												<h6 class="mb-0">Invitación a Equipo</h6>
											</div>
											<p class="mb-1"><strong>Los Tigres FC</strong> te invitó a unirte</p>
											<small class="text-muted">Capitán: Juan Pérez • 8 integrantes • 2 torneos activos</small>
										</div>
										<div class="d-flex flex-column gap-1">
											<button class="btn btn-sm btn-success">
												<i class="bi bi-check-lg me-1"></i>Aceptar
											</button>
											<button class="btn btn-sm btn-danger">
												<i class="bi bi-x-lg me-1"></i>Rechazar
											</button>
										</div>
									</div>
								</div>
								
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-check-circle-fill text-success me-2"></i>
												<h6 class="mb-0">Solicitud Aceptada</h6>
											</div>
											<p class="mb-1">Tu solicitud para unirte a <strong>Águilas Doradas</strong> fue aceptada</p>
											<small class="text-muted">Ahora eres parte del equipo</small>
										</div>
										<div>
											<button class="btn btn-sm btn-dark">
												<i class="bi bi-arrow-right me-1"></i>Ver Equipo
											</button>
										</div>
									</div>
								</div>
								
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-clock-fill text-warning me-2"></i>
												<h6 class="mb-0">Solicitud Pendiente</h6>
											</div>
											<p class="mb-1">Solicitud enviada a <strong>Rayos Azules</strong></p>
											<small class="text-muted">Esperando respuesta del capitán</small>
										</div>
										<div>
											<button class="btn btn-sm btn-dark">
												<i class="bi bi-hourglass-split me-1"></i>Esperando
											</button>
										</div>
									</div>
								</div>-->

							</div>
						</div>
					</div>

					<!-- TAB 4: TORNEOS
					<div class="tab-pane fade" id="content-torneos" role="tabpanel">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h6 class="mb-0">Actualizaciones de Torneos</h6>
							<a href="<?= PAGE_MIS_TORNEOS_JUGADOR ?>" class="btn btn-sm btn-dark">
								<i class="bi bi-trophy me-1"></i>Ver Mis Torneos
							</a>
						</div> -->

					<!-- Lista de notificaciones de torneos
						<div>
							<div class="list-group"> -->
					<!-- Avance de torneo
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-trophy-fill text-warning me-2"></i>
												<h6 class="mb-0">¡Clasificaste a Semifinales!</h6>
											</div>
											<p class="mb-1"><strong>Copa Primavera 2025</strong> - Los Tigres FC</p>
											<small class="text-muted">Próximo partido: Viernes 29/11 vs Águilas United</small>
										</div>
										<div>
											<button class="btn btn-sm btn-dark">
												<i class="bi bi-calendar-event me-1"></i>Ver Fixture
											</button>
										</div>
									</div>
								</div> -->
					<!-- Solicitud aceptada
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-check-circle-fill text-success me-2"></i>
												<h6 class="mb-0">Solicitud de Torneo Aceptada</h6>
											</div>
											<p class="mb-1"><strong>Liga Nocturna</strong> aceptó tu inscripción</p>
											<small class="text-muted">Equipo: Rayos Azules • Inicio: 1/12/2025</small>
										</div>
										<div>
											<button class="btn btn-sm btn-dark">
												<i class="bi bi-info-circle me-1"></i>Ver Detalles
											</button>
										</div>
									</div>
								</div> -->
					<!-- Nueva ronda
								<div class="list-group-item">
									<div class="d-flex justify-content-between align-items-center">
										<div>
											<div class="d-flex align-items-center mb-2">
												<i class="bi bi-calendar-plus-fill text-info me-2"></i>
												<h6 class="mb-0">Nueva Fecha Programada</h6>
											</div>
											<p class="mb-1"><strong>Torneo Relámpago</strong> - Cuartos de Final</p>
											<small class="text-muted">Sábado 25/11 - 16:00 hs - Cancha Los Pinos</small>
										</div>
										<div>
											<button class="btn btn-sm btn-dark">
												<i class="bi bi-bell me-1"></i>Recordar
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> -->
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

				<!-- Nombre -->
				<div class="row mb-3">
					<div class="col-md-6">
						<label for="inputNombre" class="form-label">Nombre</label>
						<input type="text" class="form-control" id="inputNombre" placeholder="Nombre">
					</div>
					<div class="col-md-6">
						<label for="inputApellido" class="form-label">Apellido</label>
						<input type="text" class="form-control" id="inputApellido" placeholder="Apellido">
					</div>
				</div>

				<!-- Email -->
				<div class="mb-3">
					<label for="inputEmail" class="form-label">Dirección de E-mail</label>
					<input type="email" class="form-control" id="inputEmail" placeholder="email@ejemplo.com">
				</div>

				<!-- Teléfono -->
				<div class="mb-3">
					<label for="inputTelefono" class="form-label">Teléfono (opcional)</label>
					<input type="tel" class="form-control" id="inputTelefono" placeholder="+54 9 11 1234-5678">
				</div>

				<div class="d-grid mb-4">
					<button type="button" class="btn btn-primary" id="btnGuardarDatos">
						<i class="bi bi-save me-2"></i>Guardar Datos
					</button>
				</div>

				<hr class="my-4">

				<!-- Cambiar Contraseña -->
				<h6 class="border-bottom pb-2 mb-3">Seguridad</h6>

				<button type="button" class="btn btn-warning w-100 mb-3" id="btnCambiarPassword">
					<i class="bi bi-key me-2"></i>Cambiar Contraseña
				</button>

				<!-- Formulario de cambio de contraseña (oculto por defecto) -->
				<div id="formCambiarPassword" class="d-none">
					<form id="passwordChangeForm" autocomplete="off">
						<input type="text" name="username" autocomplete="username" style="display:none;" aria-hidden="true">
						<div class="card card-body bg-dark border-warning mb-3">
							<div class="mb-3">
								<label for="inputPasswordActual" class="form-label">Contraseña Actual</label>
								<input type="password" class="form-control" id="inputPasswordActual" placeholder="Contraseña actual" autocomplete="current-password">
							</div>
							<div class="mb-3">
								<label for="inputPasswordNueva" class="form-label">Nueva Contraseña</label>
								<input type="password" class="form-control" id="inputPasswordNueva" placeholder="Nueva contraseña (mín. 6 caracteres)" autocomplete="new-password">
							</div>
							<div class="mb-3">
								<label for="inputPasswordConfirmar" class="form-label">Confirmar Nueva Contraseña</label>
								<input type="password" class="form-control" id="inputPasswordConfirmar" placeholder="Confirmar nueva contraseña" autocomplete="new-password">
							</div>
							<button type="button" class="btn btn-warning" id="btnGuardarPassword">
								<i class="bi bi-check-lg me-2"></i>Guardar Nueva Contraseña
							</button>
						</div>
					</form>
				</div>

				<hr class="my-4">

				<!--Suspender y Eliminar cuenta en fila
				<div class="row g-3">
					//Suspender cuenta
					<div class="col-12 col-lg-6">
						<h6 class="mb-2">Suspender Cuenta</h6>
						<button class="btn btn-warning w-100" type="button" data-bs-toggle="collapse" data-bs-target="#suspenderForm">
							<i class="bi bi-pause-circle me-2"></i>Suspender Temporalmente
						</button>

						//Formulario de suspensión
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

					//Eliminar cuenta
					<div class="col-12 col-lg-6">
						<h6 class="mb-2 text-danger">Eliminar Cuenta</h6>
						<button class="btn btn-danger w-100" type="button" data-bs-toggle="collapse" data-bs-target="#eliminarForm">
							<i class="bi bi-trash me-2"></i>Eliminar Permanentemente
						</button>

						//Información de eliminación
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
				-->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>


<script>
	const UPDATE_USUARIO_URL = '<?= UPDATE_USUARIO ?>';
</script>


<script src="<?= JS_NOTIFICACIONES_JUGADOR ?>"></script>
<script src="<?= JS_TOAST_MODULE ?>"></script>
<script src="<?= JS_NAVBAR_JUGADOR ?>"></script>
<script src="<?= JS_UPDATE_USUARIO ?>"></script>