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
   * Cargar y renderizar información de una cancha desde el backend
   * @param {number} idCancha - ID de la cancha a cargar
   */
  async cargarYRenderizarCancha(idCancha) {
    try {
      if (typeof cargarInfoPerfil === "undefined") {
        throw new Error("La función cargarInfoPerfil no está disponible");
      }

      const resultado = await cargarInfoPerfil(idCancha, "cancha");

      let datos;
      if (resultado.success && resultado.data) {
        datos = resultado.data;
      } else if (resultado.id_cancha) {
        datos = resultado;
      } else if (resultado.error) {
        throw new Error(resultado.error);
      } else {
        throw new Error("No se pudieron cargar los datos de la cancha");
      }

      this.datosCancha = datos;
      this.renderizarDatosCancha(this.datosCancha);
      // Los horarios ahora se manejan desde la clase base
      await this.cargarHorariosCancha(idCancha);

      return this.datosCancha;
    } catch (error) {
      console.error("Error al cargar la cancha:", error);
      alert("Error al cargar la información de la cancha");
      throw error;
    }
  }

  /**
   * Renderizar datos básicos de la cancha
   */
  renderizarDatosCancha(datos) {
    const nombreCancha = document.getElementById("nombreCancha");
    if (nombreCancha)
      nombreCancha.textContent = datos.nombre_cancha || "Cancha";

    const descripcionCancha = document.getElementById("descripcionCancha");
    if (descripcionCancha)
      descripcionCancha.textContent = datos.descripcion_cancha || "";

    const bannerCancha = document.getElementById("bannerCancha");
    if (bannerCancha && datos.banner_cancha) {
      bannerCancha.style.backgroundImage = `url('${BASE_URL}${datos.banner_cancha}')`;
    }

    const direccionCancha = document.getElementById("direccionCancha");
    if (direccionCancha)
      direccionCancha.textContent =
        datos.direccion_cancha || "Dirección no disponible";

    const superficieCancha = document.getElementById("superficieCancha");
    if (superficieCancha)
      superficieCancha.textContent = datos.tipo_superficie || "N/A";
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
    const fechaComienzo = document.getElementById("fechaComienzo");
    if (fechaComienzo) {
      const hoy = new Date().toISOString().split("T")[0];
      fechaComienzo.setAttribute("min", hoy);
      fechaComienzo.value = hoy;
      fechaComienzo.addEventListener("change", () =>
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
    const fechaComienzo = document.getElementById("fechaComienzo");
    if (fechaComienzo) {
      const hoy = new Date().toISOString().split("T")[0];
      fechaComienzo.setAttribute("min", hoy);
      fechaComienzo.value = hoy;
    }
  }

  /**
   * Ver todos los torneos disponibles
   */
  verTorneos() {
    console.log("Redirigiendo a torneos...");
    // TODO: Redirigir a página de torneos o mostrar modal
    this.showToast("Función en desarrollo", "info");
  }

  /**
   * Inscribir equipo en un torneo
   * @param {string} torneoId - ID del torneo
   */
  inscribirEquipo(torneoId) {
    console.log(`Inscribir equipo en torneo ${torneoId}`);
    // TODO: Abrir modal de inscripción o redirigir
    this.showToast("Inscripción en desarrollo", "info");
  }

  /**
   * Notificar cuando inicie un torneo
   * @param {string} torneoId - ID del torneo
   */
  notificarInicio(torneoId) {
    console.log(`Notificar inicio del torneo ${torneoId}`);
    // TODO: Implementar sistema de notificaciones
    this.showToast("Te notificaremos cuando inicie el torneo", "success");
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
    this.showToast(
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
    this.showToast("Cargando horarios disponibles...", "info");
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

// Inicializar cuando el DOM esté listo y exponer globalmente
document.addEventListener("DOMContentLoaded", function () {
  window.perfilCanchaJugador = new PerfilCanchaJugador();
});
