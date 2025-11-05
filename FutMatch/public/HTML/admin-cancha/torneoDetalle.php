<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'torneoDetalle';
$page_title = "Detalle del Torneo - FutMatch";
$page_css = [CSS_PAGES_DETALLE_TORNEO];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar de admin sistema
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT;
  ?>

  <main>
    <div class="container-fluid mt-4 pt-4 pb-5">
      <!-- Header del body principal -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="mb-1">Copa FutMatch 2025</h1>
          <p class="text-muted mb-0">
            <i class="bi bi-calendar3"></i> 11/10/2025 - 15/12/2025
            <span class="badge bg-primary ms-2">En curso</span>
          </p>
        </div>
        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetallesTorneo">
          <i class="bi bi-info-circle"></i> Detalles del torneo
        </button>
      </div>

      <div class="card shadow-lg p-0 rounded-3">
        <!-- Pestañas de navegación -->
        <ul class="nav nav-tabs" id="torneoTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="foros-tab" data-bs-toggle="tab" data-bs-target="#foros" type="button" role="tab">
              <i class="bi bi-chat-dots"></i> Foros
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="partidos-tab" data-bs-toggle="tab" data-bs-target="#partidos" type="button" role="tab">
              <i class="bi bi-diagram-3"></i> Partidos
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="equipos-tab" data-bs-toggle="tab" data-bs-target="#equipos" type="button" role="tab">
              <i class="bi bi-people"></i> Equipos
            </button>
          </li>
        </ul>

        <!-- Contenido de las pestañas -->
        <div class="tab-content p-4" id="torneoTabsContent">
          <!-- SOLAPA 1: FOROS -->
          <div class="tab-pane fade show active" id="foros" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3>Foros del Torneo</h3>
              <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrearForo">
                <i class="bi bi-plus-circle"></i> Crear Foro
              </button>
            </div>

            <div class="row">
              <!-- Foro 1 -->
              <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-chat-square-text text-primary"></i>
                      Reglas del Torneo
                    </h5>
                    <p class="card-text text-muted">Discusión sobre las reglas y normativas del torneo.</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <small class="text-muted">
                        <i class="bi bi-chat"></i> 12 mensajes
                      </small>
                      <small class="text-muted">Hace 2 horas</small>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-outline-primary btn-sm w-100">Ver Foro</button>
                  </div>
                </div>
              </div>

              <!-- Foro 2 -->
              <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-exclamation-triangle text-warning"></i>
                      Reclamos y Consultas
                    </h5>
                    <p class="card-text text-muted">Espacio para reclamos y consultas sobre el torneo.</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <small class="text-muted">
                        <i class="bi bi-chat"></i> 8 mensajes
                      </small>
                      <small class="text-muted">Hace 1 día</small>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-outline-primary btn-sm w-100">Ver Foro</button>
                  </div>
                </div>
              </div>

              <!-- Foro 3 -->
              <div class="col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                  <div class="card-body">
                    <h5 class="card-title">
                      <i class="bi bi-trophy text-success"></i>
                      Resultados y Estadísticas
                    </h5>
                    <p class="card-text text-muted">Análisis de resultados y estadísticas del torneo.</p>
                    <div class="d-flex justify-content-between align-items-center">
                      <small class="text-muted">
                        <i class="bi bi-chat"></i> 24 mensajes
                      </small>
                      <small class="text-muted">Hace 3 horas</small>
                    </div>
                  </div>
                  <div class="card-footer">
                    <button class="btn btn-outline-primary btn-sm w-100">Ver Foro</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- SOLAPA 2: PARTIDOS -->
          <div class="tab-pane fade" id="partidos" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h3>Distribución de Partidos</h3>
              <div>
                <span class="badge bg-info me-2">Octavos</span>
                <span class="badge bg-warning me-2">Cuartos</span>
                <span class="badge bg-success me-2">Semifinales</span>
                <span class="badge bg-danger">Final</span>
              </div>
            </div>

            <!-- Tournament Bracket -->
            <div class="tournament-bracket-container">
              <h2 class="mb-4 text-center">Bracket del Torneo</h2>

              <div class="bracket-wrapper">
                <!-- Octavos de Final -->
                <div class="bracket-column">
                  <h5 class="text-center mb-3">Octavos de Final</h5>

                  <!-- Grupo 1 -->
                  <div class="bracket-group">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Los Cracks FC
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Deportivo Fútbol
                    </div>
                  </div>

                  <!-- Grupo 2 -->
                  <div class="bracket-group">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Racing Club
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Boca Juniors
                    </div>
                  </div>

                  <!-- Grupo 3 -->
                  <div class="bracket-group">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      River Plate
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      San Lorenzo
                    </div>
                  </div>

                  <!-- Grupo 4 -->
                  <div class="bracket-group">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Independiente
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Estudiantes
                    </div>
                  </div>
                </div>

                <!-- Ganadores Octavos -->
                <div class="bracket-column winners-column">
                  <h5 class="text-center mb-3">Ganadores</h5>

                  <div class="winner-result">
                    <div class="winner-box completed clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      <strong>Los Cracks FC</strong>
                      <div class="score">3-1</div>
                      <button class="btn btn-xs btn-outline-light mt-1 ver-partido-btn" data-partido-id="octavos-1">
                        Ver Partido
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>

                  <div class="winner-result">
                    <div class="winner-box completed clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      <strong>Racing Club</strong>
                      <div class="score">2-0</div>
                      <button class="btn btn-xs btn-outline-light mt-1 ver-partido-btn" data-partido-id="octavos-2">
                        Ver Partido
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>

                  <div class="winner-result">
                    <div class="winner-box completed clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      <strong>River Plate</strong>
                      <div class="score">4-1</div>
                      <button class="btn btn-xs btn-outline-light mt-1 ver-partido-btn" data-partido-id="octavos-3">
                        Ver Partido
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>

                  <div class="winner-result">
                    <div class="winner-box completed clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      <strong>Independiente</strong>
                      <div class="score">3-2</div>
                      <button class="btn btn-xs btn-outline-light mt-1 ver-partido-btn" data-partido-id="octavos-4">
                        Ver Partido
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>
                </div>

                <!-- Cuartos de Final -->
                <div class="bracket-column">
                  <h5 class="text-center mb-3">Cuartos de Final</h5>

                  <!-- Grupo 1 Cuartos -->
                  <div class="bracket-group large-gap">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Los Cracks FC
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Racing Club
                    </div>
                  </div>

                  <!-- Grupo 2 Cuartos -->
                  <div class="bracket-group large-gap">
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      River Plate
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box clickable-team" data-team-url="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>">
                      Independiente
                    </div>
                  </div>
                </div>

                <!-- Ganadores Cuartos -->
                <div class="bracket-column winners-column">
                  <h5 class="text-center mb-3">Ganadores</h5>

                  <div class="winner-result large-gap">
                    <div class="winner-box pending">
                      <strong>Pendiente</strong>
                      <div class="score">-</div>
                      <button class="btn btn-xs btn-outline-secondary mt-1" disabled>
                        Pendiente
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>

                  <div class="winner-result large-gap">
                    <div class="winner-box pending">
                      <strong>Pendiente</strong>
                      <div class="score">-</div>
                      <button class="btn btn-xs btn-outline-secondary mt-1" disabled>
                        Pendiente
                      </button>
                    </div>
                    <div class="connector-right"></div>
                  </div>
                </div>

                <!-- Semifinal -->
                <div class="bracket-column">
                  <h5 class="text-center mb-3">Semifinal</h5>

                  <div class="bracket-group final-gap">
                    <div class="team-box">
                      TBD
                    </div>
                    <div class="connector-right"></div>
                    <div class="team-box">
                      TBD
                    </div>
                  </div>
                </div>

                <!-- Campeón -->
                <div class="bracket-column winners-column">
                  <h5 class="text-center mb-3">Campeón</h5>

                  <div class="winner-result final-gap">
                    <div class="winner-box champion">
                      <strong>🏆 TBD</strong>
                      <div class="score">Final</div>
                      <button class="btn btn-xs btn-outline-warning mt-1" disabled>
                        Por definir
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sección de partidos detallados -->
            <div class="row mt-5">
              <div class="col-12">
                <h4 class="mb-3">Detalles de Partidos</h4>
                <div class="accordion" id="partidosAccordion">
                  <!-- Octavos de Final -->
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#octavos">
                        Octavos de Final
                      </button>
                    </h2>
                    <div id="octavos" class="accordion-collapse collapse show" data-bs-parent="#partidosAccordion">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span class="fw-bold text-success">Los Cracks FC</span>
                                  <span class="badge bg-success">3</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span>Deportivo Fútbol</span>
                                  <span class="badge bg-secondary">1</span>
                                </div>
                                <button class="btn btn-sm btn-outline-info w-100 ver-partido-btn" data-partido-id="octavos-detalle-1">
                                  <i class="bi bi-eye"></i> Ver Partido Completo
                                </button>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6 mb-3">
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span class="fw-bold text-success">Racing Club</span>
                                  <span class="badge bg-success">2</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span>Boca Juniors</span>
                                  <span class="badge bg-secondary">0</span>
                                </div>
                                <button class="btn btn-sm btn-outline-info w-100 ver-partido-btn" data-partido-id="octavos-detalle-2">
                                  <i class="bi bi-eye"></i> Ver Partido Completo
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Cuartos de Final -->
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cuartos">
                        Cuartos de Final
                      </button>
                    </h2>
                    <div id="cuartos" class="accordion-collapse collapse" data-bs-parent="#partidosAccordion">
                      <div class="accordion-body">
                        <div class="row">
                          <div class="col-md-6 mb-3">
                            <div class="card">
                              <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span>Los Cracks FC</span>
                                  <span class="badge bg-warning">Pendiente</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                  <span>Racing Club</span>
                                  <span class="badge bg-warning">Pendiente</span>
                                </div>
                                <button class="btn btn-sm btn-outline-warning w-100" disabled>
                                  <i class="bi bi-clock"></i> Partido Pendiente
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SOLAPA 3: EQUIPOS -->
        <div class="tab-pane fade" id="equipos" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Equipos Participantes</h3>
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-outline-secondary active" data-view="table">
                <i class="bi bi-table"></i> Tabla
              </button>
              <button type="button" class="btn btn-outline-secondary" data-view="cards">
                <i class="bi bi-grid-3x3-gap"></i> Tarjetas
              </button>
            </div>
          </div>

          <!-- Vista en tabla -->
          <div id="equipos-table-view">
            <div class="table-responsive">
              <table class="table table-striped table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Posición</th>
                    <th>Equipo</th>
                    <th>Partidos Jugados</th>
                    <th>Goles a Favor</th>
                    <th>Goles en Contra</th>
                    <th>Diferencia</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><span class="badge bg-success">1°</span></td>
                    <td>
                      <strong>Los Cracks FC</strong>
                      <br><small class="text-muted">9 jugadores</small>
                    </td>
                    <td>1</td>
                    <td>3</td>
                    <td>1</td>
                    <td class="text-success">+2</td>
                    <td>
                      <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i> Ver perfil
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="badge bg-success">2°</span></td>
                    <td>
                      <strong>Racing Club</strong>
                      <br><small class="text-muted">8 jugadores</small>
                    </td>
                    <td>1</td>
                    <td>2</td>
                    <td>0</td>
                    <td class="text-success">+2</td>
                    <td>
                      <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i> Ver perfil
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="badge bg-danger">3°</span></td>
                    <td>
                      <strong>Deportivo Fútbol</strong>
                      <br><small class="text-muted">7 jugadores</small>
                    </td>
                    <td>1</td>
                    <td>1</td>
                    <td>3</td>
                    <td class="text-danger">-2</td>
                    <td>
                      <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i> Ver perfil
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td><span class="badge bg-danger">4°</span></td>
                    <td>
                      <strong>Boca Juniors</strong>
                      <br><small class="text-muted">9 jugadores</small>
                    </td>
                    <td>1</td>
                    <td>0</td>
                    <td>2</td>
                    <td class="text-danger">-2</td>
                    <td>
                      <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i> Ver perfil
                      </a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Vista en tarjetas -->
          <div id="equipos-cards-view" class="d-none">
            <div class="row">
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100">
                  <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <h5 class="card-title mb-0">Los Cracks FC</h5>
                      <span class="badge bg-success">1°</span>
                    </div>
                    <p class="card-text text-muted">9 jugadores</p>
                    <div class="row text-center">
                      <div class="col-4">
                        <div class="border-end">
                          <div class="fw-bold text-success">3</div>
                          <small class="text-muted">Goles a Favor</small>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="border-end">
                          <div class="fw-bold text-danger">1</div>
                          <small class="text-muted">Goles en Contra</small>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="fw-bold text-success">+2</div>
                        <small class="text-muted">Diferencia</small>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <a href="<?= PAGE_ADMIN_EQUIPO_PERFIL ?>" class="btn btn-outline-info btn-sm w-100">
                      <i class="bi bi-eye"></i> Ver perfil
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Modal Detalles del Torneo -->
    <div class="modal fade" id="modalDetallesTorneo" tabindex="-1" aria-labelledby="modalDetallesTorneoLabel">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalDetallesTorneoLabel">
              <i class="bi bi-info-circle"></i> Detalles del Torneo
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <!-- Información básica -->
              <div class="col-12 mb-4">
                <h6 class="fw-bold">Información General</h6>
                <table class="table table-borderless">
                  <tbody>
                    <tr>
                      <td class="fw-bold">Nombre:</td>
                      <td>Copa FutMatch 2025</td>
                    </tr>
                    <tr>
                      <td class="fw-bold">Fecha de Inicio:</td>
                      <td>11/10/2025</td>
                    </tr>
                    <tr>
                      <td class="fw-bold">Fecha de Fin:</td>
                      <td>15/12/2025 <span class="badge bg-warning text-dark">Estimativa</span></td>
                    </tr>
                    <tr>
                      <td class="fw-bold">Equipos Máximos:</td>
                      <td>16</td>
                    </tr>
                    <tr>
                      <td class="fw-bold">Estado:</td>
                      <td><span class="badge bg-primary">En curso</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Estado de inscripciones -->
              <div class="col-12">
                <h6 class="fw-bold">Estado de Inscripciones</h6>
                <div class="alert alert-info">
                  <i class="bi bi-info-circle"></i>
                  Las inscripciones se cerraron el <strong>25/10/2025</strong> con un total de <strong>12 equipos</strong> registrados.
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

    <!-- Modal Crear Foro -->
    <div class="modal fade" id="modalCrearForo" tabindex="-1" aria-labelledby="modalCrearForoLabel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCrearForoLabel">
              <i class="bi bi-plus-circle"></i> Crear Nuevo Foro
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="formCrearForo">
              <div class="mb-3">
                <label for="tituloForo" class="form-label">Título del Foro</label>
                <input type="text" class="form-control" id="tituloForo" placeholder="Ej: Consultas sobre horarios" required />
              </div>
              <div class="mb-3">
                <label for="descripcionForo" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcionForo" rows="3" placeholder="Describe el propósito de este foro..."></textarea>
              </div>
              <div class="mb-3">
                <label for="categoriaForo" class="form-label">Categoría</label>
                <select class="form-select" id="categoriaForo" required>
                  <option value="">Seleccionar categoría...</option>
                  <option value="reglas">Reglas y Normativas</option>
                  <option value="reclamos">Reclamos y Consultas</option>
                  <option value="resultados">Resultados y Estadísticas</option>
                  <option value="general">General</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" id="btnCrearForo">Crear Foro</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">

  <!-- Scripts -->
  <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="<?= JS_TORNEO_DETALLE ?>"></script>
</body>

</html>