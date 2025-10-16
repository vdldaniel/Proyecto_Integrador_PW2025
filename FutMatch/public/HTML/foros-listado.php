<!--
Página para ver un listado de "foros de discusión" creados por jugadores
- Debe mostrar una lista con tarjetas que incluyan:
  - Título de la discusión
  - Nombre del creador
  - Fecha de creación
  - Número de respuestas
    - Botón de "Ver discusión" que redirija a discusion-detalle.html
    - Botón flotante de "Crear nueva discusión" que redirija a discusion-crear.html
        + VALIDACIÓN DE SI EL JUGADOR ESTÁ LOGUEADO O NO,   
            + SI NO ESTÁ LOGUEADO, REDIRIGIR A login-jugador.html
            + SI ESTÁ LOGUEADO, REDIRIGIR A discusion-crear.html
-->

<?php
// Cargar configuración
require_once '../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'explorarForos'; 
$page_title = "Foros - FutMatch";
$page_css = [
  CSS_PAGES_FOROS_LISTADO
];


// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>
<body>
  <?php 
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT; 
  ?>

  <!-- Layout principal -->
  <div class="d-flex">
    <!-- Sidebar fijo para pantallas grandes - solo en main body -->
    <aside class="sidebar-fijo d-none d-lg-block border-end">
      
      <div class="p-3">
        <!-- Botón crear nuevo foro -->
        <div class="mb-4">
          <button id="botonCrearForoDesktop" class="btn btn-success w-100" type="button">
            <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Foro
          </button>
        </div>
        
        <!-- Sección Mis Foros -->
        <div class="mb-4">
          <h6 class="text-body-secondary text-uppercase fw-bold small mb-3">Mis Foros</h6>
          <div id="listaMisForosDesktop" class="list-group list-group-flush">
            <!-- Foros recientes se cargan dinámicamente -->
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex align-items-center">
                <i class="bi bi-chat-dots text-primary me-2"></i>
                <small class="text-truncate">Debate sobre el último partido</small>
              </div>
              <small class="text-body-secondary">2 respuestas</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex align-items-center">
                <i class="bi bi-chat-dots text-primary me-2"></i>
                <small class="text-truncate">Búsqueda de equipo para torneo</small>
              </div>
              <small class="text-body-secondary">5 respuestas</small>
            </a>
            <a href="#" class="list-group-item list-group-item-action">
              <div class="d-flex align-items-center">
                <i class="bi bi-chat-dots text-primary me-2"></i>
                <small class="text-truncate">Opiniones sobre nueva cancha</small>
              </div>
              <small class="text-body-secondary">1 respuesta</small>
            </a>
          </div>
          <div class="mt-2">
            <a href="#" class="text-decoration-none small">Ver todos mis foros</a>
          </div>
        </div>
        
        <!-- Navegación principal -->
        <div class="mb-3">
          <h6 class="text-body-secondary text-uppercase fw-bold small mb-3">Explorar</h6>
          <div class="d-grid gap-1">
            <button id="botonForosSeguidosDesktop" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-star me-2"></i>Foros Seguidos
            </button>
            <button id="botonForosMisEquiposDesktop" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-people me-2"></i>Foros de mis Equipos
            </button>
            <button id="botonDescubrirForosDesktop" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-chat-left-text me-2"></i>Descubre Foros
            </button>
          </div>
        </div>
      </div>
    </aside>
    
    <!-- Contenido principal -->
    <div class="flex-grow-1">      
      <!-- Área de contenido principal de foros -->
      <main class="container-fluid p-4">
        <div class="row">
          <div class="col-12">
            <!-- Aquí irá el contenido de los foros -->
            <div id="contenidoForos">
              <!-- Los foros se cargarán dinámicamente aquí -->
              <div class="text-center py-5">
                <h4 class="text-body-secondary">Foros de la Comunidad</h4>
                <p class="text-body-secondary">Conecta con otros jugadores y comparte tus experiencias</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div> <!-- Fin contenido principal -->
  </div> <!-- Fin layout principal -->

  <!-- Menú lateral deslizable para pantallas medianas y menores -->
  <div class="offcanvas offcanvas-start" 
       tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="sidebarMenuLabel">Menú</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <!-- Botón crear nuevo foro -->
      <div class="mb-4">
        <button id="botonCrearForoMovil" class="btn btn-success w-100" type="button">
          <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Foro
        </button>
      </div>
      
      <!-- Sección Mis Foros -->
      <div class="mb-4">
        <h6 class="text-body-secondary text-uppercase fw-bold small mb-3">Mis Foros</h6>
        <div id="listaMisForosMovil" class="list-group list-group-flush">
          <!-- Foros recientes se cargan dinámicamente -->
          <a href="#" class="list-group-item list-group-item-action">
            <div class="d-flex align-items-center">
              <i class="bi bi-chat-dots text-primary me-2"></i>
              <small class="text-truncate">Debate sobre el último partido</small>
            </div>
            <small class="text-body-secondary">2 respuestas</small>
          </a>
          <a href="#" class="list-group-item list-group-item-action">
            <div class="d-flex align-items-center">
              <i class="bi bi-chat-dots text-primary me-2"></i>
              <small class="text-truncate">Búsqueda de equipo para torneo</small>
            </div>
            <small class="text-body-secondary">5 respuestas</small>
          </a>
        </div>
        <div class="mt-2">
          <a href="#" class="text-decoration-none small">Ver todos mis foros</a>
        </div>
      </div>
      
      <!-- Navegación principal -->
      <div class="mb-4">
        <h6 class="text-body-secondary text-uppercase fw-bold small mb-3">Explorar</h6>
        <div class="d-grid gap-2">
          <button id="botonForosSeguidosMovil" class="btn btn-dark btn-sm text-start" type="button">
            <i class="bi bi-star me-2"></i>Foros Seguidos
          </button>
          <button id="botonForosMisEquiposMovil" class="btn btn-dark btn-sm text-start" type="button">
            <i class="bi bi-people me-2"></i>Foros de mis Equipos
          </button>
          <button id="botonDescubrirForosMovil" class="btn btn-dark btn-sm text-start" type="button">
            <i class="bi bi-chat-left-text me-2"></i>Descubre Foros
          </button>
        </div>
      </div>
      <!-- Otros botones, configuración y cerrar sesión -->
      <div class="mt-auto pt-3 border-top">
        <div class="d-grid gap-2">
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button">
            <i class="bi bi-house-door me-2"></i>Home
          </button>
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button">
            <i class="bi bi-calendar-event me-2"></i>Mis Partidos
          </button>
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button">
            <i class="bi bi-people me-2"></i>Mi Equipo
          </button>
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button">
            <i class="bi bi-chat-dots active me-2"></i>Foros
          </button>
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button">
            <i class="bi bi-person-circle me-2"></i>Mi Perfil
          </button>
          <button id="botonConfiguracionMovil" class="btn btn-dark" type="button"
                  data-bs-toggle="modal" data-bs-target="#modalConfiguracion">
            <i class="bi bi-gear me-2"></i>Configuración
          </button>
          <button id="btnCerrarSesionMovil" class="btn btn-outline-danger" type="button">
            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
          </button>
        </div>
      </div>
    </div>
  </div>

  <!--Scripts-->
  <script src="public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="src/scripts/pages/foros-listado.js"></script>
</body>
</html>
