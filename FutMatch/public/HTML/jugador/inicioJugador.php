<!--
Página en la cual el jugador landea al iniciar sesión.
Incluye:
- Barra de navegación superior (logo, enlaces a otras secciones, perfil, etc.)
- Sección de bienvenida con el nombre del jugador
- Resumen rápido de su perfil (foto, nivel, estadísticas básicas)
- Accesos directos a las secciones principales:
  * explorar canchas,
  * explorar partidos, 
  * mis equipos, 
  * buscar equipos, 
  * discusiones, 
  * perfil,
  * configuración)
- Noticias o actualizaciones recientes relacionadas con el fútbol o la plataforma
-->
<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'inicioJugador';

// Definir título de la página
$page_title = 'Inicio - FutMatch';

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
  
  <!-- Contenido principal -->
  <main class="container-fluid d-flex align-items-center justify-content-center main-content">
    <div class="w-100 px-4">
      <!-- Título principal -->
      <div class="text-center mb-5">
        <h2 class="mb-3">¿Qué te gustaría hacer?</h2>
        <p class="text-body-secondary">Explora las opciones disponibles para disfrutar del fútbol</p>
      </div>
      
      <!-- Tarjetas principales -->
      <div class="row g-4 justify-content-center">
        <!-- Tarjeta Reservar Cancha -->
        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
          <div class="card h-100 border-0 shadow-lg">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-geo-alt text-success icon-large"></i>
                </div>
                <h5 class="card-title mb-3">Reservar una cancha</h5>
                <p class="card-text text-body-secondary mb-4">Encuentra y reserva las mejores canchas cerca de ti</p>
                <a href="<?= PAGE_CANCHAS_LISTADO ?>" class="btn btn-success">
                  <i class="bi bi-plus-circle me-2"></i>Explorar canchas
                </a>
              </div>
            </div>
          </div>
          
          <!-- Tarjeta Unirse a Partidos -->
          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-lg">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-people text-primary icon-large"></i>
                </div>
                <h5 class="card-title mb-3">Unirse a partidos</h5>
                <p class="card-text text-body-secondary mb-4">Únete a partidos organizados por otros jugadores</p>
                <a href="<?= PAGE_PARTIDOS_LISTADO ?>" class="btn btn-primary">
                  <i class="bi bi-play-circle me-2"></i>Explorar partidos
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_JUGADOR ?>"></script>
</body>
</html>
