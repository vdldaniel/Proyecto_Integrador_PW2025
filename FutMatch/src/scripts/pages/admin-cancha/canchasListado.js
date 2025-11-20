

document.addEventListener("DOMContentLoaded", function () {
    console.log("JS cargado correctamente");
    cargarCanchas();
});

function cargarCanchas() {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_canchas.php")

        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                renderCanchas(data.data);
            } else {
                console.error("Error cargando canchas:", data.message);
            }
        })
        .catch(err => console.error("Error fetch:", err));
}


// =====================================================================
// 2) Renderizar las tarjetas de cada cancha
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

                        PAGE_MIS_PERFILES_ADMIN_CANCHA

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


// =====================================================================
// 3) Funciones auxiliares (texto y clases de estado, tipo de superficie)
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


// =====================================================================
// 4) Gestión de MODAL — EDITAR CANCHA
// =====================================================================

const modalEditar = document.getElementById('modalEditarCancha');

modalEditar.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // botón que abrió el modal
    const canchaId = button.getAttribute('data-cancha-id');

    document.getElementById("editarCanchaId").value = canchaId;

    cargarDatosCancha(canchaId);
});


// =====================================================================
// 5) AJAX para obtener datos de UNA cancha
// =====================================================================

function cargarDatosCancha(id) {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_canchas.php?id=" + id)
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {
                const cancha = data.data;

                // Cargar valores en los inputs del modal
                document.getElementById("editarNombre").value = cancha.nombre;
                document.getElementById("editarDescripcion").value = cancha.descripcion;
                document.getElementById("editarEstado").value = cancha.id_estado;
                document.getElementById("editarSuperficie").value = cancha.id_superficie;

            } else {
                console.error("Error:", data.message);
            }

        })
        .catch(err => console.error("Error fetch:", err));
}


// =====================================================================
// 6) Enviar actualización desde el modal EDITAR
// =====================================================================

document.getElementById("formEditarCancha").addEventListener("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("/src/controllers/canchas/update_cancha.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {
                alert("Cancha actualizada correctamente.");
                cargarCanchas(); // refresca el listado
                bootstrap.Modal.getInstance(modalEditar).hide();
            } else {
                alert("Error: " + data.message);
            }

        })
        .catch(err => console.error(err));
});


// =====================================================================
// 7) (Opcional) MODALES para eliminar / cerrar cancha
// =====================================================================
// Cuando quieras continúo estos módulos con su PHP correspondiente.

