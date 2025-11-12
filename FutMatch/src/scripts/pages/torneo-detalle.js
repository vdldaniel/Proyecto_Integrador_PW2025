/**
 * Funcionalidad de la página Torneo Detalle - Admin Cancha
 * Manejo completo de interacciones del torneo, bracket y equipos
 */

document.addEventListener("DOMContentLoaded", function () {
  console.log("Torneo Detalle JS cargado correctamente");

  // Inicializar todas las funcionalidades
  inicializarTooltips();
  inicializarModalPartidos();
});

/**
 * Inicializa los tooltips de Bootstrap
 */
function inicializarTooltips() {
  const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
  tooltips.forEach((tooltip) => {
    new bootstrap.Tooltip(tooltip);
  });
}

/**
 * Inicializa el sistema de modales para los detalles de partidos
 */
function inicializarModalPartidos() {
  const partidoCards = document.querySelectorAll(".partido-card");

  partidoCards.forEach((card) => {
    card.addEventListener("click", function () {
      mostrarDetallePartido(this);
    });
  });
}

/**
 * Muestra los detalles del partido en el modal
 * @param {HTMLElement} cardElement - Elemento de la tarjeta del partido clickeada
 */
function mostrarDetallePartido(cardElement) {
  // Obtener datos del partido desde los atributos data-*
  const partidoId = cardElement.dataset.partidoId;
  const equipo1 = cardElement.dataset.equipo1;
  const goles1 = cardElement.dataset.goles1;
  const equipo2 = cardElement.dataset.equipo2;
  const goles2 = cardElement.dataset.goles2;
  const fase = cardElement.dataset.fase;

  // Actualizar información básica del modal
  actualizarInfoPartido(fase, equipo1, equipo2, goles1, goles2);

  // Actualizar información adicional según el partido
  actualizarInfoAdicional(partidoId, goles1);

  console.log(
    `Mostrando detalles del partido: ${partidoId} - ${equipo1} vs ${equipo2}`
  );
}

/**
 * Actualiza la información básica del partido en el modal
 */
function actualizarInfoPartido(fase, equipo1, equipo2, goles1, goles2) {
  document.getElementById("modal-fase").textContent = fase;
  document.getElementById("modal-equipo1").textContent = equipo1;
  document.getElementById("modal-equipo2").textContent = equipo2;
  document.getElementById("modal-goles1").textContent = goles1;
  document.getElementById("modal-goles2").textContent = goles2;

  // Actualizar estilos de los badges según el estado
  actualizarBadgesGoles(goles1, goles2);
}

/**
 * Actualiza los colores de los badges según el resultado
 */
function actualizarBadgesGoles(goles1, goles2) {
  const badge1 = document.getElementById("modal-goles1");
  const badge2 = document.getElementById("modal-goles2");

  if (goles1 === "-" || goles2 === "-") {
    // Partido pendiente
    badge1.className = "badge text-bg-dark fs-4";
    badge2.className = "badge text-bg-dark fs-4";
    document.getElementById("modal-estado").innerHTML =
      '<span class="badge text-bg-dark">Pendiente</span>';
  } else {
    // Partido finalizado
    const g1 = parseInt(goles1);
    const g2 = parseInt(goles2);

    if (g1 > g2) {
      badge1.className = "badge text-bg-dark fs-4";
      badge2.className = "badge text-bg-dark fs-4";
    } else if (g2 > g1) {
      badge1.className = "badge text-bg-dark fs-4";
      badge2.className = "badge text-bg-dark fs-4";
    } else {
      badge1.className = "badge text-bg-dark fs-4";
      badge2.className = "badge text-bg-dark fs-4";
    }

    document.getElementById("modal-estado").innerHTML =
      '<span class="badge text-bg-dark">Finalizado</span>';
  }
}

/**
 * Actualiza la información adicional del partido (fecha, hora, cancha, etc.)
 */
function actualizarInfoAdicional(partidoId, goles1) {
  // Configurar datos específicos según el partido
  const partidoInfo = obtenerInfoPartido(partidoId);

  document.getElementById("modal-fecha").textContent = partidoInfo.fecha;
  document.getElementById("modal-hora").textContent = partidoInfo.hora;
  document.getElementById("modal-cancha").textContent = partidoInfo.cancha;
  document.getElementById("modal-duracion").textContent = partidoInfo.duracion;
  document.getElementById("modal-amarillas").textContent =
    partidoInfo.amarillas;
  document.getElementById("modal-rojas").textContent = partidoInfo.rojas;
  document.getElementById("modal-arbitro").textContent = partidoInfo.arbitro;

  // Actualizar botón "Ver Partido Completo"
  actualizarBotonVerCompleto(partidoId, goles1);
}

/**
 * Obtiene la información específica de un partido según su ID
 */
function obtenerInfoPartido(partidoId) {
  // Configuración de partidos con sus datos específicos
  const partidosConfig = {
    "octavo-1": {
      fecha: "28/10/2025",
      hora: "15:30",
      cancha: "Cancha Principal A",
      duracion: "90 minutos",
      amarillas: "3",
      rojas: "0",
      arbitro: "Carlos Mendez",
    },
    "octavo-2": {
      fecha: "28/10/2025",
      hora: "17:00",
      cancha: "Cancha Principal B",
      duracion: "90 minutos",
      amarillas: "2",
      rojas: "1",
      arbitro: "Ana García",
    },
    "octavo-3": {
      fecha: "29/10/2025",
      hora: "15:30",
      cancha: "Cancha Principal A",
      duracion: "90 minutos",
      amarillas: "4",
      rojas: "0",
      arbitro: "Miguel Rodriguez",
    },
    "octavo-4": {
      fecha: "29/10/2025",
      hora: "17:00",
      cancha: "Cancha Principal B",
      duracion: "90 minutos",
      amarillas: "1",
      rojas: "0",
      arbitro: "Laura Fernandez",
    },
  };

  // Devolver configuración del partido o datos por defecto para partidos pendientes
  return (
    partidosConfig[partidoId] || {
      fecha: "Por definir",
      hora: "Por definir",
      cancha: "Por definir",
      duracion: "Por definir",
      amarillas: "-",
      rojas: "-",
      arbitro: "Por asignar",
    }
  );
}

/**
 * Actualiza el botón "Ver Partido Completo"
 */
function actualizarBotonVerCompleto(partidoId, goles1) {
  const btnVerCompleto = document.getElementById("modal-btn-ver-completo");

  if (goles1 !== "-") {
    btnVerCompleto.style.display = "inline-block";
    btnVerCompleto.href = `partidoDetalle.php?id=${partidoId}`;
  } else {
    btnVerCompleto.style.display = "none";
  }
}
