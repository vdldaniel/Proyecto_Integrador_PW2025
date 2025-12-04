/**
 * Canchas Admin Sistema - JavaScript
 * Maneja la funcionalidad de la página de administración de canchas del sistema
 */

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar tooltips de Bootstrap
  initializeTooltips();

  // Inicializar filtros de búsqueda
  initializeSearchFilter();

  // Inicializar event listeners para botones de acción
  initializeActionButtons();

  // Inicializar tabs y filtros
  initializeTabFilters();

  // Cargar canchas al iniciar - solo pendientes (id_estado = 1)
  cargarCanchas(1);
});

/**
 * Cargar canchas desde el servidor
 */
async function cargarCanchas(id_estado = null) {
  try {
    const url = id_estado
      ? `${GET_CANCHAS_PENDIENTES_ADMIN_SISTEMA}?id_estado=${id_estado}`
      : GET_CANCHAS_PENDIENTES_ADMIN_SISTEMA;

    const response = await fetch(url);

    // Obtener el texto crudo primero para debug
    const textResponse = await response.text(); // Intentar parsear como JSON
    let data;
    try {
      data = JSON.parse(textResponse);
    } catch (parseError) {
      console.error("Error al parsear JSON. Respuesta recibida:", textResponse);
      showToast(
        "Error al cargar las canchas - respuesta inválida del servidor",
        "danger"
      );
      return;
    }

    if (data.success) {
      renderizarCanchas(data.data);
    } else {
      showToast(data.error || "Error al cargar las canchas", "danger");
      console.error("Error del servidor:", data);
    }
  } catch (error) {
    console.error("Error al cargar canchas:", error);
    showToast("Error al cargar las canchas", "danger");
  }
}

/**
 * Renderizar canchas en la tabla
 */
function renderizarCanchas(canchas) {
  const tbody = document.getElementById("tbody-solicitudes");
  tbody.innerHTML = "";

  if (canchas.length === 0) {
    tbody.innerHTML = `
      <tr>
        <td colspan="10" class="text-center text-muted py-4">
          No hay canchas para mostrar
        </td>
      </tr>
    `;
    return;
  }

  canchas.forEach((cancha) => {
    const row = crearFilaCancha(cancha);
    tbody.appendChild(row);
  });

  // Re-inicializar tooltips después de renderizar
  initializeTooltips();
}

/**
 * Crear fila de tabla para una cancha
 */
function crearFilaCancha(cancha) {
  const tr = document.createElement("tr");
  tr.dataset.canchaId = cancha.id_cancha;
  tr.dataset.estado = cancha.id_estado;

  // Estado badge
  let estadoBadge = "";
  switch (parseInt(cancha.id_estado)) {
    case 1:
      estadoBadge = '<span class="badge bg-warning text-dark">Pendiente</span>';
      break;
    case 2:
      estadoBadge = '<span class="badge bg-info">En Revisión</span>';
      break;
    case 3:
      estadoBadge = '<span class="badge bg-success">Habilitada</span>';
      break;
    case 4:
      estadoBadge = '<span class="badge bg-danger">Rechazada</span>';
      break;
    default:
      estadoBadge = `<span class="badge bg-secondary">${cancha.estado_cancha}</span>`;
  }

  // Estado de verificador (admin sistema que tomó el caso)
  const estadoVerificador = cancha.id_verificador
    ? `<span>${
        cancha.nombre_verificador + " " + cancha.apellido_verificador || "Admin"
      }</span>`
    : '<span class="badge bg-warning text-dark">Sin asignar</span>';

  // Estado del admin cancha (nuevo o existente basado en cantidad de canchas)
  const estadoAdminCancha = '<span class="badge bg-dark">Admin Cancha</span>';

  // Formatear fecha
  const fecha = new Date(cancha.fecha_creacion).toLocaleDateString("es-AR");

  // Botones de acción según el estado
  let botonesAccion = "";
  if (cancha.id_estado == 1) {
    // Pendiente
    botonesAccion = `
      <button class="btn btn-sm btn-primary btn-tomar" data-cancha-id="${cancha.id_cancha}"
        data-bs-toggle="tooltip" title="Tomar caso para revisión">
        <i class="bi bi-hand-thumbs-up"></i> Tomar caso
      </button>
      <button class="btn btn-sm btn-success btn-habilitar" data-cancha-id="${cancha.id_cancha}"
        data-bs-toggle="tooltip" title="Habilitar cancha">
        <i class="bi bi-check-circle"></i>
      </button>
    `;
  } else if (cancha.id_estado == 2) {
    // En revisión
    botonesAccion = `
      <button class="btn btn-sm btn-success btn-habilitar" data-cancha-id="${cancha.id_cancha}"
        data-bs-toggle="tooltip" title="Habilitar cancha">
        <i class="bi bi-check-circle"></i> Habilitar
      </button>
      <button class="btn btn-sm btn-danger btn-rechazar" data-cancha-id="${cancha.id_cancha}"
        data-bs-toggle="tooltip" title="Rechazar cancha">
        <i class="bi bi-x-circle"></i>
      </button>
    `;
  } else if (cancha.id_estado == 3) {
    // Habilitada
    botonesAccion = `
      <button class="btn btn-sm btn-secondary" disabled>
        <i class="bi bi-lock"></i> Sin acciones
      </button>
    `;
  } else if (cancha.id_estado == 4) {
    // Deshabilitada - agregar botón Reabrir caso
    botonesAccion = `
      <button class="btn btn-sm btn-warning btn-reabrir" data-cancha-id="${cancha.id_cancha}"
        data-bs-toggle="tooltip" title="Reabrir caso">
        <i class="bi bi-arrow-counterclockwise"></i> Reabrir caso
      </button>
    `;
  }

  // Crear columnas en el orden correcto: ID, Admin Cancha, Cancha, Dirección, Verificador, Fecha, Acciones
  tr.innerHTML = `
    <td class="text-center"><strong>#${cancha.id_cancha}</strong></td>
    <td>
      <div class="fw-bold">${cancha.nombre_admin} ${cancha.apellido_admin}</div>
      <small class="text-muted">${cancha.email_admin}</small>
    </td>
    <td>
      <div class="fw-bold">${cancha.nombre_cancha}</div>
      <small class="text-muted">${
        cancha.telefono_cancha || "Sin teléfono"
      }</small>
    </td>
    <td>
      <small class="d-block mb-1">${cancha.direccion_completa}</small>
      <button class="btn btn-sm btn-outline-primary btn-mapa" 
        data-direccion="${cancha.direccion_completa}"
        data-bs-toggle="tooltip" title="Ver en mapa">
        <i class="bi bi-geo-alt"></i> Ver en mapa
      </button>
    </td>
    <td>${estadoVerificador}</td>
    <td><small>${fecha}</small></td>
    <td>
      ${botonesAccion}
    </td>
  `;

  return tr;
}

/**
 * Inicializar tooltips de Bootstrap
 */
function initializeTooltips() {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
}

/**
 * Inicializar filtro de búsqueda
 */
function initializeSearchFilter() {
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      filterSolicitudes(searchTerm);
    });
  }
}

/**
 * Filtrar solicitudes por término de búsqueda
 */
function filterSolicitudes(searchTerm) {
  const solicitudCards = document.querySelectorAll(".row-card-tabla-admin");

  solicitudCards.forEach((card) => {
    const text = card.textContent.toLowerCase();
    if (text.includes(searchTerm)) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}

/**
 * Inicializar event listeners para botones de acción
 */
function initializeActionButtons() {
  // Botones "Ver solicitud"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-ver")) {
      e.preventDefault();
      const solicitudId = e.target.closest(".btn-ver").dataset.solicitudId;
      abrirModalSolicitud(solicitudId);
    }
  });

  // Botones "Mapa"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-mapa")) {
      e.preventDefault();
      const direccion = e.target.closest(".btn-mapa").dataset.direccion;
      abrirMapa(direccion);
    }
  });

  // Botones "Ver canchas del admin"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-canchas")) {
      e.preventDefault();
      const adminId = e.target.closest(".btn-canchas").dataset.adminId;
      irASolapaAdminCanchas(adminId);
    }
  });

  // Botones "Tomar caso"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-tomar")) {
      e.preventDefault();
      const canchaId = e.target.closest(".btn-tomar").dataset.canchaId;
      tomarCaso(canchaId);
    }
  });

  // Botones "Habilitar"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-habilitar")) {
      e.preventDefault();
      const canchaId = e.target.closest(".btn-habilitar").dataset.canchaId;
      habilitarCancha(canchaId);
    }
  });

  // Botones "Rechazar"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-rechazar")) {
      e.preventDefault();
      const canchaId = e.target.closest(".btn-rechazar").dataset.canchaId;
      rechazarCancha(canchaId);
    }
  });

  // Botones "Reabrir caso"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-reabrir")) {
      e.preventDefault();
      const canchaId = e.target.closest(".btn-reabrir").dataset.canchaId;
      reabrirCancha(canchaId);
    }
  });

  // Botón agregar observación
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-agregar-observacion")) {
      e.preventDefault();
      agregarObservacion();
    }
  });
}

/**
 * Recargar el tab actualmente activo
 */
function recargarTabActualCanchas() {
  const activeTab = document.querySelector(".nav-link.active");
  const tabId = activeTab.getAttribute("data-bs-target").replace("#", "");

  // Mapear tab ID a estado
  const estadoMap = {
    pendientes: 1,
    "en-revision": 2,
    habilitadas: 3,
    deshabilitadas: 4,
  };

  const estado = estadoMap[tabId] || 1;
  cargarCanchas(estado);
}

/**
 * Inicializar filtros por tabs
 */
function initializeTabFilters() {
  const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');

  tabButtons.forEach((button) => {
    button.addEventListener("shown.bs.tab", function (e) {
      const targetTab = e.target.getAttribute("data-bs-target");
      const tabId = targetTab.replace("#", "");

      // Mapear tab a id_estado
      let id_estado = null;
      switch (tabId) {
        case "pendientes":
          id_estado = 1; // Pendiente de verificación
          break;
        case "en-revision":
          id_estado = 2; // En revisión
          break;
        case "habilitadas":
          id_estado = 3; // Habilitada
          break;
        case "deshabilitadas":
          id_estado = 4; // Deshabilitada
          break;
      }

      // Recargar canchas con el filtro
      cargarCanchas(id_estado);
    });
  });
}

/**
 * Filtrar solicitudes por estado
 */
function filtrarPorEstado(estado) {
  const allCards = document.querySelectorAll(".row-card-tabla-admin");

  allCards.forEach((card) => {
    const cardEstado = card.dataset.estado;
    if (estado === "solicitudes" || cardEstado === estado) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}

/**
 * Abrir modal con detalles de la solicitud
 */
function abrirModalSolicitud(solicitudId) {
  // Aquí se cargarán los datos de la solicitud
  // Por ahora simulamos con datos de ejemplo

  const modal = document.getElementById("modalSolicitudDetalle");

  // Actualizar contenido del modal con los datos de la solicitud
  actualizarContenidoModal(solicitudId);

  // Mostrar modal
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

/**
 * Abrir mapa con la dirección
 */
function abrirMapa(direccion) {
  // Construir URL para Google Maps
  const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
    direccion
  )}`;

  // Abrir en nueva ventana
  window.open(mapsUrl, "_blank");
}

/**
 * Tomar caso - asignar verificador
 */
async function tomarCaso(canchaId) {
  mostrarModalConfirmacion(
    "Tomar Caso",
    "¿Estás seguro que querés tomar este caso? Te convertirás en el verificador responsable.",
    async () => {
      try {
        const formData = new FormData();
        formData.append("action", "tomar_caso");
        formData.append("id_cancha", canchaId);

        const response = await fetch(UPDATE_CANCHA_ADMIN_SISTEMA, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.success) {
          showToast(data.message || "Caso tomado exitosamente", "success");
          recargarTabActualCanchas();
        } else {
          showToast(data.error || "Error al tomar el caso", "danger");
        }
      } catch (error) {
        console.error("Error al tomar caso:", error);
        showToast("Error al tomar el caso", "danger");
      }
    }
  );
}

/**
 * Habilitar cancha
 */
async function habilitarCancha(canchaId) {
  mostrarModalConfirmacion(
    "Habilitar Cancha",
    "¿Estás seguro que querés habilitar esta cancha?",
    async () => {
      try {
        const formData = new FormData();
        formData.append("action", "habilitar");
        formData.append("id_cancha", canchaId);

        const response = await fetch(UPDATE_CANCHA_ADMIN_SISTEMA, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.success) {
          showToast(
            data.message || "Cancha habilitada exitosamente",
            "success"
          );
          recargarTabActualCanchas();
        } else {
          showToast(data.error || "Error al habilitar la cancha", "danger");
        }
      } catch (error) {
        console.error("Error al habilitar cancha:", error);
        showToast("Error al habilitar la cancha", "danger");
      }
    }
  );
}

/**
 * Rechazar cancha
 */
async function rechazarCancha(canchaId) {
  mostrarModalConfirmacion(
    "Rechazar Cancha",
    "¿Estás seguro que querés rechazar esta cancha?",
    async () => {
      try {
        const formData = new FormData();
        formData.append("action", "rechazar");
        formData.append("id_cancha", canchaId);

        const response = await fetch(UPDATE_CANCHA_ADMIN_SISTEMA, {
          method: "POST",
          body: formData,
        });

        const data = await response.json();

        if (data.success) {
          showToast(data.message || "Cancha rechazada", "warning");
          recargarTabActualCanchas();
        } else {
          showToast(data.error || "Error al rechazar la cancha", "danger");
        }
      } catch (error) {
        console.error("Error al rechazar cancha:", error);
        showToast("Error al rechazar la cancha", "danger");
      }
    }
  );
}

/**
 * Reabrir una cancha deshabilitada
 */
async function reabrirCancha(canchaId) {
  mostrarModalConfirmacion(
    "Reabrir caso",
    "¿Deseas reabrir esta cancha deshabilitada? La cancha volverá al estado pendiente.",
    async () => {
      try {
        const formData = new FormData();
        formData.append("action", "reabrir");
        formData.append("id_cancha", canchaId);

        const response = await fetch(UPDATE_CANCHA_ADMIN_SISTEMA, {
          method: "POST",
          body: formData,
        });

        if (!response.ok) {
          throw new Error("Error en la respuesta del servidor");
        }

        const data = await response.json();

        if (data.success) {
          showToast(data.message, "success");
          recargarTabActualCanchas();
        } else {
          showToast(data.error || "Error al reabrir la cancha", "danger");
        }
      } catch (error) {
        console.error("Error al reabrir cancha:", error);
        showToast("Error al reabrir la cancha", "danger");
      }
    }
  );
}

/**
 * Agregar nueva observación
 */
function agregarObservacion() {
  const textarea = document.getElementById("nueva-observacion");
  const observacionTexto = textarea.value.trim();

  if (observacionTexto === "") {
    showToast("Por favor, escribí una observación", "warning");
    return;
  }

  // Crear nueva observación
  const observacionesContainer = document.getElementById("observaciones-lista");
  const nuevaObservacion = crearElementoObservacion(observacionTexto);

  // Agregar al inicio de la lista
  observacionesContainer.insertBefore(
    nuevaObservacion,
    observacionesContainer.firstChild
  );

  // Limpiar textarea
  textarea.value = "";

  showToast("Observación agregada", "success");
}

/**
 * Crear elemento HTML para una observación
 */
function crearElementoObservacion(texto) {
  const div = document.createElement("div");
  div.className = "observacion-item";

  const fecha = new Date().toLocaleString("es-AR");

  div.innerHTML = `
        <div class="observacion-fecha">Admin Sistema - ${fecha}</div>
        <p class="observacion-texto">${texto}</p>
    `;

  return div;
}

/**
 * Actualizar estado visual de un caso
 */
function actualizarEstadoCaso(solicitudId, nuevoEstado) {
  const card = document.querySelector(`[data-solicitud-id="${solicitudId}"]`);
  if (card) {
    // Remover clases de estado anteriores
    card.classList.remove(
      "estado-pendiente",
      "estado-verificando",
      "estado-verificada",
      "estado-rechazada"
    );

    // Agregar nueva clase de estado
    card.classList.add(`estado-${nuevoEstado}`);

    // Actualizar data-estado
    card.dataset.estado = nuevoEstado;
  }
}

/**
 * Mostrar notificación
 */
function showToast(mensaje, tipo = "info") {
  // Crear elemento de notificación
  const notificacion = document.createElement("div");
  notificacion.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
  notificacion.style.cssText =
    "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";

  notificacion.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  // Agregar al body
  document.body.appendChild(notificacion);

  // Auto-remover después de 3 segundos
  setTimeout(() => {
    if (notificacion.parentNode) {
      notificacion.remove();
    }
  }, 3000);
}

/**
 * Ir a la solapa de Admin. Canchas con búsqueda del admin
 */
function irASolapaAdminCanchas(adminId) {
  // Activar la solapa "Admin. Canchas"
  const adminTab = document.getElementById("administradores-tab");
  const adminTabPane = document.getElementById("administradores");

  if (adminTab && adminTabPane) {
    // Activar tab
    const tab = new bootstrap.Tab(adminTab);
    tab.show();

    // Realizar búsqueda por el ID del admin
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
      searchInput.value = adminId;
      // Filtrar las solicitudes
      filterSolicitudes(adminId.toLowerCase());
    }

    // Mostrar notificación
    showToast("Mostrando canchas del administrador ${adminId}", "info");

    // Scroll hacia la solapa
    adminTabPane.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}

/**
 * Mostrar modal de confirmación genérico
 */
function mostrarModalConfirmacion(titulo, mensaje, onConfirmar) {
  const modal = document.getElementById("modalConfirmacion");
  const tituloElement = modal.querySelector(".modal-title");
  const mensajeElement = modal.querySelector(".modal-body");
  const btnConfirmar = document.getElementById("btnConfirmarAccion");

  // Establecer título y mensaje
  tituloElement.textContent = titulo;
  mensajeElement.textContent = mensaje;

  // Remover listeners previos
  const nuevoBtn = btnConfirmar.cloneNode(true);
  btnConfirmar.parentNode.replaceChild(nuevoBtn, btnConfirmar);

  // Agregar nuevo listener
  nuevoBtn.addEventListener("click", () => {
    const bsModal = bootstrap.Modal.getInstance(modal);
    bsModal.hide();
    onConfirmar();
  });

  // Mostrar modal
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}
