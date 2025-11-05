/**
 * Agenda Admin JavaScript
 * Funcionalidades específicas del admin de cancha que extienden el calendario base
 */

// Clase específica para admin de cancha que extiende CalendarioBase
class AplicacionAgendaAdmin extends CalendarioBase {
    constructor() {
        super();
        
        // Datos específicos del admin
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
                fecha: '2025-11-05',
                hora: '16:00',
                fechaSolicitud: '2025-11-04',
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
                fecha: '2025-11-06',
                hora: '14:00',
                fechaSolicitud: '2025-11-04',
                estado: 'pending'
            }
        ];

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

        // Inicializar funcionalidades específicas del admin
        this.inicializarFuncionalidadesAdmin();
    }

    inicializarFuncionalidadesAdmin() {
        this.configurarEventosAdmin();
        this.renderizarSolicitudesModal();
        this.renderizarModalConfiguracion();
        this.actualizarContadorNotificaciones();
        this.configurarEventListenersModal();
        this.generarDatosMuestra();
    }

    // Configurar eventos específicos del admin
    configurarEventosAdmin() {
        // Configurar botón "Crear Reserva"
        const botonCrearReserva = document.getElementById('botonCrearReserva');
        if (botonCrearReserva) {
            botonCrearReserva.addEventListener('click', () => {
                this.crearReserva();
            });
        }

        // Configurar búsqueda de reservas
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.filtrarReservas(e.target.value);
            });
        }

        // Configurar reset del modal al cerrarse
        const modal = document.getElementById('modalGestionReserva');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', () => {
                this.limpiarFormularioReserva();
            });
        }
    }

    // Override de callbacks del componente calendario para funcionalidades del admin
    obtenerContenidoDia(fecha) {
        const fechaStr = this.formatearFechaISO(fecha);
        const reservasDelDia = this.reservas.filter(r => r.fecha === fechaStr);
        
        if (reservasDelDia.length === 0) return '';
        
        return `
            <div class="day-reservas">
                ${reservasDelDia.slice(0, 2).map(reserva => `
                    <div class="reserva-mini ${reserva.estado}" onclick="verEditarReserva(${reserva.id})" title="${reserva.cliente} - ${reserva.hora}">
                        ${reserva.hora.substring(0, 5)}
                    </div>
                `).join('')}
                ${reservasDelDia.length > 2 ? `<div class="reserva-mini mas">+${reservasDelDia.length - 2}</div>` : ''}
            </div>
        `;
    }

    obtenerContenidoHora(fecha, hora) {
        const fechaStr = this.formatearFechaISO(fecha);
        const horaStr = `${hora.toString().padStart(2, '0')}:00`;
        const reserva = this.reservas.find(r => r.fecha === fechaStr && r.hora === horaStr);
        
        if (!reserva) return '';
        
        return `
            <div class="reserva-bloque ${reserva.estado}" onclick="verEditarReserva(${reserva.id})">
                <div class="reserva-cliente">${reserva.cliente}</div>
                <div class="reserva-cancha">${reserva.cancha}</div>
            </div>
        `;
    }

    // Renderizar solicitudes en el modal dinámicamente
    renderizarSolicitudesModal() {
        const listaSolicitudes = document.getElementById('listaSolicitudes');
        const sinSolicitudes = document.getElementById('sinSolicitudes');
        
        if (!listaSolicitudes) return;
        
        if (this.solicitudesPendientes.length === 0) {
            listaSolicitudes.classList.add('d-none');
            if (sinSolicitudes) sinSolicitudes.classList.remove('d-none');
            return;
        }
        
        listaSolicitudes.classList.remove('d-none');
        if (sinSolicitudes) sinSolicitudes.classList.add('d-none');
        
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
        
        if (fecha.toDateString() === hoy.toDateString()) {
            return 'Hoy ' + fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
        } else if (fecha.toDateString() === manana.toDateString()) {
            return 'Mañana ' + fecha.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit' });
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
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });
    }

    // Configurar modal de configuración con datos y validación
    renderizarModalConfiguracion() {
        this.cargarDatosConfiguracion();
        this.configurarValidacionHorarios();
        this.configurarBotonGuardarConfiguracion();
    }

    // Cargar datos de configuración en el modal
    cargarDatosConfiguracion() {
        const horaApertura = document.getElementById('horaApertura');
        const horaCierre = document.getElementById('horaCierre');
        
        if (horaApertura) horaApertura.value = this.configuracionCancha.horaApertura;
        if (horaCierre) horaCierre.value = this.configuracionCancha.horaCierre;
        
        // Cargar días de operación
        Object.keys(this.configuracionCancha.diasOperacion).forEach(dia => {
            const checkbox = document.getElementById(dia);
            if (checkbox) {
                checkbox.checked = this.configuracionCancha.diasOperacion[dia];
            }
        });
        
        // Cargar información de la cancha
        const nombreComplejo = document.getElementById('nombreComplejo');
        const direccionComplejo = document.getElementById('direccionComplejo');
        const telefonoComplejo = document.getElementById('telefonoComplejo');
        
        if (nombreComplejo) nombreComplejo.textContent = this.configuracionCancha.canchaInfo.nombre;
        if (direccionComplejo) direccionComplejo.textContent = this.configuracionCancha.canchaInfo.direccion;
        if (telefonoComplejo) telefonoComplejo.textContent = this.configuracionCancha.canchaInfo.telefono;
    }

    // Configurar validación en tiempo real de horarios
    configurarValidacionHorarios() {
        const horaApertura = document.getElementById('horaApertura');
        const horaCierre = document.getElementById('horaCierre');
        
        if (horaApertura && horaCierre) {
            const validarHorarios = () => {
                const apertura = horaApertura.value;
                const cierre = horaCierre.value;
                
                if (apertura && cierre && apertura >= cierre) {
                    horaCierre.setCustomValidity('El horario de cierre debe ser posterior al de apertura');
                } else {
                    horaCierre.setCustomValidity('');
                }
            };
            
            horaApertura.addEventListener('change', validarHorarios);
            horaCierre.addEventListener('change', validarHorarios);
        }
    }

    // Configurar botón guardar configuración
    configurarBotonGuardarConfiguracion() {
        const botonGuardar = document.getElementById('botonGuardarConfiguracion');
        if (botonGuardar) {
            botonGuardar.addEventListener('click', () => {
                this.procesarGuardarConfiguracion();
            });
        }
    }

    // Procesar el guardado de configuración
    procesarGuardarConfiguracion() {
        const horaApertura = document.getElementById('horaApertura').value;
        const horaCierre = document.getElementById('horaCierre').value;
        
        // Validar horarios
        if (horaApertura >= horaCierre) {
            alert('Error: El horario de cierre debe ser posterior al de apertura');
            return;
        }
        
        // Actualizar configuración
        this.configuracionCancha.horaApertura = horaApertura;
        this.configuracionCancha.horaCierre = horaCierre;
        
        // Actualizar días de operación
        Object.keys(this.configuracionCancha.diasOperacion).forEach(dia => {
            const checkbox = document.getElementById(dia);
            if (checkbox) {
                this.configuracionCancha.diasOperacion[dia] = checkbox.checked;
            }
        });
        
        // TODO: Enviar al backend
        console.log('Configuración guardada:', this.configuracionCancha);
        
        // Mostrar mensaje de éxito y cerrar modal
        alert('Configuración guardada exitosamente');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalConfiguracion'));
        modal.hide();
    }

    // Gestión de reservas
    crearReserva(fechaSeleccionada = null, horaSeleccionada = null) {
        this.abrirModalReserva('crear', null, fechaSeleccionada, horaSeleccionada);
    }

    verEditarReserva(idReserva) {
        const reserva = this.reservas.find(r => r.id === idReserva);
        if (reserva) {
            this.abrirModalReserva('editar', reserva);
        }
    }

    abrirModalReserva(modo, reserva = null, fechaSeleccionada = null, horaSeleccionada = null) {
        const modal = new bootstrap.Modal(document.getElementById('modalGestionReserva'));
        
        // Configurar título y botones según el modo
        this.configurarModalSegunModo(modo, reserva);
        
        if (modo === 'crear') {
            this.prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada);
        } else if (modo === 'editar') {
            this.prellenarFormularioEditar(reserva);
        }
        
        modal.show();
    }

    configurarModalSegunModo(modo, reserva) {
        const tituloModal = document.getElementById('tituloModal');
        const textoBoton = document.getElementById('textoBoton');
        const botonEliminar = document.getElementById('botonEliminarReserva');
        const seccionEstado = document.getElementById('seccionEstado');
        
        if (modo === 'crear') {
            if (tituloModal) tituloModal.textContent = 'Crear Nueva Reserva';
            if (textoBoton) textoBoton.textContent = 'Crear Reserva';
            if (botonEliminar) botonEliminar.classList.add('d-none');
            if (seccionEstado) seccionEstado.classList.add('d-none');
        } else if (modo === 'editar') {
            if (tituloModal) tituloModal.textContent = reserva?.esExterna ? 'Reserva Externa - Detalles' : 'Reserva App - Detalles';
            if (textoBoton) textoBoton.textContent = 'Guardar Cambios';
            if (botonEliminar) botonEliminar.classList.remove('d-none');
            if (seccionEstado) seccionEstado.classList.remove('d-none');
        }
    }

    prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada) {
        if (fechaSeleccionada) {
            const fechaInput = document.getElementById('fechaReserva');
            if (fechaInput) fechaInput.value = fechaSeleccionada;
        }
        
        if (horaSeleccionada) {
            const horaInput = document.getElementById('horaReserva');
            if (horaInput) horaInput.value = horaSeleccionada;
        }
    }

    prellenarFormularioEditar(reserva) {
        if (!reserva) return;
        
        // Llenar todos los campos con los datos de la reserva
        const campos = {
            'idReserva': reserva.id,
            'estadoReserva': reserva.estado,
            'fechaCreacion': reserva.fechaCreacion || '',
            'idJugador': reserva.idJugador || '',
            'nombreExterno': reserva.nombreExterno || '',
            'telefonoExterno': reserva.telefonoExterno || '',
            'canchaReserva': reserva.idCancha || '',
            'fechaReserva': reserva.fecha,
            'horaReserva': reserva.hora,
            'comentarioReserva': reserva.comentario || ''
        };
        
        Object.entries(campos).forEach(([id, valor]) => {
            const elemento = document.getElementById(id);
            if (elemento) {
                if (elemento.type === 'checkbox') {
                    elemento.checked = valor;
                } else {
                    elemento.value = valor;
                }
            }
        });
        
        // Configurar checkbox de reserva externa
        const reservaExterna = document.getElementById('reservaExterna');
        if (reservaExterna) {
            reservaExterna.checked = reserva.esExterna || false;
            this.alternarCamposExternos();
        }
    }

    // Configurar event listeners del modal
    configurarEventListenersModal() {
        // Checkbox de reserva externa
        const reservaExterna = document.getElementById('reservaExterna');
        if (reservaExterna) {
            reservaExterna.addEventListener('change', () => {
                this.alternarCamposExternos();
            });
        }

        // Botón guardar reserva
        const botonGuardarReserva = document.getElementById('botonGuardarReserva');
        if (botonGuardarReserva) {
            botonGuardarReserva.addEventListener('click', () => {
                this.procesarFormularioReserva();
            });
        }
        
        // Botón eliminar reserva
        const botonEliminarReserva = document.getElementById('botonEliminarReserva');
        if (botonEliminarReserva) {
            botonEliminarReserva.addEventListener('click', () => {
                this.eliminarReserva();
            });
        }
    }

    alternarCamposExternos() {
        const reservaExterna = document.getElementById('reservaExterna');
        const nombreExterno = document.getElementById('nombreExterno');
        const telefonoExterno = document.getElementById('telefonoExterno');
        const idJugador = document.getElementById('idJugador');
        
        if (reservaExterna && reservaExterna.checked) {
            // Habilitar campos externos
            nombreExterno.disabled = false;
            telefonoExterno.disabled = false;
            nombreExterno.required = true;
            telefonoExterno.required = true;
            
            // Deshabilitar campo ID jugador
            idJugador.disabled = true;
            idJugador.required = false;
            idJugador.value = '';
        } else {
            // Deshabilitar campos externos
            nombreExterno.disabled = true;
            telefonoExterno.disabled = true;
            nombreExterno.required = false;
            telefonoExterno.required = false;
            nombreExterno.value = '';
            telefonoExterno.value = '';
            
            // Habilitar campo ID jugador
            idJugador.disabled = false;
            idJugador.required = true;
        }
    }

    procesarFormularioReserva() {
        const idReserva = document.getElementById('idReserva').value;
        const esEdicion = idReserva !== '';
        
        // Recopilar datos del formulario
        const datosReserva = {
            id: esEdicion ? parseInt(idReserva) : this.generarNuevoId(),
            estado: document.getElementById('estadoReserva').value || 'confirmed',
            idJugador: document.getElementById('idJugador').value,
            nombreExterno: document.getElementById('nombreExterno').value,
            telefonoExterno: document.getElementById('telefonoExterno').value,
            esExterna: document.getElementById('reservaExterna').checked,
            idCancha: document.getElementById('canchaReserva').value,
            cancha: this.obtenerNombreCancha(document.getElementById('canchaReserva').value),
            fecha: document.getElementById('fechaReserva').value,
            hora: document.getElementById('horaReserva').value,
            comentario: document.getElementById('comentarioReserva').value,
            fechaCreacion: esEdicion ? document.getElementById('fechaCreacion').value : new Date().toISOString().split('T')[0]
        };
        
        // Validar datos
        if (!this.validarDatosReserva(datosReserva)) {
            return;
        }
        
        // Agregar cliente basado en tipo de reserva
        datosReserva.cliente = datosReserva.esExterna ? 
            datosReserva.nombreExterno : 
            `Jugador #${datosReserva.idJugador}`;
        
        if (esEdicion) {
            this.actualizarReserva(datosReserva);
        } else {
            this.crearNuevaReserva(datosReserva);
        }
    }

    validarDatosReserva(datos) {
        if (!datos.idCancha) {
            alert('Por favor seleccione una cancha');
            return false;
        }
        
        if (!datos.fecha) {
            alert('Por favor seleccione una fecha');
            return false;
        }
        
        if (!datos.hora) {
            alert('Por favor seleccione una hora');
            return false;
        }
        
        if (datos.esExterna) {
            if (!datos.nombreExterno || !datos.telefonoExterno) {
                alert('Para reservas externas, complete nombre y teléfono');
                return false;
            }
        } else {
            if (!datos.idJugador) {
                alert('Para reservas de la app, ingrese el ID del jugador');
                return false;
            }
        }
        
        return true;
    }

    crearNuevaReserva(datosReserva) {
        this.reservas.push(datosReserva);
        this.mostrarMensajeExito('Reserva creada exitosamente');
        this.cerrarModal();
    }
    
    actualizarReserva(datosReserva) {
        const indice = this.reservas.findIndex(r => r.id === datosReserva.id);
        if (indice !== -1) {
            this.reservas[indice] = datosReserva;
            this.mostrarMensajeExito('Reserva actualizada exitosamente');
            this.cerrarModal();
        }
    }

    eliminarReserva() {
        const idReserva = parseInt(document.getElementById('idReserva').value);
        
        if (confirm('¿Está seguro que desea eliminar esta reserva?')) {
            this.reservas = this.reservas.filter(r => r.id !== idReserva);
            this.mostrarMensajeExito('Reserva eliminada exitosamente');
            this.cerrarModal();
        }
    }

    cerrarModal() {
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalGestionReserva'));
        modal.hide();
        this.limpiarFormularioReserva();
        this.renderizarVistaActual();
    }

    limpiarFormularioReserva() {
        const formulario = document.getElementById('formularioGestionReserva');
        if (formulario) {
            formulario.reset();
            
            // Limpiar campos ocultos
            const idReserva = document.getElementById('idReserva');
            if (idReserva) idReserva.value = '';
            
            // Restaurar estado inicial de los campos
            const nombreExterno = document.getElementById('nombreExterno');
            const telefonoExterno = document.getElementById('telefonoExterno');
            const idJugador = document.getElementById('idJugador');
            
            if (nombreExterno) {
                nombreExterno.disabled = true;
                nombreExterno.required = false;
            }
            if (telefonoExterno) {
                telefonoExterno.disabled = true;
                telefonoExterno.required = false;
            }
            if (idJugador) {
                idJugador.disabled = false;
                idJugador.required = true;
            }
        }
    }

    filtrarReservas(termino) {
        console.log('Filtrando reservas con término:', termino);
        // TODO: Implementar filtrado real
    }

    mostrarMensajeExito(mensaje) {
        alert(mensaje); // TODO: Implementar con toast o notificación mejor
    }

    generarNuevoId() {
        return Math.max(...this.reservas.map(r => r.id), 0) + 1;
    }

    obtenerNombreCancha(idCancha) {
        const canchas = {
            '1': 'Cancha A - Fútbol 11',
            '2': 'Cancha B - Fútbol 7',
            '3': 'Cancha C - Fútbol 5'
        };
        return canchas[idCancha] || 'Cancha desconocida';
    }

    // Generar datos de muestra para demostración
    generarDatosMuestra() {
        this.reservas = [
            {
                id: 1,
                fecha: '2025-11-04',
                hora: '18:00',
                duracion: 2,
                cancha: 'Cancha A',
                cliente: 'Los Cracks FC',
                estado: 'confirmed',
                telefono: '+54 11 1234-5678',
                idCancha: '1',
                esExterna: false,
                idJugador: '101'
            },
            {
                id: 2,
                fecha: '2025-11-05',
                hora: '19:00',
                duracion: 1,
                cancha: 'Cancha B',
                cliente: 'Racing Amateur',
                estado: 'pending',
                telefono: '+54 11 2345-6789',
                idCancha: '2',
                esExterna: true,
                nombreExterno: 'Racing Amateur',
                telefonoExterno: '+54 11 2345-6789'
            }
        ];
    }
}

// Inicialización
let aplicacionAgenda;

document.addEventListener('DOMContentLoaded', function() {
    aplicacionAgenda = new AplicacionAgendaAdmin();
});

// Funciones globales para callbacks de los modales
function aceptarSolicitud(idSolicitud) {
    const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(s => s.id === idSolicitud);
    if (solicitudIndex === -1) return;
    
    const solicitud = aplicacionAgenda.solicitudesPendientes[solicitudIndex];
    
    // Crear nueva reserva a partir de la solicitud
    const nuevaReserva = {
        id: aplicacionAgenda.generarNuevoId(),
        fecha: solicitud.fecha,
        hora: solicitud.hora,
        cancha: solicitud.cancha.nombre,
        cliente: solicitud.jugador.nombre,
        estado: 'confirmed',
        telefono: solicitud.jugador.telefono,
        idCancha: solicitud.cancha.id.toString(),
        esExterna: false,
        idJugador: solicitud.jugador.id.toString()
    };
    
    aplicacionAgenda.reservas.push(nuevaReserva);
    
    // Remover de solicitudes pendientes
    aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);
    
    // Actualizar la interfaz
    aplicacionAgenda.renderizarSolicitudesModal();
    aplicacionAgenda.actualizarContadorNotificaciones();
    aplicacionAgenda.renderizarVistaActual();
    
    alert('Solicitud aceptada exitosamente');
}

function rechazarSolicitud(idSolicitud) {
    const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(s => s.id === idSolicitud);
    if (solicitudIndex === -1) return;
    
    // Remover de solicitudes pendientes
    aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);
    
    // Actualizar la interfaz
    aplicacionAgenda.renderizarSolicitudesModal();
    aplicacionAgenda.actualizarContadorNotificaciones();
    
    alert('Solicitud rechazada');
}

function verPerfilJugador(idJugador) {
    alert(`Función no implementada: Ver perfil del jugador ID ${idJugador}`);
}

function verEditarReserva(idReserva) {
    if (aplicacionAgenda) {
        aplicacionAgenda.verEditarReserva(idReserva);
    }
}

// Override de callbacks para funcionalidades del admin
function onCalendarioDiaClick(fecha) {
    console.log('Admin - Día seleccionado:', fecha);
    if (aplicacionAgenda) {
        aplicacionAgenda.cambiarVista('dia');
    }
}

function onCalendarioHorarioClick(fecha, hora) {
    console.log('Admin - Horario seleccionado:', fecha, hora);
    if (aplicacionAgenda) {
        aplicacionAgenda.crearReserva(fecha, hora);
    }
}