<!--
Dashboard del Administrador de Cancha
Incluye:
- Header con título "Dashboard" y botón de solicitudes pendientes
- Card izquierda: Eventos de hoy con link a agenda completa
- Card derecha: Gráfico de resumen de uso de canchas con filtro temporal
-->
<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'inicioAdminCancha';

// Definir título de la página
$page_title = 'Dashboard - FutMatch';

// CSS adicional específico de esta página
$page_css = [
  CSS_PAGES_DASHBOARD_ADMIN_CANCHA
];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>

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
        <h1 class="fw-bold mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Panel de control y resumen de actividades</p>
      </div>
      <div class="col-md-6 text-end">
        <button type="button" class="btn btn-success">
          <i class="bi bi-plus-circle"></i> Nueva Reserva
        </button>
      </div>
    </div>

    <!-- Cards principales -->
    <div class="row">
      <!-- Card: Eventos de Hoy -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> Hoy</h5>
            <a href="<?= PAGE_AGENDA ?>" class="text-white text-decoration-none">
              <i class="bi bi-arrow-right-circle"></i> Ver agenda completa
            </a>
          </div>

          <!-- Tabla de eventos con scroll -->
          <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
            <table class="table table-hover mb-0">
              <thead class="table-dark sticky-top">
                <tr>
                  <th>Hora</th>
                  <th>Anfitrión</th>
                  <th>Cancha</th>
                </tr>
              </thead>
              <tbody>
                <!-- Evento 1: Partido normal -->
                <tr>
                  <td><strong>14:00</strong></td>
                  <td>Juan Pérez</td>
                  <td><span class="badge bg-info">Cancha 1</span></td>
                </tr>
                <!-- Evento 2: Torneo -->
                <tr>
                  <td><strong>17:00</strong></td>
                  <td>
                    <i class="bi bi-trophy-fill text-warning me-1"></i>
                    Torneo: Copa Primavera
                  </td>
                  <td><span class="badge bg-info">Cancha 2</span></td>
                </tr>
                <!-- Evento 3: Partido normal -->
                <tr>
                  <td><strong>19:30</strong></td>
                  <td>María González</td>
                  <td><span class="badge bg-info">Cancha 1</span></td>
                </tr>
                <!-- Evento 4: Mantenimiento -->
                <tr>
                  <td><strong>20:00</strong></td>
                  <td>
                    <i class="bi bi-tools text-danger me-1"></i>
                    Mantenimiento
                  </td>
                  <td><span class="badge bg-info">Cancha 2</span></td>
                </tr>
                <!-- Evento 5: Partido normal -->
                <tr>
                  <td><strong>21:00</strong></td>
                  <td>Carlos Rodríguez</td>
                  <td class="event-field">Cancha 3</td>
                </tr>
                <!-- Evento 6: Partido final nocturno -->
                <tr>
                  <td class="event-time">22:00</td>
                  <td class="event-tournament">
                    <i class="bi bi-trophy-fill me-1"></i>
                    Torneo: Liga Nocturna
                  </td>
                  <td class="event-field">Cancha 1</td>
                </tr>
                <!-- Evento 7: Partido de cierre -->
                <tr>
                  <td class="event-time">23:00</td>
                  <td class="event-host">Roberto Sánchez</td>
                  <td class="event-field">Cancha 2</td>
                </tr>
                <!-- Evento 8: Partido tarde -->
                <tr>
                  <td class="event-time">16:00</td>
                  <td class="event-host">Laura Martínez</td>
                  <td class="event-field">Cancha 3</td>
                </tr>
                <!-- Evento 9: Entrenamiento -->
                <tr>
                  <td class="event-time">18:00</td>
                  <td class="event-host">Club Deportivo</td>
                  <td class="event-field">Cancha 1</td>
                </tr>
                <!-- Evento 10: Partido amistoso -->
                <tr>
                  <td class="event-time">15:00</td>
                  <td class="event-host">Diego Torres</td>
                  <td class="event-field">Cancha 2</td>
                </tr>
                <!-- Evento 11: Torneo juvenil -->
                <tr>
                  <td class="event-time">10:00</td>
                  <td class="event-tournament">
                    <i class="bi bi-trophy-fill me-1"></i>
                    Torneo: Copa Juvenil
                  </td>
                  <td class="event-field">Cancha 3</td>
                </tr>
                <!-- Evento 12: Partido final tarde -->
                <tr>
                  <td><strong>20:30</strong></td>
                  <td>Andrés Gómez</td>
                  <td><span class="badge bg-info">Cancha 1</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Card: Resumen / Gráfico -->
      <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Resumen</h5>
          </div>

          <!-- Gráfico de barras simple -->
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <span class="text-muted">Uso de canchas</span>
              <select class="form-select form-select-sm" id="timeFilter" style="width: auto;">
                <option value="day">Día</option>
                <option value="week" selected>Semana</option>
                <option value="month">Mes</option>
                <option value="year">Año</option>
              </select>
            </div>

            <div class="simple-bar-chart mt-3" id="barChart" style="height: 250px; display: flex; align-items: end; justify-content: space-around; background: #212529; border-radius: 0.375rem; padding: 1rem;">
              <!-- Las barras se generarán dinámicamente con JS -->
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 70%; margin-bottom: 0.5rem;" data-value="14"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">14</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Lun</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 85%; margin-bottom: 0.5rem;" data-value="17"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">17</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Mar</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 60%; margin-bottom: 0.5rem;" data-value="12"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">12</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Mié</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 90%; margin-bottom: 0.5rem;" data-value="18"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">18</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Jue</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 100%; margin-bottom: 0.5rem;" data-value="20"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">20</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Vie</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 95%; margin-bottom: 0.5rem;" data-value="19"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">19</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Sáb</div>
              </div>
              <div class="bar-wrapper text-center">
                <div class="bg-primary rounded-top" style="width: 30px; height: 75%; margin-bottom: 0.5rem;" data-value="15"></div>
                <div style="font-size: 0.75rem; font-weight: bold; color: white;">15</div>
                <div style="font-size: 0.75rem; color: #adb5bd;">Dom</div>
              </div>
            </div>

            <!-- Leyenda opcional -->
            <div class="text-center mt-3">
              <small class="text-muted">Partidos reservados por día</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_ADMIN_CANCHA ?>"></script>
</body>

</html>