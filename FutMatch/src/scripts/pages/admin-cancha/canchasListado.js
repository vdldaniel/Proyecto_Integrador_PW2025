

document.addEventListener("DOMContentLoaded", function () {
    console.log("JS cargado correctamente");
    cargarCanchas();
    cargarSuperficies();
    cargarTiposPartido(); // <--- nuevo: llena el select de tipos
    document.getElementById("btnGuardarCancha")
        .addEventListener("click", agregarCancha);
});

function cargarCanchas() {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_canchas.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                CANCHAS_CACHE = data.data;
                console.log("CANCHAS_CACHE:", CANCHAS_CACHE);
                renderCanchas(CANCHAS_CACHE);
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
    contenedor.innerHTML = ""; // Evita duplicados

    canchas.forEach(cancha => {

        const estadoTexto = obtenerTextoEstado(cancha.id_estado);
        const estadoClase = obtenerClaseEstado(cancha.id_estado);
        const capacidad = obtenerCapacidad(cancha.tipo_cancha);

        const html = `
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

                            <div class="col-md-3">
                                <h5 class="mb-1">${cancha.nombre}</h5>
                                <small class="text-muted">${cancha.direccion_completa}</small>
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
                                <button class="btn btn-dark btn-sm me-1 btn-editar" data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button class="btn btn-dark btn-sm me-1 btn-cerrar" data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-pause-circle"></i>
                                </button>

                                <button class="btn btn-dark btn-sm btn-eliminar" data-cancha-id="${cancha.id_cancha}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        `;

        contenedor.insertAdjacentHTML("beforeend", html);
    });


    document.querySelectorAll(".btn-editar").forEach(btn => {
        btn.addEventListener("click", () => {
            abrirModalEditar(btn.dataset.canchaId);
        });
    });
    document.querySelectorAll(".btn-cerrar").forEach(btn => {
        btn.addEventListener("click", () => {
            abrirModalCerrar(btn.dataset.canchaId);
        });
    });
    document.querySelectorAll(".btn-eliminar").forEach(btn => {
        btn.addEventListener("click", () => {
            abrirModalEliminar(btn.dataset.canchaId);
        });
    });
}





// Funciones auxiliares (texto y clases de estado, tipo de superficie)
// =====================================================================

function obtenerCapacidad(tipo_cancha) {
    switch (tipo_cancha) {
        case 1: return "Fútbol 5";
        case 2: return "Fútbol 7";
        case 3: return "Fútbol 9";
        case 4: return "Fútbol 11";
        default: return "N/D";
    }
}

function obtenerTextoEstado(id_estado) {
    switch (id_estado) {
        case 1: return "Habilitada";
        case 2: return "En revisión";
        case 3: return "Pendiente";
        case 4: return "Deshabilitada";
        default: return "Desconocido";
    }
}

function obtenerClaseEstado(id_estado) {
    switch (id_estado) {
        case 1: return "badge text-bg-dark";
        case 2: return "badge text-bg-warning";
        case 3: return "badge text-bg-info";
        case 4: return "badge text-bg-secondary";
        default: return "badge bg-secondary";
    }
}

// Cargar superficies  y tipos de partido
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

function cargarTiposPartido() {
    fetch(BASE_URL + "src/controllers/admin-cancha/get_tipo_partido.php")
        .then(res => res.json())
        .then(data => {
            if (data.status !== "success") return;
            const select = document.getElementById("capacidadCancha");
            select.innerHTML = `<option value="">Seleccionar...</option>`;
            data.data.forEach(t => {
                select.innerHTML += `<option value="${t.id_tipo_partido}" data-min="${t.min_participantes}" data-max="${t.max_participantes}">${t.nombre} (${t.min_participantes}-${t.max_participantes})</option>`;
            });

            // también llenar el select de editar si existe
            const selectEdit = document.getElementById("editCapacidadCancha");
            if (selectEdit) {
                selectEdit.innerHTML = `<option value="">Seleccionar...</option>`;
                data.data.forEach(t => {
                    selectEdit.innerHTML += `<option value="${t.id_tipo_partido}" data-min="${t.min_participantes}" data-max="${t.max_participantes}">${t.nombre} (${t.min_participantes}-${t.max_participantes})</option>`;
                });
            }
        })
        .catch(err => console.error("Error cargando tipos:", err));
}


// Guardar cancha nueva
// ==========================
function agregarCancha() {
    const datos = new FormData();
    datos.append("nombre", document.getElementById("nombreCancha").value);
    datos.append("superficie", document.getElementById("tipoSuperficie").value);
    datos.append("ubicacion", document.getElementById("ubicacionCancha").value);
    datos.append("descripcion", document.getElementById("descripcionCancha").value);
    datos.append("id_tipo_partido", document.getElementById("capacidadCancha").value);

    fetch(BASE_URL + "src/controllers/admin-cancha/agregar_cancha.php", {
        method: "POST",
        body: datos
    })
        .then(r => r.json())
        .then(data => {
            if (data.status === "success") {
                const modalEl = document.getElementById("modalAgregarCancha");
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                document.getElementById("formAgregarCancha").reset();
                cargarCanchas();
            } else {
                alert("Error: " + data.message);
                console.error(data);
            }
        })
        .catch(err => console.error("Error fetch agregar cancha:", err));
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
    document.getElementById("editUbicacionCancha").value = cancha.direccion_completa || "";
    document.getElementById("editDescripcionCancha").value = cancha.descripcion || "";
    document.getElementById("editTipoSuperficie").value = cancha.id_superficie || "";

    // traer id_tipo_partido real
    if (cancha.id_tipo_partido) {
        document.getElementById("editCapacidadCancha").value = cancha.id_tipo_partido;
    } else {
        document.getElementById("editCapacidadCancha").value = "";
    }

    // abrir modal
    const modal = new bootstrap.Modal(document.getElementById("modalEditarCancha"));
    modal.show();
}



document.getElementById("btnActualizarCancha").addEventListener("click", function () {
    const data = new FormData();
    data.append("id_cancha", document.getElementById("editCanchaId").value);
    data.append("nombre", document.getElementById("editNombreCancha").value);
    data.append("descripcion", document.getElementById("editDescripcionCancha").value);
    data.append("ubicacion", document.getElementById("editUbicacionCancha").value);
    data.append("superficie", document.getElementById("editTipoSuperficie").value);
    data.append("id_tipo_partido", document.getElementById("editCapacidadCancha").value);

    fetch(BASE_URL + "src/controllers/admin-cancha/update_cancha.php", {
        method: "POST",
        body: data
    })
        .then(r => r.json())
        .then(res => {
            if (res.status === "success") {
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalEditarCancha"));
                if (modal) modal.hide();
                cargarCanchas();
            } else {
                console.error(res);
                alert("Error al actualizar la cancha");
            }
        })
        .catch(err => console.error(err));
});


// Abrir modal eliminar cancha
// ==========================
function abrirModalEliminar(id) {
    document.getElementById('deleteCanchaId').value = id;

    const modal = new bootstrap.Modal(document.getElementById('modalEliminarCancha'));
    modal.show();
}

document.getElementById('btnConfirmarEliminar').addEventListener('click', () => {

    const id = document.getElementById('deleteCanchaId').value;

    fetch(BASE_URL + "src/controllers/admin-cancha/borrar_cancha.php", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_cancha: id })
    })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert("Cancha eliminada.");
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        });
});

// Abrir modal cerrar cancha
// ==========================
function abrirModalCerrar(id) {
    document.getElementById('cerrarCanchaId').value = id;

    const modal = new bootstrap.Modal(document.getElementById('modalCerrarCancha'));
    modal.show();
}

document.getElementById('btnConfirmarCierre').addEventListener('click', () => {

    const id = document.getElementById('cerrarCanchaId').value;
    const fecha = document.getElementById('fechaCierre').value;
    const indefinido = document.getElementById('cierreIndefinido').checked;
    const mensaje = document.getElementById('mensajeCierre').value;

    if (!fecha && !indefinido) {
        alert("Debes elegir una fecha o marcar cierre indefinido.");
        return;
    }

    fetch(BASE_URL + "src/controllers/admin-cancha/cerrar_cancha.php", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_cancha: id,
            fecha_cierre: indefinido ? null : fecha,
            indefinido: indefinido,
            mensaje: mensaje
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert("La cancha fue cerrada temporalmente.");
                location.reload();
            } else {
                alert("Error: " + data.error);
            }
        });
});



document.getElementById('btnSuspenderEnLugar').addEventListener('click', () => {

    const id = document.getElementById('deleteCanchaId').value;

    // Cerrar modal de eliminar
    const modalEliminar = bootstrap.Modal.getInstance(document.getElementById('modalEliminarCancha'));
    modalEliminar.hide();

    // Abrir modal de cierre temporal
    abrirModalCerrar(id);
});
