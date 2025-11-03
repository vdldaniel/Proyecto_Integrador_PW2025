/**
 * JavaScript para el modal de Nuevo Foro
 * Maneja la creación y guardado de foros como borrador
 */

document.addEventListener('DOMContentLoaded', function() {
  // Referencias a elementos
  const tituloInput = document.getElementById('tituloForo');
  const textoInput = document.getElementById('textoForo');
  const fotoInput = document.getElementById('fotoForo');
  const previewContainer = document.getElementById('previewContainer');
  const previewImage = document.getElementById('previewImage');
  const btnGuardarForo = document.getElementById('btnGuardarForo');
  const contadorTitulo = document.getElementById('contadorTitulo');
  const contadorTexto = document.getElementById('contadorTexto');

  // Solo inicializar si los elementos existen (el modal está presente)
  if (!tituloInput || !textoInput || !btnGuardarForo) {
    return; // No hacer nada si el modal no está presente
  }

  // Contador de caracteres para título
  tituloInput.addEventListener('input', function() {
    const length = this.value.length;
    contadorTitulo.textContent = length;
    
    if (length > 90) {
      contadorTitulo.classList.add('text-warning');
    } else if (length > 95) {
      contadorTitulo.classList.remove('text-warning');
      contadorTitulo.classList.add('text-danger');
    } else {
      contadorTitulo.classList.remove('text-warning', 'text-danger');
    }
  });

  // Contador de caracteres para texto
  textoInput.addEventListener('input', function() {
    const length = this.value.length;
    contadorTexto.textContent = length;
    
    if (length > 450) {
      contadorTexto.classList.add('text-warning');
    } else if (length > 480) {
      contadorTexto.classList.remove('text-warning');
      contadorTexto.classList.add('text-danger');
    } else {
      contadorTexto.classList.remove('text-warning', 'text-danger');
    }
  });

  // Vista previa de imagen
  fotoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    
    if (file) {
      // Validar tipo de archivo
      if (!file.type.startsWith('image/')) {
        alert('Por favor selecciona un archivo de imagen válido.');
        this.value = '';
        return;
      }
      
      // Validar tamaño (5MB)
      if (file.size > 5 * 1024 * 1024) {
        alert('El archivo es demasiado grande. El tamaño máximo es 5MB.');
        this.value = '';
        return;
      }
      
      // Mostrar vista previa
      const reader = new FileReader();
      reader.onload = function(e) {
        previewImage.src = e.target.result;
        previewContainer.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      previewContainer.style.display = 'none';
    }
  });

  // Función para quitar la vista previa
  window.removePreview = function() {
    fotoInput.value = '';
    previewContainer.style.display = 'none';
    previewImage.src = '';
  };

  // Validación del formulario
  btnGuardarForo.addEventListener('click', function() {
    if (validarFormulario()) {
      enviarForo();
    }
  });

  // Botón Guardar como borrador
  const btnGuardarBorrador = document.getElementById('btnGuardarBorrador');
  if (btnGuardarBorrador) {
    btnGuardarBorrador.addEventListener('click', function() {
      if (validarFormularioBorrador()) {
        guardarComoBorrador();
      }
    });
  }

  function validarFormulario() {
    const titulo = tituloInput.value.trim();
    
    if (titulo === '') {
      alert('El título del foro es obligatorio.');
      tituloInput.focus();
      return false;
    }
    
    if (titulo.length < 5) {
      alert('El título debe tener al menos 5 caracteres.');
      tituloInput.focus();
      return false;
    }
    
    return true;
  }

  function validarFormularioBorrador() {
    const titulo = tituloInput.value.trim();
    
    if (titulo === '') {
      alert('El título es obligatorio para guardar como borrador.');
      tituloInput.focus();
      return false;
    }
    
    if (titulo.length < 3) {
      alert('El título debe tener al menos 3 caracteres para guardarlo como borrador.');
      tituloInput.focus();
      return false;
    }
    
    return true;
  }

  function enviarForo() {
    const formData = new FormData();
    formData.append('titulo', tituloInput.value.trim());
    formData.append('texto', textoInput.value.trim());
    formData.append('accion', 'publicar');
    
    if (fotoInput.files[0]) {
      formData.append('foto', fotoInput.files[0]);
    }
    
    // Mostrar loading
    btnGuardarForo.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando...';
    btnGuardarForo.disabled = true;

    // Aquí iría la llamada AJAX al servidor
    // Por ahora simularemos el éxito
    setTimeout(() => {
      // Simular éxito
      alert('¡Foro creado exitosamente!');
      
      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoForo'));
      modal.hide();
      
      // Resetear formulario
      resetFormulario();
      
      // Restaurar botón
      btnGuardarForo.innerHTML = '<i class="bi bi-check-circle"></i> Crear Foro';
      btnGuardarForo.disabled = false;
      
      // Opcional: recargar la página o actualizar la sección de foros
      // location.reload();
    }, 1500);
  }

  function guardarComoBorrador() {
    const formData = new FormData();
    formData.append('titulo', tituloInput.value.trim());
    formData.append('texto', textoInput.value.trim());
    formData.append('accion', 'borrador');
    
    if (fotoInput.files[0]) {
      formData.append('foto', fotoInput.files[0]);
    }
    
    // Mostrar loading
    const btnGuardarBorrador = document.getElementById('btnGuardarBorrador');
    btnGuardarBorrador.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';
    btnGuardarBorrador.disabled = true;

    // Aquí iría la llamada AJAX al servidor
    // Por ahora simularemos el éxito
    setTimeout(() => {
      // Simular éxito
      alert('¡Borrador guardado exitosamente!');
      
      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoForo'));
      modal.hide();
      
      // Resetear formulario
      resetFormulario();
      
      // Restaurar botón
      btnGuardarBorrador.innerHTML = '<i class="bi bi-archive"></i> Guardar como borrador';
      btnGuardarBorrador.disabled = false;
      
      // Mostrar notificación de éxito
      mostrarNotificacionBorrador();
      
    }, 1500);
  }

  function resetFormulario() {
    document.getElementById('formNuevoForo').reset();
    previewContainer.style.display = 'none';
    previewImage.src = '';
    contadorTitulo.textContent = '0';
    contadorTexto.textContent = '0';
    contadorTitulo.classList.remove('text-warning', 'text-danger');
    contadorTexto.classList.remove('text-warning', 'text-danger');
  }

  function mostrarNotificacionBorrador() {
    // Crear notificación toast
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-bg-success border-0 position-fixed';
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1200;';
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">
          <i class="bi bi-check-circle"></i> Borrador guardado. 
          <a href="#" class="text-white fw-bold" onclick="abrirBorradores()">Ver borradores</a>
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remover del DOM después de que se oculte
    toast.addEventListener('hidden.bs.toast', () => {
      document.body.removeChild(toast);
    });
  }

  // Función global para abrir modal de borradores
  window.abrirBorradores = function() {
    const modalBorradores = new bootstrap.Modal(document.getElementById('modalforosBorradores'));
    modalBorradores.show();
  };

  // Resetear formulario cuando se cierra el modal
  const modalNuevoForo = document.getElementById('modalNuevoForo');
  if (modalNuevoForo) {
    modalNuevoForo.addEventListener('hidden.bs.modal', function() {
      resetFormulario();
      
      // Restaurar ambos botones
      btnGuardarForo.innerHTML = '<i class="bi bi-check-circle"></i> Crear Foro';
      btnGuardarForo.disabled = false;
      
      const btnGuardarBorrador = document.getElementById('btnGuardarBorrador');
      if (btnGuardarBorrador) {
        btnGuardarBorrador.innerHTML = '<i class="bi bi-archive"></i> Guardar como borrador';
        btnGuardarBorrador.disabled = false;
      }
    });
  }
});