/**
 * Agenda JavaScript - Funcionalidades generales del calendario
 * Contiene la lógica base que puede ser utilizada por cualquier actor del sistema
 */

// Configuración general del calendario
const CONFIGURACION_CALENDARIO = {
  DIAS: [
    "Domingo",
    "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes",
    "Sábado",
  ],
  MESES: [
    "Enero",
    "Febrero",
    "Marzo",
    "Abril",
    "Mayo",
    "Junio",
    "Julio",
    "Agosto",
    "Septiembre",
    "Octubre",
    "Noviembre",
    "Diciembre",
  ],
  HORARIOS: {
    HORA_INICIO: 6,
    HORA_FIN: 24,
    INTERVALO: 1, // horas
  },
};

// Clase base del calendario que puede ser extendida por diferentes actores
class CalendarioBase {
  constructor() {
    this.fechaActual = new Date();
    this.vistaActual = "mes";
    this.canchaSeleccionada = null;
    this.reservas = [];

    // Caché de elementos DOM frecuentemente accedidos
    this.elementos = {};

    this.inicializar();
  }

  inicializar() {
    this.cachearElementos();
    this.vincularEventosGenerales();
    this.inicializarVistaDefecto();
    this.actualizarDisplayFecha();
    // No renderizar hasta que se seleccione una cancha
  }

  // Cachear elementos DOM para mejor rendimiento
  cachearElementos() {
    this.elementos = {
      displayFechaActual: document.getElementById("displayFechaActual"),
      botonAnterior: document.getElementById("botonAnterior"),
      botonSiguiente: document.getElementById("botonSiguiente"),
      botonHoy: document.getElementById("botonHoy"),
      vistaMensual: document.getElementById("vistaMensual"),
      vistaSemanal: document.getElementById("vistaSemanal"),
      vistaDiaria: document.getElementById("vistaDiaria"),
      contenedorCalendario: document.getElementById("contenidoCalendario"),
      selectorCancha: document.getElementById("selectorCancha"),
      selectorFecha: document.getElementById("selectorFecha"),
      vistaActual: document.getElementById("vistaActual"),
      selectoresVista: document.querySelectorAll(".selector-vista"),
      mensajeSeleccionarCancha: document.getElementById(
        "mensajeSeleccionarCancha"
      ),
    };
  }

  // Configurar eventos generales del calendario
  vincularEventosGenerales() {
    this.configurarCambioVistas();
    this.configurarNavegacionFecha();
    this.configurarSelectorFecha();
  }

  // Inicializar vista por defecto
  inicializarVistaDefecto() {
    this.vistaActual = "mes";
    document.body.classList.add("monthly-view-active");

    // Actualizar texto del dropdown
    if (this.elementos.vistaActual) {
      this.elementos.vistaActual.textContent = "Mes";
    }

    // Mostrar mensaje de seleccionar cancha al inicio
    this.mostrarMensajeSeleccionarCancha();
  }

  // Configurar cambio de vistas (mensual, semanal, diaria)
  configurarCambioVistas() {
    // Configurar selectores de vista del dropdown
    if (this.elementos.selectoresVista) {
      this.elementos.selectoresVista.forEach((selector) => {
        selector.addEventListener("click", (e) => {
          e.preventDefault();
          const vista = selector.getAttribute("data-vista");
          this.cambiarVista(vista);
        });
      });
    }
  }

  // Configurar navegación de fechas
  configurarNavegacionFecha() {
    if (this.elementos.botonAnterior) {
      this.elementos.botonAnterior.addEventListener("click", () => {
        this.navegarFecha(-1);
      });
    }

    if (this.elementos.botonSiguiente) {
      this.elementos.botonSiguiente.addEventListener("click", () => {
        this.navegarFecha(1);
      });
    }

    if (this.elementos.botonHoy) {
      this.elementos.botonHoy.addEventListener("click", () => {
        this.irAHoy();
      });
    }
  }

  // Configurar selector de fecha
  configurarSelectorFecha() {
    if (this.elementos.selectorFecha) {
      this.elementos.selectorFecha.addEventListener("change", (e) => {
        this.fechaActual = new Date(e.target.value + "T12:00:00");
        this.actualizarDisplayFecha();
        this.renderizarVistaActual();
      });
    }
  }

  // Cargar reservas de una cancha específica
  async cargarReservas(idCancha) {
    if (!idCancha) {
      this.reservas = [];
      this.canchaSeleccionada = null;
      this.mostrarMensajeSeleccionarCancha();
      return;
    }

    try {
      const response = await fetch(`${GET_RESERVAS}?id_cancha=${idCancha}`);
      const data = await response.json();
      //console.log("Respuesta de reservas:", data);

      if (!response.ok) {
        throw new Error(data.message || "Error al cargar reservas");
      }

      if (data.success) {
        this.reservas = data.reservas || [];
        this.canchaSeleccionada = idCancha;
        this.ocultarMensajeSeleccionarCancha();
        // Forzar el cambio de vista para asegurar renderizado
        this.cambiarVista(this.vistaActual);
      } else {
        throw new Error(data.message || "Error al cargar reservas");
      }
    } catch (error) {
      console.error("Error al cargar reservas:", error);
      // Mostrar toast solo si la función existe (puede no estar disponible en todos los contextos)
      if (typeof showToast === "function") {
        showToast("Error al cargar las reservas: " + error.message, "error");
      }
      this.reservas = [];
      this.canchaSeleccionada = null;
      this.mostrarMensajeSeleccionarCancha();
    }
  }

  // Mostrar mensaje para seleccionar cancha
  mostrarMensajeSeleccionarCancha() {
    if (this.elementos.mensajeSeleccionarCancha) {
      this.elementos.mensajeSeleccionarCancha.classList.remove("d-none");
    }
    if (this.elementos.vistaMensual) {
      this.elementos.vistaMensual.classList.add("d-none");
    }
    if (this.elementos.vistaSemanal) {
      this.elementos.vistaSemanal.classList.add("d-none");
    }
    if (this.elementos.vistaDiaria) {
      this.elementos.vistaDiaria.classList.add("d-none");
    }
  }

  // Ocultar mensaje y mostrar calendario
  ocultarMensajeSeleccionarCancha() {
    if (this.elementos.mensajeSeleccionarCancha) {
      this.elementos.mensajeSeleccionarCancha.classList.add("d-none");
    }
  }

  // Obtener reservas de una fecha específica
  obtenerReservasFecha(fecha) {
    const fechaStr = this.formatearFechaISO(fecha);
    return this.reservas.filter((reserva) => reserva.fecha === fechaStr);
  }

  // Obtener reservas de una fecha y hora específicas
  obtenerReservasHora(fecha, hora) {
    const fechaStr = this.formatearFechaISO(fecha);
    const horaStr = hora.toString().padStart(2, "0") + ":00:00";

    return this.reservas.filter((reserva) => {
      if (reserva.fecha !== fechaStr) return false;

      // Verificar si la hora está dentro del rango de la reserva
      const horaInicio = reserva.hora_inicio;
      const horaFin = reserva.hora_fin;

      return horaStr >= horaInicio && horaStr < horaFin;
    });
  }

  // Cambiar vista del calendario
  cambiarVista(nuevaVista) {
    // Si no hay cancha seleccionada, no cambiar vista
    if (!this.canchaSeleccionada) {
      return;
    }

    // Remover clases activas previas
    document.body.classList.remove(
      "monthly-view-active",
      "weekly-view-active",
      "daily-view-active"
    );

    // Actualizar vista actual
    this.vistaActual = nuevaVista;

    // Agregar clase correspondiente al body
    const claseVista = `${
      nuevaVista === "mes"
        ? "monthly"
        : nuevaVista === "semana"
        ? "weekly"
        : "daily"
    }-view-active`;
    document.body.classList.add(claseVista);

    // Ocultar todas las vistas
    this.elementos.vistaMensual?.classList.add("d-none");
    this.elementos.vistaSemanal?.classList.add("d-none");
    this.elementos.vistaDiaria?.classList.add("d-none");

    // Mostrar vista seleccionada
    switch (nuevaVista) {
      case "mes":
        this.elementos.vistaMensual?.classList.remove("d-none");
        break;
      case "semana":
        this.elementos.vistaSemanal?.classList.remove("d-none");
        break;
      case "dia":
        this.elementos.vistaDiaria?.classList.remove("d-none");
        break;
    }

    // Actualizar texto del dropdown
    this.actualizarTextoVista(nuevaVista);

    // Renderizar nueva vista
    this.renderizarVistaActual();
  }

  // Actualizar texto del dropdown de vista
  actualizarTextoVista(vistaActiva) {
    if (this.elementos.vistaActual) {
      const textos = {
        mes: "Mes",
        semana: "Semana",
        dia: "Día",
      };
      this.elementos.vistaActual.textContent = textos[vistaActiva] || "Mes";
    }
  }

  // Navegar fechas
  navegarFecha(direccion) {
    const fecha = new Date(this.fechaActual);

    switch (this.vistaActual) {
      case "mes":
        fecha.setMonth(fecha.getMonth() + direccion);
        break;
      case "semana":
        fecha.setDate(fecha.getDate() + 7 * direccion);
        break;
      case "dia":
        fecha.setDate(fecha.getDate() + direccion);
        break;
    }

    this.fechaActual = fecha;
    this.actualizarDisplayFecha();
    this.renderizarVistaActual();
  }

  // Ir al día de hoy
  irAHoy() {
    this.fechaActual = new Date();
    this.actualizarDisplayFecha();
    this.renderizarVistaActual();
  }

  // Actualizar display de fecha actual
  actualizarDisplayFecha() {
    if (!this.elementos.displayFechaActual) return;

    let textoFecha;
    const fecha = this.fechaActual;

    switch (this.vistaActual) {
      case "mes":
        textoFecha = `${
          CONFIGURACION_CALENDARIO.MESES[fecha.getMonth()]
        } ${fecha.getFullYear()}`;
        break;
      case "semana":
        const inicioSemana = this.obtenerInicioSemana(fecha);
        const finSemana = new Date(inicioSemana);
        finSemana.setDate(inicioSemana.getDate() + 6);
        textoFecha = `${inicioSemana.getDate()} - ${finSemana.getDate()} ${
          CONFIGURACION_CALENDARIO.MESES[fecha.getMonth()]
        } ${fecha.getFullYear()}`;
        break;
      case "dia":
        textoFecha = `${
          CONFIGURACION_CALENDARIO.DIAS[fecha.getDay()]
        }, ${fecha.getDate()} ${
          CONFIGURACION_CALENDARIO.MESES[fecha.getMonth()]
        } ${fecha.getFullYear()}`;
        break;
    }

    this.elementos.displayFechaActual.textContent = textoFecha;

    // Actualizar selector de fecha
    if (this.elementos.selectorFecha) {
      this.elementos.selectorFecha.value = this.formatearFechaISO(fecha);
    }
  }

  // Renderizar vista actual
  renderizarVistaActual() {
    // No renderizar si no hay cancha seleccionada
    if (!this.canchaSeleccionada) {
      return;
    }

    switch (this.vistaActual) {
      case "mes":
        this.renderizarVistaMensual();
        break;
      case "semana":
        this.renderizarVistaSemanal();
        break;
      case "dia":
        this.renderizarVistaDiaria();
        break;
    }
  }

  // Renderizar vista mensual
  renderizarVistaMensual() {
    const calendario = this.elementos.vistaMensual?.querySelector(
      "#calendario-mes tbody"
    );
    if (!calendario) return;

    const fecha = new Date(this.fechaActual);
    const primerDia = new Date(fecha.getFullYear(), fecha.getMonth(), 1);

    // Obtener primer domingo de la vista
    const inicioCalendario = new Date(primerDia);
    inicioCalendario.setDate(primerDia.getDate() - primerDia.getDay());

    let html = "";
    const fechaIteracion = new Date(inicioCalendario);

    for (let semana = 0; semana < 6; semana++) {
      html += "<tr>";

      for (let dia = 0; dia < 7; dia++) {
        const esDelMesActual = fechaIteracion.getMonth() === fecha.getMonth();
        const esHoy = this.esMismaFecha(fechaIteracion, new Date());
        const fechaStr = this.formatearFechaISO(fechaIteracion);

        const fechaClickeable = new Date(fechaIteracion);
        html += `
                    <td class="calendario-dia ${
                      esDelMesActual ? "mes-actual" : "otro-mes"
                    } ${esHoy ? "hoy" : ""}" 
                        data-fecha="${fechaStr}" onclick="onCalendarioDiaClick(new Date('${fechaClickeable.toISOString()}'))" style="cursor: pointer;">
                        <div class="numero-dia">${fechaIteracion.getDate()}</div>
                        <div class="contenido-dia">
                            ${this.obtenerContenidoDia(fechaIteracion)}
                        </div>
                    </td>
                `;

        fechaIteracion.setDate(fechaIteracion.getDate() + 1);
      }

      html += "</tr>";
    }

    calendario.innerHTML = html;
  }

  // Renderizar vista semanal
  renderizarVistaSemanal() {
    const calendario = this.elementos.vistaSemanal?.querySelector(
      "#calendario-semana tbody"
    );
    if (!calendario) return;

    const inicioSemana = this.obtenerInicioSemana(this.fechaActual);

    let html = "";

    // Generar filas por horas
    for (
      let hora = CONFIGURACION_CALENDARIO.HORARIOS.HORA_INICIO;
      hora <= CONFIGURACION_CALENDARIO.HORARIOS.HORA_FIN;
      hora++
    ) {
      html += "<tr>";
      html += `<td class="hora-label">${hora}:00</td>`;

      // Generar celdas para cada día de la semana
      for (let i = 0; i < 7; i++) {
        const fecha = new Date(inicioSemana);
        fecha.setDate(inicioSemana.getDate() + i);
        const fechaStr = this.formatearFechaISO(fecha);
        const horaStr = `${hora.toString().padStart(2, "0")}:00`;

        html += `
                    <td class="celda-hora" data-fecha="${fechaStr}" data-hora="${horaStr}" 
                        onclick="onCalendarioHorarioClick('${fechaStr}', '${horaStr}')" style="cursor: pointer;">
                        ${this.obtenerContenidoHora(fecha, hora)}
                    </td>
                `;
      }

      html += "</tr>";
    }

    calendario.innerHTML = html;

    // Actualizar headers de días
    const headers = this.elementos.vistaSemanal?.querySelectorAll(
      "#calendario-semana thead th"
    );
    if (headers) {
      for (let i = 1; i < headers.length && i <= 7; i++) {
        // Saltar la primera columna (horas)
        const fecha = new Date(inicioSemana);
        fecha.setDate(inicioSemana.getDate() + (i - 1));
        const esHoy = this.esMismaFecha(fecha, new Date());

        headers[i].textContent = `${CONFIGURACION_CALENDARIO.DIAS[
          fecha.getDay()
        ].substring(0, 3)} ${fecha.getDate()}`;
        if (esHoy) {
          headers[i].classList.add("hoy");
        } else {
          headers[i].classList.remove("hoy");
        }
      }
    }
  }

  // Renderizar vista diaria
  renderizarVistaDiaria() {
    const calendario = this.elementos.vistaDiaria?.querySelector(
      "#calendario-dia tbody"
    );
    if (!calendario) return;

    const fecha = this.fechaActual;
    const fechaStr = this.formatearFechaISO(fecha);

    let html = "";

    for (
      let hora = CONFIGURACION_CALENDARIO.HORARIOS.HORA_INICIO;
      hora <= CONFIGURACION_CALENDARIO.HORARIOS.HORA_FIN;
      hora++
    ) {
      const horaStr = `${hora.toString().padStart(2, "0")}:00`;

      html += `
                <tr>
                    <td class="hora-label">${horaStr}</td>
                    <td class="celda-dia" data-fecha="${fechaStr}" data-hora="${horaStr}" 
                        onclick="onCalendarioHorarioClick('${fechaStr}', '${horaStr}')" style="cursor: pointer;">
                        ${this.obtenerContenidoHora(fecha, hora)}
                    </td>
                </tr>
            `;
    }

    calendario.innerHTML = html;

    // Actualizar header del día
    const header = this.elementos.vistaDiaria?.querySelector(
      "#encabezadoVistaDiaria"
    );
    if (header) {
      header.textContent = `${
        CONFIGURACION_CALENDARIO.DIAS[fecha.getDay()]
      }, ${fecha.getDate()} de ${
        CONFIGURACION_CALENDARIO.MESES[fecha.getMonth()]
      }`;
    }
  }

  // Métodos auxiliares
  obtenerInicioSemana(fecha) {
    const inicio = new Date(fecha);
    inicio.setDate(fecha.getDate() - fecha.getDay());
    return inicio;
  }

  esMismaFecha(fecha1, fecha2) {
    return (
      fecha1.getDate() === fecha2.getDate() &&
      fecha1.getMonth() === fecha2.getMonth() &&
      fecha1.getFullYear() === fecha2.getFullYear()
    );
  }

  formatearFechaISO(fecha) {
    return (
      fecha.getFullYear() +
      "-" +
      String(fecha.getMonth() + 1).padStart(2, "0") +
      "-" +
      String(fecha.getDate()).padStart(2, "0")
    );
  }

  // Métodos que pueden ser sobrescritos por clases derivadas
  obtenerContenidoDia(fecha) {
    const fechaStr = this.formatearFechaISO(fecha);

    // Contar confirmadas y pendientes del día
    const confirmadas = this.reservas.filter(
      (r) => r.fecha === fechaStr && r.id_estado === 3
    );
    const pendientes = this.reservas.filter(
      (r) => r.fecha === fechaStr && r.id_estado === 1
    );

    if (confirmadas.length === 0 && pendientes.length === 0) return "";

    let html =
      '<div class="reservas-dia d-flex gap-1 justify-content-center align-items-center" style="padding: 2px;">';

    // Globito verde para confirmadas
    if (confirmadas.length > 0) {
      html += `
        <div class="badge bg-success" 
             onclick="event.stopPropagation(); onCalendarioDiaClick(new Date('${fecha.toISOString()}'))" 
             style="cursor: pointer; font-size: 0.7rem;"
             title="${confirmadas.length} reserva(s) confirmada(s)">
            <i class="bi bi-check-circle"></i> ${confirmadas.length}
        </div>
      `;
    }

    // Globito amarillo para pendientes
    if (pendientes.length > 0) {
      html += `
        <div class="badge bg-warning text-dark" 
             onclick="event.stopPropagation(); onPendientesDiaClick('${fechaStr}')" 
             style="cursor: pointer; font-size: 0.7rem;"
             title="${pendientes.length} solicitud(es) pendiente(s)">
            <i class="bi bi-clock-history"></i> ${pendientes.length}
        </div>
      `;
    }

    html += "</div>";

    return html;
  }

  obtenerContenidoHora(fecha, hora) {
    const fechaStr = this.formatearFechaISO(fecha);
    const horaStr = hora.toString().padStart(2, "0") + ":00:00";

    // Separar reservas por estado
    const reservasActivas = this.reservas.filter((reserva) => {
      return (
        reserva.fecha === fechaStr &&
        reserva.hora_inicio === horaStr &&
        reserva.id_estado === 3
      ); // Solo aceptadas
    });

    const reservasPendientes = this.reservas.filter((reserva) => {
      return (
        reserva.fecha === fechaStr &&
        reserva.hora_inicio === horaStr &&
        reserva.id_estado === 1
      ); // Solo pendientes
    });

    const reservasHistoricas = this.reservas.filter((reserva) => {
      return (
        reserva.fecha === fechaStr &&
        reserva.hora_inicio === horaStr &&
        (reserva.id_estado === 4 || reserva.id_estado === 5)
      ); // Rechazadas y canceladas
    });

    let html = "";

    // Renderizar reservas activas/aceptadas (ocupan espacio completo) - vista DIA y SEMANA
    if (this.vistaActual === "dia" || this.vistaActual === "semana") {
      reservasActivas.forEach((reserva) => {
        const horaInicio = parseInt(reserva.hora_inicio.substring(0, 2));
        const horaFin = parseInt(reserva.hora_fin.substring(0, 2));
        const duracion = horaFin - horaInicio;
        const altura = duracion * 40;

        // En vista dia, dejar espacio para badges a los lados
        const marginLeft = this.vistaActual === "dia" ? "32px" : "2px";
        const marginRight = this.vistaActual === "dia" ? "32px" : "2px";

        html += `
          <div class="reserva-bloque confirmada" 
               onclick="event.stopPropagation(); onReservaClick(${
                 reserva.id_reserva
               })" 
               style="cursor: pointer; position: absolute; top: 0; left: ${marginLeft}; right: ${marginRight}; 
                      height: ${altura}px; z-index: 10; margin-top: 2px; margin-bottom: 2px;">
              <strong>${reserva.titular_nombre_completo}</strong><br>
              <small>${reserva.hora_inicio.substring(
                0,
                5
              )} - ${reserva.hora_fin.substring(0, 5)}</small>
              ${
                duracion > 1
                  ? `<small class="d-block text-muted">(${duracion}h)</small>`
                  : ""
              }
          </div>
        `;
      });

      // Globito de pendientes (vista semana y dia)
      if (reservasPendientes.length > 0) {
        html += `
          <div class="badge bg-warning position-absolute" 
               onclick="event.stopPropagation(); onPendientesClick('${fechaStr}', '${horaStr}')" 
               style="cursor: pointer; top: 2px; right: 2px; z-index: 15;"
               title="${reservasPendientes.length} solicitud(es) pendiente(s)">
              <i class="bi bi-clock-history"></i> ${reservasPendientes.length}
          </div>
        `;
      }

      // Globito de históricas (solo vista dia)
      if (this.vistaActual === "dia" && reservasHistoricas.length > 0) {
        html += `
          <div class="badge bg-secondary position-absolute" 
               onclick="event.stopPropagation(); onHistoricasClick('${fechaStr}', '${horaStr}')" 
               style="cursor: pointer; bottom: 2px; left: 2px; z-index: 15;"
               title="${reservasHistoricas.length} reserva(s) cancelada(s)/rechazada(s)">
              <i class="bi bi-archive"></i> ${reservasHistoricas.length}
          </div>
        `;
      }
    }

    return html;
  }
}

// Exportar para uso en módulos
if (typeof module !== "undefined" && module.exports) {
  module.exports = { CalendarioBase, CONFIGURACION_CALENDARIO };
}
