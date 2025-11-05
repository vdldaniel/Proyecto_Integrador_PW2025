/**
 * Funcionalidad de la página Equipos Listado - Jugador
 * Manejo de modals, búsqueda y visualización de equipos
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Cargar equipos del usuario al iniciar
    cargarMisEquipos();
    
    // Búsqueda de equipos
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filtrarEquipos(this.value);
        });
    }

    // Modal Unirse a Equipo
    const btnSolicitarUnirse = document.getElementById('btnSolicitarUnirse');
    if (btnSolicitarUnirse) {
        btnSolicitarUnirse.addEventListener('click', function() {
            const codigo = document.getElementById('codigoEquipo').value;
            if (validarCodigoEquipo(codigo)) {
                solicitarUnirseEquipo(codigo);
            }
        });
    }

    // Validación en tiempo real del código de equipo
    const codigoEquipo = document.getElementById('codigoEquipo');
    if (codigoEquipo) {
        codigoEquipo.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 5);
        });
    }

    // Modal Crear Equipo
    const btnCrearEquipoSubmit = document.getElementById('btnCrearEquipoSubmit');
    if (btnCrearEquipoSubmit) {
        btnCrearEquipoSubmit.addEventListener('click', function() {
            const formData = recopilarDatosFormulario();
            if (validarFormularioCrearEquipo(formData)) {
                crearEquipo(formData);
            }
        });
    }

    // Agregar jugador dinámicamente
    const btnAgregarJugador = document.getElementById('btnAgregarJugador');
    if (btnAgregarJugador) {
        btnAgregarJugador.addEventListener('click', function() {
            agregarCampoJugador();
        });
    }

    // Modal Invitar Jugador
    const btnInvitarJugador = document.getElementById('btnInvitarJugador');
    if (btnInvitarJugador) {
        btnInvitarJugador.addEventListener('click', function() {
            const username = document.getElementById('usernameInvitar').value;
            if (validarUsername(username)) {
                invitarJugador(username);
            }
        });
    }

    // Modal Editar Equipo
    const btnAgregarJugadorEdit = document.getElementById('btnAgregarJugadorEdit');
    if (btnAgregarJugadorEdit) {
        btnAgregarJugadorEdit.addEventListener('click', function() {
            agregarCampoJugadorEdit();
        });
    }

    const btnGuardarEdicionEquipo = document.getElementById('btnGuardarEdicionEquipo');
    if (btnGuardarEdicionEquipo) {
        btnGuardarEdicionEquipo.addEventListener('click', function() {
            const formData = recopilarDatosFormularioEdit();
            if (validarFormularioEditarEquipo(formData)) {
                editarEquipo(formData);
            }
        });
    }

    // Event delegation para botones dinámicos de remover jugador
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-jugador-btn')) {
            const button = e.target.closest('.remove-jugador-btn');
            removeJugador(button);
        } else if (e.target.closest('.remove-jugador-edit-btn')) {
            const button = e.target.closest('.remove-jugador-edit-btn');
            removeJugadorEdit(button);
        }
    });
});

// Función para cargar los equipos del usuario
function cargarMisEquipos() {
    // Simulación de datos - aquí iría la llamada AJAX real
    const equiposEjemplo = [
        {
            id: 1,
            nombre: "Los Tigres FC",
            foto: "", // Foto vacía por ahora
            integrantes: 8,
            torneosActivos: 2,
            partidosProximos: 4,
            claveTemp: "ABC123"
        },
        {
            id: 2,
            nombre: "Águilas Doradas",
            foto: "", // Foto vacía por ahora
            integrantes: 6,
            torneosActivos: 1,
            partidosProximos: 2,
            claveTemp: "XYZ789"
        },
        {
            id: 3,
            nombre: "Rayos Azules",
            foto: "", // Foto vacía por ahora
            integrantes: 10,
            torneosActivos: 3,
            partidosProximos: 5,
            claveTemp: "RAY456"
        }
    ];
    
    mostrarEquipos(equiposEjemplo);
}

// Función para mostrar equipos en formato tarjetas tipo fila
function mostrarEquipos(equipos) {
    const container = document.getElementById('equiposList');
    if (!container) return;
    
    container.innerHTML = '';
    
    equipos.forEach(equipo => {
        const equipoCard = `
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-1 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; border: 2px;">
                                    <i class="bi bi-people text-muted" style="font-size: 1.2rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">
                                    <a href="#" class="text-decoration-none equipo-nombre-link" data-equipo-id="${equipo.id}">
                                        ${equipo.nombre}
                                    </a>
                                </h5>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-people"></i> ${equipo.integrantes} integrantes</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-trophy"></i> ${equipo.torneosActivos} torneos</small>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted d-block"><i class="bi bi-calendar-event"></i> ${equipo.partidosProximos} partidos</small>
                            </div>
                            <div class="col-md-2 text-end">
                                <a href="#" class="btn btn-outline-primary btn-sm me-1 equipo-ver-btn" 
                                   data-equipo-id="${equipo.id}"
                                   data-bs-toggle="tooltip" title="Ver detalles del equipo">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-outline-warning btn-sm me-1 equipo-editar-btn" 
                                        data-equipo-id="${equipo.id}"
                                        data-bs-toggle="tooltip" title="Editar equipo">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm equipo-agregar-btn" 
                                        data-equipo-id="${equipo.id}"
                                        data-clave-temp="${equipo.claveTemp}"
                                        data-bs-toggle="tooltip" title="Invitar jugadores">
                                    <i class="bi bi-person-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += equipoCard;
    });
    
    // Inicializar tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
    
    // Event listeners para nombres de equipos (ir a detalle)
    document.querySelectorAll('.equipo-nombre-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const equipoId = this.dataset.equipoId;
            irADetalleEquipo(equipoId);
        });
    });

    // Event listeners para botones "Ver"
    document.querySelectorAll('.equipo-ver-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const equipoId = this.dataset.equipoId;
            irADetalleEquipo(equipoId);
        });
    });

    // Event listeners para botones "Editar"
    document.querySelectorAll('.equipo-editar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const equipoId = this.dataset.equipoId;
            abrirModalEditar(equipoId);
        });
    });

    // Event listeners para botones "Invitar/Agregar"
    document.querySelectorAll('.equipo-agregar-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const equipoId = this.dataset.equipoId;
            const claveTemp = this.dataset.claveTemp;
            abrirModalInvitar(equipoId, claveTemp);
        });
    });
}

// Función para filtrar equipos
function filtrarEquipos(termino) {
    const cards = document.querySelectorAll('#equiposList .col-12');
    
    cards.forEach(card => {
        const texto = card.textContent.toLowerCase();
        if (texto.includes(termino.toLowerCase())) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Validaciones
function validarCodigoEquipo(codigo) {
    if (!codigo || codigo.length !== 5) {
        alert('El código debe tener exactamente 5 dígitos');
        return false;
    }
    if (!/^\d{5}$/.test(codigo)) {
        alert('El código debe contener solo números');
        return false;
    }
    return true;
}

function validarFormularioCrearEquipo(formData) {
    if (!formData.nombre || formData.nombre.trim().length < 3) {
        alert('El nombre del equipo debe tener al menos 3 caracteres');
        return false;
    }
    if (formData.jugadores.length === 0) {
        alert('Debe agregar al menos un jugador');
        return false;
    }
    return true;
}

function validarUsername(username) {
    if (!username || username.trim().length < 3) {
        alert('El username debe tener al menos 3 caracteres');
        return false;
    }
    return true;
}

// Funciones de formulario
function agregarCampoJugador() {
    const container = document.getElementById('jugadoresContainer');
    const nuevoJugador = `
        <div class="input-group mb-2">
            <input type="text" class="form-control" placeholder="Username del jugador" name="jugador[]">
            <button class="btn btn-outline-danger" type="button" onclick="removeJugador(this)">
                <i class="bi bi-dash"></i>
            </button>
        </div>
    `;
    container.innerHTML += nuevoJugador;
}

function removeJugador(button) {
    const container = document.getElementById('jugadoresContainer');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}

function recopilarDatosFormulario() {
    const nombre = document.getElementById('nombreEquipo').value;
    const foto = document.getElementById('fotoEquipo').files[0];
    const jugadores = Array.from(document.querySelectorAll('[name="jugador[]"]'))
        .map(input => input.value.trim())
        .filter(value => value.length > 0);
    
    return { nombre, foto, jugadores };
}

// Funciones de acción (aquí irían las llamadas AJAX reales)
function solicitarUnirseEquipo(codigo) {
    // Aquí iría la lógica AJAX para unirse al equipo
    alert(`Solicitando unirse al equipo con código: ${codigo}`);
    bootstrap.Modal.getInstance(document.getElementById('modalUnirseEquipo')).hide();
}

function crearEquipo(formData) {
    // Aquí iría la lógica AJAX para crear el equipo
    alert(`Creando equipo: ${formData.nombre} con ${formData.jugadores.length} jugadores`);
    bootstrap.Modal.getInstance(document.getElementById('modalCrearEquipo')).hide();
}

function invitarJugador(username) {
    // Aquí iría la lógica AJAX para invitar al jugador
    alert(`Invitando a ${username} al equipo`);
    bootstrap.Modal.getInstance(document.getElementById('modalInvitarJugador')).hide();
}

// Funciones para el modal de editar equipo
function cargarDatosEquipoEnModal(equipoId = null) {
    // Aquí se cargarían los datos reales del equipo desde el servidor
    // Por ahora usamos datos de ejemplo
    const datosEquipo = {
        nombre: equipoId ? `Equipo ${equipoId}` : "Los Tigres FC",
        lider: "@jugador_lider",
        fechaCreacion: "15 de Octubre, 2024",
        integrantes: 8,
        torneosActivos: 2,
        partidosJugados: 12,
        jugadores: ["@jugador_miembro1", "@jugador_miembro2", "@nuevo_jugador"]
    };

    // Cargar datos básicos
    document.getElementById('editNombreEquipo').value = datosEquipo.nombre;
    
    // Aquí iría la lógica AJAX real para cargar datos del equipo específico
    console.log(`Cargando datos del equipo ID: ${equipoId}`);
}

function inicializarTooltipsModal() {
    // Inicializar todos los tooltips en el modal
    const tooltips = document.querySelectorAll('#modalEditarEquipo [data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });
}

function agregarCampoJugadorEdit() {
    const container = document.getElementById('editJugadoresContainer');
    const nuevoJugador = `
        <div class="input-group mb-2">
            <span class="input-group-text">
                <i class="bi bi-person-fill"></i>
            </span>
            <input type="text" class="form-control" placeholder="Username del nuevo jugador" name="jugadorEdit[]">
            <button class="btn btn-outline-danger" type="button" onclick="removeJugadorEdit(this)">
                <i class="bi bi-dash"></i>
            </button>
        </div>
    `;
    container.innerHTML += nuevoJugador;
}

function removeJugadorEdit(button) {
    const container = document.getElementById('editJugadoresContainer');
    // No permitir eliminar si solo quedan 2 jugadores (líder + 1)
    const jugadorInputs = container.querySelectorAll('[name="jugadorEdit[]"]');
    if (jugadorInputs.length > 1) {
        button.parentElement.remove();
    } else {
        alert('El equipo debe tener al menos un miembro además del líder');
    }
}

function recopilarDatosFormularioEdit() {
    const nombre = document.getElementById('editNombreEquipo').value;
    const jugadores = Array.from(document.querySelectorAll('[name="jugadorEdit[]"]'))
        .map(input => input.value.trim())
        .filter(value => value.length > 0);
    
    return { nombre, jugadores };
}

function validarFormularioEditarEquipo(formData) {
    if (formData.jugadores.length === 0) {
        alert('El equipo debe tener al menos un miembro además del líder');
        return false;
    }
    
    // Validar que no haya usernames duplicados
    const usernames = formData.jugadores.map(j => j.toLowerCase());
    const uniqueUsernames = [...new Set(usernames)];
    if (usernames.length !== uniqueUsernames.length) {
        alert('No puedes agregar el mismo jugador dos veces');
        return false;
    }
    
    return true;
}

function editarEquipo(formData) {
    // Aquí iría la lógica AJAX para editar el equipo
    console.log('Datos del equipo a editar:', formData);
    alert(`Guardando cambios del equipo. Jugadores: ${formData.jugadores.join(', ')}`);
    bootstrap.Modal.getInstance(document.getElementById('modalEditarEquipo')).hide();
    
    // Recargar la lista de equipos para mostrar los cambios
    cargarMisEquipos();
}

// Funciones para navegación y modales
function irADetalleEquipo(equipoId) {
    // Usar la ruta de config.php - aquí simularemos la construcción de la URL
    const baseUrl = window.location.origin + window.location.pathname.replace('equiposListado.php', 'equipoDetalle.php');
    window.location.href = `${baseUrl}?id=${equipoId}`;
}

function abrirModalEditar(equipoId) {
    // Cargar datos específicos del equipo
    cargarDatosEquipoEnModal(equipoId);
    inicializarTooltipsModal();
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('modalEditarEquipo'));
    modal.show();
}

function abrirModalInvitar(equipoId, claveTemp) {
    // Establecer la clave temporal en el modal
    document.getElementById('claveTemporalEquipo').textContent = claveTemp;
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('modalInvitarJugador'));
    modal.show();
}