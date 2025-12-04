/**
 * SOLICITUDES ADMIN CANCHA - Sistema de gestión
 * ============================================
 * Cargar, filtrar y gestionar solicitudes de administradores de cancha
 */

document.addEventListener("DOMContentLoaded", function () {
  // Debug: Verificar las URLs
  console.log("GET URL:", GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA);
  console.log("UPDATE URL:", UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA);

  // Cargar solicitudes pendientes al inicio
  cargarSolicitudes(1);

  // Event listeners para tabs
  document
    .getElementById("pendientes-tab")
    .addEventListener("click", function () {
      cargarSolicitudes(1); // Estado 1: Pendiente
    });

  document
    .getElementById("en-verificacion-tab")
    .addEventListener("click", function () {
      cargarSolicitudes(2); // Estado 2: En verificación
    });

  document
    .getElementById("aceptadas-tab")
    .addEventListener("click", function () {
      cargarSolicitudes(3); // Estado 3: Aceptadas
    });

  document
    .getElementById("rechazadas-tab")
    .addEventListener("click", function () {
      cargarSolicitudes(4); // Estado 4: Rechazadas
    });

  // Event listeners para filtros de columna
  initializeColumnFilters();
});

/**
 * Inicializar filtros de columna
 */
function initializeColumnFilters() {
  document.addEventListener("input", function (e) {
    if (e.target.classList.contains("filter-input")) {
      aplicarFiltros();
    }
  });
}

/**
 * Aplicar todos los filtros de columna
 */
function aplicarFiltros() {
  const filtros = {
    id: document.querySelector('[data-column="id"]')?.value.toLowerCase() || "",
    usuario:
      document.querySelector('[data-column="usuario"]')?.value.toLowerCase() ||
      "",
    cancha:
      document.querySelector('[data-column="cancha"]')?.value.toLowerCase() ||
      "",
    direccion:
      document
        .querySelector('[data-column="direccion"]')
        ?.value.toLowerCase() || "",
    verificador:
      document
        .querySelector('[data-column="verificador"]')
        ?.value.toLowerCase() || "",
    fecha:
      document.querySelector('[data-column="fecha"]')?.value.toLowerCase() ||
      "",
  };

  const filas = document.querySelectorAll("#tbody-solicitudes tr");

  filas.forEach((fila) => {
    const id = fila.getAttribute("data-id")?.toLowerCase() || "";
    const usuario = fila.getAttribute("data-usuario")?.toLowerCase() || "";
    const cancha = fila.getAttribute("data-cancha")?.toLowerCase() || "";
    const direccion = fila.getAttribute("data-direccion")?.toLowerCase() || "";
    const verificador =
      fila.getAttribute("data-verificador")?.toLowerCase() || "";
    const fecha = fila.getAttribute("data-fecha")?.toLowerCase() || "";

    const cumpleFiltros =
      id.includes(filtros.id) &&
      usuario.includes(filtros.usuario) &&
      cancha.includes(filtros.cancha) &&
      direccion.includes(filtros.direccion) &&
      verificador.includes(filtros.verificador) &&
      fecha.includes(filtros.fecha);

    fila.style.display = cumpleFiltros ? "" : "none";
  });
}

/**
 * Cargar solicitudes por estado
 */
async function cargarSolicitudes(idEstado) {
  try {
    const response = await fetch(
      `${GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA}?estado=${idEstado}`
    );
    if (!response.ok) throw new Error("Error al cargar solicitudes");

    const solicitudes = await response.json();

    // Mapear id_estado a tipo de solicitud
    const tipoMap = {
      1: "pendiente",
      2: "en-verificacion",
      3: "aceptada",
      4: "rechazada",
    };

    renderizarSolicitudes(solicitudes, tipoMap[idEstado]);
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al cargar solicitudes", "error");
  }
}

/**
 * Recargar el tab actualmente activo
 */
function recargarTabActual() {
  const activeTab = document.querySelector(".nav-link.active");
  const tabId = activeTab.getAttribute("id");

  // Mapear tab ID a estado
  const estadoMap = {
    "pendientes-tab": 1,
    "en-verificacion-tab": 2,
    "aceptadas-tab": 3,
    "rechazadas-tab": 4,
  };

  const estado = estadoMap[tabId] || 1;
  cargarSolicitudes(estado);
}

function renderizarSolicitudes(solicitudes, tipoSolicitud) {
  const tbody = document.getElementById("tbody-solicitudes");
  tbody.innerHTML = "";

  const mensajes = {
    pendiente: "pendientes",
    "en-verificacion": "en verificación",
    aceptada: "aceptadas",
    rechazada: "rechazadas",
  };

  if (solicitudes.length === 0) {
    tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="bi bi-info-circle me-2"></i>
                    No hay solicitudes ${
                      mensajes[tipoSolicitud] || tipoSolicitud
                    } en este momento.
                </td>
            </tr>
        `;
    return;
  }

  solicitudes.forEach((solicitud) => {
    const fila = crearFilaSolicitud(solicitud, tipoSolicitud);
    tbody.insertAdjacentHTML("beforeend", fila);
  });

  // Inicializar tooltips
  const tooltips = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltips.map((el) => new bootstrap.Tooltip(el));

  // Limpiar filtros cuando cambia la pestaña
  document.querySelectorAll(".filter-input").forEach((input) => {
    input.value = "";
  });
}

function crearFilaSolicitud(solicitud, tipoSolicitud) {
  const fecha = new Date(solicitud.fecha_solicitud).toLocaleDateString("es-AR");
  const verificador = solicitud.verificador_nombre || "Sin asignar";
  const nombreCompleto = `${solicitud.nombre} ${solicitud.apellido}`;

  // Construir dirección formateada
  const direccionPartes = [];
  if (solicitud.localidad) direccionPartes.push(solicitud.localidad);
  if (solicitud.provincia) direccionPartes.push(solicitud.provincia);
  if (solicitud.pais) direccionPartes.push(solicitud.pais);
  const direccionFormateada =
    direccionPartes.length > 0 ? direccionPartes.join(", ") : "Sin dirección";

  // URL de Google Maps
  const direccionCompleta = solicitud.direccion_completa || direccionFormateada;
  const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    direccionCompleta
  )}`;

  // Definir botones según tipo de solicitud
  let botonesAccion = "";
  let estadoClase = "";

  switch (tipoSolicitud) {
    case "pendiente":
      estadoClase = "";
      const sinVerificador =
        !solicitud.verificador_nombre ||
        solicitud.verificador_nombre === "Sin asignar";
      const botonTomar = sinVerificador
        ? `
        <button class="btn btn-info btn-sm me-1 mb-1" onclick="mostrarConfirmacion('tomar', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Asignarme como verificador">
            <i class="bi bi-person-check"></i> Tomar caso
        </button>
      `
        : "";
      botonesAccion = `
        ${botonTomar}
        <button class="btn btn-success btn-sm me-1 mb-1" onclick="mostrarConfirmacion('aceptar', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Aceptar solicitud y generar usuario">
            <i class="bi bi-check-circle"></i> Aceptar
        </button>
        <button class="btn btn-danger btn-sm mb-1" onclick="mostrarConfirmacion('rechazar', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Rechazar solicitud">
            <i class="bi bi-x-circle"></i> Rechazar
        </button>
      `;
      break;
    case "en-verificacion":
      estadoClase = "";
      botonesAccion = `
        <button class="btn btn-success btn-sm me-1 mb-1" onclick="mostrarConfirmacion('aceptar', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Aceptar solicitud y generar usuario">
            <i class="bi bi-check-circle"></i> Aceptar
        </button>
        <button class="btn btn-danger btn-sm mb-1" onclick="mostrarConfirmacion('rechazar', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Rechazar solicitud">
            <i class="bi bi-x-circle"></i> Rechazar
        </button>
      `;
      break;
    case "aceptada":
      estadoClase = "";
      botonesAccion = `
        <span class="badge bg-success">
            <i class="bi bi-check-circle-fill"></i> Aceptada
        </span>
      `;
      break;
    case "rechazada":
      estadoClase = "";
      botonesAccion = `
        <button class="btn btn-warning btn-sm" onclick="mostrarConfirmacion('reabrir', ${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Reabrir caso">
            <i class="bi bi-arrow-counterclockwise"></i> Reabrir caso
        </button>
      `;
      break;
  }

  return `
    <tr class="${estadoClase}" 
        data-id="${solicitud.id_solicitud}"
        data-usuario="${nombreCompleto} ${solicitud.email}"
        data-cancha="${solicitud.nombre_cancha}"
        data-direccion="${direccionFormateada}"
        data-verificador="${verificador}"
        data-fecha="${fecha}">
      <td class="text-center"><strong>#${solicitud.id_solicitud}</strong></td>
      <td>
        <div class="fw-bold">${solicitud.nombre} ${solicitud.apellido}</div>
        <small class="text-muted">${solicitud.email}</small>
      </td>
      <td><strong>${solicitud.nombre_cancha}</strong></td>
      <td>
        <small class="d-block mb-1">${direccionFormateada}</small>
        <a href="${mapsUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-geo-alt"></i> Ver en mapa
        </a>
      </td>
      <td>${verificador}</td>
      <td>${fecha}</td>
      <td>${botonesAccion}</td>
    </tr>
  `;
}

/**
 * Mostrar modal de confirmación para acciones
 */
function mostrarConfirmacion(accion, idSolicitud) {
  const modal = new bootstrap.Modal(
    document.getElementById("modalConfirmacion")
  );
  const textoModal = document.getElementById("modalConfirmacionTexto");
  const btnConfirmar = document.getElementById("btnConfirmarAccion");

  let mensaje = "";
  let colorBoton = "btn-primary";

  switch (accion) {
    case "aceptar":
      mensaje = `
        <p><strong>¿Estás seguro de aceptar esta solicitud?</strong></p>
        <p class="mb-0">Se creará:</p>
        <ul class="mb-0">
          <li>Un usuario con email de la solicitud</li>
          <li>Contraseña: password</li>
          <li>Un registro de Admin Cancha</li>
          <li>Una cancha asociada</li>
        </ul>
      `;
      colorBoton = "btn-success";
      break;
    case "rechazar":
      mensaje =
        "<p><strong>¿Estás seguro de rechazar esta solicitud?</strong></p><p class='text-muted mb-0'>Esta acción puede revertirse posteriormente.</p>";
      colorBoton = "btn-danger";
      break;
    case "reabrir":
      mensaje =
        "<p><strong>¿Deseas reabrir esta solicitud rechazada?</strong></p><p class='text-muted mb-0'>La solicitud volverá al estado pendiente.</p>";
      colorBoton = "btn-warning";
      break;
    case "tomar":
      mensaje =
        "<p><strong>¿Deseas tomar este caso?</strong></p><p class='text-muted mb-0'>Serás asignado como verificador responsable de esta solicitud.</p>";
      colorBoton = "btn-info";
      break;
  }

  textoModal.innerHTML = mensaje;
  btnConfirmar.className = `btn ${colorBoton}`;

  // Remover listeners anteriores y agregar nuevo
  const nuevoBtn = btnConfirmar.cloneNode(true);
  btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

  nuevoBtn.addEventListener("click", () => {
    modal.hide();
    switch (accion) {
      case "aceptar":
        aceptarSolicitud(idSolicitud);
        break;
      case "rechazar":
        rechazarSolicitud(idSolicitud);
        break;
      case "reabrir":
        reabrirSolicitud(idSolicitud);
        break;
      case "tomar":
        tomarCaso(idSolicitud);
        break;
    }
  });

  modal.show();
}

async function aceptarSolicitud(idSolicitud) {
  try {
    const formData = new FormData();
    formData.append("accion", "aceptar");
    formData.append("id_solicitud", idSolicitud);

    const response = await fetch(UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Error al aceptar solicitud");

    const result = await response.json();

    if (result.success) {
      showToast(result.message, "success");
      recargarTabActual();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al aceptar la solicitud", "error");
  }
}

async function rechazarSolicitud(idSolicitud) {
  try {
    const formData = new FormData();
    formData.append("accion", "rechazar");
    formData.append("id_solicitud", idSolicitud);

    const response = await fetch(UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Error al rechazar solicitud");

    const result = await response.json();

    if (result.success) {
      showToast("Solicitud rechazada correctamente", "success");
      recargarTabActual();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al rechazar la solicitud", "error");
  }
}

async function reabrirSolicitud(idSolicitud) {
  try {
    const formData = new FormData();
    formData.append("accion", "reabrir");
    formData.append("id_solicitud", idSolicitud);

    const response = await fetch(UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Error al reabrir solicitud");

    const result = await response.json();

    if (result.success) {
      showToast("Solicitud reabierta correctamente", "success");
      recargarTabActual();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al reabrir la solicitud", "error");
  }
}

async function tomarCaso(idSolicitud) {
  try {
    const formData = new FormData();
    formData.append("accion", "tomar");
    formData.append("id_solicitud", idSolicitud);

    const response = await fetch(UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) throw new Error("Error al tomar caso");

    const result = await response.json();

    if (result.success) {
      showToast("Caso asignado correctamente", "success");
      recargarTabActual();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al tomar el caso", "error");
  }
}

// El sistema de toasts se importa desde toast.js que está cargado globalmente
// window.showToast está disponible
