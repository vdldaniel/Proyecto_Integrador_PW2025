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
  
  <!-- Contenido principal del Dashboard -->
  <main class="dashboard-container">
    <!-- Header del Dashboard -->
    <div class="dashboard-header">
      <h1 class="dashboard-title">Dashboard</h1>
      <button class="btn btn-pending-requests">
        <i class="bi bi-bell-fill me-2"></i>
        Solicitudes pendientes
        <span class="badge-count">3</span>
      </button>
    </div>

    <!-- Cards principales -->
    <div class="dashboard-cards">
      <!-- Card: Eventos de Hoy -->
      <div class="dashboard-card">
        <div class="card-header-custom">
          <h2 class="card-title-custom">Hoy</h2>
          <a href="<?= PAGE_AGENDA ?>" class="card-link">
            <i class="bi bi-calendar3 me-1"></i>
            Ver agenda completa
          </a>
        </div>

        <!-- Tabla de eventos con scroll -->
        <div class="events-table-container">
          <table class="events-table">
            <thead>
              <tr>
                <th>Hora</th>
                <th>Anfitrión</th>
                <th>Cancha</th>
              </tr>
            </thead>
            <tbody>
              <!-- Evento 1: Partido normal -->
              <tr>
                <td class="event-time">14:00</td>
                <td class="event-host">Juan Pérez</td>
                <td class="event-field">Cancha 1</td>
              </tr>
              <!-- Evento 2: Torneo -->
              <tr>
                <td class="event-time">17:00</td>
                <td class="event-tournament">
                  <i class="bi bi-trophy-fill me-1"></i>
                  Torneo: Copa Primavera
                </td>
                <td class="event-field">Cancha 2</td>
              </tr>
              <!-- Evento 3: Partido normal -->
              <tr>
                <td class="event-time">19:30</td>
                <td class="event-host">María González</td>
                <td class="event-field">Cancha 1</td>
              </tr>
              <!-- Evento 4: Mantenimiento -->
              <tr>
                <td class="event-time">20:00</td>
                <td class="event-maintenance">
                  <i class="bi bi-tools me-1"></i>
                  Mantenimiento
                </td>
                <td class="event-field">Cancha 2</td>
              </tr>
              <!-- Evento 5: Partido normal -->
              <tr>
                <td class="event-time">21:00</td>
                <td class="event-host">Carlos Rodríguez</td>
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
                <td class="event-time">20:30</td>
                <td class="event-host">Andrés Gómez</td>
                <td class="event-field">Cancha 1</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Card: Resumen / Gráfico -->
      <div class="dashboard-card">
        <div class="card-header-custom">
          <h2 class="card-title-custom">Resumen</h2>
        </div>

        <!-- Gráfico de barras simple -->
        <div class="chart-container">
          <div class="chart-header">
            <select class="chart-filter" id="timeFilter">
              <option value="day">Día</option>
              <option value="week" selected>Semana</option>
              <option value="month">Mes</option>
              <option value="year">Año</option>
            </select>
          </div>
          
          <div class="simple-bar-chart" id="barChart">
            <!-- Las barras se generarán dinámicamente con JS -->
            <div class="bar-wrapper">
              <div class="bar" style="height: 70%;" data-value="14">
                <span class="bar-value">14</span>
              </div>
              <div class="bar-label">Lun</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 85%;" data-value="17">
                <span class="bar-value">17</span>
              </div>
              <div class="bar-label">Mar</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 60%;" data-value="12">
                <span class="bar-value">12</span>
              </div>
              <div class="bar-label">Mié</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 90%;" data-value="18">
                <span class="bar-value">18</span>
              </div>
              <div class="bar-label">Jue</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 100%;" data-value="20">
                <span class="bar-value">20</span>
              </div>
              <div class="bar-label">Vie</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 95%;" data-value="19">
                <span class="bar-value">19</span>
              </div>
              <div class="bar-label">Sáb</div>
            </div>
            <div class="bar-wrapper">
              <div class="bar" style="height: 75%;" data-value="15">
                <span class="bar-value">15</span>
              </div>
              <div class="bar-label">Dom</div>
            </div>
          </div>
          
          <!-- Leyenda opcional -->
          <div class="text-center mt-3">
            <small class="text-body-secondary">Partidos reservados por día</small>
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
