<!-- Página que el usuario puede utilizar para explorar los partidos disponibles -->

<?php
require_once("../../../src/app/config.php");

// Definir página actual para navbar activo
$current_page = 'partidosExplorar';

// Definir título de la página
$page_title = 'Explorar Partidos - FutMatch';

// CSS adicional específico de esta página
$page_css = [
  CSS_PAGES_PARTIDOS_JUGADOR
];

// Iniciar sesión
require_once AUTH_REQUIRED_COMPONENT;

// Verificar que el usuario esté autenticado
// Si no está autenticado, será redirigido automáticamente al login
requireAuth();

// Verificar que sea un jugador
requireUserType('jugador');

// Obtener información del usuario actual
$currentUser = getCurrentUser();

// Cargar head común
require_once HEAD_COMPONENT;
?>
<body>
  <?php 
  // Cargar navbar de jugador
  require_once NAVBAR_JUGADOR_COMPONENT; 
  ?>
  
  <!-- Contenido principal -->
  <main>
    <div class="bg-body-secondary">
      <div class="container-fluid py-5">
        <div class="busqueda-container">
          <!--Barra de búsqueda-->
          <div class="busqueda-wrapper">
            <div class="busqueda-header">
              <div class="barra-de-busqueda">
                <input
                  type="text"
                  class="input-busqueda form-control"
                  placeholder="Buscar partidos..."
                  id="inputBusqueda"
                />
                <i class="bi bi-search busqueda-icon"></i>
              </div>
            </div>
            
            <!--Filtros de búsqueda-->
            <div class="container-fluid filtro-de-busquedas">
              <form id="filtrosForm">
                <div class="row g-3">
                  <!--Ubicación-->
                  <div class="col-md-4">
                    <label for="ubicacion" class="form-label">Ubicación</label>
                    <input
                      type="text"
                      class="form-control"
                      id="ubicacion"
                      name="ubicacion"
                      placeholder="Ingrese una ubicación..."
                    />
                  </div>
                  
                  <!--Tipo de cancha-->
                  <div class="col-md-2">
                    <label for="jugadores" class="form-label">Tipo de cancha</label>
                    <select class="form-select" id="jugadores" name="tipo_cancha">
                      <option value="" selected>Todos</option>
                      <option value="5">Fútbol 5</option>
                      <option value="7">Fútbol 7</option>
                      <option value="11">Fútbol 11</option>
                    </select>
                  </div>
                  
                  <!--Género-->
                  <div class="col-md-2">
                    <label for="genero" class="form-label">Género</label>
                    <select class="form-select" id="genero" name="genero">
                      <option value="" selected>Todos</option>
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                      <option value="MIX">Mixto</option>
                    </select>
                  </div>
                  
                  <!--Fecha-->
                  <div class="col-md-2">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input
                      type="date"
                      class="form-control"
                      id="fecha"
                      name="fecha"
                    />
                  </div>
                  
                  <!--Botón aplicar filtros-->
                  <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                      <i class="bi bi-funnel me-2"></i>Aplicar filtros
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!--Listado de partidos GRID VIEW-->
            <div class="listado-grid border-top">
              <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mt-1" id="partidosContainer">
                <?php
                // TODO: Reemplazar con consulta a la base de datos
                // Por ahora, mostramos datos de ejemplo
                
                // Simulación de datos de partidos
                $partidos = [
                  [
                    'id' => 1,
                    'cancha' => 'MegaFutbol Llavallol',
                    'ubicacion' => 'Antártida Argentina 2340, B1833CDN Llavallol, Provincia de Buenos Aires',
                    'fecha' => '2025-10-27',
                    'hora_inicio' => '17:00',
                    'hora_fin' => '18:00',
                    'imagen' => 'https://www.megafutbol.com.ar/fotos/foto040.jpg',
                    'tipo' => '5',
                    'genero' => 'M'
                  ],
                  [
                    'id' => 2,
                    'cancha' => 'Cancha del Parque',
                    'ubicacion' => 'Av. Libertador 1234, CABA',
                    'fecha' => '2025-10-28',
                    'hora_inicio' => '19:00',
                    'hora_fin' => '20:00',
                    'imagen' => 'https://www.megafutbol.com.ar/fotos/foto040.jpg',
                    'tipo' => '7',
                    'genero' => 'MIX'
                  ],
                  [
                    'id' => 3,
                    'cancha' => 'Complejo Deportivo Sur',
                    'ubicacion' => 'Calle 50 N° 789, La Plata',
                    'fecha' => '2025-10-29',
                    'hora_inicio' => '15:00',
                    'hora_fin' => '16:30',
                    'imagen' => 'https://www.megafutbol.com.ar/fotos/foto040.jpg',
                    'tipo' => '11',
                    'genero' => 'F'
                  ]
                ];
                
                foreach ($partidos as $partido):
                ?>
                <div class="col">
                  <div class="card h-100 shadow-sm">
                    <img
                      src="<?= htmlspecialchars($partido['imagen']) ?>"
                      class="card-img"
                      alt="Imagen de <?= htmlspecialchars($partido['cancha']) ?>"
                    />
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($partido['cancha']) ?></h5>
                      <p class="d-flex gap-1">
                        <i class="bi bi-geo-alt"></i>
                        <?= htmlspecialchars($partido['ubicacion']) ?>
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-calendar-event"></i> 
                        <?= date('d/m/Y', strtotime($partido['fecha'])) ?>
                      </p>
                      <p class="d-flex gap-1">
                        <i class="bi bi-clock"></i>
                        <?= htmlspecialchars($partido['hora_inicio']) ?> a <?= htmlspecialchars($partido['hora_fin']) ?> hs
                      </p>
                      <div class="d-flex gap-2">
                        <span class="badge bg-primary">Fútbol <?= htmlspecialchars($partido['tipo']) ?></span>
                        <span class="badge bg-secondary">
                          <?php
                          $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'MIX' => 'Mixto'];
                          echo $generos[$partido['genero']] ?? 'No especificado';
                          ?>
                        </span>
                      </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                      <!--Botones-->
                      <div class="d-flex gap-2 align-items-center">
                        <a href="partidoDetalle.html?id=<?= $partido['id'] ?>" 
                           class="btn btn-primary btn-sm flex-grow-1">
                          Ver detalles
                        </a>
                        <button type="button" 
                                class="btn btn-success btn-sm flex-grow-1"
                                data-partido-id="<?= $partido['id'] ?>">
                          Unirse
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
              
              <?php if (empty($partidos)): ?>
              <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted"></i>
                <h4 class="mt-3">No se encontraron partidos</h4>
                <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Scripts -->
  <script src="<?= JS_BOOTSTRAP ?>"></script>
  <script>
    // Script para manejar filtros
    document.getElementById('filtrosForm').addEventListener('submit', function(e) {
      e.preventDefault();
      
      // TODO: Implementar lógica de filtrado con AJAX
      const formData = new FormData(this);
      console.log('Filtros aplicados:', Object.fromEntries(formData));
      
      // Por ahora solo mostramos un mensaje
      alert('Funcionalidad de filtros en desarrollo');
    });
    
    // Script para manejar el botón de unirse
    document.querySelectorAll('[data-partido-id]').forEach(button => {
      button.addEventListener('click', function() {
        const partidoId = this.getAttribute('data-partido-id');
        
        // TODO: Implementar lógica para unirse al partido
        if (confirm('¿Deseas unirte a este partido?')) {
          console.log('Unirse al partido:', partidoId);
          alert('Funcionalidad de unirse en desarrollo');
        }
      });
    });
  </script>
</body>
</html>



