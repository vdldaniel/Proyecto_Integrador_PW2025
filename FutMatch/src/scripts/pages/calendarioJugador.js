/**
 * Calendario Jugador JavaScript
 * Funcionalidad específica para jugadores que extiende CalendarioBase
 */

// Clase que extiende la funcionalidad base del calendario para jugadores
class AplicacionCalendarioJugador extends CalendarioBase {
  constructor() {
    super();
    this.horarioSeleccionado = null;
    this.fechaSeleccionada = null;
    this.reservasSimuladas = [
      {
        fecha: "2025-11-04",
        hora: "18:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Los Cracks FC",
      },
      {
        fecha: "2025-11-04",
        hora: "20:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Equipo Delta",
      },
      {
        fecha: "2025-11-05",
        hora: "19:00",
        duracion: 1,
        estado: "reservado",
        cliente: "Racing Amateur",
      },
      {
        fecha: "2025-11-05",
        hora: "21:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Villa FC",
      },
      {
        fecha: "2025-11-06",
        hora: "17:00",
        duracion: 3,
        estado: "reservado",
        cliente: "Deportivo Sur",
      },
      {
        fecha: "2025-11-07",
        hora: "18:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Club Atlético",
      },
      {
        fecha: "2025-11-08",
        hora: "20:00",
        duracion: 1,
        estado: "reservado",
        cliente: "Los Tigres",
      },
      {
        fecha: "2025-11-09",
        hora: "16:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Torneo Copa Verano",
      },
      {
        fecha: "2025-11-09",
        hora: "18:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Torneo Copa Verano",
      },
      {
        fecha: "2025-11-09",
        hora: "20:00",
        duracion: 2,
        estado: "reservado",
        cliente: "Torneo Copa Verano",
      },
    ];

    this.inicializarEventosJugador();
    this.personalizarCalendarioJugador();
  }

  personalizarCalendarioJugador() {
    // Ocultar selector de cancha ya que estamos viendo una cancha específica
    const selectorCancha = document.getElementById("selectorCancha");
    if (selectorCancha) {
      const contenedorCancha = selectorCancha.closest(".col-md-3");
      if (contenedorCancha) {
        contenedorCancha.style.display = "none";
      }
    }

    // Agregar indicadores de disponibilidad en el espacio libre
    const espacioLibre = document.querySelector(
      ".calendario-header .col-md-3:nth-child(3)"
    );
    if (espacioLibre) {
      espacioLibre.innerHTML = `
                <div class="d-flex align-items-center justify-content-center">
                    <div class="me-3">
                        <span class="badge text-bg-dark me-1">●</span>
                        <small>Disponible</small>
                    </div>
                    <div class="me-3">
                        <span class="badge text-bg-dark me-1">●</span>
                        <small>Reservado</small>
                    </div>
                    <div>
                        <span class="badge text-bg-dark me-1">●</span>
                        <small>No disponible</small>
                    </div>
                </div>
            `;
    }
  }

  inicializarEventosJugador() {
    // Botón de reservar horario - abrir modal de reserva
    const botonReservar = document.getElementById("botonReservarHorario");
    if (botonReservar) {
      botonReservar.addEventListener("click", () => {
        this.mostrarModalReservarCancha();
      });
    }

    // Event delegation para los botones del calendario
    document.addEventListener("click", (e) => {
      if (e.target.closest(".btnSeleccionarHorario")) {
        const boton = e.target.closest(".btnSeleccionarHorario");
        const fecha = boton.getAttribute("data-fecha");
        const hora = boton.getAttribute("data-hora");
        this.seleccionarHorario(fecha, hora);
      }
    });

    // Event listeners para botones específicos de la página
    // Nota: btnVolverCancha ya no existe, fue cambiado por un enlace directo

    // Guardar referencia de 'this' para usar en los event listeners
    const self = this;

    const btnVerPoliticas1 = document.getElementById("btnVerPoliticas1");
    if (btnVerPoliticas1) {
      btnVerPoliticas1.addEventListener("click", function () {
        self.verPoliticasReserva();
      });
    }

    const btnVerPoliticas2 = document.getElementById("btnVerPoliticas2");
    if (btnVerPoliticas2) {
      btnVerPoliticas2.addEventListener("click", function () {
        self.verPoliticasReserva();
      });
    }

    const btnEnviarSolicitud = document.getElementById("btnEnviarSolicitud");
    if (btnEnviarSolicitud) {
      btnEnviarSolicitud.addEventListener("click", function () {
        self.enviarSolicitudReserva();
      });
    }
  }

  // Override para personalizar el renderizado de vista diaria con funcionalidad de reservas
  renderizarVistaDiaria() {
    super.renderizarVistaDiaria();
    this.agregarFuncionalidadReservasDiarias();
  }

  agregarFuncionalidadReservasDiarias() {
    const cuerpoTabla = document.getElementById("tablaCalendario");
    if (!cuerpoTabla) return;

    const filas = cuerpoTabla.querySelectorAll("tr");
    filas.forEach((fila) => {
      const celdaHora = fila.cells[0];
      const celdaContenido = fila.cells[1];

      if (celdaHora && celdaContenido) {
        const hora = celdaHora.textContent.trim();
        if (hora.includes(":00")) {
          const horaCompleta = hora.replace(" - ", "").split(" ")[0];

          if (this.esHorarioDisponible(this.fechaActual, horaCompleta)) {
            celdaContenido.innerHTML = `
                            <button class="btn btn-dark btn-sm w-100 btnSeleccionarHorario" 
                                    data-fecha="${this.formatearFecha(
                                      this.fechaActual
                                    )}" data-hora="${horaCompleta}">
                                <i class="bi bi-plus-circle"></i> Disponible
                            </button>
                        `;
          } else {
            const reserva = this.obtenerReserva(this.fechaActual, horaCompleta);
            if (reserva) {
              celdaContenido.innerHTML = `
                                <div class="badge text-bg-dark w-100 p-2">
                                    <i class="bi bi-x-circle"></i> Reservado - ${reserva.cliente}
                                </div>
                            `;
            }
          }
        }
      }
    });
  }

  // Override para personalizar vista mensual con disponibilidad
  renderizarVistaMensual() {
    super.renderizarVistaMensual();
    this.agregarIndicadoresDisponibilidad();
  }

  agregarIndicadoresDisponibilidad() {
    const celdas = document.querySelectorAll("#tablaCalendario td[data-fecha]");
    celdas.forEach((celda) => {
      const fecha = new Date(celda.dataset.fecha);
      const disponibilidad = this.obtenerDisponibilidadDia(fecha);

      if (disponibilidad.total > 0) {
        const indicador = document.createElement("div");
        indicador.className = "small text-muted mt-1";
        indicador.innerHTML = `
                    <i class="bi bi-circle-fill text-success" style="font-size: 0.5rem;"></i> ${disponibilidad.disponibles}
                    <i class="bi bi-circle-fill text-danger ms-1" style="font-size: 0.5rem;"></i> ${disponibilidad.reservados}
                `;
        celda.appendChild(indicador);
      }
    });
  }

  obtenerDisponibilidadDia(fecha) {
    const fechaString = this.formatearFecha(fecha);
    let disponibles = 0;
    let reservados = 0;
    let total = 17; // 7:00 AM - 11:00 PM = 17 horas

    // Verificar si es un día en el pasado
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (fecha < hoy) {
      return { disponibles: 0, reservados: 0, total: 0 };
    }

    for (let hora = 7; hora <= 23; hora++) {
      const horaString = `${hora.toString().padStart(2, "0")}:00`;
      if (this.esHorarioDisponible(fecha, horaString)) {
        disponibles++;
      } else if (this.obtenerReserva(fecha, horaString)) {
        reservados++;
      }
    }

    return { disponibles, reservados, total };
  }

  esHorarioDisponible(fecha, hora) {
    const fechaString = this.formatearFecha(fecha);
    const hoy = new Date();

    // No permitir reservas en el pasado
    if (
      fecha < hoy.toDateString() ||
      (fecha.toDateString() === hoy.toDateString() &&
        parseInt(hora) <= hoy.getHours())
    ) {
      return false;
    }

    // Verificar si ya está reservado
    return !this.reservasSimuladas.some(
      (reserva) => reserva.fecha === fechaString && reserva.hora === hora
    );
  }

  obtenerReserva(fecha, hora) {
    const fechaString = this.formatearFecha(fecha);
    return this.reservasSimuladas.find(
      (reserva) => reserva.fecha === fechaString && reserva.hora === hora
    );
  }

  seleccionarHorario(fecha, hora) {
    if (typeof fecha === "string") {
      this.fechaSeleccionada = fecha;
    } else {
      this.fechaSeleccionada = this.formatearFecha(fecha);
    }

    this.horarioSeleccionado = hora;

    console.log(
      "Horario seleccionado:",
      this.fechaSeleccionada,
      this.horarioSeleccionado
    );

    // Abrir modal de reserva directamente con fecha y hora prellenados
    this.mostrarModalReservarCancha(
      this.fechaSeleccionada,
      this.horarioSeleccionado
    );
  }

  // Funciones adicionales específicas del jugador
  volverCancha() {
    window.history.back();
  }

  verPoliticasReserva() {
    const modalElement = document.getElementById("modalPoliticas");
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    }
  }

  mostrarModalReservarCancha(fecha = null, hora = null) {
    // Pre-llenar fecha y hora si se proporcionan
    const fechaReserva = document.getElementById("fechaReserva");
    const horaReserva = document.getElementById("horaReserva");

    if (fechaReserva) {
      if (fecha) {
        // Si fecha es un string ISO, usarlo directamente
        if (typeof fecha === "string") {
          fechaReserva.value = fecha;
        } else {
          fechaReserva.value = fecha.toISOString().split("T")[0];
        }
      } else {
        // Establecer fecha actual por defecto
        const hoy = new Date();
        const mañana = new Date(hoy);
        mañana.setDate(hoy.getDate() + 1);
        fechaReserva.value = mañana.toISOString().split("T")[0];
      }
    }

    if (horaReserva && hora) {
      horaReserva.value = hora;
    }

    // Mostrar modal
    const modalElement = document.getElementById("modalReservarCancha");
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    }
  }

  // Funciones adicionales para la interfaz de jugador
  volverCancha() {
    // Regresar a la página de perfil de cancha
    window.history.back();
  }

  verPoliticasReserva() {
    // Mostrar modal de políticas
    const modalElement = document.getElementById("modalPoliticas");
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    }
  }

  enviarSolicitudReserva() {
    // Obtener datos del formulario
    const fecha = document.getElementById("fechaReserva").value;
    const hora = document.getElementById("horaReserva").value;
    const duracion = document.getElementById("duracionReserva").value;
    const comentarios = document.getElementById("comentariosReserva").value;

    // Validar campos obligatorios
    if (!fecha || !hora || !duracion) {
      alert("Por favor, complete todos los campos obligatorios.");
      return;
    }

    // Simular envío de solicitud
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalReservarCancha")
    );
    modal.hide();

    // Mostrar mensaje de éxito
    setTimeout(() => {
      alert(
        "¡Solicitud enviada con éxito! El administrador de la cancha revisará tu solicitud y te contactará pronto."
      );

      // Limpiar formulario
      document.getElementById("formReservarCancha").reset();
    }, 500);
  }

  // Funciones adicionales para la interfaz de jugador
  volverCancha() {
    // Regresar a la página de perfil de cancha
    window.history.back();
  }

  verPoliticasReserva() {
    // Mostrar modal de políticas
    const modalElement = document.getElementById("modalPoliticas");
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    }
  }
}

// Función global para enviar solicitud de reserva (mantenida para compatibilidad)
function enviarSolicitudReserva() {
  if (calendarioJugador) {
    calendarioJugador.enviarSolicitudReserva();
  }
}

// Sobrescribir funciones globales del calendario para funcionalidad de jugador
function onCalendarioDiaClick(fecha) {
  console.log("Día seleccionado:", fecha);
  if (calendarioJugador) {
    try {
      // Cambiar a vista diaria del día seleccionado
      calendarioJugador.fechaActual = new Date(fecha);
      calendarioJugador.cambiarVista("dia");
      console.log("Vista cambiada a día:", fecha);
    } catch (error) {
      console.error("Error al cambiar a vista diaria:", error);
    }
  } else {
    console.warn("calendarioJugador no está inicializado");
  }
}

function onCalendarioHorarioClick(fecha, hora) {
  console.log("Horario seleccionado:", fecha, hora);
  if (calendarioJugador) {
    try {
      // Abrir modal de reserva con la fecha y hora seleccionadas
      calendarioJugador.seleccionarHorario(fecha, hora);
      console.log("Modal de reserva abierto para:", fecha, hora);
    } catch (error) {
      console.error("Error al abrir modal de reserva:", error);
    }
  } else {
    console.warn("calendarioJugador no está inicializado");
  }
}

// Instancia global para el calendario del jugador
let calendarioJugador;

// Inicialización cuando se carga la página
document.addEventListener("DOMContentLoaded", function () {
  calendarioJugador = new AplicacionCalendarioJugador();
});
