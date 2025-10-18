<?php
// Cargar configuración
require_once '../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'equipoCrear';

// Definir título de la página
$page_title = 'Crear Equipo - FutMatch';

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

        <h1 class="h3 mb-4">Crear Nuevo Equipo</h1>

        <form id="crearEquipoForm" novalidate>
        
            <div class="mb-4">
                <label for="nombreEquipo" class="form-label">Nombre del Equipo</label>
                <input type="text" class="form-control" id="nombreEquipo" required />
                <div class="invalid-feedback">Por favor, ingresa el nombre del equipo.</div>
            </div>

            <div class="mb-4">
                <label for="descripcionEquipo" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcionEquipo" rows="3" required></textarea>
                <div class="invalid-feedback">Por favor, ingresa una descripción.</div>
            </div>

            <div class="mb-4">
                <label for="fotoEquipo" class="form-label">Foto del Equipo (opcional)</label>
                <input type="file" class="form-control" id="fotoEquipo" accept="image/*" />
            </div>

            <div class="mb-4">
                <label for="rangoEtario" class="form-label">Rango Etario Permitido</label>
                <row class="row g-2">
                <div class="col-12 col-md-6">
                    <input type="number" class="form-control" id="edadMinima" placeholder="Edad mínima" required min="18" max="120" step="1">
                </div>
                <div class="col-12 col-md-6">
                    <input type="number" class="form-control" id="edadMaxima" placeholder="Edad máxima" required min="18" max="120" step="1">
                </div>
                </row>
                <div class="invalid-feedback">Por favor, selecciona un rango etario.</div>
            </div>

            <div class="mb-4">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <select class="form-select" id="ubicacion" required>
                <option value="" disabled selected>Selecciona una ubicación</option>
                <option value="Ciudad A">Ciudad A</option>
                <option value="Ciudad B">Ciudad B</option>
                <option value="Ciudad C">Ciudad C</option>
                <!-- Más opciones -->
                </select>
                <div class="invalid-feedback">Por favor, selecciona una ubicación.</div>
            </div>

        <submit><button type="submit" class="btn btn-success my-4">Crear Equipo</button></submit>
        </form>
    </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script src ="<?= JS_EQUIPO_CREAR ?>"></script>

</body>
</html>
