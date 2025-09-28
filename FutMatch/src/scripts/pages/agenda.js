/**
 * Agenda JavaScript - Funcionalidad para la aplicación de agenda de canchas
 * Maneja la visualización del calendario, reservas, y navegación entre vistas
 */

// Reemplazar con back-end
const CONFIGURACION_CALENDARIO = {
    DIAS: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    MESES: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    HORARIOS: {
        HORA_INICIO: 8,
        HORA_FIN: 22,
        INTERVALO: 1 // horas
    }
};

class AplicacionAgenda {
    constructor() {
        this.fechaActual = new Date();
        this.vistaActual = 'mes';
        this.canchaSeleccionada = null;
        this.reservas = [];
        
        // Solicitudes hardcodeadas (reemplazar con back-end)
        this.solicitudesPendientes = [
            {
                id: 1,
                jugador: {
                    nombre: 'Juan Pérez',
                    telefono: '+54 11 2345-6789',
                    id: 101
                },
                cancha: {
                    id: 1,
                    nombre: 'Cancha A'
                },
                fecha: '2025-09-29', // Mañana
                hora: '16:00',
                fechaSolicitud: '2025-09-27',
                estado: 'pending'
            },
            {
                id: 2,
                jugador: {
                    nombre: 'María González',
                    telefono: '+54 11 8765-4321',
                    id: 102
                },
                cancha: {
                    id: 2,
                    nombre: 'Cancha B'
                },
                fecha: '2025-09-30', // Pasado mañana
                hora: '14:00',
                fechaSolicitud: '2025-09-28',
                estado: 'pending'
            },
            {
                id: 3,
                jugador: {
                    nombre: 'Carlos Ruiz',
                    telefono: '+54 11 9876-5432',
                    id: 103
                },
                cancha: {
                    id: 1,
                    nombre: 'Cancha A'
                },
                fecha: '2025-09-28', // Hoy
                hora: '18:00',
                fechaSolicitud: '2025-09-27',
                estado: 'pending'
            }
        ];
        
        // Configuración hardcodeada de la cancha (reemplazar con back-end)
        this.configuracionCancha = {
            horaApertura: '08:00',
            horaCierre: '22:00',
            diasOperacion: {
                lunes: true,
                martes: true,
                miercoles: true,
                jueves: true,
                viernes: true,
                sabado: true,
                domingo: true
            },
            canchaInfo: {
                nombre: 'Complejo Deportivo Central',
                direccion: 'Av. Principal 123, Buenos Aires',
                telefono: '+54 11 1234-5678'
            }
        };
        
        // Caché de elementos DOM frecuentemente accedidos
        this.elementos = {};
        
        this.inicializar();
    }

    inicializar() {
        this.cachearElementos();
        this.vincularEventos();
        this.inicializarVistaDefecto();
        this.actualizarDisplayFecha();
        this.generarDatosMuestra(); // reemplazar con back-end - generar datos ANTES de renderizar
        this.renderizarVistaActual(); // ahora ya tenemos datos para mostrar badges
        this.renderizarSolicitudesModal();
        this.renderizarModalConfiguracion();
        this.actualizarContadorNotificaciones();
    }

    // Renderizar solicitudes en el modal dinámicamente
    renderizarSolicitudesModal() {
        const listaSolicitudes = document.getElementById('listaSolicitudes');
        const sinSolicitudes = document.getElementById('sinSolicitudes');
        
        if (this.solicitudesPendientes.length === 0) {
            listaSolicitudes.classList.add('d-none');
            sinSolicitudes.classList.remove('d-none');
            return;
        }
        
        listaSolicitudes.classList.remove('d-none');
        sinSolicitudes.classList.add('d-none');
        
        listaSolicitudes.innerHTML = this.solicitudesPendientes.map(solicitud => {
            const fechaSolicitud = new Date(solicitud.fecha);
            const fechaTexto = this.formatearFechaSolicitud(fechaSolicitud);
            
            return `
                <div class="card mb-3 border-warning" data-solicitud-id="${solicitud.id}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${solicitud.jugador.nombre}</strong> - <span class="text-warning">${solicitud.cancha.nombre}</span>
                            <br><small class="text-muted">${fechaTexto} a las ${solicitud.hora}</small>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm me-1" onclick="aceptarSolicitud(${solicitud.id})">
                                <i class="bi bi-check-lg"></i> Aceptar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(${solicitud.id})">
                                <i class="bi bi-x-lg"></i> Rechazar
                            </button>
                        </div>
                    </div>
                    <div class="collapse" id="detallesSolicitud${solicitud.id}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Teléfono:</strong> ${solicitud.jugador.telefono}
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-outline-info btn-sm" onclick="verPerfilJugador(${solicitud.jugador.id})">
                                        <i class="bi bi-person"></i> Ver perfil jugador
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-link btn-sm p-0" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#detallesSolicitud${solicitud.id}">
                            <i class="bi bi-chevron-down"></i> Ver detalles
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Formatear fecha para mostrar en solicitudes
    formatearFechaSolicitud(fecha) {
        const hoy = new Date();
        const manana = new Date(hoy);
        manana.setDate(hoy.getDate() + 1);
        const pasadoManana = new Date(hoy);
        pasadoManana.setDate(hoy.getDate() + 2);
        
        if (fecha.toDateString() === hoy.toDateString()) {
            return 'Hoy ' + fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        } else if (fecha.toDateString() === manana.toDateString()) {
            return 'Mañana ' + fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        } else if (fecha.toDateString() === pasadoManana.toDateString()) {
            return 'Pasado mañana ' + fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        } else {
            return fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        }
    }

    // Actualizar contador de notificaciones
    actualizarContadorNotificaciones() {
        const badges = document.querySelectorAll('.badge.bg-danger');
        const cantidad = this.solicitudesPendientes.length;
        
        badges.forEach(badge => {
            if (cantidad > 0) {
                badge.textContent = cantidad.toString();
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
            }
        });
    }

    // Contar solicitudes para una fecha y hora específicos
    contarSolicitudesParaFechaHora(fecha, hora) {
        return this.solicitudesPendientes.filter(solicitud => {
            const fechaSolicitud = new Date(solicitud.fecha);
            return fechaSolicitud.toDateString() === fecha.toDateString() && 
                   solicitud.hora === hora;
        }).length;
    }

    // Crear indicador de solicitudes (badge amarillo) - estilo similar al de reservas
    crearIndicadorSolicitudes(cantidad, esVistaDetallada = true) {
        if (cantidad === 0) return null;
        
        const indicador = document.createElement('small');
        if (esVistaDetallada) {
            // Para vistas semanal y diaria - badge más grande y clickeable
            indicador.className = 'badge bg-warning text-dark ms-1';
            indicador.style.cursor = 'pointer';
            indicador.style.position = 'absolute';
            indicador.style.right = '4px';
            indicador.style.top = '4px';
            indicador.title = `${cantidad} solicitud${cantidad > 1 ? 'es' : ''} pendiente${cantidad > 1 ? 's' : ''} - Click para gestionar`;
        } else {
            // Para vista mensual - badge pequeño como el de reservas
            indicador.className = 'badge bg-warning text-dark ms-1';
            indicador.title = `${cantidad} solicitud${cantidad > 1 ? 'es' : ''} pendiente${cantidad > 1 ? 's' : ''}`;
        }
        
        indicador.textContent = cantidad.toString();
        return indicador;
    }

    // Contar solicitudes para una fecha específica (todas las horas)
    contarSolicitudesParaFecha(fecha) {
        return this.solicitudesPendientes.filter(solicitud => {
            const fechaSolicitud = new Date(solicitud.fecha);
            return fechaSolicitud.toDateString() === fecha.toDateString();
        }).length;
    }

    // Abrir modal de notificaciones y hacer focus en una solicitud específica
    abrirModalNotificacionesConFocus(fecha, hora) {
        // Abrir el modal
        const modalElement = document.getElementById('modalNotificaciones');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
            
            // Encontrar la primera solicitud que coincida con la fecha y hora
            const solicitudCoincidente = this.solicitudesPendientes.find(solicitud => {
                const fechaSolicitud = new Date(solicitud.fecha);
                return fechaSolicitud.toDateString() === fecha.toDateString() && 
                       solicitud.hora === hora;
            });
            
            if (solicitudCoincidente) {
                // Esperar a que el modal se abra completamente antes de hacer scroll
                modalElement.addEventListener('shown.bs.modal', () => {
                    const cardSolicitud = document.querySelector(`#listaSolicitudes [data-solicitud-id="${solicitudCoincidente.id}"]`);
                    if (cardSolicitud) {
                        cardSolicitud.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Resaltar temporalmente la solicitud
                        cardSolicitud.style.boxShadow = '0 0 0 3px rgba(255, 193, 7, 0.5)';
                        setTimeout(() => {
                            cardSolicitud.style.boxShadow = '';
                        }, 2000);
                    }
                }, { once: true });
            }
        }
    }

    // Configurar modal de configuración con datos y validación
    renderizarModalConfiguracion() {
        // Cargar datos en los campos del formulario
        this.cargarDatosConfiguracion();
        
        // Configurar validación en tiempo real
        this.configurarValidacionHorarios();
        
        // Configurar event listeners
        this.configurarBotonGuardarConfiguracion();
        this.configurarBotonVerPerfilCancha();
    }
    
    // Configurar event listener para el botón guardar configuración
    configurarBotonGuardarConfiguracion() {
        const botonGuardar = document.getElementById('botonGuardarConfiguracion');
        if (botonGuardar) {
            // Remover listener anterior si existe
            botonGuardar.removeEventListener('click', this.guardarConfiguracion);
            
            // Crear función bound para mantener el contexto
            this.guardarConfiguracion = () => {
                this.procesarGuardarConfiguracion();
            };
            
            // Agregar nuevo listener
            botonGuardar.addEventListener('click', this.guardarConfiguracion);
        }
    }
    
    // Procesar el guardado de configuración
    procesarGuardarConfiguracion() {
        const formulario = document.getElementById('configuracionForm');
        
        // Validación del formulario
        if (!formulario.checkValidity()) {
            formulario.reportValidity();
            return;
        }
        
        // Validación adicional de horarios
        const horaApertura = document.getElementById('horaApertura');
        const horaCierre = document.getElementById('horaCierre');
        
        if (horaApertura.value && horaCierre.value) {
            const convertirAMinutos = (tiempo) => {
                const [horas, minutos] = tiempo.split(':').map(Number);
                return horas * 60 + minutos;
            };
            
            const minutosApertura = convertirAMinutos(horaApertura.value);
            const minutosCierre = convertirAMinutos(horaCierre.value);
            
            // Validar que apertura sea antes que cierre
            if (minutosApertura >= minutosCierre) {
                alert('La hora de apertura debe ser anterior a la de cierre');
                horaApertura.focus();
                return;
            }
            
            // Validar duración mínima
            if (minutosCierre - minutosApertura < 60) {
                alert('Debe haber al menos 1 hora de diferencia entre apertura y cierre');
                return;
            }
            
            // Validar horarios lógicos
            if (minutosApertura < 360 || minutosCierre > 1440) { // 6:00 AM a 24:00
                alert('Los horarios deben estar entre 06:00 y 24:00');
                return;
            }
        }
        
        // Validar que al menos un día esté seleccionado
        const diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        const algunDiaSeleccionado = diasSemana.some(dia => {
            const checkbox = document.getElementById(dia);
            return checkbox && checkbox.checked;
        });
        
        if (!algunDiaSeleccionado) {
            alert('Debe seleccionar al menos un día de operación');
            return;
        }
        
        // Actualizar configuración
        this.actualizarConfiguracionDesdeFormulario();
        
        // Mostrar confirmación
        const resumen = this.generarResumenConfiguracion();
        if (confirm(`¿Confirmar los siguientes cambios?\n\n${resumen}`)) {
            // TODO: Enviar al backend
            alert('Configuración guardada exitosamente');
            
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfiguracion'));
            if (modal) modal.hide();
            
            // Actualizar vista si es necesario
            this.renderizarVistaActual();
        }
    }
    
    // Generar resumen de configuración para confirmación
    generarResumenConfiguracion() {
        return `Horarios: ${this.configuracionCancha.horaApertura} - ${this.configuracionCancha.horaCierre}\nDías: ${this.obtenerDiasSeleccionados()}`;
    }
    
    // Obtener días seleccionados como texto
    obtenerDiasSeleccionados() {
        const diasTexto = {
            lunes: 'Lun', martes: 'Mar', miercoles: 'Mié', 
            jueves: 'Jue', viernes: 'Vie', sabado: 'Sáb', domingo: 'Dom'
        };
        
        return Object.keys(this.configuracionCancha.diasOperacion)
            .filter(dia => this.configuracionCancha.diasOperacion[dia])
            .map(dia => diasTexto[dia])
            .join(', ');
    }
    
    // Configurar event listener para el botón ver perfil cancha
    configurarBotonVerPerfilCancha() {
        const botonVerPerfil = document.getElementById('botonVerPerfilCancha');
        if (botonVerPerfil) {
            botonVerPerfil.addEventListener('click', () => {
                this.verPerfilCancha();
            });
        }
    }
    
    // Ver perfil de cancha
    verPerfilCancha() {
        // TODO: Redirigir a página de perfil de cancha o abrir modal de perfil
        console.log('Ver perfil de cancha');
        alert('Redirigiendo a perfil de cancha...');
    }
    
    // Cargar datos de configuración en el formulario
    cargarDatosConfiguracion() {
        // Cargar horarios
        const horaApertura = document.getElementById('horaApertura');
        const horaCierre = document.getElementById('horaCierre');
        
        if (horaApertura) horaApertura.value = this.configuracionCancha.horaApertura;
        if (horaCierre) horaCierre.value = this.configuracionCancha.horaCierre;
        
        // Cargar días de operación
        const diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        diasSemana.forEach(dia => {
            const checkbox = document.getElementById(dia);
            if (checkbox) {
                checkbox.checked = this.configuracionCancha.diasOperacion[dia];
            }
        });
        
        // Cargar información del complejo
        const nombreComplejo = document.getElementById('nombreComplejo');
        const direccionComplejo = document.getElementById('direccionComplejo');
        const telefonoComplejo = document.getElementById('telefonoComplejo');
        
        if (nombreComplejo) nombreComplejo.textContent = this.configuracionCancha.canchaInfo.nombre;
        if (direccionComplejo) direccionComplejo.textContent = this.configuracionCancha.canchaInfo.direccion;
        if (telefonoComplejo) telefonoComplejo.textContent = this.configuracionCancha.canchaInfo.telefono;
    }
    
    // Configurar validación en tiempo real para los horarios
    configurarValidacionHorarios() {
        const horaApertura = document.getElementById('horaApertura');
        const horaCierre = document.getElementById('horaCierre');
        
        if (!horaApertura || !horaCierre) return;
        
        const validarHorarios = () => {
            if (!horaApertura.value || !horaCierre.value) return;
            
            const convertirAMinutos = (tiempo) => {
                const [horas, minutos] = tiempo.split(':').map(Number);
                return horas * 60 + minutos;
            };
            
            const minutosApertura = convertirAMinutos(horaApertura.value);
            const minutosCierre = convertirAMinutos(horaCierre.value);
            
            // Limpiar estilos previos
            horaApertura.classList.remove('is-invalid', 'is-valid');
            horaCierre.classList.remove('is-invalid', 'is-valid');
            
            // Remover mensajes de error previos
            const mensajesError = document.querySelectorAll('.horario-error');
            mensajesError.forEach(msg => msg.remove());
            
            if (minutosApertura >= minutosCierre) {
                // Error: apertura >= cierre
                horaApertura.classList.add('is-invalid');
                horaCierre.classList.add('is-invalid');
                
                const mensajeError = document.createElement('div');
                mensajeError.className = 'invalid-feedback horario-error';
                mensajeError.textContent = 'La hora de apertura debe ser anterior a la de cierre';
                horaCierre.parentNode.appendChild(mensajeError);
                
            } else if (minutosCierre - minutosApertura < 60) {
                // Advertencia: menos de 1 hora de diferencia
                horaApertura.classList.add('is-invalid');
                horaCierre.classList.add('is-invalid');
                
                const mensajeError = document.createElement('div');
                mensajeError.className = 'invalid-feedback horario-error';
                mensajeError.textContent = 'Debe haber al menos 1 hora de diferencia';
                horaCierre.parentNode.appendChild(mensajeError);
                
            } else {
                // Válido
                horaApertura.classList.add('is-valid');
                horaCierre.classList.add('is-valid');
            }
        };
        
        // Agregar event listeners
        horaApertura.addEventListener('change', validarHorarios);
        horaCierre.addEventListener('change', validarHorarios);
        horaApertura.addEventListener('input', validarHorarios);
        horaCierre.addEventListener('input', validarHorarios);
        
        // Validar inicialmente
        setTimeout(validarHorarios, 100);
    }

    // Actualizar configuración desde el formulario
    actualizarConfiguracionDesdeFormulario() {
        this.configuracionCancha.horaApertura = document.getElementById('horaApertura').value;
        this.configuracionCancha.horaCierre = document.getElementById('horaCierre').value;
        
        // Actualizar días de operación
        const diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        diasSemana.forEach(dia => {
            const checkbox = document.getElementById(dia);
            if (checkbox) {
                this.configuracionCancha.diasOperacion[dia] = checkbox.checked;
            }
        });
    }


    // Cachear elementos DOM para optimizar rendimiento
    cachearElementos() {
        this.elementos = {
            // Vistas del calendario
            vistaMensual: document.getElementById('vistaMensual'),
            vistaSemanal: document.getElementById('vistaSemanal'),
            vistaDiaria: document.getElementById('vistaDiaria'),
            
            // Navegación
            botonHoy: document.getElementById('botonHoy'),
            botonAnterior: document.getElementById('botonAnterior'),
            botonSiguiente: document.getElementById('botonSiguiente'),
            selectorFecha: document.getElementById('selectorFecha'),
            displayFechaActual: document.getElementById('displayFechaActual'),
            
            // Sidebar
            selectorCancha: document.getElementById('selectorCancha'),
            botonCrearReserva: document.getElementById('botonCrearReserva'),
            
            // Headers
            encabezadoVistaDiaria: document.getElementById('encabezadoVistaDiaria')
        };
    }
    
    // Inicializar vista por defecto
    // Esto fue agregado porque apenas arrancaba la pestaña no mostraba la vista mensual
    inicializarVistaDefecto() {
        // Establecer vista mensual como activa (solo si no está ya presente)
        if (!document.body.classList.contains('vista-mensual-activa')) {
            document.body.classList.add('vista-mensual-activa');
        }
        
        // Asegurar que la vista mensual esté visible por defecto
        if (this.elementos.vistaMensual) this.elementos.vistaMensual.classList.remove('d-none');
        if (this.elementos.vistaSemanal) this.elementos.vistaSemanal.classList.add('d-none');
        if (this.elementos.vistaDiaria) this.elementos.vistaDiaria.classList.add('d-none');
        
        // Actualizar selector de fecha con fecha actual
        if (this.elementos.selectorFecha) {
            this.elementos.selectorFecha.value = this.formatearFechaParaInput(this.fechaActual);
        }
    }

    vincularEventos() {
        // Configurar todos los event listeners inmediatamente ya que el DOM está listo
        this.configurarCambioVistas();
        this.configurarNavegacionFecha();
        this.configurarBarraLateral();
        this.configurarSelectorFecha();
        this.configurarResetModalAlCerrar();
    }

    // Configurar reset del modal al cerrarse
    configurarResetModalAlCerrar() {
        const modal = document.getElementById('modalGestionReserva');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                this.limpiarFormularioReserva();
            });
        }
    }


    // Configurar cambio de vistas (mensual, semanal, diaria) 
    configurarCambioVistas() {
        // Seleccionar todos los elementos con data-vista (botones y dropdown items)
        const selectoresVista = document.querySelectorAll('[data-vista]');
        
        selectoresVista.forEach(selector => {
            selector.addEventListener('click', (e) => {
                e.preventDefault();
                const vistaObjetivo = selector.dataset.vista;
                this.cambiarVista(vistaObjetivo);
                
                // Actualizar estado activo solo en botones (no en dropdown items)
                if (selector.tagName === 'BUTTON') {
                    const botones = document.querySelectorAll('button[data-vista]');
                    botones.forEach(btn => btn.classList.remove('active'));
                    selector.classList.add('active');
                }
            });
        });
    }

    // Configurar navegación de fechas
    configurarNavegacionFecha() {
        if (this.elementos.botonHoy) {
            this.elementos.botonHoy.addEventListener('click', (e) => {
                e.preventDefault();
                this.irAHoy();
            });
        }
        
        if (this.elementos.botonAnterior) {
            this.elementos.botonAnterior.addEventListener('click', (e) => {
                e.preventDefault();
                this.navegarFecha(-1);
            });
        }
        
        if (this.elementos.botonSiguiente) {
            this.elementos.botonSiguiente.addEventListener('click', (e) => {
                e.preventDefault();
                this.navegarFecha(1);
            });
        }
    }

    // Configurar sidebar y selección de cancha
    configurarBarraLateral() {
        if (this.elementos.selectorCancha) {
            this.elementos.selectorCancha.addEventListener('change', (e) => {
                this.canchaSeleccionada = e.target.value;
                this.renderizarVistaActual();
                this.actualizarContadorInsignia();
            });
        }
        
        if (this.elementos.botonCrearReserva) {
            this.elementos.botonCrearReserva.addEventListener('click', (e) => {
                this.crearReserva();
            });
        }
        
        // El botón de gestionar solicitudes usa data-bs-toggle="modal" de Bootstrap
        // No necesita event listener adicional
    }

    // Métodos preparados para integración con backend
    crearReserva(fechaSeleccionada = null, horaSeleccionada = null) {
        this.abrirModalReserva('crear', null, fechaSeleccionada, horaSeleccionada);
    }

    // Abrir modal para ver/editar reserva existente
    verEditarReserva(idReserva) {
        const reserva = this.reservas.find(r => r.id === idReserva);
        if (reserva) {
            this.abrirModalReserva('editar', reserva);
        }
    }

    // Función unificada para abrir modal en diferentes modos
    abrirModalReserva(modo, reserva = null, fechaSeleccionada = null, horaSeleccionada = null) {
        const modal = new bootstrap.Modal(document.getElementById('modalGestionReserva'));
        this.configurarEventListenersModal();
        
        // Configurar título y botones según el modo
        this.configurarModalSegunModo(modo, reserva);
        
        if (modo === 'crear') {
            this.prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada);
        } else if (modo === 'editar') {
            this.prellenarFormularioEditar(reserva);
        }
        
        modal.show();
    }

    // Configurar modal según el modo (crear/editar)
    configurarModalSegunModo(modo, reserva) {
        const tituloModal = document.getElementById('tituloModal');
        const textoBoton = document.getElementById('textoBoton');
        const botonEliminar = document.getElementById('botonEliminarReserva');
        const seccionEstado = document.getElementById('seccionEstado');
        
        if (modo === 'crear') {
            tituloModal.textContent = 'Crear Nueva Reserva';
            textoBoton.textContent = 'Crear Reserva';
            botonEliminar.classList.add('d-none');
            seccionEstado.classList.add('d-none');
        } else if (modo === 'editar') {
            tituloModal.textContent = reserva.esExterna ? 'Reserva Externa - Detalles' : 'Reserva App - Detalles';
            textoBoton.textContent = 'Guardar Cambios';
            botonEliminar.classList.remove('d-none');
            seccionEstado.classList.remove('d-none');
        }
    }

    // Pre-llenar formulario para crear
    prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada) {
        // Limpiar formulario
        this.limpiarFormularioReserva();
        
        // Pre-llenar cancha si hay una seleccionada
        const canchaReserva = document.getElementById('canchaReserva');
        if (this.canchaSeleccionada && canchaReserva) {
            canchaReserva.value = this.canchaSeleccionada;
        }
        
        // Pre-llenar fecha
        const fechaReserva = document.getElementById('fechaReserva');
        if (fechaReserva) {
            const fechaAPrellenar = fechaSeleccionada || this.fechaActual;
            fechaReserva.value = this.formatearFechaParaInput(fechaAPrellenar);
        }
        
        // Pre-llenar hora si se especifica
        const horaReserva = document.getElementById('horaReserva');
        if (horaReserva && horaSeleccionada) {
            horaReserva.value = horaSeleccionada;
        }
    }

    // Pre-llenar formulario para editar
    prellenarFormularioEditar(reserva) {
        // Llenar campos ocultos
        document.getElementById('idReserva').value = reserva.id;
        
        // Llenar campos de estado
        document.getElementById('estadoReserva').value = reserva.status;
        document.getElementById('fechaCreacion').value = reserva.fechaCreacion.toLocaleDateString();
        
        // Llenar checkbox reserva externa
        const reservaExterna = document.getElementById('reservaExterna');
        reservaExterna.checked = reserva.esExterna;
        
        // Trigger del evento change para habilitar/deshabilitar campos
        reservaExterna.dispatchEvent(new Event('change'));
        
        // Llenar datos del jugador o externos
        if (reserva.esExterna) {
            document.getElementById('nombreExterno').value = reserva.nombreExterno || '';
            document.getElementById('telefonoExterno').value = reserva.telefonoExterno || '';
        } else {
            document.getElementById('idJugador').value = reserva.jugadorId || '';
        }
        
        // Llenar detalles de la reserva
        document.getElementById('canchaReserva').value = reserva.cancha;
        document.getElementById('fechaReserva').value = this.formatearFechaParaInput(reserva.date);
        document.getElementById('horaReserva').value = reserva.time;
        document.getElementById('comentarioReserva').value = reserva.comentario || '';
    }

    // Crear reserva desde una celda específica (vista semanal/diaria)
    crearReservaEnHorario(fecha, hora) {
        this.crearReserva(fecha, hora);
    }

    // Configurar event listeners del modal
    configurarEventListenersModal() {
        // Toggle campos externos
        const reservaExterna = document.getElementById('reservaExterna');
        const nombreExterno = document.getElementById('nombreExterno');
        const telefonoExterno = document.getElementById('telefonoExterno');
        const idJugador = document.getElementById('idJugador');
        
        if (reservaExterna) {
            // Remover listeners anteriores
            reservaExterna.removeEventListener('change', this.alternarCamposExternos);
            
            // Agregar nuevo listener
            this.alternarCamposExternos = function() {
                if (this.checked) {
                    // Habilitar campos externos
                    nombreExterno.disabled = false;
                    telefonoExterno.disabled = false;
                    nombreExterno.required = true;
                    telefonoExterno.required = true;
                    
                    // Deshabilitar campo ID jugador
                    idJugador.disabled = true;
                    idJugador.required = false;
                    idJugador.value = ''; // Limpiar valor
                } else {
                    // Deshabilitar campos externos
                    nombreExterno.disabled = true;
                    telefonoExterno.disabled = true;
                    nombreExterno.required = false;
                    telefonoExterno.required = false;
                    nombreExterno.value = ''; // Limpiar valores
                    telefonoExterno.value = '';
                    
                    // Habilitar campo ID jugador
                    idJugador.disabled = false;
                    idJugador.required = true;
                }
            };
            
            reservaExterna.addEventListener('change', this.alternarCamposExternos);
        }

        // Botón guardar reserva
        const botonGuardarReserva = document.getElementById('botonGuardarReserva');
        if (botonGuardarReserva) {
            botonGuardarReserva.removeEventListener('click', this.procesarGuardarReserva);
            this.procesarGuardarReserva = () => {
                this.procesarFormularioReserva();
            };
            botonGuardarReserva.addEventListener('click', this.procesarGuardarReserva);
        }
        
        // Botón eliminar reserva
        const botonEliminarReserva = document.getElementById('botonEliminarReserva');
        if (botonEliminarReserva) {
            botonEliminarReserva.removeEventListener('click', this.manejadorEliminarReserva);
            this.manejadorEliminarReserva = () => {
                this.eliminarReserva();
            };
            botonEliminarReserva.addEventListener('click', this.manejadorEliminarReserva);
        }
    }

    // Procesar formulario de reserva (crear o editar)
    procesarFormularioReserva() {
        const formulario = document.getElementById('formularioGestionReserva');
        const idReserva = document.getElementById('idReserva').value;
        const esEdicion = idReserva && idReserva !== '';
        
        // Validación personalizada de cancha
        const canchaReserva = document.getElementById('canchaReserva');
        if (!canchaReserva.value || canchaReserva.value === '') {
            this.mostrarMensajeError('Por favor seleccione una cancha');
            canchaReserva.focus();
            return;
        }
        
        // Validación estándar del formulario
        if (!formulario.checkValidity()) {
            formulario.reportValidity();
            return;
        }
        
        // Validación adicional para hora de reserva
        const horaInput = document.getElementById('horaReserva');
        const horaValue = horaInput.value;
        if (horaValue) {
            const convertirAMinutos = (tiempo) => {
                const [horas, minutos] = tiempo.split(':').map(Number);
                return horas * 60 + minutos;
            };
            
            const minutosReserva = convertirAMinutos(horaValue);
            const minutosMin = convertirAMinutos('08:00');
            const minutosMax = convertirAMinutos('22:00');
            
            if (minutosReserva < minutosMin || minutosReserva > minutosMax) {
                alert('La hora de reserva debe estar entre las 08:00 y las 22:00');
                horaInput.focus();
                return;
            }
        }

        // Recopilar datos del formulario
        const datosReserva = {
            id: esEdicion ? parseInt(idReserva) : this.generarNuevoId(),
            jugadorId: document.getElementById('idJugador').value || null,
            esExterna: document.getElementById('reservaExterna').checked,
            nombreExterno: document.getElementById('nombreExterno').value || null,
            telefonoExterno: document.getElementById('telefonoExterno').value || null,
            cancha: document.getElementById('canchaReserva').value,
            fecha: document.getElementById('fechaReserva').value,
            hora: document.getElementById('horaReserva').value,
            comentario: document.getElementById('comentarioReserva').value || '',
            status: esEdicion ? document.getElementById('estadoReserva').value : 'pending',
            fechaCreacion: esEdicion ? this.reservas.find(r => r.id == idReserva).fechaCreacion : new Date()
        };

        // Convertir fecha string a objeto Date
        const [anio, mes, dia] = datosReserva.fecha.split('-').map(num => parseInt(num));
        datosReserva.date = new Date(anio, mes - 1, dia);
        datosReserva.time = datosReserva.hora;
        
        // Generar título y descripción
        datosReserva.title = datosReserva.esExterna ? 'Reserva Externa' : `Reserva Jugador #${datosReserva.jugadorId}`;
        datosReserva.description = `Cancha ${this.obtenerNombreCancha(datosReserva.cancha)} - ${this.obtenerTextoEstado(datosReserva.status)}`;

        console.log('Datos de la reserva:', datosReserva);
        
        if (esEdicion) {
            this.actualizarReserva(datosReserva);
        } else {
            this.crearNuevaReserva(datosReserva);
        }
    }
    
    // Crear nueva reserva
    crearNuevaReserva(datosReserva) {
        // TODO: Enviar al backend
        // await this.enviarReservaAlBackend(datosReserva);
        
        this.reservas.push(datosReserva);
        this.mostrarMensajeExito('Reserva creada exitosamente');
        this.cerrarModal();
    }
    
    // Actualizar reserva existente
    actualizarReserva(datosReserva) {
        // TODO: Enviar al backend
        // await this.actualizarReservaEnBackend(datosReserva);
        
        const indice = this.reservas.findIndex(r => r.id === datosReserva.id);
        if (indice !== -1) {
            this.reservas[indice] = datosReserva;
            this.mostrarMensajeExito('Reserva actualizada exitosamente');
            this.cerrarModal();
        }
    }
    
    // Eliminar reserva
    eliminarReserva() {
        const idReserva = parseInt(document.getElementById('idReserva').value);
        
        if (confirm('¿Está seguro que desea eliminar esta reserva?')) {
            // TODO: Enviar al backend
            // await this.eliminarReservaEnBackend(idReserva);
            
            this.reservas = this.reservas.filter(r => r.id !== idReserva);
            this.mostrarMensajeExito('Reserva eliminada exitosamente');
            this.cerrarModal();
        }
    }
    
    // Cerrar modal y actualizar vista
    cerrarModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalGestionReserva'));
        modal.hide();
        this.limpiarFormularioReserva();
        this.renderizarVistaActual();
    }

    // Limpiar formulario del modal
    limpiarFormularioReserva() {
        const formulario = document.getElementById('formularioGestionReserva');
        if (formulario) {
            formulario.reset();
            
            // Limpiar campos ocultos
            document.getElementById('idReserva').value = '';
            
            // Restaurar estado inicial de los campos
            const nombreExterno = document.getElementById('nombreExterno');
            const telefonoExterno = document.getElementById('telefonoExterno');
            const idJugador = document.getElementById('idJugador');
            
            // Deshabilitar campos externos por defecto
            nombreExterno.disabled = true;
            telefonoExterno.disabled = true;
            nombreExterno.required = false;
            telefonoExterno.required = false;
            
            // Habilitar campo ID jugador por defecto
            idJugador.disabled = false;
            idJugador.required = true;
        }
    }
    
    // Funciones auxiliares
    generarNuevoId() {
        return Math.max(...this.reservas.map(r => r.id)) + 1;
    }
    
    obtenerNombreCancha(idCancha) {
        const nombres = { '1': 'A', '2': 'B', '3': 'C' };
        return nombres[idCancha] || idCancha;
    }
    
    obtenerTextoEstado(estado) {
        const textos = {
            'pending': 'Pendiente',
            'confirmed': 'Confirmada',
            'cancelled': 'Cancelada'
        };
        return textos[estado] || estado;
    }

    // Mostrar mensaje de éxito (temporal hasta integrar con backend)
    mostrarMensajeExito(mensaje) {
        // TODO: Implementar sistema de notificaciones
        alert(mensaje);
    }

    // Mostrar mensaje de error (temporal hasta integrar con backend)
    mostrarMensajeError(mensaje) {
        // TODO: Implementar sistema de notificaciones
        alert('Error: ' + mensaje);
    }

    // Configurar selector de fecha
    configurarSelectorFecha() {
        if (this.elementos.selectorFecha) {
            // Establecer fecha actual por defecto
            this.elementos.selectorFecha.value = this.formatearFechaParaInput(this.fechaActual);
            
            this.elementos.selectorFecha.addEventListener('change', (e) => {
                // Crear fecha usando componentes individuales para evitar problemas de zona horaria
                const cadenaFecha = e.target.value;
                const [anio, mes, dia] = cadenaFecha.split('-').map(num => parseInt(num));
                this.fechaActual = new Date(anio, mes - 1, dia); // mes - 1 porque los meses son 0-indexados
                this.actualizarDisplayFecha();
                this.renderizarVistaActual();
            });
        }
    }

    // Cambiar vista del calendario
    cambiarVista(vista) {
        this.vistaActual = vista;
        const vistasCalendario = document.querySelectorAll('.vista-calendario');
        
        // Ocultar todas las vistas
        vistasCalendario.forEach(elementoVista => elementoVista.classList.add('d-none'));
        
        // Mostrar vista seleccionada
        let idVistaObjetivo;
        if (vista === 'mes') idVistaObjetivo = 'vistaMensual';
        else if (vista === 'semana') idVistaObjetivo = 'vistaSemanal';
        else if (vista === 'dia') idVistaObjetivo = 'vistaDiaria';
        
        const vistaObjetivo = document.getElementById(idVistaObjetivo);
        if (vistaObjetivo) {
            vistaObjetivo.classList.remove('d-none');
            this.renderizarVistaActual();
        }
        
        // Alternar visibilidad de navegación según la vista
        const body = document.body;
        if (vista === 'mes') {
            body.classList.add('vista-mensual-activa');
        } else {
            body.classList.remove('vista-mensual-activa');
        }
    }

    // Ir a hoy
    irAHoy() {
        this.fechaActual = new Date();
        if (this.elementos.selectorFecha) {
            this.elementos.selectorFecha.value = this.formatearFechaParaInput(this.fechaActual);
        }
        this.actualizarDisplayFecha();
        this.renderizarVistaActual();
    }

    // Navegar fechas (anterior/siguiente)
    navegarFecha(direccion) {
        const vistaActual = this.vistaActual;
        
        if (vistaActual === 'mes') {
            this.fechaActual.setMonth(this.fechaActual.getMonth() + direccion);
        } else if (vistaActual === 'semana') {
            this.fechaActual.setDate(this.fechaActual.getDate() + (direccion * 7));
        } else if (vistaActual === 'dia') {
            this.fechaActual.setDate(this.fechaActual.getDate() + direccion);
        }
        
        const selectorFecha = document.getElementById('selectorFecha');
        if (selectorFecha) {
            selectorFecha.value = this.formatearFechaParaInput(this.fechaActual);
        }
        
        this.actualizarDisplayFecha();
        this.renderizarVistaActual();
    }

    // Actualizar display de fecha actual
    actualizarDisplayFecha() {
        if (!this.elementos.displayFechaActual) return;
        
        const mes = CONFIGURACION_CALENDARIO.MESES[this.fechaActual.getMonth()];
        const anio = this.fechaActual.getFullYear();
        
        if (this.vistaActual === 'mes') {
            this.elementos.displayFechaActual.textContent = `${mes} ${anio}`;
        } else if (this.vistaActual === 'semana') {
            const inicioSemana = this.obtenerInicioSemana(this.fechaActual);
            const finSemana = new Date(inicioSemana);
            finSemana.setDate(finSemana.getDate() + 6);
            
            this.elementos.displayFechaActual.textContent = `Semana del ${inicioSemana.getDate()} al ${finSemana.getDate()} de ${mes} ${anio}`;
        } else if (this.vistaActual === 'dia') {
            const nombreDia = CONFIGURACION_CALENDARIO.DIAS[this.fechaActual.getDay()];
            
            this.elementos.displayFechaActual.textContent = `${nombreDia} ${this.fechaActual.getDate()} de ${mes} ${anio}`;
        }
    }

    // Renderizar vista actual
    renderizarVistaActual() {
        switch (this.vistaActual) {
            case 'mes':
                this.renderizarVistaMensual();
                break;
            case 'semana':
                this.renderizarVistaSemanal();
                break;
            case 'dia':
                this.renderizarVistaDiaria();
                break;
        }
    }

    // Renderizar vista mensual
    renderizarVistaMensual() {
        if (!this.elementos.vistaMensual) return;
        
        const tabla = this.elementos.vistaMensual.querySelector('table tbody');
        if (!tabla) return;
        
        tabla.innerHTML = '';
        
        const anio = this.fechaActual.getFullYear();
        const mes = this.fechaActual.getMonth();
        const primerDia = new Date(anio, mes, 1);
        const fechaInicio = new Date(primerDia);
        fechaInicio.setDate(fechaInicio.getDate() - primerDia.getDay());
        
        let fechaActual = new Date(fechaInicio);
        
        for (let semana = 0; semana < 6; semana++) {
            const fila = document.createElement('tr');
            
            for (let dia = 0; dia < 7; dia++) {
                const celda = document.createElement('td');
                const numeroDia = fechaActual.getDate();
                const esMesActual = fechaActual.getMonth() === mes;
                const esHoy = this.esHoy(fechaActual);
                
                celda.textContent = numeroDia;
                
                if (!esMesActual) {
                    celda.classList.add('text-muted');
                }
                
                if (esHoy) {
                    celda.classList.add('table-primary', 'fw-bold');
                }
                
                // Agregar eventos del día
                const reservasDia = this.obtenerReservasParaFecha(fechaActual);
                if (reservasDia.length > 0) {
                    const insignia = document.createElement('small');
                    insignia.className = 'badge bg-success ms-1';
                    insignia.textContent = reservasDia.length;
                    celda.appendChild(insignia);
                }
                
                // Agregar indicador de solicitudes del día
                const solicitudesDia = this.contarSolicitudesParaFecha(fechaActual);
                const indicadorSolicitudes = this.crearIndicadorSolicitudes(solicitudesDia, false);
                if (indicadorSolicitudes) {
                    celda.appendChild(indicadorSolicitudes);
                }
                
                celda.addEventListener('click', ((fechaClicada) => {
                    return () => {
                        this.fechaActual = new Date(fechaClicada);
                        this.cambiarVista('dia');
                    };
                })(new Date(fechaActual)));
                
                fila.appendChild(celda);
                fechaActual.setDate(fechaActual.getDate() + 1);
            }
            
            tabla.appendChild(fila);
        }
    }

    // Renderizar vista semanal
    renderizarVistaSemanal() {
        const vistaSemanal = document.getElementById('vistaSemanal');
        if (!vistaSemanal) return;
        
        const tabla = vistaSemanal.querySelector('table');
        if (!tabla) return;
        
        const inicioSemana = this.obtenerInicioSemana(this.fechaActual);
        const horas = this.generarRangosHora();
        
        // Actualizar headers con fechas de la semana
        const encabezados = tabla.querySelectorAll('thead th');
        for (let i = 1; i < encabezados.length; i++) {
            const fecha = new Date(inicioSemana);
            fecha.setDate(fecha.getDate() + (i - 1));
            
            const nombresDias = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            encabezados[i].textContent = `${nombresDias[fecha.getDay()]} ${fecha.getDate()}`;
        }
        
        // Actualizar cuerpo de la tabla
        const cuerpoTabla = tabla.querySelector('tbody');
        cuerpoTabla.innerHTML = '';
        
        horas.forEach(hora => {
            const fila = document.createElement('tr');
            
            // Celda de hora
            const celdaHora = document.createElement('td');
            celdaHora.className = 'table-light';
            celdaHora.textContent = hora;
            fila.appendChild(celdaHora);
            
            // Celdas para cada día de la semana
            for (let dia = 0; dia < 7; dia++) {
                const celda = document.createElement('td');
                const fechaCelda = new Date(inicioSemana);
                fechaCelda.setDate(fechaCelda.getDate() + dia);
                
                // Configurar posición relativa para indicadores
                celda.style.position = 'relative';
                
                const reserva = this.obtenerReservaParaFechaHora(fechaCelda, hora);
                const cantidadSolicitudes = this.contarSolicitudesParaFechaHora(fechaCelda, hora);
                
                if (reserva) {
                    celda.className = reserva.status === 'confirmed' ? 'table-success' : 'table-warning';
                    celda.style.cursor = 'pointer';
                    celda.title = 'Click para ver/editar reserva';
                    celda.innerHTML = `<strong>${reserva.title}</strong><br><small>${reserva.description}</small>`;
                    
                    // Event listener para ver/editar reserva
                    celda.addEventListener('click', () => {
                        this.verEditarReserva(reserva.id);
                    });
                } else {
                    // Celda vacía - hacer clickeable para crear reserva
                    celda.style.cursor = 'pointer';
                    celda.title = `Crear reserva para ${hora}`;
                    celda.classList.add('table-hover');
                    celda.innerHTML = '<small class="text-muted">Disponible</small>';
                    
                    // Agregar event listener para crear reserva en este horario
                    celda.addEventListener('click', ((fechaClicada, horaClicada) => {
                        return () => {
                            this.crearReservaEnHorario(new Date(fechaClicada), horaClicada);
                        };
                    })(fechaCelda, hora));
                }
                
                // Agregar indicador de solicitudes si hay alguna
                const indicadorSolicitudes = this.crearIndicadorSolicitudes(cantidadSolicitudes, true);
                if (indicadorSolicitudes) {
                    // Agregar event listener para abrir modal con focus
                    indicadorSolicitudes.addEventListener('click', (e) => {
                        e.stopPropagation(); // Evitar que se ejecute el click de la celda
                        this.abrirModalNotificacionesConFocus(fechaCelda, hora);
                    });
                    celda.appendChild(indicadorSolicitudes);
                }
                
                fila.appendChild(celda);
            }
            
            cuerpoTabla.appendChild(fila);
        });
    }

    // Renderizar vista diaria
    renderizarVistaDiaria() {
        if (!this.elementos.vistaDiaria) return;
        
        const tabla = this.elementos.vistaDiaria.querySelector('table');
        if (!tabla) return;
        
        const horas = this.generarRangosHora();
        const cuerpoTabla = tabla.querySelector('tbody');
        cuerpoTabla.innerHTML = '';
        
        // Actualizar header con fecha del día usando el elemento cacheado
        if (this.elementos.encabezadoVistaDiaria) {
            const nombreDia = CONFIGURACION_CALENDARIO.DIAS[this.fechaActual.getDay()];
            const nombreMes = CONFIGURACION_CALENDARIO.MESES[this.fechaActual.getMonth()];
            
            this.elementos.encabezadoVistaDiaria.textContent = `${nombreDia} ${this.fechaActual.getDate()} de ${nombreMes}`;
        }
        
        horas.forEach(hora => {
            const fila = document.createElement('tr');
            
            // Celda de hora
            const celdaHora = document.createElement('td');
            celdaHora.className = 'table-light';
            celdaHora.textContent = hora;
            fila.appendChild(celdaHora);
            
            // Celda de evento
            const celdaEvento = document.createElement('td');
            // Configurar posición relativa para indicadores
            celdaEvento.style.position = 'relative';
            
            const reserva = this.obtenerReservaParaFechaHora(this.fechaActual, hora);
            const cantidadSolicitudes = this.contarSolicitudesParaFechaHora(this.fechaActual, hora);
            
            if (reserva) {
                celdaEvento.className = reserva.status === 'confirmed' ? 'table-success' : 'table-warning';
                celdaEvento.style.cursor = 'pointer';
                celdaEvento.title = 'Click para ver/editar reserva';
                celdaEvento.innerHTML = `
                    <strong>${reserva.title}</strong><br>
                    <small class="text-muted">${reserva.description}</small>
                `;
                
                // Event listener para ver/editar reserva
                celdaEvento.addEventListener('click', () => {
                    this.verEditarReserva(reserva.id);
                });
            } else {
                // Celda vacía - hacer clickeable para crear reserva
                celdaEvento.style.cursor = 'pointer';
                celdaEvento.title = `Crear reserva para ${hora}`;
                celdaEvento.classList.add('table-hover');
                celdaEvento.innerHTML = '<small class="text-muted">Disponible</small>';
                
                // Agregar event listener para crear reserva en este horario
                celdaEvento.addEventListener('click', () => {
                    this.crearReservaEnHorario(new Date(this.fechaActual), hora);
                });
            }
            
            // Agregar indicador de solicitudes si hay alguna
            const indicadorSolicitudes = this.crearIndicadorSolicitudes(cantidadSolicitudes, true);
            if (indicadorSolicitudes) {
                // Agregar event listener para abrir modal con focus
                indicadorSolicitudes.addEventListener('click', (e) => {
                    e.stopPropagation(); // Evitar que se ejecute el click de la celda
                    this.abrirModalNotificacionesConFocus(this.fechaActual, hora);
                });
                celdaEvento.appendChild(indicadorSolicitudes);
            }
            
            fila.appendChild(celdaEvento);
            cuerpoTabla.appendChild(fila);
        });
        
        // Actualizar resumen del día
        this.actualizarResumenDia();
    }

    // Actualizar resumen del día
    actualizarResumenDia() {
        const tarjetaResumen = document.querySelector('#vistaDiaria .card-body');
        if (!tarjetaResumen) return;
        
        const reservasDia = this.obtenerReservasParaFecha(this.fechaActual);
        const confirmadas = reservasDia.filter(r => r.status === 'confirmed').length;
        const pendientes = reservasDia.filter(r => r.status === 'pending').length;
        const totalRangos = this.generarRangosHora().length;
        const libres = totalRangos - reservasDia.length;
        
        // Obtener solicitudes del día actual
        const solicitudesDelDia = this.solicitudesPendientes.filter(solicitud => {
            const fechaSolicitud = new Date(solicitud.fecha);
            return fechaSolicitud.toDateString() === this.fechaActual.toDateString();
        });
        
        let htmlSolicitudes = '';
        if (solicitudesDelDia.length > 0) {
            htmlSolicitudes = `
                <hr class="my-3">
                <div class="mb-2">
                    <strong class="text-warning">Solicitudes del día (${solicitudesDelDia.length})</strong>
                </div>
            `;
            
            solicitudesDelDia.forEach(solicitud => {
                htmlSolicitudes += `
                    <div class="card border-warning mb-2" style="font-size: 0.85rem;">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>${solicitud.jugador.nombre}</strong><br>
                                    <small class="text-muted">${solicitud.cancha.nombre} - ${solicitud.hora}</small>
                                </div>
                                <div>
                                    <button class="btn btn-success btn-sm me-1" onclick="aceptarSolicitud(${solicitud.id})" title="Aceptar">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(${solicitud.id})" title="Rechazar">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        tarjetaResumen.innerHTML = `
            <p class="card-text">
                <span class="badge bg-success me-2">${confirmadas}</span>Reservas confirmadas<br>
                <span class="badge bg-warning me-2">${pendientes}</span>Solicitudes pendientes<br>
                <span class="badge bg-secondary me-2">${libres}</span>Horarios libres
            </p>
            ${htmlSolicitudes}
        `;
    }

    // Actualizar contador de insignia
    actualizarContadorInsignia() {
        const insignia = document.querySelector('.badge.bg-danger');
        if (insignia) {
            const contadorPendientes = this.reservas.filter(r => r.status === 'pending').length;
            insignia.textContent = contadorPendientes;
        }
    }

    // Utilidades
    formatearFechaParaInput(fecha) {
        return fecha.toISOString().split('T')[0];
    }

    esHoy(fecha) {
        const hoy = new Date();
        return fecha.toDateString() === hoy.toDateString();
    }

    obtenerInicioSemana(fecha) {
        const inicioSemana = new Date(fecha);
        inicioSemana.setDate(fecha.getDate() - fecha.getDay());
        return inicioSemana;
    }

    generarRangosHora() {
        const rangos = [];
        for (let hora = CONFIGURACION_CALENDARIO.HORARIOS.HORA_INICIO; hora <= CONFIGURACION_CALENDARIO.HORARIOS.HORA_FIN; hora++) {
            rangos.push(`${hora.toString().padStart(2, '0')}:00`);
        }
        return rangos;
    }

    obtenerReservasParaFecha(fecha) {
        return this.reservas.filter(reserva => {
            const fechaReserva = new Date(reserva.date);
            return fechaReserva.toDateString() === fecha.toDateString();
        });
    }

    obtenerReservaParaFechaHora(fecha, hora) {
        return this.reservas.find(reserva => {
            const fechaReserva = new Date(reserva.date);
            return fechaReserva.toDateString() === fecha.toDateString() && reserva.time === hora;
        });
    }


}

// Inicializar la aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño delay para asegurar que todos los elementos estén listos
    setTimeout(() => {
        window.aplicacionAgenda = new AplicacionAgenda();
    }, 100);
});

// DATOS HARDCODEADOS - Mover a backend cuando esté listo
AplicacionAgenda.prototype.generarDatosMuestra = function() {
    // TODO: Reemplazar con llamadas a API del backend
    // Endpoints necesarios:
    // - GET /api/reservas?cancha={id}&fecha={date}
    // - GET /api/canchas
    // - POST /api/reservas
    // - PUT /api/reservas/{id}
    // - DELETE /api/reservas/{id}
    
    this.reservas = [
        {
            id: 1,
            title: 'Reserva Equipo A',
            description: 'Cancha A - Confirmada',
            date: new Date(2025, 8, 28), // 28 de septiembre (hoy)
            time: '08:00',
            status: 'confirmed',
            cancha: '1',
            jugadorId: 1001,
            esExterna: false,
            nombreExterno: null,
            telefonoExterno: null,
            comentario: 'Partido amistoso equipo regular',
            fechaCreacion: new Date(2025, 8, 25)
        },
        {
            id: 2,
            title: 'Reserva Externa',
            description: 'Cancha B - Pendiente',
            date: new Date(2025, 8, 28), // 28 de septiembre (hoy)
            time: '15:00',
            status: 'pending',
            cancha: '2',
            jugadorId: null,
            esExterna: true,
            nombreExterno: 'Carlos Mendoza',
            telefonoExterno: '+54 11 1234-5678',
            comentario: 'Evento corporativo - requiere confirmación',
            fechaCreacion: new Date(2025, 8, 26)
        },
        {
            id: 3,
            title: 'Torneo Local',
            description: 'Cancha C - Confirmada',
            date: new Date(2025, 8, 29), // 29 de septiembre (mañana)
            time: '10:00',
            status: 'confirmed',
            cancha: '3',
            jugadorId: 2005,
            esExterna: false,
            nombreExterno: null,
            telefonoExterno: null,
            comentario: 'Semifinal torneo local',
            fechaCreacion: new Date(2025, 8, 20)
        },
        {
            id: 4,
            title: 'Partido Amistoso',
            description: 'Cancha A - Confirmada',
            date: new Date(2025, 8, 30), // 30 de septiembre (pasado mañana)
            time: '14:00',
            status: 'confirmed',
            cancha: '1',
            jugadorId: 1002,
            esExterna: false,
            nombreExterno: null,
            telefonoExterno: null,
            comentario: 'Partido entre equipos locales',
            fechaCreacion: new Date(2025, 8, 27)
        }
    ];
};

// FUNCIONES GLOBALES PARA MODALES

// Funciones para gestión de solicitudes
function aceptarSolicitud(idSolicitud) {
    if (confirm('¿Está seguro que desea aceptar esta solicitud de reserva?')) {
        // TODO: Enviar aceptación al backend
        console.log('Aceptando solicitud:', idSolicitud);
        
        // Simular eliminación de la solicitud de la lista
        const elementoSolicitud = document.querySelector(`#detallesSolicitud${idSolicitud}`).closest('.card');
        elementoSolicitud.remove();
        
        // Actualizar contador
        actualizarContadorSolicitudes();
        
        alert('Solicitud aceptada exitosamente');
    }
}

function rechazarSolicitud(idSolicitud) {
    if (confirm('¿Está seguro que desea rechazar esta solicitud de reserva?')) {
        // TODO: Enviar rechazo al backend
        console.log('Rechazando solicitud:', idSolicitud);
        
        // Simular eliminación de la solicitud de la lista
        const elementoSolicitud = document.querySelector(`#detallesSolicitud${idSolicitud}`).closest('.card');
        elementoSolicitud.remove();
        
        // Actualizar contador
        actualizarContadorSolicitudes();
        
        alert('Solicitud rechazada');
    }
}

function actualizarContadorSolicitudes() {
    const solicitudesRestantes = document.querySelectorAll('#listaSolicitudes .card').length;
    
    // Actualizar badges de contador
    const insignias = document.querySelectorAll('.badge.bg-danger');
    insignias.forEach(insignia => {
        insignia.textContent = solicitudesRestantes;
    });
    
    // Mostrar mensaje si no hay solicitudes
    if (solicitudesRestantes === 0) {
        document.getElementById('listaSolicitudes').classList.add('d-none');
        document.getElementById('sinSolicitudes').classList.remove('d-none');
    }
}

// Funciones globales para manejar solicitudes (llamadas desde HTML dinámico)
function aceptarSolicitud(idSolicitud) {
    // Encontrar la solicitud en el array
    const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(s => s.id === idSolicitud);
    if (solicitudIndex === -1) return;
    
    const solicitud = aplicacionAgenda.solicitudesPendientes[solicitudIndex];
    
    // Simular aceptación - en el futuro será una llamada al backend
    console.log(`Aceptando solicitud de ${solicitud.jugador.nombre} para ${solicitud.cancha.nombre} el ${solicitud.fecha} a las ${solicitud.hora}`);
    
    // Remover de solicitudes pendientes
    aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);
    
    // Actualizar la interfaz
    aplicacionAgenda.renderizarSolicitudesModal();
    aplicacionAgenda.actualizarContadorNotificaciones();
    
    // Si estamos en vista diaria, actualizar el resumen
    if (aplicacionAgenda.vistaActual === 'dia') {
        aplicacionAgenda.actualizarResumenDia();
    }
    
    // Mostrar mensaje de confirmación
    alert(`Solicitud aceptada exitosamente`);
}

function rechazarSolicitud(idSolicitud) {
    // Encontrar la solicitud en el array
    const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(s => s.id === idSolicitud);
    if (solicitudIndex === -1) return;
    
    const solicitud = aplicacionAgenda.solicitudesPendientes[solicitudIndex];
    
    // Simular rechazo - en el futuro será una llamada al backend
    console.log(`Rechazando solicitud de ${solicitud.jugador.nombre} para ${solicitud.cancha.nombre} el ${solicitud.fecha} a las ${solicitud.hora}`);
    
    // Remover de solicitudes pendientes
    aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);
    
    // Actualizar la interfaz
    aplicacionAgenda.renderizarSolicitudesModal();
    aplicacionAgenda.actualizarContadorNotificaciones();
    
    // Si estamos en vista diaria, actualizar el resumen
    if (aplicacionAgenda.vistaActual === 'dia') {
        aplicacionAgenda.actualizarResumenDia();
    }
    
    // Mostrar mensaje de confirmación
    alert(`Solicitud rechazada`);
}

function verPerfilJugador(idJugador) {
    // Simular navegación al perfil del jugador - en el futuro será una redirección real
    console.log(`Navegando al perfil del jugador ID: ${idJugador}`);
    alert(`Función no implementada: Ver perfil del jugador ID ${idJugador}`);
}

// Funciones para configuración - Ahora manejadas por la clase AplicacionAgenda

function verPerfilCancha() {
    // TODO: Redirigir a página de perfil de cancha o abrir modal de perfil
    console.log('Ver perfil de cancha');
    alert('Redirigiendo a perfil de cancha...');
}

// Exportar para uso en módulos si es necesario
if (typeof module !== 'undefined' && module.exports) {
    module.exports = AplicacionAgenda;
}