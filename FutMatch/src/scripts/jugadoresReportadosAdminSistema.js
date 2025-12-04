/**
 * JavaScript para la gestión de reportes de jugadores
 * Maneja modales, filtros y acciones de administración
 */

document.addEventListener("DOMContentLoaded", function () {
  console.log("Sistema de reportes de jugadores inicializado");

  // ===================================
  // ELEMENTOS DEL DOM
  // ===================================

  // Modales
  const modalDetalleReporte = document.getElementById("modalDetalleReporte");
  const modalSuspenderJugador = document.getElementById(
    "modalSuspenderJugador"
  );
  const modalRestablecerJugador = document.getElementById(
    "modalRestablecerJugador"
  );

  // Botones de acción
  const btnDetalle = document.querySelectorAll(".btn-detalle");
  const btnSuspender = document.querySelectorAll(".btn-suspender");
  const btnReestablecer = document.querySelectorAll(".btn-reestablecer");
  const btnTomar = document.querySelectorAll(".btn-tomar");

  // Formularios
  const formSuspender = document.getElementById("formSuspenderJugador");
  const formReestablecer = document.getElementById("formRestablecerJugador");

  // Botones de confirmación
  const btnConfirmarSuspension = document.querySelector(
    ".btn-confirmar-suspension"
  );
  const btnConfirmarRestablecimiento = document.querySelector(
    ".btn-confirmar-restablecimiento"
  );
  const btnTomarCaso = document.querySelector(".btn-tomar-caso");

  // ===================================
  // DATOS DE EJEMPLO PARA REPORTES
  // ===================================

  const reportesData = {
    "REP-001": {
      partidoId: "PAR-445",
      partidoFecha: "10/11/2025",
      partidoCancha: "Complejo Deportivo Centro",
      partidoHora: "15:30 - 17:00",
      reportadoUsername: "agresivo_99",
      reportadoNombre: "Alejandro Ruiz",
      reportadoCalificacion: "★★☆☆☆ (2.1)",
      evaluadorUsername: "carlos_futbol",
      evaluadorNombre: "Carlos García",
      evaluadorCalificacion: "★★★★☆ (4.3)",
      puntuacion: "1",
      comentario:
        "Este jugador tuvo un comportamiento muy agresivo durante todo el partido. Hizo varias entradas peligrosas sin intención de jugar la pelota y cuando le dijimos algo respondió de manera muy irrespetuosa. No recomiendo que juegue con otros usuarios.",
    },
    "REP-002": {
      partidoId: "PAR-458",
      partidoFecha: "08/11/2025",
      partidoCancha: "Club San Lorenzo",
      partidoHora: "18:00 - 19:30",
      reportadoUsername: "malcomportado",
      reportadoNombre: "Diego Torres",
      reportadoCalificacion: "★★★☆☆ (2.8)",
      evaluadorUsername: "maria_goals",
      evaluadorNombre: "María Fernández",
      evaluadorCalificacion: "★★★★★ (4.9)",
      puntuacion: "2",
      comentario:
        "No respeta las reglas del juego, hace faltas innecesarias y discute constantemente con el referí y otros jugadores. Su actitud arruina el ambiente del partido.",
    },
    "REP-003": {
      partidoId: "PAR-423",
      partidoFecha: "05/11/2025",
      partidoCancha: "Futbol 5 Norte",
      partidoHora: "20:00 - 21:30",
      reportadoUsername: "problematico",
      reportadoNombre: "Roberto Silva",
      reportadoCalificacion: "★★☆☆☆ (2.3)",
      evaluadorUsername: "futbol_pro",
      evaluadorNombre: "Lucas Mendoza",
      evaluadorCalificacion: "★★★★☆ (4.1)",
      puntuacion: "1",
      comentario:
        "Actitud muy mala, insulta a los compañeros de equipo y rival. No acepta las decisiones del referí y amenaza con irse del partido constantemente.",
    },
    "REP-004": {
      partidoId: "PAR-401",
      partidoFecha: "02/11/2025",
      partidoCancha: "Complejo La Cancha",
      partidoHora: "16:00 - 17:30",
      reportadoUsername: "suspendido_user",
      reportadoNombre: "Andrés López",
      reportadoCalificacion: "★☆☆☆☆ (1.8)",
      evaluadorUsername: "fair_player",
      evaluadorNombre: "Fernando Castro",
      evaluadorCalificacion: "★★★★★ (4.7)",
      puntuacion: "1",
      comentario:
        "Jugador extremadamente agresivo, hizo una entrada que podría haber lesionado gravemente a otro jugador. Además, cuando se le llamó la atención, comenzó a insultar y amenazar físicamente.",
    },
  };

  // ===================================
  // EVENT LISTENERS PARA BOTONES
  // ===================================

  // Detalle del reporte
  btnDetalle.forEach((btn) => {
    btn.addEventListener("click", function () {
      const reporteId = this.getAttribute("data-reporte-id");
      mostrarDetalleReporte(reporteId);
    });
  });

  // Suspender jugador
  btnSuspender.forEach((btn) => {
    btn.addEventListener("click", function () {
      const jugadorId = this.getAttribute("data-jugador-id");
      mostrarModalSuspension(jugadorId);
    });
  });

  // Reestablecer jugador
  btnReestablecer.forEach((btn) => {
    btn.addEventListener("click", function () {
      const jugadorId = this.getAttribute("data-jugador-id");
      mostrarModalRestablecimiento(jugadorId);
    });
  });

  // Tomar caso
  btnTomar.forEach((btn) => {
    btn.addEventListener("click", function () {
      const reporteId = this.getAttribute("data-reporte-id");
      tomarCaso(reporteId);
    });
  });

  // Confirmar suspensión
  if (btnConfirmarSuspension) {
    btnConfirmarSuspension.addEventListener("click", confirmarSuspension);
  }

  // Confirmar restablecimiento
  if (btnConfirmarRestablecimiento) {
    btnConfirmarRestablecimiento.addEventListener(
      "click",
      confirmarRestablecimiento
    );
  }

  // Tomar caso desde modal
  if (btnTomarCaso) {
    btnTomarCaso.addEventListener("click", function () {
      const reporteId = modalDetalleReporte.getAttribute("data-reporte-activo");
      tomarCaso(reporteId);
      const modal = bootstrap.Modal.getInstance(modalDetalleReporte);
      modal.hide();
    });
  }

  // ===================================
  // FUNCIONES PRINCIPALES
  // ===================================

  function mostrarDetalleReporte(reporteId) {
    const reporte = reportesData[reporteId];
    if (!reporte) {
      console.error("Reporte no encontrado:", reporteId);
      return;
    }

    // Llenar datos del modal
    document.getElementById("detalle-partido-id").textContent =
      reporte.partidoId;
    document.getElementById("detalle-partido-fecha").textContent =
      reporte.partidoFecha;
    document.getElementById("detalle-partido-cancha").textContent =
      reporte.partidoCancha;
    document.getElementById("detalle-partido-hora").textContent =
      reporte.partidoHora;

    document.getElementById("detalle-reportado-username").textContent =
      reporte.reportadoUsername;
    document.getElementById("detalle-reportado-nombre").textContent =
      reporte.reportadoNombre;
    document.getElementById("detalle-reportado-calificacion").textContent =
      reporte.reportadoCalificacion;

    document.getElementById("detalle-evaluador-username").textContent =
      reporte.evaluadorUsername;
    document.getElementById("detalle-evaluador-nombre").textContent =
      reporte.evaluadorNombre;
    document.getElementById("detalle-evaluador-calificacion").textContent =
      reporte.evaluadorCalificacion;

    document.getElementById("detalle-puntuacion").textContent =
      reporte.puntuacion;
    document.getElementById("detalle-comentario").textContent =
      reporte.comentario;

    // Guardar ID del reporte activo
    modalDetalleReporte.setAttribute("data-reporte-activo", reporteId);

    // Mostrar modal
    const modal = new bootstrap.Modal(modalDetalleReporte);
    modal.show();
  }

  function mostrarModalSuspension(jugadorId) {
    // Limpiar formulario
    if (formSuspender) {
      formSuspender.reset();
      document.getElementById("suspender-jugador-id").value = jugadorId;
    }

    // Establecer fecha mínima como mañana
    const fechaSuspension = document.getElementById("fecha-suspension");
    if (fechaSuspension) {
      const mañana = new Date();
      mañana.setDate(mañana.getDate() + 1);
      fechaSuspension.min = mañana.toISOString().split("T")[0];
    }

    // Mostrar modal
    const modal = new bootstrap.Modal(modalSuspenderJugador);
    modal.show();
  }

  function mostrarModalRestablecimiento(jugadorId) {
    // Limpiar formulario
    if (formReestablecer) {
      formReestablecer.reset();
      document.getElementById("reestablecer-jugador-id").value = jugadorId;
    }

    // Mostrar modal
    const modal = new bootstrap.Modal(modalRestablecerJugador);
    modal.show();
  }

  function tomarCaso(reporteId) {
    // Simular tomar el caso
    console.log("Tomando caso:", reporteId);

    // Mostrar notificación
    showToast(
      "Caso tomado correctamente. Ahora eres el verificador asignado.",
      "success"
    );

    // Actualizar la interfaz (esto sería reemplazado por una llamada AJAX real)
    setTimeout(() => {
      location.reload();
    }, 2000);
  }

  function confirmarSuspension() {
    const jugadorId = document.getElementById("suspender-jugador-id").value;
    const fechaSuspension = document.getElementById("fecha-suspension").value;
    const mensaje = document.getElementById("mensaje-suspension").value;

    if (!fechaSuspension) {
      showToast("Por favor selecciona una fecha de suspensión", "error");
      return;
    }

    // Aquí iría la llamada AJAX para suspender al jugador
    console.log("Suspendiendo jugador:", {
      jugadorId,
      fechaSuspension,
      mensaje,
    });

    // Cerrar modal y mostrar notificación
    const modal = bootstrap.Modal.getInstance(modalSuspenderJugador);
    modal.hide();

    showToast(
      "Jugador suspendido correctamente. Se ha enviado un email de notificación.",
      "success"
    );

    // Actualizar la interfaz
    setTimeout(() => {
      location.reload();
    }, 2000);
  }

  function confirmarRestablecimiento() {
    const jugadorId = document.getElementById("reestablecer-jugador-id").value;
    const mensaje = document.getElementById("mensaje-restablecimiento").value;

    // Aquí iría la llamada AJAX para reestablecer al jugador
    console.log("Restableciendo jugador:", { jugadorId, mensaje });

    // Cerrar modal y mostrar notificación
    const modal = bootstrap.Modal.getInstance(modalRestablecerJugador);
    modal.hide();

    mostrarNotificación(
      "Cuenta restablecida correctamente. Se ha enviado un email de confirmación.",
      "success"
    );

    // Actualizar la interfaz
    setTimeout(() => {
      location.reload();
    }, 2000);
  }

  // ===================================
  // FILTROS Y BÚSQUEDA
  // ===================================

  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const filtro = this.value.toLowerCase();
      filtrarReportes(filtro);
    });
  }

  function filtrarReportes(filtro) {
    const reportes = document.querySelectorAll(".reporte-card");

    reportes.forEach((reporte) => {
      const texto = reporte.textContent.toLowerCase();
      reporte.style.display = texto.includes(filtro) ? "" : "none";
    });
  }

  // ===================================
  // TOOLTIPS DE BOOTSTRAP
  // ===================================

  // Inicializar tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  console.log("Sistema de reportes completamente inicializado");
});
