/**
 * SOLICITUDES ADMIN CANCHA - Sistema de gestión
 * ============================================
 * Cargar, filtrar y gestionar solicitudes de administradores de cancha
 */

document.addEventListener("DOMContentLoaded", function () {
  // Debug: Verificar las URLs
  console.log("GET URL:", GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA);
  console.log("UPDATE URL:", UPDATE_SOLICITUD_ADMIN_CANCHA_ADMIN_SISTEMA);

  cargarSolicitudesPendientes();

  // Event listener para el tab de rechazadas
  document
    .getElementById("rechazadas-tab")
    .addEventListener("click", function () {
      cargarSolicitudesRechazadas();
    });

  // Event listener para el tab de solicitudes
  document
    .getElementById("solicitudes-tab")
    .addEventListener("click", function () {
      cargarSolicitudesPendientes();
    });
});

async function cargarSolicitudesPendientes() {
  try {
    const response = await fetch(
      GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA + "?estado=1"
    );
    if (!response.ok) throw new Error("Error al cargar solicitudes");

    const solicitudes = await response.json();
    renderizarSolicitudes(solicitudes, "solicitudes", true);
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al cargar solicitudes pendientes", "error");
  }
}

async function cargarSolicitudesRechazadas() {
  try {
    const response = await fetch(
      GET_SOLICITUDES_ADMIN_CANCHA_ADMIN_SISTEMA + "?estado=4"
    );
    if (!response.ok) throw new Error("Error al cargar solicitudes");

    const solicitudes = await response.json();
    renderizarSolicitudes(solicitudes, "rechazadas", false);
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al cargar solicitudes rechazadas", "error");
  }
}

function renderizarSolicitudes(solicitudes, contenedorId, esPendiente) {
  const contenedor = document.getElementById(contenedorId);
  contenedor.innerHTML = "";

  if (solicitudes.length === 0) {
    contenedor.innerHTML = `
            <div class="alert alert-info mt-3">
                <i class="bi bi-info-circle me-2"></i>
                No hay solicitudes ${
                  esPendiente ? "pendientes" : "rechazadas"
                } en este momento.
            </div>
        `;
    return;
  }

  solicitudes.forEach((solicitud) => {
    const card = crearCardSolicitud(solicitud, esPendiente);
    contenedor.insertAdjacentHTML("beforeend", card);
  });

  // Inicializar tooltips
  const tooltips = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltips.map((el) => new bootstrap.Tooltip(el));
}

function crearCardSolicitud(solicitud, esPendiente) {
  const fecha = new Date(solicitud.fecha_solicitud).toLocaleDateString("es-AR");
  const verificador = solicitud.verificador_nombre || "Sin asignar";

  const botonesAccion = esPendiente
    ? `
        <button class="btn btn-success btn-sm me-1" onclick="aceptarSolicitud(${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Aceptar solicitud y generar usuario">
            <i class="bi bi-check-circle"></i> Aceptar
        </button>
        <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Rechazar solicitud">
            <i class="bi bi-x-circle"></i> Rechazar
        </button>
    `
    : `
        <button class="btn btn-warning btn-sm" onclick="reabrirSolicitud(${solicitud.id_solicitud})" 
                data-bs-toggle="tooltip" title="Reabrir caso">
            <i class="bi bi-arrow-counterclockwise"></i> Reabrir caso
        </button>
    `;

  return `
        <div class="card row-card-tabla-admin ${
          esPendiente ? "estado-pendiente" : "estado-rechazada"
        }" 
             data-solicitud-id="${solicitud.id_solicitud}">
            <div class="card-body">
                <div class="row solicitud-row align-items-center">
                    <div class="col-md-1">
                        <strong>#${solicitud.id_solicitud}</strong>
                    </div>
                    <div class="col-md-2">
                        <div class="fw-bold">${solicitud.nombre} ${
    solicitud.apellido
  }</div>
                        <small class="text-muted">${solicitud.email}</small>
                    </div>
                    <div class="col-md-2">
                        <div class="fw-bold">${solicitud.nombre_cancha}</div>
                    </div>
                    <div class="col-md-2">
                        <small class="text-muted">${
                          solicitud.direccion_completa || "Sin dirección"
                        }</small>
                    </div>
                    <div class="col-md-2">
                        <span class="text-muted">${verificador}</span>
                    </div>
                    <div class="col-md-1">
                        <small>${fecha}</small>
                    </div>
                    <div class="col-md-2">
                        <div class="acciones-container">
                            ${botonesAccion}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

async function aceptarSolicitud(idSolicitud) {
  if (
    !confirm(
      "¿Estás seguro de aceptar esta solicitud?\n\nSe creará:\n- Un usuario con email de la solicitud\n- Contraseña: password\n- Un registro de Admin Cancha\n- Una cancha asociada"
    )
  ) {
    return;
  }

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
      cargarSolicitudesPendientes();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al aceptar la solicitud", "error");
  }
}

async function rechazarSolicitud(idSolicitud) {
  if (!confirm("¿Estás seguro de rechazar esta solicitud?")) {
    return;
  }

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
      cargarSolicitudesPendientes();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al rechazar la solicitud", "error");
  }
}

async function reabrirSolicitud(idSolicitud) {
  if (!confirm("¿Deseas reabrir esta solicitud rechazada?")) {
    return;
  }

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
      cargarSolicitudesRechazadas();
    } else {
      showToast(result.error || "Error al procesar solicitud", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al reabrir la solicitud", "error");
  }
}

// El sistema de toasts se importa desde toast.js que está cargado globalmente
// window.showToast está disponible
