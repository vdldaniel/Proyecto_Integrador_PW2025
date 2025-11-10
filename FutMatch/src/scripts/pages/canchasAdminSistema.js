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
});

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
  const solicitudCards = document.querySelectorAll(".solicitud-card");

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
      const solicitudId = e.target.closest(".btn-tomar").dataset.solicitudId;
      tomarCaso(solicitudId);
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
 * Inicializar filtros por tabs
 */
function initializeTabFilters() {
  const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');

  tabButtons.forEach((button) => {
    button.addEventListener("shown.bs.tab", function (e) {
      const targetTab = e.target.getAttribute("data-bs-target");
      const estado = targetTab.replace("#", "");
      filtrarPorEstado(estado);
    });
  });
}

/**
 * Filtrar solicitudes por estado
 */
function filtrarPorEstado(estado) {
  const allCards = document.querySelectorAll(".solicitud-card");

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
 * Actualizar contenido del modal con datos de la solicitud
 */
function actualizarContenidoModal(solicitudId) {
  // Aquí iría la lógica para cargar los datos desde el servidor
  // Por ahora usamos datos de ejemplo

  document.getElementById("modal-solicitud-id").textContent = solicitudId;
  document.getElementById("modal-nombre-admin").textContent = "Juan Pérez";
  document.getElementById("modal-email-admin").textContent =
    "juan.perez@email.com";
  document.getElementById("modal-telefono-admin").textContent =
    "+54 11 1234-5678";
  document.getElementById("modal-nombre-cancha").textContent =
    "Cancha Los Pinos";
  document.getElementById("modal-direccion-cancha").textContent =
    "Av. Libertador 1234, CABA";
  document.getElementById("modal-fecha-solicitud").textContent =
    "10/11/2025 14:30";
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
function tomarCaso(solicitudId) {
  if (
    confirm(
      "¿Estás seguro que querés tomar este caso? Te convertirás en el verificador responsable."
    )
  ) {
    // Aquí iría la lógica para asignar el caso al usuario actual
    console.log(`Tomando caso: ${solicitudId}`);

    // Simular éxito
    mostrarNotificacion("Caso tomado exitosamente", "success");

    // Actualizar la interfaz
    actualizarEstadoCaso(solicitudId, "verificando");
  }
}

/**
 * Agregar nueva observación
 */
function agregarObservacion() {
  const textarea = document.getElementById("nueva-observacion");
  const observacionTexto = textarea.value.trim();

  if (observacionTexto === "") {
    mostrarNotificacion("Por favor, escribí una observación", "warning");
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

  mostrarNotificacion("Observación agregada", "success");
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
function mostrarNotificacion(mensaje, tipo = "info") {
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
    mostrarNotificacion(
      `Mostrando canchas del administrador ${adminId}`,
      "info"
    );

    // Scroll hacia la solapa
    adminTabPane.scrollIntoView({ behavior: "smooth", block: "start" });
  }
}
