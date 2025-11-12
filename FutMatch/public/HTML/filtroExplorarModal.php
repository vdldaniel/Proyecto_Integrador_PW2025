<!-- Modal de Filtros -->
<div class="modal fade" id="modalFiltros" tabindex="-1" aria-labelledby="modalFiltrosLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <!-- Header del Modal -->
      <div class="modal-header">
        <h5 class="modal-title" id="modalFiltrosLabel">
          <i class="bi bi-funnel"></i> Filtros de búsqueda
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body del Modal -->
      <div class="modal-body">
        <form id="formFiltros">
          <!-- Línea 1: Ubicación (4 columnas completas) -->
          <div class="row mb-3">
            <div class="col-12">
              <label for="filtroUbicacion" class="form-label">
                <i class="bi bi-geo-alt"></i> Ubicación
              </label>
              <input type="text" class="form-control" id="filtroUbicacion" 
                     placeholder="Ej: Palermo, Recoleta, Villa Crespo...">
            </div>
          </div>

          <!-- Línea 2: Rango de fecha (2 col) | Rango de horario (2 col) -->
          <div class="row mb-3">
            <div class="col-6">
              <label class="form-label">
                <i class="bi bi-calendar-range"></i> Rango de Fechas
              </label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="date" class="form-control" id="filtroFechaDesde">
                  <div class="form-text small">Desde</div>
                </div>
                <div class="col-6">
                  <input type="date" class="form-control" id="filtroFechaHasta">
                  <div class="form-text small">Hasta</div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <label class="form-label">
                <i class="bi bi-clock"></i> Rango de Horario
              </label>
              <div class="row g-2">
                <div class="col-6">
                  <input type="time" class="form-control" id="filtroHoraDesde">
                  <div class="form-text small">Desde</div>
                </div>
                <div class="col-6">
                  <input type="time" class="form-control" id="filtroHoraHasta">
                  <div class="form-text small">Hasta</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Línea 3: Tamaño de cancha (2 col) | Tipo de superficie (2 col) -->
          <div class="row mb-3">
            <div class="col-6">
              <label for="filtroTamano" class="form-label">
                <i class="bi bi-people"></i> Tamaño de la cancha
              </label>
              <select class="form-select" id="filtroTamano">
                <option value="">Todos los tamaños</option>
                <option value="futbol-5">Fútbol 5</option>
                <option value="futbol-7">Fútbol 7</option>
                <option value="futbol-11">Fútbol 11</option>
                <option value="futbol-sala">Fútbol Sala</option>
              </select>
            </div>
            <div class="col-6" id="seccionSuperficie">
              <label for="filtroSuperficie" class="form-label">
                <i class="bi bi-grid"></i> Tipo de superficie
              </label>
              <select class="form-select" id="filtroSuperficie">
                <option value="">Todas las superficies</option>
                <option value="sintetico">Sintético</option>
                <option value="cemento">Cemento</option>
                <option value="parquet">Parquet</option>
                <option value="cesped-natural">Césped Natural</option>
              </select>
            </div>
            <!-- Género para partidos (se muestra/oculta dinámicamente) -->
            <div class="col-6 d-none" id="seccionGenero">
              <label for="filtroGenero" class="form-label">
                <i class="bi bi-gender-ambiguous"></i> Género
              </label>
              <select class="form-select" id="filtroGenero">
                <option value="">Todos los géneros</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="mixto">Mixto</option>
              </select>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer del Modal -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="limpiarFiltrosModal">
          <i class="bi bi-arrow-clockwise"></i> Limpiar filtros
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x"></i> Cancelar
        </button>
        <button type="button" class="btn btn-primary" id="aplicarFiltros" data-bs-dismiss="modal">
          <i class="bi bi-check"></i> Aplicar filtros
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Detectar tipo de página para mostrar/ocultar secciones
  const currentPage = window.location.pathname;
  const isPartidosPage = currentPage.includes('partidos');
  
  // Mostrar/ocultar secciones según el tipo de página
  const seccionSuperficie = document.getElementById('seccionSuperficie');
  const seccionGenero = document.getElementById('seccionGenero');
  
  if (isPartidosPage) {
    // Página de partidos: mostrar género, ocultar superficie
    seccionSuperficie.classList.add('d-none');
    seccionGenero.classList.remove('d-none');
    
    // Cambiar título
    document.getElementById('modalFiltrosLabel').innerHTML = 
      '<i class="bi bi-funnel"></i> Filtros de partidos';
  } else {
    // Página de canchas: mostrar superficie, ocultar género
    seccionGenero.classList.add('d-none');
    seccionSuperficie.classList.remove('d-none');
    
    // Cambiar título
    document.getElementById('modalFiltrosLabel').innerHTML = 
      '<i class="bi bi-funnel"></i> Filtros de canchas';
  }
  
  // Establecer fecha mínima como hoy
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('filtroFechaDesde').setAttribute('min', today);
  document.getElementById('filtroFechaHasta').setAttribute('min', today);
});
</script>
