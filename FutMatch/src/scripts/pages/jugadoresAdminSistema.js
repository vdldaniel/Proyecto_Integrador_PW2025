/**
 * Jugadores Admin Sistema - JavaScript
 * Maneja la funcionalidad de la página de administración de jugadores del sistema
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
      filterJugadores(searchTerm);
    });
  }
}

/**
 * Filtrar jugadores por término de búsqueda
 */
function filterJugadores(searchTerm) {
  const jugadorCards = document.querySelectorAll(".jugador-card");

  jugadorCards.forEach((card) => {
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
  // Botones "Ver perfil"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-perfil")) {
      // Este botón es un enlace, no necesita preventDefault
      console.log("Redirigiendo a perfil del jugador");
    }
  });

  // Botones "Reporte"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-reporte")) {
      // Este botón es un enlace, no necesita preventDefault
      console.log("Redirigiendo a reportes");
    }
  });

  // Botones "Suspender"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-suspender")) {
      e.preventDefault();
      const jugadorId = e.target.closest(".btn-suspender").dataset.jugadorId;
      abrirModalSuspender(jugadorId);
    }
  });

  // Botones "Reestablecer"
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-reestablecer")) {
      e.preventDefault();
      const jugadorId = e.target.closest(".btn-reestablecer").dataset.jugadorId;
      abrirModalReestablecer(jugadorId);
    }
  });

  // Botón confirmar suspensión
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-confirmar-suspension")) {
      e.preventDefault();
      confirmarSuspension();
    }
  });

  // Botón confirmar restablecimiento
  document.addEventListener("click", function (e) {
    if (e.target.closest(".btn-confirmar-restablecimiento")) {
      e.preventDefault();
      confirmarRestablecimiento();
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
 * Filtrar jugadores por estado
 */
function filtrarPorEstado(estado) {
  const allCards = document.querySelectorAll(".jugador-card");

  allCards.forEach((card) => {
    const cardEstado = card.dataset.estado;
    if (estado === "jugadores" || cardEstado === estado) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}

/**
 * Abrir modal para suspender jugador
 */
function abrirModalSuspender(jugadorId) {
  const modal = document.getElementById("modalSuspenderJugador");

  // Actualizar ID del jugador en el modal
  document.getElementById("suspender-jugador-id").value = jugadorId;
  document.getElementById(
    "modal-suspender-titulo"
  ).textContent = `Suspender Jugador ${jugadorId}`;

  // Mostrar modal
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

/**
 * Abrir modal para reestablecer jugador
 */
function abrirModalReestablecer(jugadorId) {
  const modal = document.getElementById("modalRestablecerJugador");

  // Actualizar ID del jugador en el modal
  document.getElementById("reestablecer-jugador-id").value = jugadorId;
  document.getElementById(
    "modal-reestablecer-titulo"
  ).textContent = `Reestablecer Cuenta ${jugadorId}`;

  // Mostrar modal
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
}

/**
 * Confirmar suspensión de jugador
 */
function confirmarSuspension() {
  const jugadorId = document.getElementById("suspender-jugador-id").value;
  const fechaSuspension = document.getElementById("fecha-suspension").value;
  const mensajePersonalizado =
    document.getElementById("mensaje-suspension").value;

  if (!fechaSuspension) {
    mostrarNotificacion(
      "Por favor, seleccioná una fecha de suspensión",
      "warning"
    );
    return;
  }

  // Aquí iría la lógica para suspender al jugador
  console.log(`Suspendiendo jugador: ${jugadorId}`);
  console.log(`Hasta fecha: ${fechaSuspension}`);
  console.log(`Mensaje: ${mensajePersonalizado}`);

  // Simular éxito
  mostrarNotificacion(
    `Jugador ${jugadorId} suspendido exitosamente`,
    "success"
  );

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalSuspenderJugador")
  );
  modal.hide();

  // Actualizar estado visual del jugador
  actualizarEstadoJugador(jugadorId, "suspendido");
}

/**
 * Confirmar restablecimiento de jugador
 */
function confirmarRestablecimiento() {
  const jugadorId = document.getElementById("reestablecer-jugador-id").value;
  const mensajePersonalizado = document.getElementById(
    "mensaje-restablecimiento"
  ).value;

  // Aquí iría la lógica para reestablecer al jugador
  console.log(`Restableciendo jugador: ${jugadorId}`);
  console.log(`Mensaje: ${mensajePersonalizado}`);

  // Simular éxito
  mostrarNotificacion(
    `Cuenta del jugador ${jugadorId} restablecida exitosamente`,
    "success"
  );

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalRestablecerJugador")
  );
  modal.hide();

  // Actualizar estado visual del jugador
  actualizarEstadoJugador(jugadorId, "activo");
}

/**
 * Actualizar estado visual de un jugador
 */
function actualizarEstadoJugador(jugadorId, nuevoEstado) {
  const card = document.querySelector(`[data-jugador-id="${jugadorId}"]`);
  if (card) {
    // Remover clases de estado anteriores
    card.classList.remove("estado-activo", "estado-suspendido");

    // Agregar nueva clase de estado
    card.classList.add(`estado-${nuevoEstado}`);

    // Actualizar data-estado
    card.dataset.estado = nuevoEstado;

    // Actualizar botones según el estado
    const btnSuspender = card.querySelector(".btn-suspender");
    const btnReestablecer = card.querySelector(".btn-reestablecer");

    if (nuevoEstado === "suspendido") {
      if (btnSuspender) btnSuspender.style.display = "none";
      if (btnReestablecer) btnReestablecer.style.display = "inline-block";
    } else {
      if (btnSuspender) btnSuspender.style.display = "inline-block";
      if (btnReestablecer) btnReestablecer.style.display = "none";
    }
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
