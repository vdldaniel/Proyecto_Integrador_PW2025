<?php
// Cargar configuración
require_once '../../../src/app/config.php';
require_once '../../../src/app/auth-required.php';

// Resalta la página actual en el navbar
$current_page = 'partidosJugador';

// Definir título de la página
$page_title = 'Mis Partidos - FutMatch';

// CSS adicional específico de esta página
$page_css = [CSS_PAGES_PARTIDOS_JUGADOR];

// Cargar head común
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar
  require_once NAVBAR_JUGADOR_COMPONENT;
  ?>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container mt-4">

    <!-- TÍTULO Y FILTROS -->
    <div class="row mb-4 align-items-center">
      <div class="col-md-6">
        <h1 class="fw-bold mb-1">Mis Partidos</h1>
        <p class="text-muted mb-0">Gestiona tus partidos y encuentra nuevos rivales</p>
      </div>
      <div class="col-md-6 text-end">
        <a href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>" class="btn btn-primary">
          <i class="bi bi-plus-circle me-2"></i>Buscar nuevo partido
        </a>
      </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
      <div class="col-12">
        <div class="d-flex gap-2 flex-wrap align-items-center">
          <!-- Botón Filtro -->
          <button class="btn btn-dark" id="btnFiltros">
            <i class="bi bi-funnel"></i>
          </button>
          <!-- Filtros de estado -->
          <button class="btn btn-sm btn-dark active" data-filter="todos">
            Todos
          </button>
          <button class="btn btn-sm btn-dark" data-filter="confirmados">
            Confirmados
          </button>
          <button class="btn btn-sm btn-dark" data-filter="pendientes">
            Pendientes
          </button>
        </div>
      </div>
    </div>

    <!-- LISTA DE PARTIDOS POR SEMANAS -->
    <div id="listaPartidos">

      <!-- ESTA SEMANA -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-week me-2"></i>Esta semana
            </h4>
          </div>
        </div>

        <!-- Partido 1 - Sábado -->
        <div class="partido-fila mb-3" data-estado="confirmado">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Columna izquierda: Fecha y hora -->
            <div class="partido-datetime">
              <div class="fw-bold">Sábado</div>
              <div class="text-muted small">15/02/2025</div>
              <div class="fw-bold">19:00</div>
            </div>

            <!-- Columna central: Información de la cancha -->
            <div class="partido-cancha flex-fill mx-3">
              <h6 class="fw-bold mb-1">Cancha Premium - Fútbol 5</h6>
              <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Av. Libertador 1234, Centro</span>
                <a href="#" class="btn btn-sm btn-dark">
                  <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
              </div>
            </div>

            <!-- Columna intermedia: Chips de estado y rol -->
            <div class="partido-chips mx-2">
              <div class="d-flex flex-column gap-1">
                <span class="badge text-bg-dark">Confirmado</span>
                <span class="badge text-bg-dark">
                  <i class="bi bi-star me-1"></i>Anfitrión
                </span>
              </div>
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="partido-acciones text-end">
              <button class="btn btn-sm btn-dark"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#partido1Detalles" aria-expanded="false">
                <i class="bi bi-chevron-down"></i> Ver detalles
              </button>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <div class="collapse mt-3" id="partido1Detalles">
            <div class="border-top pt-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <!-- Equipos -->
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Tu equipo</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Los Tigres
                        </a>
                        <small class="equipo-contador text-success fw-bold">5/5</small>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Equipo rival</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>FC Barcelona
                        </a>
                        <small class="equipo-contador text-muted fw-bold">5/5</small>
                      </div>
                    </div>
                  </div>

                  <!-- Rol del usuario -->
                  <div class="alert alert-info py-2 mb-0">
                    <i class="bi bi-star-fill me-2"></i>
                    <strong>Sos anfitrión</strong> de este partido
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Acciones de anfitrión -->
                  <div class="d-grid gap-2">
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-envelope-open me-2"></i>Ver solicitudes (2 pendientes)
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-chat-dots me-2"></i>Ver foro del partido
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-x-circle me-2"></i>Cancelar partido
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Partido 2 - Domingo -->
        <div class="partido-fila mb-3" data-estado="confirmado">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Columna izquierda: Fecha y hora -->
            <div class="partido-datetime">
              <div class="fw-bold">Domingo</div>
              <div class="text-muted small">16/02/2025</div>
              <div class="fw-bold">16:30</div>
            </div>

            <!-- Columna central: Información de la cancha -->
            <div class="partido-cancha flex-fill mx-3">
              <h6 class="fw-bold mb-1">Deportivo Municipal - Fútbol 7</h6>
              <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Calle San Martín 567, Oeste</span>
                <a href="#" class="btn btn-sm btn-dark">
                  <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
              </div>
            </div>

            <!-- Columna intermedia: Chips de estado y rol -->
            <div class="partido-chips mx-2">
              <div class="d-flex flex-column gap-1">
                <span class="badge text-bg-dark">Confirmado</span>
                <span class="badge text-bg-dark">
                  <i class="bi bi-person-check me-1"></i>Invitado
                </span>
              </div>
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="partido-acciones text-end">
              <button class="btn btn-sm btn-dark"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#partido2Detalles" aria-expanded="false">
                <i class="bi bi-chevron-down"></i> Ver detalles
              </button>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <div class="collapse mt-3" id="partido2Detalles">
            <div class="border-top pt-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <!-- Equipos -->
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Equipo local</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Real Madrid
                        </a>
                        <small class="equipo-contador text-success fw-bold">7/7</small>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Tu equipo</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Manchester
                        </a>
                        <small class="equipo-contador text-success fw-bold">7/7</small>
                      </div>
                    </div>
                  </div>

                  <!-- Rol del usuario -->
                  <div class="alert alert-primary py-2 mb-0">
                    <i class="bi bi-person-check-fill me-2"></i>
                    <strong>Sos invitado</strong> en este partido
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Acciones de invitado -->
                  <div class="d-grid gap-2">
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-chat-dots me-2"></i>Ver foro del partido
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-person-dash me-2"></i>Cancelar participación
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- Fin ESTA SEMANA -->

      <!-- PRÓXIMA SEMANA -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-plus me-2"></i>Próxima semana
            </h4>
          </div>
        </div>

        <!-- Partido 3 - Martes -->
        <div class="partido-fila mb-3" data-estado="pendiente">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Columna izquierda: Fecha y hora -->
            <div class="partido-datetime">
              <div class="fw-bold">Martes</div>
              <div class="text-muted small">18/02/2025</div>
              <div class="fw-bold">20:00</div>
            </div>

            <!-- Columna central: Información de la cancha -->
            <div class="partido-cancha flex-fill mx-3">
              <h6 class="fw-bold mb-1">Complejo San Lorenzo - Fútbol 11</h6>
              <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Ruta Provincial 6 Km 12, Sur</span>
                <a href="#" class="btn btn-sm btn-dark">
                  <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
              </div>
            </div>

            <!-- Columna intermedia: Chips de estado y rol -->
            <div class="partido-chips mx-2">
              <div class="d-flex flex-column gap-1">
                <span class="badge text-bg-dark">Pendiente</span>
                <span class="badge text-bg-dark text-dark">
                  <i class="bi bi-hourglass-split me-1"></i>Solicitante
                </span>
              </div>
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="partido-acciones text-end">
              <button class="btn btn-sm btn-dark"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#partido3Detalles" aria-expanded="false">
                <i class="bi bi-chevron-down"></i> Ver detalles
              </button>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <div class="collapse mt-3" id="partido3Detalles">
            <div class="border-top pt-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <!-- Equipos -->
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Equipo local</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Juventus
                        </a>
                        <small class="equipo-contador text-success fw-bold">11/11</small>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Tu equipo</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>AC Milan
                        </a>
                        <small class="equipo-contador text-warning fw-bold">10/11</small>
                      </div>
                    </div>
                  </div>

                  <!-- Rol del usuario -->
                  <div class="alert alert-warning py-2 mb-0">
                    <i class="bi bi-hourglass-split me-2"></i>
                    <strong>Solicitud pendiente</strong> de aprobación
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Acciones de solicitante -->
                  <div class="d-grid gap-2">
                    <button class="btn btn-sm btn-dark" disabled>
                      <i class="bi bi-chat-dots me-2"></i>Foro (disponible si te aceptan)
                    </button>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-x-circle me-2"></i>Cancelar solicitud
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Partido 4 - Viernes -->
        <div class="partido-fila mb-3" data-estado="buscando">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Columna izquierda: Fecha y hora -->
            <div class="partido-datetime">
              <div class="fw-bold">Viernes</div>
              <div class="text-muted small">21/02/2025</div>
              <div class="fw-bold text-primary">18:00</div>
            </div>

            <!-- Columna central: Información de la cancha -->
            <div class="partido-cancha flex-fill mx-3">
              <h6 class="fw-bold mb-1">Polideportivo Este - Fútbol 8</h6>
              <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Av. Costanera 890, Este</span>
                <a href="#" class="btn btn-sm btn-dark">
                  <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
              </div>
            </div>

            <!-- Columna intermedia: Chips de estado y rol -->
            <div class="partido-chips mx-2">
              <div class="d-flex flex-column gap-1">
                <span class="badge text-bg-dark">Buscando rival</span>
                <span class="badge text-bg-dark">
                  <i class="bi bi-star me-1"></i>Anfitrión
                </span>
              </div>
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="partido-acciones text-end">
              <button class="btn btn-sm btn-dark"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#partido4Detalles" aria-expanded="false">
                <i class="bi bi-chevron-down"></i> Ver detalles
              </button>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <div class="collapse mt-3" id="partido4Detalles">
            <div class="border-top pt-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <!-- Equipos -->
                  <div class="row g-2 mb-3">
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Tu equipo</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Los Tigres
                        </a>
                        <small class="equipo-contador text-success fw-bold">8/8</small>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Equipo rival</small>
                        <div class="btn btn-sm btn-dark w-100 mb-1 text-muted disabled">
                          <i class="bi bi-search me-1"></i>Buscando...
                        </div>
                        <small class="equipo-contador text-muted fw-bold">-/-</small>
                      </div>
                    </div>
                  </div>

                  <!-- Rol del usuario -->
                  <div class="alert alert-info py-2 mb-0">
                    <i class="bi bi-star-fill me-2"></i>
                    <strong>Sos anfitrión</strong> de este partido
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Acciones de anfitrión buscando rival -->
                  <div class="d-grid gap-2">
                    <a href="#" class="btn btn-sm btn-success">
                      <i class="bi bi-envelope-open me-2"></i>Abrir convocatoria
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-plus-circle me-2"></i>Invitar equipo
                    </a>
                    <button class="btn btn-sm btn-dark" disabled>
                      <i class="bi bi-chat-dots me-2"></i>Crear foro
                    </button>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-x-circle me-2"></i>Cancelar partido
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- Fin PRÓXIMA SEMANA -->

      <!-- MÁS ADELANTE -->
      <div class="semana-divider mb-4">
        <div class="row">
          <div class="col">
            <h4 class="fw-bold text-secondary mb-3">
              <i class="bi bi-calendar-event me-2"></i>Más adelante
            </h4>
          </div>
        </div>

        <!-- Partido 5 - Torneo -->
        <div class="partido-fila mb-3" data-estado="confirmado">
          <div class="d-flex align-items-center justify-content-between">
            <!-- Columna izquierda: Fecha y hora -->
            <div class="partido-datetime">
              <div class="fw-bold">Sábado</div>
              <div class="text-muted small">01/03/2025</div>
              <div class="fw-bold">10:00</div>
            </div>

            <!-- Columna central: Información de la cancha -->
            <div class="partido-cancha flex-fill mx-3">
              <h6 class="fw-bold mb-1">
                <i class="bi bi-trophy me-2 text-warning"></i>
                Copa Primavera 2025 - Cancha Norte
              </h6>
              <div class="d-flex align-items-center">
                <span class="text-muted small me-2">Complejo Deportivo Norte, Zona Industrial</span>
                <a href="#" class="btn btn-sm btn-dark">
                  <i class="bi bi-geo-alt"></i> Ver en mapa
                </a>
              </div>
            </div>

            <!-- Columna intermedia: Chips de estado y rol -->
            <div class="partido-chips mx-2">
              <div class="d-flex flex-column gap-1">
                <span class="badge text-bg-dark">Confirmado</span>
                <span class="badge text-bg-dark">
                  <i class="bi bi-trophy me-1"></i>Torneo
                </span>
              </div>
            </div>

            <!-- Columna derecha: Acciones -->
            <div class="partido-acciones text-end">
              <button class="btn btn-sm btn-dark"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#partido5Detalles" aria-expanded="false">
                <i class="bi bi-chevron-down"></i> Ver detalles
              </button>
            </div>
          </div>

          <!-- Detalles expandibles -->
          <div class="collapse mt-3" id="partido5Detalles">
            <div class="border-top pt-3">
              <div class="row g-3">
                <div class="col-md-6">
                  <!-- Equipo -->
                  <div class="row g-2 mb-3">
                    <div class="col-12">
                      <div class="text-center">
                        <small class="equipo-label text-muted d-block mb-1">Tu equipo participa</small>
                        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
                          <i class="bi bi-people-fill me-1"></i>Los Tigres
                        </a>
                        <small class="equipo-contador text-success fw-bold">5/5 confirmados</small>
                      </div>
                    </div>
                  </div>

                  <!-- Rol del usuario -->
                  <div class="alert alert-primary py-2 mb-0">
                    <i class="bi bi-trophy-fill me-2"></i>
                    <strong>Tu equipo participará</strong> en este torneo
                  </div>
                </div>
                <div class="col-md-6">
                  <!-- Acciones de torneo -->
                  <div class="d-grid gap-2">
                    <a href="#" class="btn btn-sm btn-primary">
                      <i class="bi bi-info-circle me-2"></i>Ver información del torneo
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-chat-dots me-2"></i>Ver foro del torneo
                    </a>
                    <a href="#" class="btn btn-sm btn-dark">
                      <i class="bi bi-x-circle me-2"></i>No asistiré
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- Fin MÁS ADELANTE -->

    </div><!-- Fin lista partidos -->
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_PARTIDOS_JUGADOR ?>"></script>
</body>

</html>