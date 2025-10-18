<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'equiposListado';

// Definir título de la página
$page_title = 'Listado Equipos - FutMatch';

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
    <main class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">Equipos Disponibles</h1>
      <div>
        <a href="#" id="btnMisEquipos" class="btn btn-primary me-2">Mis Equipos</a>
        <a href="#" id="btnCrearEquipo" class="btn btn-success">Crear Equipo</a>
      </div>
    </div>

    <!-- Barra de búsqueda -->
    <div class="mb-4">
      <input
        type="text"
        id="searchInput"
        class="form-control"
        placeholder="Buscar equipos por nombre, ubicación, categoría..."
      />
    </div>

    <!-- Lista de equipos -->
    <div id="equiposList" class="row g-4">
      <!-- Aquí se insertarán dinámicamente las tarjetas de los equipos -->
    </div>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src ="<?= JS_EQUIPOS_LISTADO ?>"></script>

</body>
</html>
