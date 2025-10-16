<!--
Página en la cual el jugador ve sus partidos.
Muestra los partidos en los que está inscrito, con detalles como:
- Nombre del partido
- Fecha y hora
- Cancha
- Estado (pendiente, confirmado, cancelado)
-->

<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'partidosJugador';

// Definir título de la página
$page_title = 'Mis Partidos - FutMatch';

// CSS adicional específico de esta página
$page_css = [
  CSS_PAGES_INICIO_JUGADOR
];

// Cargar head común
require_once HEAD_COMPONENT;
?>
<body>
  <?php 
  // Cargar navbar
  require_once NAVBAR_JUGADOR_COMPONENT; 
  ?>
  
  
  <!-- Contenido Principal -->
  <main class="container mt-4">
    <!-- Línea 1: Header con título y botones de navegación -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Mis Partidos</h1>
        <p class="text-muted mb-0">Gestiona tus partidos programados y pasados</p>
      </div>
      <div class="col-md-6 text-end">
        <!-- Dropdown Explorar -->
        <div class="btn-group me-2">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" 
                  data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-search me-1"></i>Explorar
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= PAGE_PARTIDOS_LISTADO ?>">
              <i class="bi bi-people me-2"></i>Partidos
            </a></li>
            <li><a class="dropdown-item" href="<?= PAGE_CANCHAS_LISTADO ?>">
              <i class="bi bi-geo-alt me-2"></i>Canchas
            </a></li>
          </ul>
        </div>
        <!-- Botón Ver Historial -->
        <button class="btn btn-outline-secondary">
          <i class="bi bi-clock-history me-1"></i>Ver Historial
        </button>
      </div>
    </div>

    <!-- Línea 2: Filtros y estados -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex gap-2 align-items-center">
          <!-- Botón Filtro -->
          <button class="btn btn-outline-secondary" id="btnFiltros">
            <i class="bi bi-funnel"></i>
          </button>
          <!-- Filtros de estado -->
          <button class="btn btn-sm btn-outline-primary active" data-filter="todos">
            Todos
          </button>
          <button class="btn btn-sm btn-outline-success" data-filter="confirmados">
            Confirmados
          </button>
          <button class="btn btn-sm btn-outline-warning" data-filter="pendientes">
            Pendientes
          </button>
        </div>
      </div>
    </div>

    <!-- Lista de Partidos -->
    <div class="row" id="listaPartidos">
      
      <!-- Partido 1 - Anfitrión, Confirmado -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3" data-estado="confirmado">
        <div class="card">
          <div class="card-body p-3">
            <!-- Fila 1: Día, Fecha, Hora -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <strong>Sábado</strong> 15/02/2025 - <strong>19:00</strong>
              </div>
              <span class="badge bg-success">Confirmado</span>
            </div>

            <!-- Fila 2: Nombre de la cancha -->
            <h6 class="fw-bold mb-1">Cancha Premium - Fútbol 5</h6>

            <!-- Fila 3: Dirección + Botón ubicación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-muted small mb-0">Av. Libertador 1234, Centro</p>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt"></i>
              </button>
            </div>

            <hr class="my-2">

            <!-- Fila 4: Equipos -->
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Tu equipo</small>
                  <button class="btn btn-sm btn-outline-primary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Los Tigres
                  </button>
                  <small class="text-success fw-bold">5/5</small>
                </div>
              </div>
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Equipo rival</small>
                  <button class="btn btn-sm btn-outline-secondary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>FC Barcelona
                  </button>
                  <small class="text-muted fw-bold">5/5</small>
                </div>
              </div>
            </div>

            <!-- Botón Expandir -->
            <button class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#partido1Detalles" aria-expanded="false">
              <i class="bi bi-chevron-down"></i> Ver detalles
            </button>

            <!-- Detalles expandibles -->
            <div class="collapse mt-3" id="partido1Detalles">
              <div class="border-top pt-3">
                <!-- Rol del usuario -->
                <div class="alert alert-info py-2 mb-3">
                  <i class="bi bi-star-fill me-2"></i>
                  <strong>Sos anfitrión</strong> de este partido
                </div>

                <!-- Acciones de anfitrión -->
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-envelope-open me-2"></i>Ver solicitudes (2 pendientes)
                  </button>
                  <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chat-dots me-2"></i>Ver foro del partido
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Cancelar partido
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Partido 2 - Invitado, Confirmado -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3" data-estado="confirmado">
        <div class="card">
          <div class="card-body p-3">
            <!-- Fila 1: Día, Fecha, Hora -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <strong>Domingo</strong> 16/02/2025 - <strong>16:30</strong>
              </div>
              <span class="badge bg-success">Confirmado</span>
            </div>

            <!-- Fila 2: Nombre de la cancha -->
            <h6 class="fw-bold mb-1">Deportivo Municipal - Fútbol 7</h6>

            <!-- Fila 3: Dirección + Botón ubicación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-muted small mb-0">Calle San Martín 567, Oeste</p>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt"></i>
              </button>
            </div>

            <hr class="my-2">

            <!-- Fila 4: Equipos -->
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Equipo local</small>
                  <button class="btn btn-sm btn-outline-secondary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Real Madrid
                  </button>
                  <small class="text-success fw-bold">7/7</small>
                </div>
              </div>
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Tu equipo</small>
                  <button class="btn btn-sm btn-outline-primary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Manchester
                  </button>
                  <small class="text-success fw-bold">7/7</small>
                </div>
              </div>
            </div>

            <!-- Botón Expandir -->
            <button class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#partido2Detalles" aria-expanded="false">
              <i class="bi bi-chevron-down"></i> Ver detalles
            </button>

            <!-- Detalles expandibles -->
            <div class="collapse mt-3" id="partido2Detalles">
              <div class="border-top pt-3">
                <!-- Rol del usuario -->
                <div class="alert alert-primary py-2 mb-3">
                  <i class="bi bi-person-check-fill me-2"></i>
                  <strong>Sos invitado</strong> en este partido
                </div>

                <!-- Acciones de invitado -->
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chat-dots me-2"></i>Ver foro del partido
                  </button>
                  <button class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-person-dash me-2"></i>Cancelar participación
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Partido 3 - Solicitante, Pendiente -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3" data-estado="pendiente">
        <div class="card">
          <div class="card-body p-3">
            <!-- Fila 1: Día, Fecha, Hora -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <strong>Viernes</strong> 21/02/2025 - <strong>20:00</strong>
              </div>
              <span class="badge bg-warning text-dark">Pendiente</span>
            </div>

            <!-- Fila 2: Nombre de la cancha -->
            <h6 class="fw-bold mb-1">Estadio Central - Fútbol 11</h6>

            <!-- Fila 3: Dirección + Botón ubicación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-muted small mb-0">Av. Independencia 890, Norte</p>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt"></i>
              </button>
            </div>

            <hr class="my-2">

            <!-- Fila 4: Equipos -->
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Equipo anfitrión</small>
                  <button class="btn btn-sm btn-outline-secondary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Atlético Nacional
                  </button>
                  <small class="text-success fw-bold">11/11</small>
                </div>
              </div>
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Tu equipo</small>
                  <button class="btn btn-sm btn-outline-primary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Liverpool
                  </button>
                  <small class="text-warning fw-bold">9/11</small>
                </div>
              </div>
            </div>

            <!-- Botón Expandir -->
            <button class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#partido3Detalles" aria-expanded="false">
              <i class="bi bi-chevron-down"></i> Ver detalles
            </button>

            <!-- Detalles expandibles -->
            <div class="collapse mt-3" id="partido3Detalles">
              <div class="border-top pt-3">
                <!-- Rol del usuario -->
                <div class="alert alert-warning py-2 mb-3">
                  <i class="bi bi-hourglass-split me-2"></i>
                  <strong>Sos solicitante</strong> - Esperando confirmación del anfitrión
                </div>

                <!-- Acciones de solicitante -->
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="bi bi-chat-dots me-2"></i>Foro no disponible
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Cancelar solicitud
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Partido 4 - Anfitrión, Buscando rival -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3" data-estado="pendiente">
        <div class="card">
          <div class="card-body p-3">
            <!-- Fila 1: Día, Fecha, Hora -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <strong>Martes</strong> 25/02/2025 - <strong>18:00</strong>
              </div>
              <span class="badge bg-secondary">Buscando rival</span>
            </div>

            <!-- Fila 2: Nombre de la cancha -->
            <h6 class="fw-bold mb-1">Complejo Deportivo Sur - Fútbol 5</h6>

            <!-- Fila 3: Dirección + Botón ubicación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-muted small mb-0">Ruta Provincial 45, Km 12</p>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt"></i>
              </button>
            </div>

            <hr class="my-2">

            <!-- Fila 4: Equipos -->
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Tu equipo</small>
                  <button class="btn btn-sm btn-outline-primary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Los Cracks
                  </button>
                  <small class="text-success fw-bold">5/5</small>
                </div>
              </div>
              <div class="col-6">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Equipo rival</small>
                  <button class="btn btn-sm btn-outline-secondary w-100 mb-1 disabled">
                    <i class="bi bi-question-circle me-1"></i>Por confirmar
                  </button>
                  <small class="text-muted fw-bold">-/-</small>
                </div>
              </div>
            </div>

            <!-- Botón Expandir -->
            <button class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#partido4Detalles" aria-expanded="false">
              <i class="bi bi-chevron-down"></i> Ver detalles
            </button>

            <!-- Detalles expandibles -->
            <div class="collapse mt-3" id="partido4Detalles">
              <div class="border-top pt-3">
                <!-- Rol del usuario -->
                <div class="alert alert-info py-2 mb-3">
                  <i class="bi bi-star-fill me-2"></i>
                  <strong>Sos anfitrión</strong> de este partido
                </div>

                <!-- Acciones de anfitrión buscando rival -->
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-success">
                    <i class="bi bi-envelope-open me-2"></i>Abrir convocatoria
                  </button>
                  <button class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-plus-circle me-2"></i>Invitar equipo
                  </button>
                  <button class="btn btn-sm btn-outline-secondary" disabled>
                    <i class="bi bi-chat-dots me-2"></i>Crear foro
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>Cancelar partido
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Partido 5 - Torneo -->
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3" data-estado="confirmado">
        <div class="card border-primary">
          <div class="card-body p-3">
            <!-- Fila 1: Día, Fecha, Hora -->
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <strong>Sábado</strong> 01/03/2025 - <strong>10:00</strong>
              </div>
              <span class="badge bg-primary">Torneo</span>
            </div>

            <!-- Fila 2: Nombre de la cancha -->
            <h6 class="fw-bold mb-1">
              <i class="bi bi-trophy me-2 text-primary"></i>
              Copa Primavera 2025 - Cancha Norte
            </h6>

            <!-- Fila 3: Dirección + Botón ubicación -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <p class="text-muted small mb-0">Complejo Deportivo Norte, Zona Industrial</p>
              <button class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-geo-alt"></i>
              </button>
            </div>

            <hr class="my-2">

            <!-- Fila 4: Equipos -->
            <div class="row g-2 mb-2">
              <div class="col-12">
                <div class="text-center">
                  <small class="text-muted d-block mb-1">Tu equipo participa</small>
                  <button class="btn btn-sm btn-outline-primary w-100 mb-1">
                    <i class="bi bi-people-fill me-1"></i>Los Tigres
                  </button>
                  <small class="text-success fw-bold">5/5 confirmados</small>
                </div>
              </div>
            </div>

            <!-- Botón Expandir -->
            <button class="btn btn-sm btn-outline-secondary w-100 mt-2" 
                    type="button" data-bs-toggle="collapse" 
                    data-bs-target="#partido5Detalles" aria-expanded="false">
              <i class="bi bi-chevron-down"></i> Ver detalles
            </button>

            <!-- Detalles expandibles -->
            <div class="collapse mt-3" id="partido5Detalles">
              <div class="border-top pt-3">
                <!-- Rol del usuario -->
                <div class="alert alert-primary py-2 mb-3">
                  <i class="bi bi-trophy-fill me-2"></i>
                  <strong>Tu equipo participará</strong> en este torneo
                </div>

                <!-- Acciones de torneo -->
                <div class="d-grid gap-2">
                  <button class="btn btn-sm btn-primary">
                    <i class="bi bi-info-circle me-2"></i>Ver información del torneo
                  </button>
                  <button class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-chat-dots me-2"></i>Ver foro del torneo
                  </button>
                  <button class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-x-circle me-2"></i>No asistiré
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- Fin lista partidos -->
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_JUGADOR ?>"></script>
</body>
</html>
