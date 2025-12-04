// Variables globales para los mapas
let mapAgregar = null;
let markerAgregar = null;
let mapEditar = null;
let markerEditar = null;

document.addEventListener("DOMContentLoaded", function () {
  console.log("JS cargado correctamente");
  cargarCanchas();
  cargarSuperficies();
  cargarTiposPartido(); // <--- nuevo: llena el select de tipos
  document
    .getElementById("btnGuardarCancha")
    .addEventListener("click", agregarCancha);

  // Inicializar mapas cuando se abren los modales
  document
    .getElementById("modalAgregarCancha")
    .addEventListener("shown.bs.modal", inicializarMapaAgregar);
  document
    .getElementById("modalEditarCancha")
    .addEventListener("shown.bs.modal", inicializarMapaEditar);
});

// ===================================
// INICIALIZACIÓN DEL MAPA AGREGAR
// ===================================
function inicializarMapaAgregar() {
  if (mapAgregar) {
    mapAgregar.remove();
  }

  // Centrado en La Plata, Argentina por defecto
  mapAgregar = L.map("mapAgregar").setView([-34.9214, -57.9544], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(mapAgregar);

  markerAgregar = L.marker([-34.9214, -57.9544], {
    draggable: true,
  }).addTo(mapAgregar);

  // Actualizar coordenadas cuando se arrastra el marcador
  markerAgregar.on("dragend", function (e) {
    const position = markerAgregar.getLatLng();
    obtenerDireccionPorCoordenadas(position.lat, position.lng, "agregar");
  });

  // Botón buscar dirección
  document.getElementById("btnBuscarDireccionAgregar").onclick = function () {
    buscarDireccion("agregar");
  };

  document.getElementById("inputBuscadorDireccionAgregar").onkeypress =
    function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        buscarDireccion("agregar");
      }
    };
}

// ===================================
// INICIALIZACIÓN DEL MAPA EDITAR
// ===================================
function inicializarMapaEditar() {
  if (mapEditar) {
    mapEditar.remove();
  }

  // Obtener coordenadas actuales de la cancha o usar ubicación por defecto
  const lat =
    parseFloat(document.getElementById("inputLatitudEditar").value) || -34.9214;
  const lng =
    parseFloat(document.getElementById("inputLongitudEditar").value) ||
    -57.9544;

  mapEditar = L.map("mapEditar").setView([lat, lng], 13);

  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(mapEditar);

  markerEditar = L.marker([lat, lng], {
    draggable: true,
  }).addTo(mapEditar);

  // Actualizar coordenadas cuando se arrastra el marcador
  markerEditar.on("dragend", function (e) {
    const position = markerEditar.getLatLng();
    obtenerDireccionPorCoordenadas(position.lat, position.lng, "editar");
  });

  // Botón buscar dirección
  document.getElementById("btnBuscarDireccionEditar").onclick = function () {
    buscarDireccion("editar");
  };

  document.getElementById("inputBuscadorDireccionEditar").onkeypress =
    function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        buscarDireccion("editar");
      }
    };
}

// ===================================
// GEOCODIFICACIÓN: Búsqueda de dirección
// ===================================
function buscarDireccion(tipo) {
  const inputId =
    tipo === "agregar"
      ? "inputBuscadorDireccionAgregar"
      : "inputBuscadorDireccionEditar";
  const query = document.getElementById(inputId).value.trim();

  if (!query) {
    showToast("Por favor, ingresá una dirección para buscar.", "warning");
    return;
  }

  const url = `${GEOCODING_PROXY}?tipo=search&q=${encodeURIComponent(query)}`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      if (data.length > 0) {
        const result = data[0];
        const lat = parseFloat(result.lat);
        const lon = parseFloat(result.lon);

        // Mover el mapa y el marcador
        if (tipo === "agregar") {
          mapAgregar.setView([lat, lon], 16);
          markerAgregar.setLatLng([lat, lon]);
        } else {
          mapEditar.setView([lat, lon], 16);
          markerEditar.setLatLng([lat, lon]);
        }

        // Obtener dirección detallada
        obtenerDireccionPorCoordenadas(lat, lon, tipo);
      } else {
        showToast(
          "No se encontró la dirección. Intentá con otra búsqueda o arrastrá el marcador en el mapa.",
          "warning"
        );
      }
    })
    .catch((error) => {
      console.error("Error en la búsqueda:", error);
      showToast("Error al buscar la dirección. Intentá nuevamente.", "error");
    });
}

// ===================================
// GEOCODIFICACIÓN INVERSA: Coordenadas -> Dirección
// ===================================
function obtenerDireccionPorCoordenadas(lat, lon, tipo) {
  const url = `${GEOCODING_PROXY}?tipo=reverse&lat=${lat}&lon=${lon}`;

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      if (data && data.address) {
        const displayName = data.display_name;

        if (tipo === "agregar") {
          document.getElementById("ubicacionCancha").value = displayName;
          document.getElementById("inputLatitudAgregar").value = lat;
          document.getElementById("inputLongitudAgregar").value = lon;
          document.getElementById("textoDireccionAgregar").textContent =
            displayName;
          document
            .getElementById("direccionSeleccionadaAgregar")
            .classList.remove("d-none");
        } else {
          document.getElementById("editUbicacionCancha").value = displayName;
          document.getElementById("inputLatitudEditar").value = lat;
          document.getElementById("inputLongitudEditar").value = lon;
          document.getElementById("textoDireccionEditar").textContent =
            displayName;
          document
            .getElementById("direccionSeleccionadaEditar")
            .classList.remove("d-none");
        }
      }
    })
    .catch((error) => {
      console.error("Error en geocodificación inversa:", error);
    });
}

function cargarCanchas() {
  fetch(BASE_URL + "src/controllers/admin-cancha/get_canchas.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        CANCHAS_CACHE = data.data;
        console.log("Canchas cargadas:", CANCHAS_CACHE);
        renderCanchas(CANCHAS_CACHE);
        filtrarCanchas(); 
      } else {
        console.error("Error cargando canchas:", data.message);
      }
    })
    .catch((err) => console.error("Error fetch:", err));
}

// ====================================================
// Renderizar las tarjetas de cada cancha
// ====================================================

function renderCanchas(canchas) {
  const contenedor = document.getElementById("canchasList");
  contenedor.innerHTML = "";

  canchas.forEach((cancha) => {
    const estadoTexto = obtenerTextoEstado(cancha.id_estado);
    const estadoClase = obtenerClaseEstado(cancha.id_estado);
    const capacidad = obtenerCapacidad(cancha.id_tipo_partido);

    let botonAccion = "";
    let iconoAccion = "";
    let claseAccion = "";
    let accion = "";

    if (cancha.id_estado == 3) {
      //  mostrar botón CERRAR
      botonAccion = "Cerrar";
      iconoAccion = "bi-pause-circle";
      claseAccion = "btn-warning";
      accion = "cerrar";
    } else if (cancha.id_estado == 4) {
      //  mostrar botón RESTAURAR
      botonAccion = "Restaurar";
      iconoAccion = "bi-arrow-clockwise";
      claseAccion = "btn-success";
      accion = "restaurar";
    } else {
      //  otros estados
      accion = "ninguna";
    }

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
                                <small class="text-muted">${cancha.direccion_completa
      }</small>
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

                                <a class="btn btn-dark btn-sm me-1" href="<?= PAGE_MIS_PERFILES_ADMIN_CANCHA ?>" title="Ver perfil">
									<i class="bi bi-eye"></i>
								</a>

                                <button class="btn btn-dark btn-sm me-1 btn-editar" data-cancha-id="${cancha.id_cancha
      }">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                ${accion !== "ninguna"
        ? `
                                        <button class="btn ${claseAccion} btn-sm me-1 btn-accion" 
                                            data-accion="${accion}" 
                                            data-cancha-id="${cancha.id_cancha}">
                                            <i class="bi ${iconoAccion}"></i> ${botonAccion}
                                        </button>
                                    `
        : ""
      }

                                <button class="btn btn-danger btn-sm btn-eliminar" data-cancha-id="${cancha.id_cancha
      }">
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

  // EVENTOS
  document.querySelectorAll(".btn-editar").forEach((btn) => {
    btn.addEventListener("click", () => abrirModalEditar(btn.dataset.canchaId));
  });

  document.querySelectorAll(".btn-eliminar").forEach((btn) => {
    btn.addEventListener("click", () =>
      abrirModalEliminar(btn.dataset.canchaId)
    );
  });

  document.querySelectorAll(".btn-accion").forEach((btn) => {
    btn.addEventListener("click", () => {
      const id = btn.dataset.canchaId;
      const accion = btn.dataset.accion;

      if (accion === "cerrar") {
        abrirModalCerrar(id);
      } else if (accion === "restaurar") {
        abrirModalRestaurar(id);
      }
    });
  });
}

// =====================================================================
// Funciones auxiliares (texto y clases de estado, tipo de superficie)
// =====================================================================

function obtenerCapacidad(idTipoPartido) {
  const select = document.getElementById("capacidadCancha");
  if (!select) return "N/D";

  const option = select.querySelector(`option[value="${idTipoPartido}"]`);
  if (!option) return "N/D";

  // Extrae solo el nombre (lo que está antes del paréntesis)
  return option.textContent.split("(")[0].trim();
}

function obtenerTextoEstado(id_estado) {
  switch (id_estado) {
    case 1:
      return "Pendiente de verificación";
    case 2:
      return "En revisión";
    case 3:
      return "Habilitada";
    case 4:
      return "Deshabilitada";
    case 5:
      return "Suspendida";
    default:
      return "Desconocido";
  }
}

function obtenerClaseEstado(id_estado) {
  switch (id_estado) {
    case 1:
      return "badge text-bg-dark";
    case 2:
      return "badge text-bg-warning";
    case 3:
      return "badge text-bg-info";
    case 4:
      return "badge text-bg-secondary";
    default:
      return "badge bg-secondary";
  }
}

// Cargar superficies  y tipos de partido
// ==========================
function cargarSuperficies() {
  fetch(BASE_URL + "src/controllers/admin-cancha/get_superficies.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.status !== "success") return;

      const select = document.getElementById("tipoSuperficie");
      select.innerHTML = `<option value="">Seleccionar...</option>`;

      data.data.forEach((s) => {
        select.innerHTML += `
                    <option value="${s.id_superficie}">${s.nombre}</option>
                `;
      });
    })
    .catch((err) => console.error("Error cargando superficies:", err));
}

function cargarTiposPartido() {
  fetch(BASE_URL + "src/controllers/admin-cancha/get_tipo_partido.php")
    .then((res) => res.json())
    .then((data) => {
      if (data.status !== "success") return;
      const select = document.getElementById("capacidadCancha");
      select.innerHTML = `<option value="">Seleccionar...</option>`;
      data.data.forEach((t) => {
        select.innerHTML += `<option value="${t.id_tipo_partido}" data-min="${t.min_participantes}" data-max="${t.max_participantes}">${t.nombre} (${t.min_participantes}-${t.max_participantes})</option>`;
      });

      // también llenar el select de editar si existe
      const selectEdit = document.getElementById("editCapacidadCancha");
      if (selectEdit) {
        selectEdit.innerHTML = `<option value="">Seleccionar...</option>`;
        data.data.forEach((t) => {
          selectEdit.innerHTML += `<option value="${t.id_tipo_partido}" data-min="${t.min_participantes}" data-max="${t.max_participantes}">${t.nombre} (${t.min_participantes}-${t.max_participantes})</option>`;
        });
      }
    })
    .catch((err) => console.error("Error cargando tipos:", err));
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
    body: datos,
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.status === "success") {
        const modalEl = document.getElementById("modalAgregarCancha");
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();

        document.getElementById("formAgregarCancha").reset();
        document
          .getElementById("direccionSeleccionadaAgregar")
          .classList.add("d-none");
        cargarCanchas();


        mostrarToast("Cancha agregada correctamente", "success");

      } else {

        mostrarToast("Error: " + data.message, "error");
        console.error(data);
      }
    })
    .catch((err) => {
      console.error("Error fetch agregar cancha:", err);
     
      mostrarToast("Error al comunicarse con el servidor", "error");
    });
}
// ==========================
// Editar cancha
// ==========================
function abrirModalEditar(id) {
  const cancha = CANCHAS_CACHE.find((c) => c.id_cancha == id);

  if (!cancha) {
    console.error("No se encontró la cancha con ID", id);
    return;
  }

  document.getElementById("editCanchaId").value = cancha.id_cancha;
  document.getElementById("editNombreCancha").value = cancha.nombre;
  document.getElementById("editUbicacionCancha").value =
    cancha.direccion_completa || "";
  document.getElementById("editDescripcionCancha").value =
    cancha.descripcion || "";
  document.getElementById("editTipoSuperficie").value =
    cancha.id_superficie || "";

  // Establecer coordenadas si existen
  document.getElementById("inputLatitudEditar").value =
    cancha.latitud || -34.9214;
  document.getElementById("inputLongitudEditar").value =
    cancha.longitud || -57.9544;

  // Mostrar dirección si existe
  if (cancha.direccion_completa) {
    document.getElementById("textoDireccionEditar").textContent =
      cancha.direccion_completa;
    document
      .getElementById("direccionSeleccionadaEditar")
      .classList.remove("d-none");
  } else {
    document
      .getElementById("direccionSeleccionadaEditar")
      .classList.add("d-none");
  }

  // traer id_tipo_partido real
  if (cancha.id_tipo_partido) {
    document.getElementById("editCapacidadCancha").value =
      cancha.id_tipo_partido;
  } else {
    document.getElementById("editCapacidadCancha").value = "";
  }

  // abrir modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalEditarCancha")
  );
  modal.show();
}

document
  .getElementById("btnActualizarCancha")
  .addEventListener("click", function () {
    const data = new FormData();
    data.append("id_cancha", document.getElementById("editCanchaId").value);
    data.append("nombre", document.getElementById("editNombreCancha").value);
    data.append(
      "descripcion",
      document.getElementById("editDescripcionCancha").value
    );
    data.append(
      "ubicacion",
      document.getElementById("editUbicacionCancha").value
    );
    data.append("latitud", document.getElementById("inputLatitudEditar").value);
    data.append(
      "longitud",
      document.getElementById("inputLongitudEditar").value
    );
    data.append(
      "superficie",
      document.getElementById("editTipoSuperficie").value
    );
    data.append(
      "id_tipo_partido",
      document.getElementById("editCapacidadCancha").value
    );

    fetch(BASE_URL + "src/controllers/admin-cancha/update_cancha.php", {
      method: "POST",
      body: data,
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.status === "success") {
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("modalEditarCancha")
          );
          if (modal) modal.hide();
          document
            .getElementById("direccionSeleccionadaEditar")
            .classList.add("d-none");
          cargarCanchas();
          showToast("Cancha actualizada exitosamente", "success");
        } else {
          console.error(res);
          showToast("Error al actualizar la cancha", "error");
        }
      })
      .catch((err) => console.error(err));
  });

// Abrir modal eliminar cancha
// ==========================
function abrirModalEliminar(id) {
  document.getElementById("deleteCanchaId").value = id;

  const modal = new bootstrap.Modal(
    document.getElementById("modalEliminarCancha")
  );
  modal.show();
}

document
  .getElementById("btnConfirmarEliminar")
  .addEventListener("click", () => {
    const id = document.getElementById("deleteCanchaId").value;

    fetch(BASE_URL + "src/controllers/admin-cancha/borrar_cancha.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_cancha: id }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.ok) {
          showToast("Cancha eliminada exitosamente", "success");
          location.reload();
        } else {
          showToast("Error: " + data.error, "error");
        }
      });
  });

// Abrir modal cerrar cancha
// ==========================
function abrirModalCerrar(id) {
  document.getElementById("cerrarCanchaId").value = id;

  const modal = new bootstrap.Modal(
    document.getElementById("modalCerrarCancha")
  );
  modal.show();
}

document.getElementById("btnConfirmarCierre").addEventListener("click", () => {
  const id = document.getElementById("cerrarCanchaId").value;

  fetch(BASE_URL + "src/controllers/admin-cancha/cerrar_cancha.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_cancha: id }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.ok) {
        showToast("La cancha fue deshabilitada exitosamente", "success");
        location.reload();
      } else {
        showToast("Error: " + data.error, "error");
      }
    })
    .catch((err) => console.error("Error en cierre:", err));
});

// Cambiar de modal eliminar a cerrar cancha
// ==========================
document.getElementById("btnSuspenderEnLugar").addEventListener("click", () => {
  const id = document.getElementById("deleteCanchaId").value;

  // Cerrar modal de eliminar
  const modalEliminar = bootstrap.Modal.getInstance(
    document.getElementById("modalEliminarCancha")
  );
  modalEliminar.hide();

  // Abrir modal de cierre temporal
  abrirModalCerrar(id);
});

// Abrir modal restaurar cancha
// ==========================
function abrirModalRestaurar(id) {
  document.getElementById("restaurarCanchaId").value = id;

  const modal = new bootstrap.Modal(
    document.getElementById("modalRestaurarCancha")
  );
  modal.show();
}

document
  .getElementById("btnConfirmarRestaurar")
  .addEventListener("click", () => {
    const id = document.getElementById("restaurarCanchaId").value;

    restaurarCancha(id, true); // true = recargar página luego
  });

// Filtro buscar cancha
// ==========================
document
  .getElementById("searchInput")
  .addEventListener("input", filtrarCanchas);

function filtrarCanchas() {
  const texto = document
    .getElementById("searchInput")
    .value.toLowerCase()
    .trim();

  if (texto === "") {
    renderCanchas(CANCHAS_CACHE);
    return;
  }

  const filtradas = CANCHAS_CACHE.filter((cancha) => {
    // Obtener nombre del tipo de partido
    const tipoPartidoNombre = obtenerCapacidad(
      cancha.id_tipo_partido
    ).toLowerCase();

    return (
      cancha.nombre.toLowerCase().includes(texto) ||
      (cancha.direccion_completa &&
        cancha.direccion_completa.toLowerCase().includes(texto)) ||
      (cancha.tipo_cancha &&
        cancha.tipo_cancha.toLowerCase().includes(texto)) ||
      tipoPartidoNombre.includes(texto)
    );
  });

  renderCanchas(filtradas);
}

// Historial de canchas
// ==========================
function cargarHistorialDesdeCache() {
  if (!Array.isArray(CANCHAS_CACHE)) return;

  const historial = CANCHAS_CACHE.filter(
    (c) => c.id_estado == 4 || c.id_estado == 5
  );

  renderHistorial(historial);
}

function renderHistorial(lista) {
  const tbody = document.querySelector("#modalHistorialCanchas tbody");
  tbody.innerHTML = "";

  if (lista.length === 0) {
    tbody.innerHTML = `
            <tr><td colspan="6" class="text-center text-muted">No hay canchas en historial.</td></tr>
        `;
    return;
  }

  lista.forEach((c, index) => {
    let estadoTexto = "";
    switch (c.id_estado) {
      case 4:
        estadoTexto = "Deshabilitada";
        break;
      case 5:
        estadoTexto = "Suspendida";
        break;
      default:
        estadoTexto = "Estado desconocido";
    }

    tbody.innerHTML += `
            <tr>
                <td>${index + 1}</td>
                <td>${c.nombre}</td>
                <td>${c.tipo_nombre}</td>
                <td>${c.descripcion || "-"}</td>
                <td>${estadoTexto}</td>

                <td>
                    ${c.id_estado == 4
        ? `<button class="btn btn-sm btn-dark btn-restaurar" data-id="${c.id_cancha}">
                                <i class="bi bi-arrow-clockwise"></i> Restaurar
                           </button>`
        : ""
      }
                </td>
            </tr>
        `;
  });

  // activar el botón Restaurar
  document.querySelectorAll(".btn-restaurar").forEach((btn) => {
    btn.addEventListener("click", () => {
      restaurarCancha(btn.dataset.id, false); // no recargar página
    });
  });
}

function restaurarCancha(id, reload = false) {
  return fetch(BASE_URL + "src/controllers/admin-cancha/restaurar_cancha.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id_cancha: id }),
  })
    .then((r) => r.json())
    .then((res) => {
      if (!res.ok) {
        showToast("Error al restaurar la cancha", "error");
        return;
      }

      //actualiza cache sin recargar
      if (!reload) {
        const cancha = CANCHAS_CACHE.find((c) => c.id_cancha == id);
        if (cancha) cancha.id_estado = 1;

        cargarHistorialDesdeCache();
        cargarCanchas();

        showToast("Cancha restaurada con éxito", "success");
      }

      if (reload) {
        showToast(
          "La cancha fue restaurada y está pendiente de verificación",
          "success"
        );
        location.reload();
      }
    });
}

document
  .getElementById("modalHistorialCanchas")
  .addEventListener("show.bs.modal", () => {
    cargarHistorialDesdeCache();
  });


// --- Sistema de Toasts ---
function mostrarToast(mensaje, tipo = "success") {
  const toastContainer = document.getElementById("toastContainer");

  const colores = {
    success: "bg-success text-white",
    error: "bg-danger text-white",
    warning: "bg-warning text-dark",
    info: "bg-info text-dark"
  };

  const toast = document.createElement("div");
  toast.className = `toast align-items-center ${colores[tipo]} border-0`;
  toast.role = "alert";
  toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

  toastContainer.appendChild(toast);

  const bsToast = new bootstrap.Toast(toast, { delay: 3500 });
  bsToast.show();

  toast.addEventListener("hidden.bs.toast", () => {
    toast.remove();
  });
}
