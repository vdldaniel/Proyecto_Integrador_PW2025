<?php

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

<!-- Navbar Admin Sistema -->
<header>
    <nav id="navbarFutmatchAdmin" class="navbar navbar-expand-lg navbar-dark bg-dark text-white sticky-top border-bottom">
        <div class="container-fluid">
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
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>"
                                class="btn btn-dark me-2 <?= isActive('inicioAdminSistema', $current_page) ?>"
                                id="adminHome"
                                title="Home">
                                <i class="bi bi-house-door"></i>
                                <span class="d-none d-lg-inline ms-1">Home</span>
                            </a>
                            <a href="<?= PAGE_SOLICITUDES_ADMIN_SISTEMA ?>"
                                class="btn btn-dark me-2 <?= isActive('solicitudesAdminSistema', $current_page) ?>"
                                id="botonSolicitudes"
                                title="Solicitudes">
                                <i class="bi bi-file-earmark-text"></i>
                                <span class="d-none d-lg-inline ms-1">Solicitudes</span>
                            </a>
                            <a href="<?= PAGE_CANCHAS_LISTADO_ADMIN_SISTEMA ?>"
                                class="btn btn-dark me-2 <?= isActive('canchasAdminSistema', $current_page) ?>"
                                id="botonCanchas"
                                title="Canchas">
                                <i class="bi bi-building"></i>
                                <span class="d-none d-lg-inline ms-1">Canchas</span>
                            </a>
                            <!--<a href="<?= PAGE_JUGADORES_LISTADO_ADMIN_SISTEMA ?>"
                                class="btn btn-dark me-2 <?= isActive('jugadoresAdminSistema', $current_page) ?>"
                                id="botonAdminJugadores"
                                title="Jugadores">
                                <span class="d-none d-lg-inline ms-1">Jugadores</span>
                            </a>-->
                        </li>

                        <!-- Dropdown Reportes
                        <div class="dropdown">
                            <button class="btn btn-dark dropdown-toggle me-2" type="button" id="dropdownExplorar"
                                data-bs-toggle="dropdown" aria-expanded="false" title="Explorar">
                                <i class="bi bi-search"></i>
                                <span class="d-none d-lg-inline ms-1">Reportes</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownExplorar">
                                <li><a class="dropdown-item" href="<?= PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA ?>">
                                        <i class="bi bi-geo-alt me-2"></i>Canchas reportadas
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN_SISTEMA ?>">
                                        <i class="bi bi-people me-2"></i>Jugadores reportados
                                    </a>
                                </li>
                            </ul>
                        </div> -->
                    </ul>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <!--<button class="btn btn-dark position-relative me-2"
                    type="button" data-bs-toggle="modal" data-bs-target="#modalNotificaciones"
                    title="Notificaciones">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </button>-->

                <button id="botonConfiguracionAdmin" class="btn btn-dark me-2" type="button"
                    data-bs-toggle="modal" data-bs-target="#modalConfiguracion" title="Configuración">
                    <i class="bi bi-gear"></i>
                </button>

                <a href="<?= CONTROLLER_LOGOUT ?>" class="btn btn-danger text-start"
                    title="Cerrar Sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
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
            <a href="<?= PAGE_INICIO_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('inicioAdminSistema', $current_page) ?>"
                title="Home">
                <i class="bi bi-house-door me-2"></i>Home
            </a>
            <a href="<?= PAGE_SOLICITUDES_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('solicitudesAdminSistema', $current_page) ?>"
                title="Solicitudes">
                <i class="bi bi-file-earmark-text me-2"></i>Solicitudes
            </a>
            <a href="<?= PAGE_CANCHAS_LISTADO_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('canchasAdminSistema', $current_page) ?>"
                title="Canchas">
                <i class="bi bi-building me-2"></i>Canchas
            </a>
            <!--<a href="<?= PAGE_JUGADORES_LISTADO_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('jugadoresAdminSistema', $current_page) ?>"
                title="Mis Equipos">
                <i class="bi bi-people me-2"></i>Jugadores
            </a>-->
        </div>

        <!-- Sección Reportes
        <h6 class="offcanvas-section-title mb-2">Reportes</h6>
        <div class="d-grid gap-2 mb-4">
            <a href="<?= PAGE_CANCHAS_REPORTADAS_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('canchasReportadasAdmin', $current_page) ?>">
                <i class="bi bi-geo-alt me-2"></i>Canchas reportadas
            </a>
            <a href="<?= PAGE_JUGADORES_REPORTADOS_ADMIN_SISTEMA ?>"
                class="btn btn-dark text-start <?= isActive('jugadoresReportadosAdmin', $current_page) ?>">
                <i class="bi bi-people me-2"></i>Jugadores reportados
            </a>
        </div> -->

        <!-- Perfil y configuración -->
        <div class="mt-auto pt-3 border-top">
            <div class="d-grid gap-2">
                <!--<a href="<?= PAGE_MI_PERFIL_JUGADOR ?>"
                    class="btn btn-dark text-start <?= isActive('miPerfil', $current_page) ?>"
                    title="Mi Perfil">
                    <i class="bi bi-person-circle me-2"></i>Mi Perfil
                </a>-->
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

<script src="<?= JS_TOAST_MODULE ?>"></script>
<script src="<?= JS_UPDATE_USUARIO ?>"></script>