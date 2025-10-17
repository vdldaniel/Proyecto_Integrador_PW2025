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
    <!-- Sidebar fijo para pantallas grandes - estilo Reddit -->
    <aside class="sidebar-fijo d-none d-lg-block border-end">
      <div class="p-3">
        <!-- Botón crear nuevo foro -->
        <div class="mb-4">
          <button id="botonCrearForo" class="btn btn-success w-100" type="button">
            <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Foro
          </button>
        </div>
        
        <!-- Input de búsqueda - Solo en sidebar -->
        <div class="mb-4">
          <div class="input-group">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input type="text" id="inputBusquedaForos" class="form-control" placeholder="Buscar foros...">
          </div>
        </div>
        
        <!-- Sección Mis Foros -->
        <div class="mb-4">
          <h6 class="text-body-secondary text-uppercase fw-bold small mb-3">Mis Foros</h6>
          <div id="listaMisForos" class="list-group list-group-flush">
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
            <button id="botonForosSeguidos" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-star me-2"></i>Foros Seguidos
            </button>
            <button id="botonForosMisPartidos" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-people me-2"></i>Foros de mis Partidos
            </button>
            <button id="botonDescubreForos" class="btn btn-dark btn-sm text-start" type="button">
              <i class="bi bi-chat-left-text me-2"></i>Descubre Foros
            </button>
          </div>
        </div>
      </div>
    </aside>
    
    <!-- Contenido principal -->
    <div class="flex-grow-1">
      <!-- Barra de controles - Solo visible en pantallas menores a lg -->
      <div class="bg-body-tertiary border-bottom sticky-top d-lg-none">
        <div class="container-fluid py-2 px-3">
          <div class="row align-items-center g-2 justify-content-center justify-content-md-start">
            <!-- Botón Crear Nuevo Foro -->
            <div class="col-auto">
              <button id="botonCrearForoMobile" class="btn btn-success" type="button">
                <i class="bi bi-plus-circle d-md-none"></i>
                <span class="d-none d-md-inline"><i class="bi bi-plus-circle me-2"></i>Crear Nuevo Foro</span>
              </button>
            </div>

            <!-- Botón Mis Foros -->
            <div class="col-auto">
              <button id="botonMisForosMobile" class="btn btn-outline-primary" type="button">
                <i class="bi bi-chat-dots me-2"></i>Mis Foros
              </button>
            </div>

            <!-- Dropdown Explorar Foros -->
            <div class="col-auto ms-md-auto">
              <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownExplorar" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-compass me-2"></i>Explorar
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownExplorar">
                  <li>
                    <a class="dropdown-item" href="#" id="opcionForosSeguidosMobile">
                      <i class="bi bi-star me-2"></i>Foros Seguidos
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#" id="opcionForosMisPartidosMobile">
                      <i class="bi bi-people me-2"></i>Foros de mis Partidos
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item" href="#" id="opcionDescubreForosMobile">
                      <i class="bi bi-chat-left-text me-2"></i>Descubre Foros
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Input de búsqueda - Pantallas pequeñas -->
          <div class="row mt-2">
            <div class="col-12">
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bi bi-search"></i>
                </span>
                <input type="text" id="inputBusquedaForosMobile" class="form-control" placeholder="Buscar foros...">
              </div>
            </div>
          </div>
        </div>
      </div>

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

  <!--Scripts-->
  <script src="public/assets/js/bootstrap.bundle.min.js"></script>
  <script src="src/scripts/pages/foros-listado.js"></script>
</body>
</html>
