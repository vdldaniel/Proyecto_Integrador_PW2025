/**
 * Cancha Perfil Jugador JavaScript
 * Funcionalidad para la página de perfil de cancha desde la perspectiva del jugador
 * Extiende PerfilCanchaBase con funcionalidades específicas del jugador
 */

class PerfilCanchaJugador extends PerfilCanchaBase {
  constructor() {
    super(); // Llamar al constructor de la clase base
    this.inicializarFuncionalidadesJugador();
  }

  /**
   * Inicializar funcionalidades específicas del jugador
   */
  inicializarFuncionalidadesJugador() {
    this.inicializarReservas();
    this.inicializarModales();
    this.configurarFechaMinima();
    this.configurarEventListenersJugador();
  }

  /**
   * Configurar event listeners específicos del jugador
   */
  configurarEventListenersJugador() {
    // Botón ver torneos
    const btnVerTorneos = document.getElementById("btnVerTorneos");
    if (btnVerTorneos) {
      btnVerTorneos.addEventListener("click", () => this.verTorneos());
    }

    // Botones de inscribir equipo
    const botonesInscribir = document.querySelectorAll(".btnInscribirEquipo");
    botonesInscribir.forEach((boton) => {
      boton.addEventListener("click", (e) => {
        const torneoId = e.target.getAttribute("data-torneo-id");
        this.inscribirEquipo(torneoId);
      });
    });

    // Botones de notificar inicio
    const botonesNotificar = document.querySelectorAll(".btnNotificarInicio");
    botonesNotificar.forEach((boton) => {
      boton.addEventListener("click", (e) => {
        const torneoId = e.target.getAttribute("data-torneo-id");
        this.notificarInicio(torneoId);
      });
    });

    // Botones de reservar horario
    const botonesReservar = document.querySelectorAll(".btnReservarHorario");
    botonesReservar.forEach((boton) => {
      boton.addEventListener("click", (e) => this.abrirModalReserva(e));
    });

    // Botón calendario completo
    const btnCalendarioCompleto = document.getElementById(
      "btnVerCalendarioCompleto"
    );
    if (btnCalendarioCompleto) {
      btnCalendarioCompleto.addEventListener("click", () =>
        this.irACalendario()
      );
    }
  }

  /**
   * Funcionalidad de reservas
   */
  inicializarReservas() {
    // Configurar fecha mínima (hoy)
    const fechaReserva = document.getElementById("fechaReserva");
    if (fechaReserva) {
      const hoy = new Date().toISOString().split("T")[0];
      fechaReserva.setAttribute("min", hoy);
      fechaReserva.value = hoy;
      fechaReserva.addEventListener("change", () =>
        this.actualizarHorariosDisponibles()
      );
    }

    // Calcular total cuando cambien la duración
    const duracionReserva = document.getElementById("duracionReserva");
    if (duracionReserva) {
      duracionReserva.addEventListener("change", () => this.calcularTotal());
    }
  }

  /**
   * Funcionalidad de modales
   */
  inicializarModales() {
    // Modal de reserva
    const modalReserva = document.getElementById("modalReservarCancha");
    if (modalReserva) {
      modalReserva.addEventListener("hidden.bs.modal", () => {
        const form = document.getElementById("formReservarCancha");
        if (form) {
          form.reset();
          this.configurarFechaMinima();
        }
      });
    }
  }

  /**
   * Configurar fecha mínima para reservas
   */
  configurarFechaMinima() {
    const fechaReserva = document.getElementById("fechaReserva");
    if (fechaReserva) {
      const hoy = new Date().toISOString().split("T")[0];
      fechaReserva.setAttribute("min", hoy);
      fechaReserva.value = hoy;
    }
  }

  /**
   * Ver todos los torneos disponibles
   */
  verTorneos() {
    console.log("Redirigiendo a torneos...");
    // TODO: Redirigir a página de torneos o mostrar modal
    this.mostrarNotificacion("Función en desarrollo", "info");
  }

  /**
   * Inscribir equipo en un torneo
   * @param {string} torneoId - ID del torneo
   */
  inscribirEquipo(torneoId) {
    console.log(`Inscribir equipo en torneo ${torneoId}`);
    // TODO: Abrir modal de inscripción o redirigir
    this.mostrarNotificacion("Inscripción en desarrollo", "info");
  }

  /**
   * Notificar cuando inicie un torneo
   * @param {string} torneoId - ID del torneo
   */
  notificarInicio(torneoId) {
    console.log(`Notificar inicio del torneo ${torneoId}`);
    // TODO: Implementar sistema de notificaciones
    this.mostrarNotificacion(
      "Te notificaremos cuando inicie el torneo",
      "success"
    );
  }

  /**
   * Abrir modal para reservar cancha
   * @param {Event} event - Evento del clic
   */
  abrirModalReserva(event) {
    const fecha = event.target.getAttribute("data-fecha");
    const hora = event.target.getAttribute("data-hora");

    // TODO: Implementar modal de reserva
    console.log(`Reservar para ${fecha} a las ${hora}`);
    this.mostrarNotificacion(
      `Reservando para ${this.formatearFecha(fecha)} a las ${hora}`,
      "info"
    );

    // Redirigir al calendario para reservar
    this.irACalendario();
  }

  /**
   * Ir al calendario completo
   */
  irACalendario() {
    // La URL ya está configurada en el componente base como PAGE_CALENDARIO_CANCHA_JUGADOR
    console.log("Redirigiendo al calendario...");
  }

  /**
   * Actualizar horarios disponibles según la fecha seleccionada
   */
  actualizarHorariosDisponibles() {
    console.log("Actualizando horarios disponibles...");
    // TODO: Hacer petición AJAX para obtener horarios de la fecha
    this.mostrarNotificacion("Cargando horarios disponibles...", "info");
  }

  /**
   * Calcular total de la reserva
   */
  calcularTotal() {
    const duracion = document.getElementById("duracionReserva")?.value || 1;
    const precioHora = 5000; // TODO: Obtener precio real de la cancha
    const total = duracion * precioHora;

    const totalElement = document.getElementById("totalReserva");
    if (totalElement) {
      totalElement.textContent = `$${total.toLocaleString()}`;
    }

    console.log(`Total calculado: $${total}`);
  }
}

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  new PerfilCanchaJugador();
});
