<?php
// Cargar configuración
require_once __DIR__ . '/../../../src/app/config.php';

// Resalta la página actual en el navbar
$current_page = 'canchaPerfil'; 
$page_title = "Perfil de Cancha - FutMatch";
$page_css = [];

// Cargar head común (incluye <!DOCTYPE html> y <html data-bs-theme="dark">)
require_once HEAD_COMPONENT;
?>
<body>
  <?php 
  // Cargar navbar de admin cancha
  require_once NAVBAR_ADMIN_CANCHA_COMPONENT; 
  ?>
  
  <main>
    <div class="container-fluid mt-4 pt-4 pb-5">
      <!-- Header del body principal -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex gap-3 align-items-center">
          <button type="button" class="btn btn-success" onclick="window.location.href='<?= PAGE_AGENDA ?>'">
            <i class="bi bi-calendar-week"></i> Ir a agenda
          </button>
          <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEditarCancha">
            <i class="bi bi-gear"></i> Configuración
          </button>
          <button type="button" class="btn btn-primary" id="btnEditarPerfil">
            <i class="bi bi-pencil-square"></i> Editar perfil
          </button>
        </div>
        
        <!-- Dropdown para seleccionar cancha -->
        <div class="dropdown">
          <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="bi bi-building"></i> MegaFutbol Cancha A1-F5
          </button>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item active" href="#"><i class="bi bi-check-circle"></i> MegaFutbol Cancha A1-F5</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-building"></i> MegaFutbol Cancha A2-F9</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-building"></i> Cancha Delantera</a></li>
          </ul>
        </div>
      </div>

      <!-- Perfil de la cancha -->
      <div class="row">
        <!-- Banner de la cancha -->
        <div class="col-12 mb-4">
          <div class="card shadow-lg rounded-3 overflow-hidden">
            <!-- Imagen de banner -->
            <div class="position-relative">
              <img src="<?= IMG_PATH ?>bg2.jpg" class="card-img-top" alt="Banner de la cancha" style="height: 300px; object-fit: cover;">
              <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white p-4">
                <h1 class="mb-2" id="nombreCancha">MegaFutbol Cancha A1-F5</h1>
                <p class="mb-0 fs-5" id="descripcionCancha">Cancha de césped sintético de última generación con iluminación LED profesional. Ideal para partidos de Fútbol 5 con excelente drenaje y superficie antideslizante.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-lg-8">
          <!-- Sección de Foros de la cancha -->
          <div class="card shadow-lg mb-4 rounded-3">
            <div class="card-header bg-primary text-white">
              <h4 class="mb-0"><i class="bi bi-chat-dots"></i> Foros de la Cancha</h4>
            </div>
            <div class="card-body p-0">
              <!-- Post 1: Anuncio de torneo -->
              <div class="border-bottom p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-primary rounded-circle p-2 me-3">
                    <i class="bi bi-trophy text-white"></i>
                  </div>
                  <div>
                    <h6 class="mb-1 fw-bold">Inscripciones abiertas: Copa Verano 2025</h6>
                    <small class="text-muted">Hace 2 horas</small>
                  </div>
                </div>
                <p class="mb-3">¡Se abrieron las inscripciones para la Copa Verano 2025! 🏆 Modalidad Fútbol 5, máximo 16 equipos. Fecha de inicio: 15 de enero. ¡No te quedes afuera!</p>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-hand-thumbs-up"></i> Me gusta</button>
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comentar</button>
                  <button class="btn btn-sm btn-outline-info"><i class="bi bi-share"></i> Compartir</button>
                </div>
              </div>

              <!-- Post 2: Anuncio general -->
              <div class="border-bottom p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-success rounded-circle p-2 me-3">
                    <i class="bi bi-megaphone text-white"></i>
                  </div>
                  <div>
                    <h6 class="mb-1 fw-bold">Nuevos horarios disponibles</h6>
                    <small class="text-muted">Hace 1 día</small>
                  </div>
                </div>
                <p class="mb-3">¡Buenas noticias! Ampliamos nuestros horarios de atención. Ahora abrimos desde las 7:00 AM hasta las 23:00 PM de lunes a domingo. ¡Más oportunidades para jugar! ⚽</p>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-hand-thumbs-up"></i> Me gusta</button>
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comentar</button>
                  <button class="btn btn-sm btn-outline-info"><i class="bi bi-share"></i> Compartir</button>
                </div>
              </div>

              <!-- Post 3: Partido relacionado -->
              <div class="p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-warning rounded-circle p-2 me-3">
                    <i class="bi bi-person-plus text-dark"></i>
                  </div>
                  <div>
                    <h6 class="mb-1 fw-bold">Se buscan jugadores para partido</h6>
                    <small class="text-muted">Hace 3 días</small>
                  </div>
                </div>
                <p class="mb-3">¡Hola futboleros! Somos el equipo "Los Cracks FC" y necesitamos 2 jugadores más para completar nuestro equipo para el partido del sábado 2/11 a las 16:00. ¡Sumate! 🔥</p>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-outline-primary"><i class="bi bi-hand-thumbs-up"></i> Me gusta</button>
                  <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-chat"></i> Comentar</button>
                  <button class="btn btn-sm btn-outline-info"><i class="bi bi-share"></i> Compartir</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar izquierda con información -->
        <div class="col-lg-4">
          <!-- Información básica -->
          <div class="card shadow-lg mb-4 rounded-3">
            <div class="card-header bg-secondary text-white">
              <h5 class="mb-0"><i class="bi bi-info-circle"></i> Información</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="fw-bold text-muted d-block mb-1">
                  <i class="bi bi-geo-alt"></i> Dirección
                </label>
                <p class="mb-0" id="direccionCancha">Av. Corrientes 1234, CABA, Buenos Aires, Argentina</p>
              </div>
              <div class="mb-3">
                <label class="fw-bold text-muted d-block mb-1">
                  <i class="bi bi-tag"></i> Tipo de Cancha
                </label>
                <p class="mb-0" id="tipoCancha">Fútbol 5</p>
              </div>
              <div class="mb-3">
                <label class="fw-bold text-muted d-block mb-1">
                  <i class="bi bi-layers"></i> Superficie
                </label>
                <p class="mb-0" id="superficieCancha">Césped sintético</p>
              </div>
              <div class="mb-3">
                <label class="fw-bold text-muted d-block mb-1">
                  <i class="bi bi-people"></i> Capacidad
                </label>
                <p class="mb-0" id="capacidadCancha">10 jugadores</p>
              </div>
              <div class="mb-0">
                <label class="fw-bold text-muted d-block mb-1">
                  <i class="bi bi-clipboard-check"></i> Estado
                </label>
                <span class="badge bg-success" id="estadoCancha">Habilitada</span>
              </div>
            </div>
          </div>

          <!-- Horarios de atención -->
          <div class="card shadow-lg mb-4 rounded-3">
            <div class="card-header bg-info text-white">
              <h5 class="mb-0"><i class="bi bi-clock"></i> Horarios</h5>
            </div>
            <div class="card-body">
              <small class="text-muted">Lunes a Domingo</small>
              <p class="fw-bold mb-2">07:00 - 23:00</p>
              <hr class="my-2">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">
                  <i class="bi bi-circle-fill"></i> Abierto ahora
                </span>
                <small class="text-muted">Cierra a las 23:00</small>
              </div>
            </div>
          </div>

          <!-- Estadísticas rápidas -->
          <div class="card shadow-lg rounded-3">
            <div class="card-header bg-dark text-white">
              <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Estadísticas</h5>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-6 border-end">
                  <h4 class="text-primary mb-1">156</h4>
                  <small class="text-muted">Partidos jugados</small>
                </div>
                <div class="col-6">
                  <h4 class="text-success mb-1">4.8</h4>
                  <small class="text-muted">Calificación</small>
                </div>
              </div>
              <hr class="my-3">
              <div class="row text-center">
                <div class="col-6 border-end">
                  <h4 class="text-warning mb-1">8</h4>
                  <small class="text-muted">Torneos activos</small>
                </div>
                <div class="col-6">
                  <h4 class="text-info mb-1">342</h4>
                  <small class="text-muted">Jugadores únicos</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal Editar Cancha (reutilizado de canchasListado.php) -->
  <div class="modal fade" id="modalEditarCancha" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="bi bi-pencil-square"></i> Editar Cancha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="formEditarCancha">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editNombreCancha" class="form-label">Nombre de la cancha</label>
                <input type="text" class="form-control" id="editNombreCancha" value="MegaFutbol Cancha A1-F5" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editTipoCancha" class="form-label">Tipo de cancha</label>
                <select class="form-select" id="editTipoCancha" required>
                  <option value="futbol5" selected>Fútbol 5</option>
                  <option value="futbol7">Fútbol 7</option>
                  <option value="futbol11">Fútbol 11</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editSuperficie" class="form-label">Tipo de superficie</label>
                <select class="form-select" id="editSuperficie" required>
                  <option value="cesped_natural">Césped natural</option>
                  <option value="cesped_sintetico" selected>Césped sintético</option>
                  <option value="parquet">Parquet</option>
                  <option value="cemento">Cemento</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editCapacidad" class="form-label">Capacidad</label>
                <input type="number" class="form-control" id="editCapacidad" value="10" min="4" max="22" required>
              </div>
            </div>
            <div class="mb-3">
              <label for="editDescripcion" class="form-label">Descripción</label>
              <textarea class="form-control" id="editDescripcion" rows="3" required>Cancha de césped sintético de última generación con iluminación LED profesional. Ideal para partidos de Fútbol 5 con excelente drenaje y superficie antideslizante.</textarea>
            </div>
            <div class="mb-3">
              <label for="editDireccion" class="form-label">Dirección completa</label>
              <input type="text" class="form-control" id="editDireccion" value="Av. Corrientes 1234, CABA, Buenos Aires, Argentina" required>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="editHoraApertura" class="form-label">Hora de apertura</label>
                <input type="time" class="form-control" id="editHoraApertura" value="07:00" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="editHoraCierre" class="form-label">Hora de cierre</label>
                <input type="time" class="form-control" id="editHoraCierre" value="23:00" required>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardarCambiosCancha()">Guardar cambios</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="<?= CSS_ICONS ?>">
  <!-- Scripts -->
  <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
  <script>
    let modoEdicion = false;

    // Función para alternar modo edición
    document.getElementById('btnEditarPerfil').addEventListener('click', function() {
      modoEdicion = !modoEdicion;
      toggleModoEdicion();
    });

    function toggleModoEdicion() {
      const elementos = [
        { id: 'nombreCancha', tipo: 'text' },
        { id: 'descripcionCancha', tipo: 'textarea' },
        { id: 'direccionCancha', tipo: 'text' },
        { id: 'tipoCancha', tipo: 'select', opciones: ['Fútbol 5', 'Fútbol 7', 'Fútbol 11', 'Fútbol Sala'] },
        { id: 'superficieCancha', tipo: 'select', opciones: ['Césped natural', 'Césped sintético', 'Parquet', 'Cemento'] },
        { id: 'capacidadCancha', tipo: 'text' }
      ];

      const btnEditar = document.getElementById('btnEditarPerfil');

      if (modoEdicion) {
        // Activar modo edición
        btnEditar.innerHTML = '<i class="bi bi-check-circle"></i> Guardar cambios';
        btnEditar.className = 'btn btn-success';

        elementos.forEach(elem => {
          const elemento = document.getElementById(elem.id);
          const valorActual = elemento.textContent || elemento.innerText;

          if (elem.tipo === 'textarea') {
            elemento.innerHTML = `<textarea class="form-control form-control-sm" id="${elem.id}_input">${valorActual}</textarea>`;
          } else if (elem.tipo === 'select') {
            let options = '';
            elem.opciones.forEach(opcion => {
              const selected = opcion === valorActual ? 'selected' : '';
              options += `<option value="${opcion}" ${selected}>${opcion}</option>`;
            });
            elemento.innerHTML = `<select class="form-select form-select-sm" id="${elem.id}_input">${options}</select>`;
          } else {
            elemento.innerHTML = `<input type="text" class="form-control form-control-sm" id="${elem.id}_input" value="${valorActual}">`;
          }
        });
      } else {
        // Guardar cambios y desactivar modo edición
        btnEditar.innerHTML = '<i class="bi bi-pencil-square"></i> Editar perfil';
        btnEditar.className = 'btn btn-primary';

        elementos.forEach(elem => {
          const input = document.getElementById(elem.id + '_input');
          const elemento = document.getElementById(elem.id);
          
          if (input) {
            elemento.textContent = input.value;
          }
        });

        // Aquí se podría agregar una llamada AJAX para guardar en la base de datos
        alert('Cambios guardados correctamente');
      }
    }

    // Función para guardar cambios desde el modal de configuración
    function guardarCambiosCancha() {
      // Actualizar los valores en el perfil con los del modal
      document.getElementById('nombreCancha').textContent = document.getElementById('editNombreCancha').value;
      document.getElementById('descripcionCancha').textContent = document.getElementById('editDescripcion').value;
      document.getElementById('direccionCancha').textContent = document.getElementById('editDireccion').value;
      
      const tipoSelect = document.getElementById('editTipoCancha');
      document.getElementById('tipoCancha').textContent = tipoSelect.options[tipoSelect.selectedIndex].text;
      
      const superficieSelect = document.getElementById('editSuperficie');
      document.getElementById('superficieCancha').textContent = superficieSelect.options[superficieSelect.selectedIndex].text;
      
      document.getElementById('capacidadCancha').textContent = document.getElementById('editCapacidad').value + ' jugadores';

      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarCancha'));
      modal.hide();

      // Mostrar confirmación
      alert('Configuración de cancha actualizada correctamente');
    }

    // Actualizar dropdown de cancha cuando se cambia
    document.querySelectorAll('.dropdown-menu a').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const texto = this.textContent.trim();
        document.querySelector('.dropdown-toggle').innerHTML = '<i class="bi bi-building"></i> ' + texto;
        
        // Actualizar clase active
        document.querySelectorAll('.dropdown-menu a').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
        // Aquí se podría cargar la información de la cancha seleccionada
      });
    });
  </script>
</body>
</html>