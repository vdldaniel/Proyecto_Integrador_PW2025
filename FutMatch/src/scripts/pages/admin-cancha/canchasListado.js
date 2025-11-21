

document.addEventListener("DOMContentLoaded", function () {
    console.log("JS cargado correctamente");
    cargarCanchas();
    cargarSuperficies();
    document.getElementById("btnGuardarCancha")
        .addEventListener("click", agregarCancha);
});

function cargarCanchas() {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_canchas.php")

        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                CANCHAS_CACHE = data.data;   // ← necesario para editar
                renderCanchas(data.data);
            } else {
                console.error("Error cargando canchas:", data.message);
            }
        })
        .catch(err => console.error("Error fetch:", err));
}


// Renderizar las tarjetas de cada cancha
// =====================================================================

function renderCanchas(canchas) {
    const contenedor = document.getElementById("canchasList");
    contenedor.innerHTML = "";

    canchas.forEach(cancha => {
        const estadoTexto = obtenerTextoEstado(cancha.id_estado);
        const estadoClase = obtenerClaseEstado(cancha.id_estado);
        const capacidad = obtenerCapacidad(cancha.id_superficie);

        contenedor.innerHTML += `
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">

                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-geo-alt text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>

                        

                            <div class="col-md-2">
                                <span class="text-muted">
                                    <i class="bi bi-people"></i> ${capacidad}
                                </span>
                            </div>

                            <div class="col-md-2">
                                <span class="badge ${estadoClase}">${estadoTexto}</span>
                            </div>

                            <div class="col-md-3 text-end">
                                

                                <button class="btn btn-dark btn-sm me-1" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditarCancha"
                                    data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-pencil"></i>
                                </button>


                                <button class="btn btn-dark btn-sm me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCerrarCancha"
                                    data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-pause-circle"></i>
                                </button>

                                <button class="btn btn-dark btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEliminarCancha"
                                    data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        `;
    });
}

document.addEventListener("click", function (e) {
    if (e.target.closest("[data-cancha-id]") && e.target.closest("#modalEditarCancha") === null) {
        const idCancha = e.target.closest("[data-cancha-id]").getAttribute("data-cancha-id");
        abrirModalEditar(idCancha);
    }
});

// Funciones auxiliares (texto y clases de estado, tipo de superficie)
// =====================================================================

function obtenerCapacidad(idSuperficie) {
    switch (idSuperficie) {
        case 1: return "Fútbol 5";
        case 2: return "Fútbol 7";
        case 3: return "Fútbol 9";
        case 4: return "Fútbol 11";
        default: return "N/D";
    }
}

function obtenerTextoEstado(idEstado) {
    switch (idEstado) {
        case 1: return "Habilitada";
        case 2: return "En revisión";
        case 3: return "Pendiente";
        case 4: return "Deshabilitada";
        default: return "Desconocido";
    }
}

function obtenerClaseEstado(idEstado) {
    switch (idEstado) {
        case 1: return "badge text-bg-dark";
        case 2: return "badge text-bg-warning";
        case 3: return "badge text-bg-info";
        case 4: return "badge text-bg-secondary";
        default: return "badge bg-secondary";
    }
}

// Cargar superficies
// ==========================
function cargarSuperficies() {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_superficies.php")
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") return;

            const select = document.getElementById("tipoSuperficie");
            select.innerHTML = `<option value="">Seleccionar...</option>`;

            data.data.forEach(s => {
                select.innerHTML += `
                    <option value="${s.id_superficie}">${s.nombre}</option>
                `;
            });
        })
        .catch(err => console.error("Error cargando superficies:", err));
}



// Guardar cancha nueva
// ==========================
function agregarCancha() {

    const datos = new FormData();
    console.log("nombreCancha:", document.getElementById("nombreCancha"));
    console.log("tipoSuperficie:", document.getElementById("tipoSuperficie"));
    console.log("ubicacionCancha:", document.getElementById("ubicacionCancha"));
    console.log("descripcionCancha:", document.getElementById("descripcionCancha"));
    console.log("capacidadCancha:", document.getElementById("capacidadCancha"));

    datos.append("nombre", document.getElementById("nombreCancha").value);
    datos.append("superficie", document.getElementById("tipoSuperficie").value);
    datos.append("ubicacion", document.getElementById("ubicacionCancha").value);
    datos.append("descripcion", document.getElementById("descripcionCancha").value);
    datos.append("tipo_cancha", document.getElementById("capacidadCancha").value);

    fetch(BASE_URL + "src/controllers/admin-cancha/agregar_cancha.php", {
        method: "POST",
        body: datos
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {

                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalAgregarCancha"));
                modal.hide();

                // Limpiar formulario
                document.getElementById("formAgregarCancha").reset();

                // Recargar lista
                cargarCanchas();

            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(err => {
            console.error("Error fetch:", err);
        });
}

// Editar cancha
// ==========================
function abrirModalEditar(id) {

    const cancha = CANCHAS_CACHE.find(c => c.id_cancha == id);

    if (!cancha) {
        console.error("No se encontró la cancha con ID", id);
        return;
    }

    
    document.getElementById("editCanchaId").value = cancha.id_cancha;
    document.getElementById("editNombreCancha").value = cancha.nombre;
    document.getElementById("editUbicacionCancha").value = cancha.direccion_completa;
    document.getElementById("editDescripcionCancha").value = cancha.descripcion;

    // Select superficie
    document.getElementById("editTipoSuperficie").value = cancha.id_superficie;

    // Tipo de cancha (capacidad)
    document.getElementById("editCapacidadCancha").value = cancha.tipo_cancha;
}


document.getElementById("btnActualizarCancha").addEventListener("click", actualizarCancha);

function actualizarCancha() {
    const data = new FormData();

    data.append("id_cancha", document.getElementById("editCanchaId").value);
    data.append("nombre", document.getElementById("editNombreCancha").value);
    data.append("descripcion", document.getElementById("editDescripcionCancha").value);
    data.append("ubicacion", document.getElementById("editUbicacionCancha").value);
    data.append("id_superficie", document.getElementById("editTipoSuperficie").value);
    data.append("capacidad", document.getElementById("editCapacidadCancha").value);

    fetch("/backend/canchas/update_cancha.php", {
        method: "POST",
        body: data
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === "success") {

                // Cerrar modal
                bootstrap.Modal.getInstance(document.getElementById("modalEditarCancha")).hide();

                // Volver a cargar lista
                cargarCanchas();
            } else {
                console.error(res.message);
                alert("Error al actualizar la cancha");
            }
        })
        .catch(err => console.log(err));
}
