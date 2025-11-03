/**
 * Funcionalidad para la página de listado de canchas del admin
 * Maneja modales, validaciones y eventos de interacción
 */

document.addEventListener('DOMContentLoaded', function() {
    inicializarEventos();
});

/**
 * Inicializa todos los eventos de la página
 */
function inicializarEventos() {
    inicializarModalHistorial();
    inicializarCierreIndefinido();
    inicializarAlertasUbicacion();
    inicializarModalSuspender();
    inicializarCapturarIds();
}

/**
 * Script para mostrar modal de historial
 */
function inicializarModalHistorial() {
    const btnHistorialCanchas = document.getElementById('btnHistorialCanchas');
    if (btnHistorialCanchas) {
        btnHistorialCanchas.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('modalHistorialCanchas'));
            modal.show();
        });
    }
}

/**
 * Script para manejar checkbox de cierre indefinido
 */
function inicializarCierreIndefinido() {
    const cierreIndefinido = document.getElementById('cierreIndefinido');
    if (cierreIndefinido) {
        cierreIndefinido.addEventListener('change', function() {
            const fechaCierre = document.getElementById('fechaCierre');
            fechaCierre.disabled = this.checked;
            if (this.checked) {
                fechaCierre.value = '';
            }
        });
    }
}

/**
 * Maneja las alertas de ubicación en modales
 */
function inicializarAlertasUbicacion() {
    // Mostrar alerta cuando se completa el campo ubicación en modal Agregar
    const ubicacionCancha = document.getElementById('ubicacionCancha');
    if (ubicacionCancha) {
        ubicacionCancha.addEventListener('input', function() {
            const alert = document.getElementById('alertUbicacionAgregar');
            if (this.value.trim().length > 0) {
                alert.classList.remove('d-none');
            } else {
                alert.classList.add('d-none');
            }
        });
    }

    // Mostrar alerta cuando se modifica el campo ubicación en modal Editar
    let ubicacionOriginal = '';
    const modalEditarCancha = document.getElementById('modalEditarCancha');
    if (modalEditarCancha) {
        modalEditarCancha.addEventListener('show.bs.modal', function() {
            const editUbicacionCancha = document.getElementById('editUbicacionCancha');
            ubicacionOriginal = editUbicacionCancha.value;
            document.getElementById('alertUbicacionEditar').classList.add('d-none');
        });
    }

    const editUbicacionCancha = document.getElementById('editUbicacionCancha');
    if (editUbicacionCancha) {
        editUbicacionCancha.addEventListener('input', function() {
            const alert = document.getElementById('alertUbicacionEditar');
            if (this.value.trim() !== ubicacionOriginal && this.value.trim().length > 0) {
                alert.classList.remove('d-none');
            } else {
                alert.classList.add('d-none');
            }
        });
    }
}

/**
 * Script para botón suspender en lugar de eliminar
 */
function inicializarModalSuspender() {
    const btnSuspenderEnLugar = document.getElementById('btnSuspenderEnLugar');
    if (btnSuspenderEnLugar) {
        btnSuspenderEnLugar.addEventListener('click', function() {
            const modalEliminarCancha = document.getElementById('modalEliminarCancha');
            const deleteModal = bootstrap.Modal.getInstance(modalEliminarCancha);
            const canchaId = document.getElementById('deleteCanchaId').value;
            deleteModal.hide();
            
            // Abrir modal de cerrar cancha
            setTimeout(() => {
                document.getElementById('cerrarCanchaId').value = canchaId;
                const cerrarModal = new bootstrap.Modal(document.getElementById('modalCerrarCancha'));
                cerrarModal.show();
                const fechaCierre = document.getElementById('fechaCierre');
                if (fechaCierre) {
                    fechaCierre.focus();
                }
            }, 300);
        });
    }
}

/**
 * Capturar ID de cancha al abrir modales
 */
function inicializarCapturarIds() {
    // Modal Editar Cancha
    document.querySelectorAll('[data-bs-target="#modalEditarCancha"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const editCanchaId = document.getElementById('editCanchaId');
            if (editCanchaId) {
                editCanchaId.value = this.dataset.canchaId;
            }
        });
    });

    // Modal Eliminar Cancha
    document.querySelectorAll('[data-bs-target="#modalEliminarCancha"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const deleteCanchaId = document.getElementById('deleteCanchaId');
            if (deleteCanchaId) {
                deleteCanchaId.value = this.dataset.canchaId;
            }
        });
    });

    // Modal Cerrar Cancha
    document.querySelectorAll('[data-bs-target="#modalCerrarCancha"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const cerrarCanchaId = document.getElementById('cerrarCanchaId');
            if (cerrarCanchaId) {
                cerrarCanchaId.value = this.dataset.canchaId;
            }
        });
    });

    // Modal Restaurar Cancha
    document.querySelectorAll('[data-bs-target="#modalRestaurarCancha"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const restaurarCanchaId = document.getElementById('restaurarCanchaId');
            if (restaurarCanchaId) {
                restaurarCanchaId.value = this.dataset.canchaId;
            }
        });
    });
}

/**
 * Funciones para operaciones CRUD de canchas
 */

/**
 * Crear nueva cancha
 */
function crearCancha() {
    // Validar formulario
    const form = document.getElementById('formAgregarCancha');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Obtener datos del formulario
    const datos = {
        nombre: document.getElementById('nombreCancha').value,
        tipoSuperficie: document.getElementById('tipoSuperficie').value,
        ubicacion: document.getElementById('ubicacionCancha').value,
        descripcion: document.getElementById('descripcionCancha').value,
        capacidad: document.getElementById('capacidadCancha').value
    };
    
    console.log('Creando cancha:', datos);
    // Aquí iría la llamada AJAX al backend
    
    // Simular éxito
    mostrarNotificacion('Cancha creada correctamente', 'success');
    
    // Cerrar modal y limpiar formulario
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalAgregarCancha'));
    modal.hide();
    form.reset();
}

/**
 * Actualizar cancha existente
 */
function actualizarCancha() {
    const form = document.getElementById('formEditarCancha');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const canchaId = document.getElementById('editCanchaId').value;
    const datos = {
        id: canchaId,
        nombre: document.getElementById('editNombreCancha').value,
        tipoSuperficie: document.getElementById('editTipoSuperficie').value,
        ubicacion: document.getElementById('editUbicacionCancha').value,
        descripcion: document.getElementById('editDescripcionCancha').value,
        capacidad: document.getElementById('editCapacidadCancha').value
    };
    
    console.log('Actualizando cancha:', datos);
    // Aquí iría la llamada AJAX al backend
    
    mostrarNotificacion('Cancha actualizada correctamente', 'success');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarCancha'));
    modal.hide();
}

/**
 * Confirmar cierre de cancha
 */
function confirmarCierreCancha() {
    const canchaId = document.getElementById('cerrarCanchaId').value;
    const fechaCierre = document.getElementById('fechaCierre').value;
    const cierreIndefinido = document.getElementById('cierreIndefinido').checked;
    const mensaje = document.getElementById('mensajeCierre').value;
    
    const datos = {
        id: canchaId,
        fechaCierre: cierreIndefinido ? null : fechaCierre,
        indefinido: cierreIndefinido,
        mensaje: mensaje
    };
    
    console.log('Cerrando cancha:', datos);
    // Aquí iría la llamada AJAX al backend
    
    mostrarNotificacion('Cancha cerrada temporalmente', 'warning');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCerrarCancha'));
    modal.hide();
}

/**
 * Confirmar eliminación de cancha
 */
function confirmarEliminacionCancha() {
    const canchaId = document.getElementById('deleteCanchaId').value;
    
    console.log('Eliminando cancha:', canchaId);
    // Aquí iría la llamada AJAX al backend
    
    mostrarNotificacion('Cancha eliminada correctamente', 'danger');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalEliminarCancha'));
    modal.hide();
    
    // Remover fila de la tabla
    // document.querySelector(`tr[data-cancha-id="${canchaId}"]`).remove();
}

/**
 * Confirmar restauración de cancha
 */
function confirmarRestauracionCancha() {
    const canchaId = document.getElementById('restaurarCanchaId').value;
    
    console.log('Restaurando cancha:', canchaId);
    // Aquí iría la llamada AJAX al backend
    
    mostrarNotificacion('Cancha restaurada correctamente. Pendiente de verificación.', 'success');
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalRestaurarCancha'));
    modal.hide();
}

/**
 * Muestra notificaciones usando toast de Bootstrap
 */
function mostrarNotificacion(mensaje, tipo = 'info') {
    // Crear contenedor de toast si no existe
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1200';
        document.body.appendChild(toastContainer);
    }
    
    const colorClass = {
        'success': 'text-bg-success',
        'danger': 'text-bg-danger',
        'warning': 'text-bg-warning',
        'info': 'text-bg-primary'
    }[tipo] || 'text-bg-primary';
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center ${colorClass}`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Eliminar el toast después de que se oculte
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}
