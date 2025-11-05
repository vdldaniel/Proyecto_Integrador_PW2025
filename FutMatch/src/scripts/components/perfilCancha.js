/**
 * Funcionalidades base del perfil de cancha
 * Funciones comunes compartidas entre jugador y admin
 */

class PerfilCanchaBase {
    constructor() {
        this.inicializarEventosComunes();
    }

    /**
     * Inicializa eventos comunes a todas las vistas de perfil de cancha
     */
    inicializarEventosComunes() {
        // Botón compartir cancha (solo jugadores)
        const btnCompartirCancha = document.getElementById('btnCompartirCancha');
        if (btnCompartirCancha) {
            btnCompartirCancha.addEventListener('click', () => this.compartirCancha());
        }

        // Botón ver en mapa (solo jugadores) 
        const btnVerEnMapa = document.getElementById('btnVerEnMapa');
        if (btnVerEnMapa) {
            btnVerEnMapa.addEventListener('click', () => this.verEnMapa());
        }

        // Botón ver reseñas (solo jugadores)
        const btnVerResenas = document.getElementById('btnVerResenas');
        if (btnVerResenas) {
            btnVerResenas.addEventListener('click', () => this.verResenas());
        }

        // Botones ver detalles de torneos
        const botonesVerDetalles = document.querySelectorAll('.btnVerDetalles');
        botonesVerDetalles.forEach(boton => {
            boton.addEventListener('click', (e) => {
                const torneoId = e.target.getAttribute('data-torneo-id');
                this.verDetallesTorneo(torneoId);
            });
        });

        // Selector de cancha (solo admin)
        const selectorCancha = document.querySelector('.dropdown-menu');
        if (selectorCancha) {
            selectorCancha.addEventListener('click', (e) => {
                if (e.target.classList.contains('dropdown-item')) {
                    e.preventDefault();
                    this.cambiarCancha(e.target);
                }
            });
        }

        // Botón de navegación hacia disponibilidad/agenda
        const btnNavegacion = document.querySelector('[data-url]');
        if (btnNavegacion) {
            btnNavegacion.addEventListener('click', (e) => {
                const url = e.target.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        }
    }

    /**
     * Compartir información de la cancha
     */
    compartirCancha() {
        const nombreCancha = document.getElementById('nombreCancha')?.textContent || 'Cancha';
        const direccion = document.getElementById('direccionCancha')?.textContent || '';
        
        if (navigator.share) {
            navigator.share({
                title: `${nombreCancha} - FutMatch`,
                text: `¡Mirá esta cancha! ${nombreCancha} - ${direccion}`,
                url: window.location.href
            }).catch(err => console.log('Error al compartir:', err));
        } else {
            // Fallback: copiar URL al clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.mostrarNotificacion('¡URL copiada al portapapeles!', 'success');
            }).catch(() => {
                this.mostrarNotificacion('No se pudo copiar la URL', 'error');
            });
        }
    }

    /**
     * Abrir ubicación en Google Maps
     */
    verEnMapa() {
        const direccion = document.getElementById('direccionCancha')?.textContent;
        if (direccion) {
            const urlMaps = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(direccion)}`;
            window.open(urlMaps, '_blank');
        } else {
            this.mostrarNotificacion('No se encontró la dirección', 'error');
        }
    }

    /**
     * Ver todas las reseñas de la cancha
     */
    verResenas() {
        // TODO: Implementar modal o página de reseñas
        console.log('Mostrar reseñas de la cancha');
        this.mostrarNotificacion('Función de reseñas en desarrollo', 'info');
    }

    /**
     * Ver detalles de un torneo específico
     * @param {string} torneoId - ID del torneo
     */
    verDetallesTorneo(torneoId) {
        console.log(`Ver detalles del torneo ${torneoId}`);
        // TODO: Redirigir a página de detalles del torneo o abrir modal
        this.mostrarNotificacion('Redirigiendo a detalles del torneo...', 'info');
    }

    /**
     * Cambiar cancha seleccionada (solo admin)
     * @param {HTMLElement} item - Elemento clickeado del dropdown
     */
    cambiarCancha(item) {
        // Remover clase active de todos los items
        document.querySelectorAll('.dropdown-item').forEach(i => {
            i.classList.remove('active');
            i.innerHTML = i.innerHTML.replace('<i class="bi bi-check-circle"></i>', '<i class="bi bi-building"></i>');
        });

        // Marcar como activo el item seleccionado
        item.classList.add('active');
        item.innerHTML = item.innerHTML.replace('<i class="bi bi-building"></i>', '<i class="bi bi-check-circle"></i>');

        // Actualizar el botón del dropdown
        const dropdownButton = document.querySelector('.dropdown-toggle');
        const textoCancha = item.textContent.trim();
        dropdownButton.innerHTML = `<i class="bi bi-building"></i> ${textoCancha}`;

        // Cargar datos de la nueva cancha
        this.cargarDatosCancha(textoCancha);
    }

    /**
     * Cargar datos de una cancha específica
     * @param {string} nombreCancha - Nombre de la cancha a cargar
     */
    cargarDatosCancha(nombreCancha) {
        console.log(`Cargando datos de: ${nombreCancha}`);
        
        // Actualizar información en la página
        document.getElementById('nombreCancha').textContent = nombreCancha;
        
        // TODO: Hacer petición AJAX para cargar datos reales de la cancha
        this.mostrarNotificacion(`Cancha cambiada a: ${nombreCancha}`, 'success');
    }

    /**
     * Mostrar notificación temporal
     * @param {string} mensaje - Mensaje a mostrar
     * @param {string} tipo - Tipo de notificación (success, error, info, warning)
     */
    mostrarNotificacion(mensaje, tipo = 'info') {
        // Crear elemento de notificación
        const notificacion = document.createElement('div');
        notificacion.className = `alert alert-${tipo === 'error' ? 'danger' : tipo} alert-dismissible fade show position-fixed`;
        notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        
        notificacion.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Agregar al body
        document.body.appendChild(notificacion);

        // Auto-remover después de 3 segundos
        setTimeout(() => {
            if (notificacion.parentNode) {
                notificacion.remove();
            }
        }, 3000);
    }

    /**
     * Actualizar información de la cancha en la interfaz
     * @param {Object} datosCancha - Objeto con datos de la cancha
     */
    actualizarInterfazCancha(datosCancha) {
        // Actualizar elementos si existen
        const elementos = {
            'nombreCancha': datosCancha.nombre,
            'descripcionCancha': datosCancha.descripcion,
            'direccionCancha': datosCancha.direccion,
            'tipoCancha': datosCancha.tipo,
            'superficieCancha': datosCancha.superficie,
            'capacidadCancha': datosCancha.capacidad + ' jugadores'
        };

        Object.keys(elementos).forEach(id => {
            const elemento = document.getElementById(id);
            if (elemento && elementos[id]) {
                elemento.textContent = elementos[id];
            }
        });
    }

    /**
     * Formatear fecha para mostrar en la interfaz
     * @param {string|Date} fecha - Fecha a formatear
     * @returns {string} Fecha formateada
     */
    formatearFecha(fecha) {
        const fechaObj = typeof fecha === 'string' ? new Date(fecha) : fecha;
        return fechaObj.toLocaleDateString('es-AR', {
            weekday: 'short',
            day: 'numeric',
            month: 'numeric'
        });
    }

    /**
     * Formatear hora para mostrar en la interfaz
     * @param {string} hora - Hora en formato HH:mm
     * @returns {string} Hora formateada
     */
    formatearHora(hora) {
        return hora + ':00';
    }
}

// Exportar la clase para uso en otros archivos
window.PerfilCanchaBase = PerfilCanchaBase;