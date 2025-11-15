<!--
Página en la cual el jugador landea al iniciar sesión.
Incluye:
- Barra de navegación superior (navbar_jugador si está logueado, navbar_guest si no)
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

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verificar si el usuario está logueado
$is_authenticated = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Resalta la página actual en el navbar
$current_page = 'inicioJugador';

// Definir título de la página
$page_title = 'Inicio - FutMatch';

// CSS adicional específico de esta página
$page_css = [];

// Cargar head común
require_once HEAD_COMPONENT;
?>

<body>
  <?php
  // Cargar navbar según el estado de autenticación
  if ($is_authenticated && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'jugador') {
    // Usuario logueado como jugador - mostrar navbar de jugador
    require_once NAVBAR_JUGADOR_COMPONENT;
  } else {
    // Usuario no logueado o no es jugador - mostrar navbar de guest
    require_once NAVBAR_GUEST_COMPONENT;
  }
  ?>

  <!-- Contenido principal -->
  <main class="container-fluid d-flex align-items-center justify-content-center main-content">
    <div class="w-100 px-4">

      <?php if ($is_authenticated): ?>
        <!-- Contenido para usuarios logueados -->

        <!-- Mensaje de bienvenida para nuevos usuarios -->
        <?php if (isset($_SESSION['registration_success'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            <?= htmlspecialchars($_SESSION['registration_success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <?php unset($_SESSION['registration_success']); ?>
        <?php endif; ?>

        <!-- Título principal para usuarios logueados -->
        <div class="text-center mb-5">
          <h2 class="mb-3">
            ¡Hola <?= isset($_SESSION['nombre']) ? htmlspecialchars($_SESSION['nombre']) : 'Jugador' ?>!
            ¿Qué te gustaría hacer?
          </h2>
          <p class="text-body-secondary">Explora las opciones disponibles para disfrutar del fútbol</p>
        </div>

        <!-- Tarjetas principales para usuarios logueados -->
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
                <a href="<?= PAGE_CANCHAS_EXPLORAR_JUGADOR ?>" class="btn btn-success">
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
                <a href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>" class="btn btn-primary">
                  <i class="bi bi-play-circle me-2"></i>Explorar partidos
                </a>
              </div>
            </div>
          </div>

          <!-- Tarjeta Mis Equipos -->
          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-lg">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-shield text-warning icon-large"></i>
                </div>
                <h5 class="card-title mb-3">Mis Equipos</h5>
                <p class="card-text text-body-secondary mb-4">Gestiona tus equipos y encuentra nuevos compañeros</p>
                <a href="<?= PAGE_MIS_EQUIPOS_JUGADOR ?>" class="btn btn-warning">
                  <i class="bi bi-people-fill me-2"></i>Ver equipos
                </a>
              </div>
            </div>
          </div>
        </div>

      <?php else: ?>
        <!-- Contenido para visitantes no logueados -->

        <div class="text-center mb-5">
          <h2 class="mb-3">¡Bienvenido a FutMatch!</h2>
          <p class="text-body-secondary">La plataforma para encontrar canchas y organizar partidos de fútbol</p>
        </div>

        <!-- Tarjetas principales para visitantes -->
        <div class="row g-4 justify-content-center">
          <!-- Tarjeta Explorar Canchas -->
          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-lg">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-geo-alt text-success icon-large"></i>
                </div>
                <h5 class="card-title mb-3">Explorar canchas</h5>
                <p class="card-text text-body-secondary mb-4">Descubre las mejores canchas disponibles</p>
                <a href="<?= PAGE_CANCHAS_EXPLORAR_JUGADOR ?>" class="btn btn-success">
                  <i class="bi bi-search me-2"></i>Ver canchas
                </a>
              </div>
            </div>
          </div>

          <!-- Tarjeta Ver Partidos -->
          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-lg">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-people text-primary icon-large"></i>
                </div>
                <h5 class="card-title mb-3">Ver partidos</h5>
                <p class="card-text text-body-secondary mb-4">Consulta partidos disponibles en tu zona</p>
                <a href="<?= PAGE_PARTIDOS_EXPLORAR_JUGADOR ?>" class="btn btn-primary">
                  <i class="bi bi-eye me-2"></i>Explorar partidos
                </a>
              </div>
            </div>
          </div>

          <!-- Tarjeta Registrarse -->
          <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 border-0 shadow-lg border-info">
              <div class="card-body text-center p-4">
                <div class="mb-3">
                  <i class="bi bi-person-plus text-info icon-large"></i>
                </div>
                <h5 class="card-title mb-3 text-info">¡Únete ya!</h5>
                <p class="card-text text-body-secondary mb-4">Regístrate para reservar canchas y organizar partidos</p>
                <a href="<?= PAGE_REGISTRO_JUGADOR_PHP ?>" class="btn btn-info">
                  <i class="bi bi-person-add me-2"></i>Registrarse
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Call to action para visitantes -->
        <div class="text-center mt-5">
          <h4 class="mb-3">¿Ya tienes cuenta?</h4>
          <a href="<?= PAGE_LANDING_PHP ?>" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
          </a>
        </div>

      <?php endif; ?>

    </div>
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src="<?= JS_INICIO_JUGADOR ?>"></script>

  <?php
  // Incluir modal de login solo si el usuario no está autenticado
  if (!$is_authenticated) {
    require_once MODAL_LOGIN_COMPONENT;
  }
  ?>
</body>

</html>