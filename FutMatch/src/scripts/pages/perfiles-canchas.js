/**
 * Perfiles de Cancha Admin JavaScript  
 * Funcionalidad para la página de perfil de cancha desde la perspectiva del admin
 * Extiende PerfilCanchaBase con funcionalidades específicas del administrador
 */

class PerfilCanchaAdmin extends PerfilCanchaBase {
    constructor() {
        super(); // Llamar al constructor de la clase base
        this.inicializarFuncionalidadesAdmin();
    }

    /**
     * Inicializar funcionalidades específicas del admin
     */
    inicializarFuncionalidadesAdmin() {
        this.configurarEventListenersAdmin();
        this.inicializarModalEdicion();
        this.configurarFormularios();
    }

    /**
     * Configurar event listeners específicos del admin
     */
    configurarEventListenersAdmin() {

        // Botón editar perfil
        const btnEditarPerfil = document.getElementById('btnEditarPerfil');
        if (btnEditarPerfil) {
            btnEditarPerfil.addEventListener('click', () => this.editarPerfil());
        }

        // Botones gestionar torneos
        const botonesGestionar = Array.from(document.querySelectorAll("button"))
            .filter(btn => btn.textContent.trim().includes("Gestionar"));

        botonesGestionar.forEach(boton => {
            boton.addEventListener('click', (e) => {

                // Contenedor más cercano del torneo
                const torneoElement = e.target.closest('.border-bottom, .p-4');

                // Nombre del torneo dentro del contenedor
                const torneoNombre = torneoElement?.querySelector('.fw-bold')?.textContent?.trim();

                this.gestionarTorneo(torneoNombre);
            });
        });

        // Botón ver perfil cancha
        const btnVerPerfilCancha = document.getElementById('botonVerPerfilCancha');
        if (btnVerPerfilCancha) {
            btnVerPerfilCancha.addEventListener('click', () => this.verPerfilCompleto());
        }

        // Botón guardar configuración del modal
        const btnGuardarConfig = document.getElementById('botonGuardarConfiguracion');
        if (btnGuardarConfig) {
            btnGuardarConfig.addEventListener('click', () => this.guardarConfiguracion());
        }
    }


    /**
     * Inicializar modal de edición de cancha
     */
    inicializarModalEdicion() {
        const modalEditar = document.getElementById('modalEditarCancha');
        if (modalEditar) {
            modalEditar.addEventListener('show.bs.modal', () => this.cargarDatosEnModal());
            modalEditar.addEventListener('hidden.bs.modal', () => this.limpiarModal());
        }

        // Botón guardar cambios en el modal de edición
        const btnGuardarCambios = modalEditar?.querySelector('.btn-primary');
        if (btnGuardarCambios) {
            btnGuardarCambios.addEventListener('click', () => this.guardarCambiosCancha());
        }
    }

    /**
     * Configurar validaciones de formularios
     */
    configurarFormularios() {
        // Validación de horarios
        const horaApertura = document.getElementById('editHoraApertura');
        const horaCierre = document.getElementById('editHoraCierre');

        if (horaApertura && horaCierre) {
            horaApertura.addEventListener('change', () => this.validarHorarios());
            horaCierre.addEventListener('change', () => this.validarHorarios());
        }

        // Validación de capacidad según tipo de cancha
        const tipoCancha = document.getElementById('editTipoCancha');
        const capacidad = document.getElementById('editCapacidad');

        if (tipoCancha && capacidad) {
            tipoCancha.addEventListener('change', () => this.ajustarCapacidadSegunTipo());
        }
    }

    /**
     * Editar perfil de la cancha
     */
    editarPerfil() {
        // Abrir modal de edición
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditarCancha'));
        modalEditar.show();
    }

    /**
     * Gestionar un torneo específico
     * @param {string} nombreTorneo - Nombre del torneo a gestionar
     */
    gestionarTorneo(nombreTorneo) {
        console.log(`Gestionando torneo: ${nombreTorneo}`);
        // TODO: Redirigir a página de gestión de torneo
        this.mostrarNotificacion(`Abriendo gestión de: ${nombreTorneo}`, 'info');
    }

    /**
     * Ver perfil completo del complejo
     */
    verPerfilCompleto() {
        console.log('Abriendo perfil completo del complejo');
        // TODO: Redirigir a vista completa del perfil
        this.mostrarNotificacion('Abriendo perfil completo...', 'info');
    }

    /**
     * Cargar datos actuales en el modal de edición
     */
    cargarDatosEnModal() {
        // Cargar datos desde la interfaz actual
        const datos = {
            nombre: document.getElementById('nombreCancha')?.textContent,
            descripcion: document.getElementById('descripcionCancha')?.textContent,
            direccion: document.getElementById('direccionCancha')?.textContent,
            tipo: document.getElementById('tipoCancha')?.textContent,
            superficie: document.getElementById('superficieCancha')?.textContent,
            capacidad: document.getElementById('capacidadCancha')?.textContent?.replace(' jugadores', '')
        };

        // Llenar campos del modal
        if (datos.nombre) document.getElementById('editNombreCancha').value = datos.nombre;
        if (datos.descripcion) document.getElementById('editDescripcion').value = datos.descripcion;
        if (datos.direccion) document.getElementById('editDireccion').value = datos.direccion;
        if (datos.capacidad) document.getElementById('editCapacidad').value = datos.capacidad;

        // Seleccionar opciones correctas
        if (datos.tipo) {
            const tipoSelect = document.getElementById('editTipoCancha');
            const tipoValue = datos.tipo.toLowerCase().replace(' ', '').replace('ú', 'u');
            if (tipoSelect) tipoSelect.value = tipoValue;
        }

        if (datos.superficie) {
            const superficieSelect = document.getElementById('editSuperficie');
            const superficieValue = datos.superficie.toLowerCase().replace(' ', '_');
            if (superficieSelect) superficieSelect.value = superficieValue;
        }
    }

    /**
     * Limpiar modal después de cerrarlo
     */
    limpiarModal() {
        const form = document.getElementById('formEditarCancha');
        if (form) {
            form.reset();
        }
    }

    /**
     * Guardar cambios de la cancha
     */
    guardarCambiosCancha() {
        if (!this.validarFormularioEdicion()) {
            return;
        }

        // Obtener datos del formulario
        const datosCancha = {
            nombre: document.getElementById('editNombreCancha').value,
            tipo: document.getElementById('editTipoCancha').value,
            superficie: document.getElementById('editSuperficie').value,
            capacidad: document.getElementById('editCapacidad').value,
            descripcion: document.getElementById('editDescripcion').value,
            direccion: document.getElementById('editDireccion').value,
            horaApertura: document.getElementById('editHoraApertura').value,
            horaCierre: document.getElementById('editHoraCierre').value
        };

        console.log('Guardando cambios:', datosCancha);

        // TODO: Hacer petición AJAX para guardar
        this.mostrarNotificacion('Guardando cambios...', 'info');

        // Actualizar interfaz
        setTimeout(() => {
            this.actualizarInterfazCancha(datosCancha);
            this.mostrarNotificacion('Cambios guardados correctamente', 'success');

            // Cerrar modal
            const modalEditar = bootstrap.Modal.getInstance(document.getElementById('modalEditarCancha'));
            modalEditar.hide();
        }, 1500);
    }

    /**
     * Guardar configuración general del complejo
     */
    guardarConfiguracion() {
        // TODO: Implementar guardado de configuración
        console.log('Guardando configuración del complejo');
        this.mostrarNotificacion('Configuración guardada', 'success');
    }

    /**
     * Validar formulario de edición
     * @returns {boolean} True si es válido
     */
    validarFormularioEdicion() {
        const campos = [
            'editNombreCancha',
            'editTipoCancha',
            'editSuperficie',
            'editCapacidad',
            'editDescripcion',
            'editDireccion',
            'editHoraApertura',
            'editHoraCierre'
        ];

        let esValido = true;

        campos.forEach(campoId => {
            const campo = document.getElementById(campoId);
            if (campo && !campo.value.trim()) {
                campo.classList.add('is-invalid');
                esValido = false;
            } else if (campo) {
                campo.classList.remove('is-invalid');
            }
        });

        if (!this.validarHorarios()) {
            esValido = false;
        }

        if (!esValido) {
            this.mostrarNotificacion('Por favor completa todos los campos correctamente', 'error');
        }

        return esValido;
    }

    /**
     * Validar que los horarios sean coherentes
     * @returns {boolean} True si son válidos
     */
    validarHorarios() {
        const horaApertura = document.getElementById('editHoraApertura')?.value;
        const horaCierre = document.getElementById('editHoraCierre')?.value;

        if (horaApertura && horaCierre) {
            const apertura = new Date(`2000-01-01 ${horaApertura}`);
            const cierre = new Date(`2000-01-01 ${horaCierre}`);

            if (cierre <= apertura) {
                document.getElementById('editHoraCierre')?.classList.add('is-invalid');
                this.mostrarNotificacion('La hora de cierre debe ser posterior a la de apertura', 'error');
                return false;
            } else {
                document.getElementById('editHoraApertura')?.classList.remove('is-invalid');
                document.getElementById('editHoraCierre')?.classList.remove('is-invalid');
                return true;
            }
        }
        return true;
    }

    /**
     * Ajustar capacidad máxima según el tipo de cancha
     */
    ajustarCapacidadSegunTipo() {
        const tipo = document.getElementById('editTipoCancha')?.value;
        const capacidadInput = document.getElementById('editCapacidad');

        if (capacidadInput && tipo) {
            const limites = {
                'futbol5': { min: 8, max: 12 },
                'futbol7': { min: 12, max: 16 },
                'futbol11': { min: 18, max: 24 }
            };

            const limite = limites[tipo];
            if (limite) {
                capacidadInput.setAttribute('min', limite.min);
                capacidadInput.setAttribute('max', limite.max);

                // Ajustar valor si está fuera del rango
                const valorActual = parseInt(capacidadInput.value);
                if (valorActual < limite.min) {
                    capacidadInput.value = limite.min;
                } else if (valorActual > limite.max) {
                    capacidadInput.value = limite.max;
                }
            }
        }
    }
}

// Inicializar cuando el DOM esté listo y exponer globalmente
document.addEventListener('DOMContentLoaded', function () {
    window.perfilCanchaAdmin = new PerfilCanchaAdmin();
});