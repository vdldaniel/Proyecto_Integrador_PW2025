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
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                                     style="width: 60px; height: 60px; border: 2px;">
                                    <i class="bi bi-people text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">
                                    <a href="equipoDetalle.php?id=${equipo.id}" class="text-decoration-none">
                                        ${equipo.nombre}
                                    </a>
                                </h5>
                            </div>
                            <div class="col-md-2">
                                <a href="equipoDetalle.php?id=${equipo.id}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-people"></i> ${equipo.integrantes} integrantes
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="equipoDetalle.php?id=${equipo.id}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-trophy"></i> ${equipo.torneosActivos} torneos activos
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="equipoDetalle.php?id=${equipo.id}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-calendar-event"></i> ${equipo.partidosProximos} partidos próx.
                                </a>
                            </div>
                            <div class="col-md-1 text-end">
                                <button class="btn btn-outline-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalInvitarJugador"
                                        data-equipo-id="${equipo.id}"
                                        data-clave-temp="${equipo.claveTemp}"
                                        data-bs-toggle="tooltip" 
                                        title="Invitar jugador">
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
    
    // Event listeners para botones de invitar
    document.querySelectorAll('[data-bs-target="#modalInvitarJugador"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const claveTemp = this.dataset.claveTemp;
            document.getElementById('claveTemporalEquipo').textContent = claveTemp;
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