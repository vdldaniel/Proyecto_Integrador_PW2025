
// solicitudes: equipos que piden participar
let solicitudes = [
  { id: 1, nombre: "Los Halcones" },
  { id: 2, nombre: "La Academia" },
  { id: 3, nombre: "FutStars" },
];



document.addEventListener("DOMContentLoaded", () => {
  renderDetalle();
  renderSolicitudes();
  renderPartidos();
  updateBadges();
  setupButtons();
});

/* ------ RENDER ------ */
function renderDetalle() {
  document.getElementById("det-nombre").textContent = tournament.nombre;
  document.getElementById("det-ubicacion").textContent = tournament.ubicacion;
  document.getElementById("det-tamano").textContent = tournament.tamano;
  document.getElementById("det-cierre").textContent = formatDate(tournament.cierre);
  document.getElementById("det-inicio").textContent = formatDate(tournament.inicio);
  document.getElementById("det-fin").textContent = formatDate(tournament.fin);
  document.getElementById("det-equipos").textContent = tournament.maxEquipos;
  document.getElementById("det-descripcion").textContent = tournament.descripcion;

  const estadoEl = document.getElementById("det-estado");
  estadoEl.innerHTML = `<span class="badge ${badgeClass(tournament.estado)}">${capitalize(tournament.estado)}</span>`;
  document.getElementById("tournament-state-label").textContent = `Estado actual: ${capitalize(tournament.estado)}`;

  // Si inscripciones cerradas, desactivar pestaña solicitudes
  const solicitudesTab = document.getElementById("solicitudes-tab");
  if (tournament.estado !== "inscripciones abiertas") {
    solicitudesTab.classList.add("disabled");
    solicitudesTab.setAttribute("aria-disabled", "true");
  } else {
    solicitudesTab.classList.remove("disabled");
    solicitudesTab.removeAttribute("aria-disabled");
  }
}

function renderSolicitudes() {
  const container = document.getElementById("lista-solicitudes");
  container.innerHTML = "";

  if (solicitudes.length === 0) {
    container.innerHTML = `<div class="text-muted small">No hay solicitudes pendientes.</div>`;
    updateBadges();
    return;
  }

}

function renderPartidos() {
  const tbody = document.getElementById("body-partidos");
  tbody.innerHTML = "";

  if (partidos.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" class="text-muted small">No hay partidos generados todavía.</td></tr>`;
    return;
  }

}

/* ------ ACCIONES ------ */
function aceptarSolicitud(id) {
  const index = solicitudes.findIndex(s => s.id === id);
  if (index === -1) return;
  const aceptado = solicitudes.splice(index,1)[0];
  equiposAceptados.push(aceptado);
  renderSolicitudes();
  showSuccess("Equipo aceptado");
}

function confirmarRechazo(id, nombre) {
  const confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"));
  document.getElementById("confirm-text").textContent = `¿Rechazar la solicitud de "${nombre}"?`;
  // attach one-shot handler
  const yesBtn = document.getElementById("confirm-yes");
  const handler = () => {
    rechazarSolicitud(id);
    confirmModal.hide();
    yesBtn.removeEventListener("click", handler);
  };
  yesBtn.addEventListener("click", handler);
  confirmModal.show();
}

function rechazarSolicitud(id) {
  const index = solicitudes.findIndex(s => s.id === id);
  if (index === -1) return;
  const rechazado = solicitudes.splice(index,1)[0];
  renderSolicitudes();
  showSuccess(`Solicitud de "${rechazado.nombre}" rechazada`);
}

function aceptarTodos() {
  while (solicitudes.length && equiposAceptados.length < tournament.maxEquipos) {
    const s = solicitudes.shift();
    equiposAceptados.push(s);
  }
  renderSolicitudes();
  showSuccess("Todas las solicitudes aceptadas (hasta cupo)");
}

function rechazarTodos() {
  solicitudes = [];
  renderSolicitudes();
  showSuccess("Todas las solicitudes rechazadas");
}

/* Cerrar inscripciones -> realiza sorteo automático y genera partidos */
function closeInscripciones() {
  if (tournament.estado !== "inscripciones abiertas") {
    alert("Las inscripciones ya están cerradas o el torneo no está en estado correcto.");
    return;
  }

  // Cambiar estado
  tournament.estado = "inscripciones cerradas";
  renderDetalle();

  // Si hay solicitudes pendientes, las rechazamos automáticamente
  if (solicitudes.length > 0) {
    solicitudes = []; // simplificación
    showSuccess("Solicitudes pendientes rechazadas al cerrar inscripciones");
  }

  // Realizar sorteo: si equiposAceptados < 2 no hace nada
  if (equiposAceptados.length < 2) {
    showSuccess("No hay suficientes equipos aceptados para generar partidos.");
    return;
  }

  generarPartidosPorSorteo();
  tournament.estado = "partidos generados";
  renderDetalle();
  renderPartidos();
  showSuccess("Inscripciones cerradas y sorteo realizado");
}

/* Regenerar sorteo (re-hace emparejamientos con equipos aceptados) */
function regenerarSorteo() {
  if (equiposAceptados.length < 2) {
    alert("No hay suficientes equipos aceptados para sortear.");
    return;
  }
  generarPartidosPorSorteo();
  renderPartidos();
  showSuccess("Sorteo regenerado");
}

/* ------ UTILIDADES y UI ------ */
function setupButtons() {
  document.getElementById("btn-volver").addEventListener("click", () => {
    // si estás embebiendo, podrías cerrar el contenedor; aquí hacemos un simple history.back()
    history.back();
  });

  document.getElementById("btn-close-insc").addEventListener("click", () => {
    if (confirm("¿Deseás cerrar las inscripciones ahora? Esto desencadenará el sorteo automático.")) {
      closeInscripciones();
    }
  });

  document.getElementById("btn-aceptar-todos").addEventListener("click", () => aceptarTodos());
  document.getElementById("btn-rechazar-todos").addEventListener("click", () => rechazarTodos());
  document.getElementById("btn-abrir-agenda").addEventListener("click", () => {
    // placeholder - redirigir a agenda
    alert("Redirigir a AGENDA ");
  });
  document.getElementById("btn-generar").addEventListener("click", () => {
    if (confirm("Regenerar el sorteo sobrescribirá la programación actual. Continuar?")) {
      regenerarSorteo();
    }
  });
}

/* Mostrar modal de éxito por 3 segundos */
function showSuccess(msg) {
  const modalEl = document.getElementById("successModal");
  const modal = new bootstrap.Modal(modalEl);
  modal.show();
  setTimeout(() => {
    modal.hide();
  }, 3000);
}

function badgeClass(estado) {
  switch (estado) {
    case "inscripciones abiertas": return "bg-success";
    case "inscripciones cerradas": return "bg-warning text-dark";
    case "partidos generados": return "bg-primary";
    case "en curso": return "bg-danger";
    default: return "bg-secondary";
  }
}



// Cancelar un torneo Activo o Cancelado
function cancelarTorneo(){
  if (confirm("¿Seguro que quieres cancelar el torneo?")) {
    window.location.href = "mis-torneos.html"; // vuelve a la principal
  }
}