<!-- Modal Borradores de Foros -->
<div class="modal fade" id="modalforosBorradores" tabindex="-1" aria-labelledby="modalforosBorradoresLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title" id="modalforosBorradoresLabel">
          <i class="bi bi-archive"></i> Borradores de Foros
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Información sobre borradores -->
        <div class="alert alert-info mb-4">
          <i class="bi bi-info-circle"></i> 
          <strong>Gestiona tus borradores:</strong> Aquí encontrarás todos los foros que guardaste como borrador. Puedes editarlos, publicarlos o eliminarlos.
        </div>

        <!-- Filtros y búsqueda -->
        <div class="row mb-4">
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input type="text" class="form-control" id="buscarBorrador" placeholder="Buscar en borradores...">
            </div>
          </div>
          <div class="col-md-3">
            <select class="form-select" id="filtroFecha">
              <option value="">Todas las fechas</option>
              <option value="hoy">Hoy</option>
              <option value="semana">Esta semana</option>
              <option value="mes">Este mes</option>
            </select>
          </div>
          <div class="col-md-3">
            <button type="button" class="btn btn-primary w-100" id="btnNuevoBorradorDesdeModal">
              <i class="bi bi-plus-circle"></i> Nuevo Borrador
            </button>
          </div>
        </div>

        <!-- Lista de borradores -->
        <div class="row" id="listaBorradores">
          
          <!-- Borrador 1 -->
          <div class="col-12 mb-3 borrador-item">
            <div class="card border-warning">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <h6 class="card-title mb-2">
                      <i class="bi bi-file-earmark-text text-warning"></i>
                      Copa de Invierno 2025 - Inscripciones Próximamente
                    </h6>
                    <p class="card-text text-muted mb-2">
                      Se aproxima una nueva edición de nuestra copa de invierno. Modalidad Fútbol 5, fecha tentativa para junio...
                    </p>
                    <div class="d-flex gap-2 align-items-center mb-2">
                      <span class="badge bg-warning text-dark">
                        <i class="bi bi-clock"></i> Guardado hace 2 horas
                      </span>
                      <span class="badge bg-info">
                        <i class="bi bi-images"></i> Con imagen
                      </span>
                      <small class="text-muted">Última modificación: 3/11/2025 14:30</small>
                    </div>
                  </div>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarBorrador(1)" data-bs-toggle="tooltip" title="Editar borrador">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="publicarBorrador(1)" data-bs-toggle="tooltip" title="Publicar ahora">
                      <i class="bi bi-send"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="duplicarBorrador(1)" data-bs-toggle="tooltip" title="Duplicar borrador">
                      <i class="bi bi-copy"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarBorrador(1)" data-bs-toggle="tooltip" title="Eliminar borrador">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Borrador 2 -->
          <div class="col-12 mb-3 borrador-item">
            <div class="card border-warning">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <h6 class="card-title mb-2">
                      <i class="bi bi-file-earmark-text text-warning"></i>
                      Mantenimiento programado de canchas
                    </h6>
                    <p class="card-text text-muted mb-2">
                      Informamos que durante la primera semana de diciembre realizaremos mantenimiento preventivo...
                    </p>
                    <div class="d-flex gap-2 align-items-center mb-2">
                      <span class="badge bg-warning text-dark">
                        <i class="bi bi-clock"></i> Guardado hace 1 día
                      </span>
                      <small class="text-muted">Última modificación: 2/11/2025 09:15</small>
                    </div>
                  </div>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarBorrador(2)" data-bs-toggle="tooltip" title="Editar borrador">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="publicarBorrador(2)" data-bs-toggle="tooltip" title="Publicar ahora">
                      <i class="bi bi-send"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="duplicarBorrador(2)" data-bs-toggle="tooltip" title="Duplicar borrador">
                      <i class="bi bi-copy"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarBorrador(2)" data-bs-toggle="tooltip" title="Eliminar borrador">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Borrador 3 -->
          <div class="col-12 mb-3 borrador-item">
            <div class="card border-warning">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                  <div class="flex-grow-1">
                    <h6 class="card-title mb-2">
                      <i class="bi bi-file-earmark-text text-warning"></i>
                      Nuevos horarios de reservas para diciembre
                    </h6>
                    <p class="card-text text-muted mb-2">
                      A partir del 1 de diciembre implementaremos nuevos horarios de reserva para optimizar...
                    </p>
                    <div class="d-flex gap-2 align-items-center mb-2">
                      <span class="badge bg-warning text-dark">
                        <i class="bi bi-clock"></i> Guardado hace 3 días
                      </span>
                      <span class="badge bg-info">
                        <i class="bi bi-images"></i> Con imagen
                      </span>
                      <small class="text-muted">Última modificación: 31/10/2025 16:45</small>
                    </div>
                  </div>
                  <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editarBorrador(3)" data-bs-toggle="tooltip" title="Editar borrador">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="publicarBorrador(3)" data-bs-toggle="tooltip" title="Publicar ahora">
                      <i class="bi bi-send"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="duplicarBorrador(3)" data-bs-toggle="tooltip" title="Duplicar borrador">
                      <i class="bi bi-copy"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarBorrador(3)" data-bs-toggle="tooltip" title="Eliminar borrador">
                      <i class="bi bi-trash"></i>
                    </button>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <!-- Estado vacío -->
          <div class="col-12 text-center py-5 d-none" id="estadoVacio">
            <i class="bi bi-archive text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">No hay borradores guardados</h5>
            <p class="text-muted">Crea un nuevo foro y guárdalo como borrador para verlo aquí.</p>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#modalNuevoForo">
              <i class="bi bi-plus-circle"></i> Crear Nuevo Foro
            </button>
          </div>
        </div>
      </div>
      
      <div class="modal-footer">
        <div class="d-flex justify-content-between w-100">
          <div>
            <button type="button" class="btn btn-outline-danger" id="btnEliminarTodosBorradores">
              <i class="bi bi-trash"></i> Eliminar todos
            </button>
          </div>
          <div>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle"></i> Cerrar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // Inicializar tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
  
  // Búsqueda de borradores
  document.getElementById('buscarBorrador').addEventListener('input', function() {
    const termino = this.value.toLowerCase();
    filtrarBorradores(termino);
  });

  // Filtro por fecha
  document.getElementById('filtroFecha').addEventListener('change', function() {
    const filtro = this.value;
    aplicarFiltroFecha(filtro);
  });

  // Botón nuevo borrador desde modal
  document.getElementById('btnNuevoBorradorDesdeModal').addEventListener('click', function() {
    // Cerrar modal actual y abrir modal nuevo foro
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalforosBorradores'));
    modal.hide();
    
    setTimeout(() => {
      const modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoForo'));
      modalNuevo.show();
    }, 300);
  });

  // Eliminar todos los borradores
  document.getElementById('btnEliminarTodosBorradores').addEventListener('click', function() {
    if (confirm('¿Estás seguro de que deseas eliminar todos los borradores? Esta acción no se puede deshacer.')) {
      eliminarTodosBorradores();
    }
  });


});

// Funciones para gestionar borradores
function filtrarBorradores(termino) {
  const borradores = document.querySelectorAll('.borrador-item');
  let visibles = 0;
  
  borradores.forEach(borrador => {
    const titulo = borrador.querySelector('.card-title').textContent.toLowerCase();
    const contenido = borrador.querySelector('.card-text').textContent.toLowerCase();
    
    if (titulo.includes(termino) || contenido.includes(termino)) {
      borrador.style.display = 'block';
      visibles++;
    } else {
      borrador.style.display = 'none';
    }
  });
  
  // Mostrar estado vacío si no hay resultados
  const estadoVacio = document.getElementById('estadoVacio');
  if (visibles === 0 && termino !== '') {
    estadoVacio.classList.remove('d-none');
    estadoVacio.innerHTML = `
      <i class="bi bi-search text-muted" style="font-size: 4rem;"></i>
      <h5 class="text-muted mt-3">No se encontraron borradores</h5>
      <p class="text-muted">No hay borradores que coincidan con "<strong>${termino}</strong>"</p>
    `;
  } else if (visibles > 0) {
    estadoVacio.classList.add('d-none');
  }
}

function aplicarFiltroFecha(filtro) {
  // Simulación de filtro por fecha
  console.log('Aplicando filtro de fecha:', filtro);
  // Aquí iría la lógica para filtrar por fecha
}

function editarBorrador(id) {
  console.log('Editando borrador:', id);
  // Cerrar modal de borradores
  const modal = bootstrap.Modal.getInstance(document.getElementById('modalforosBorradores'));
  modal.hide();
  
  // Cargar datos del borrador en el modal de nuevo foro
  setTimeout(() => {
    cargarDatosBorrador(id);
    const modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoForo'));
    modalNuevo.show();
  }, 300);
}

function publicarBorrador(id) {
  if (confirm('¿Deseas publicar este borrador ahora?')) {
    console.log('Publicando borrador:', id);
    // Aquí iría la lógica AJAX para publicar
    alert('¡Borrador publicado exitosamente!');
    // Remover el borrador de la lista
    document.querySelector(`.borrador-item:nth-child(${id})`).remove();
  }
}

function duplicarBorrador(id) {
  console.log('Duplicando borrador:', id);
  alert('Borrador duplicado correctamente');
  // Aquí iría la lógica para duplicar
}



function eliminarBorrador(id) {
  if (confirm('¿Estás seguro de que deseas eliminar este borrador?')) {
    console.log('Eliminando borrador:', id);
    document.querySelector(`.borrador-item:nth-child(${id})`).remove();
    alert('Borrador eliminado correctamente');
  }
}

function eliminarTodosBorradores() {
  document.querySelectorAll('.borrador-item').forEach(item => item.remove());
  document.getElementById('estadoVacio').classList.remove('d-none');
  alert('Todos los borradores han sido eliminados');
}

function cargarDatosBorrador(id) {
  // Simulación de carga de datos del borrador
  const datos = {
    1: {
      titulo: "Copa de Invierno 2025 - Inscripciones Próximamente",
      contenido: "Se aproxima una nueva edición de nuestra copa de invierno. Modalidad Fútbol 5, fecha tentativa para junio..."
    },
    2: {
      titulo: "Mantenimiento programado de canchas", 
      contenido: "Informamos que durante la primera semana de diciembre realizaremos mantenimiento preventivo..."
    },
    3: {
      titulo: "Nuevos horarios de reservas para diciembre",
      contenido: "A partir del 1 de diciembre implementaremos nuevos horarios de reserva para optimizar..."
    }
  };
  
  if (datos[id]) {
    document.getElementById('tituloForo').value = datos[id].titulo;
    document.getElementById('textoForo').value = datos[id].contenido;
  }
}
</script>
