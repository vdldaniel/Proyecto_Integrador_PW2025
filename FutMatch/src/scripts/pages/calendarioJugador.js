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
    this.datosCancha = null;
    this.horariosCancha = null;
    this.politicasCancha = null;

    this.inicializarEventosJugador();
    this.personalizarCalendarioJugador();
    this.cargarDatosCancha();
  }

  /**
   * Cargar datos de la cancha desde el backend
   */
  async cargarDatosCancha() {
    if (!ID_CANCHA) {
      //console.error("No hay ID de cancha definido");
      return;
    }

    try {
      // Cargar información del perfil
      const responsePerfil = await fetch(
        `${GET_INFO_PERFIL}?id=${ID_CANCHA}&tipo=cancha`
      );
      const dataPerfil = await responsePerfil.json();
      //console.log("Datos del perfil de cancha:", dataPerfil);

      if (dataPerfil.id_cancha || dataPerfil) {
        this.datosCancha = dataPerfil.id_cancha ? dataPerfil : dataPerfil;
        this.actualizarInfoCancha();

        // Cargar políticas si existen
        if (this.datosCancha.politicas_reservas) {
          this.politicasCancha = this.datosCancha.politicas_reservas;
          this.actualizarPoliticas();
        }
      }

      // Cargar horarios
      const responseHorarios = await fetch(
        `${GET_HORARIOS_CANCHAS}?id_cancha=${ID_CANCHA}`
      );
      const dataHorarios = await responseHorarios.json();
      //console.log("Horarios de la cancha:", dataHorarios);

      if (dataHorarios.status === "success" && dataHorarios.data) {
        this.horariosCancha = dataHorarios.data;
        this.actualizarInfoHorarios();
        this.actualizarModalHorarios();
      }

      // Cargar políticas (desde datosCancha)
      if (this.datosCancha && this.datosCancha.politicas_reservas) {
        this.politicasCancha = this.datosCancha.politicas_reservas;
        this.actualizarPoliticas();
      }

      // Cargar disponibilidad (reservas confirmadas)
      await this.cargarDisponibilidad(ID_CANCHA);

      // Cargar reservas automáticamente
      await this.cargarReservas(ID_CANCHA);

      // Actualizar botón de volver al perfil
      const btnVolver = document.getElementById("btnVolverPerfil");
      if (btnVolver) {
        btnVolver.href = `${PAGE_PERFIL_CANCHA_JUGADOR}?id=${ID_CANCHA}`;
      }
    } catch (error) {
      //console.error("Error al cargar datos de la cancha:", error);
    }
  }

  /**
   * Actualizar información de la cancha en el header
   */
  actualizarInfoCancha() {
    if (!this.datosCancha) return;

    const nombreCanchaTexto = document.getElementById("nombreCanchaTexto");
    if (nombreCanchaTexto) {
      nombreCanchaTexto.textContent =
        this.datosCancha.nombre_cancha || "Cancha";
    }

    const detalleCanchaTexto = document.getElementById("detalleCanchaTexto");
    if (detalleCanchaTexto) {
      const superficie = this.datosCancha.tipo_superficie || "N/A";
      detalleCanchaTexto.textContent = `Fútbol 5 • ${superficie}`;
    }

    const subtituloCancha = document.getElementById("subtituloCancha");
    if (subtituloCancha) {
      subtituloCancha.textContent = `Disponibilidad de ${
        this.datosCancha.nombre_cancha || "Cancha"
      }`;
    }

    const nombreCanchaModal = document.getElementById("nombreCanchaModal");
    if (nombreCanchaModal) {
      nombreCanchaModal.textContent =
        this.datosCancha.nombre_cancha || "Cancha";
    }
  }

  /**
   * Actualizar información de horarios
   */
  actualizarInfoHorarios() {
    if (!this.horariosCancha || this.horariosCancha.length === 0) return;

    const textoHorarios = document.getElementById("textoHorarios");
    if (textoHorarios) {
      const diasAbiertos = this.horariosCancha.filter(
        (h) => h.hora_apertura && h.hora_cierre
      );
      if (diasAbiertos.length > 0) {
        const primerHorario = diasAbiertos[0];
        const ultimoHorario = diasAbiertos[diasAbiertos.length - 1];
        const horaInicio = primerHorario.hora_apertura.substring(0, 5);
        const horaFin = ultimoHorario.hora_cierre.substring(0, 5);
        textoHorarios.innerHTML = `<strong>Horarios:</strong> ${horaInicio} - ${horaFin}`;
      }
    }
  }

  /**
   * Actualizar políticas en el modal
   */
  actualizarPoliticas() {
    const contenidoPoliticas = document.getElementById("contenidoPoliticas");
    if (contenidoPoliticas && this.politicasCancha) {
      // Convertir texto con saltos de línea a HTML
      const politicasHTML = this.politicasCancha
        .split("\n")
        .filter((linea) => linea.trim())
        .map((linea) => `<p class="mb-2">${linea}</p>`)
        .join("");

      contenidoPoliticas.innerHTML =
        politicasHTML || '<p class="text-muted">No hay políticas definidas</p>';
    }
  }

  /**
   * Actualizar modal de horarios con tabla día por día
   */
  actualizarModalHorarios() {
    const contenidoHorarios = document.getElementById("contenidoHorarios");
    if (
      !contenidoHorarios ||
      !this.horariosCancha ||
      this.horariosCancha.length === 0
    ) {
      if (contenidoHorarios) {
        contenidoHorarios.innerHTML =
          '<p class="text-muted text-center">No hay horarios definidos</p>';
      }
      return;
    }

    const diasOrdenados = [
      "Lunes",
      "Martes",
      "Miércoles",
      "Jueves",
      "Viernes",
      "Sábado",
      "Domingo",
    ];

    //console.log("Horarios para modal:", this.horariosCancha);

    let html = '<div class="list-group">';

    diasOrdenados.forEach((dia) => {
      const horario = this.horariosCancha.find(
        (h) => h.dia_nombre?.trim().toLowerCase() === dia.toLowerCase()
      );

      if (horario && horario.hora_apertura && horario.hora_cierre) {
        const apertura = horario.hora_apertura.substring(0, 5);
        const cierre = horario.hora_cierre.substring(0, 5);

        html += `
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fw-bold">${dia}</span>
            <span class="badge bg-success">${apertura} - ${cierre}</span>
          </div>
        `;
      } else {
        html += `
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <span class="fw-bold text-muted">${dia}</span>
            <span class="badge bg-secondary">Cerrado</span>
          </div>
        `;
      }
    });

    html += "</div>";
    contenidoHorarios.innerHTML = html;
  }

  /**
   * Cargar disponibilidad de la cancha (reservas confirmadas)
   */
  async cargarDisponibilidad(idCancha) {
    if (!idCancha) {
      /*console.error(
        "No se proporcionó ID de cancha para cargar disponibilidad"
      );*/
      return;
    }

    try {
      const response = await fetch(
        `${GET_DISPONIBILIDAD}?id_cancha=${idCancha}`
      );
      const data = await response.json();

      if (data.status === "success" && data.data) {
        this.disponibilidad = data.data;
        //console.log("Disponibilidad cargada:", this.disponibilidad);
      } else {
        /*console.warn(
          "No se pudo cargar la disponibilidad:",
          data.error || "Error desconocido"
        );*/
        this.disponibilidad = [];
      }
    } catch (error) {
      //console.error("Error al cargar disponibilidad:", error);
      this.disponibilidad = [];
    }
  }

  personalizarCalendarioJugador() {
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

    // Los botones de políticas y horarios ahora usan data-bs-toggle directamente en el HTML
    // No necesitamos event listeners adicionales que causan conflictos

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

          // Verificar si la hora está dentro del horario de la cancha
          const estaDisponible = this.esHorarioDisponible(
            this.fechaActual,
            horaCompleta
          );
          const estaDentroHorario = this.estaHoraDentroHorarioCancha(
            this.fechaActual,
            horaCompleta
          );

          if (estaDisponible && estaDentroHorario) {
            celdaContenido.innerHTML = `
                            <button class="btn btn-dark btn-sm w-100 btnSeleccionarHorario" 
                                    data-fecha="${this.formatearFechaISO(
                                      this.fechaActual
                                    )}" data-hora="${horaCompleta}">
                                <i class="bi bi-plus-circle"></i> Disponible
                            </button>
                        `;
          } else if (!estaDentroHorario) {
            // Mostrar que está fuera del horario de la cancha
            celdaContenido.innerHTML = `
              <div class="badge text-bg-secondary w-100 p-2">
                  <i class="bi bi-lock"></i> Cerrado
              </div>
            `;
          } else {
            const reserva = this.obtenerReserva(this.fechaActual, horaCompleta);
            if (reserva) {
              // Mostrar información según permisos
              let textoReserva = "Reservado";
              if (reserva.es_mi_reserva && reserva.titular_nombre_completo) {
                textoReserva = `Mi Reserva - ${reserva.titular_nombre_completo}`;
              } else if (reserva.titular_nombre_completo) {
                // Si tiene nombre es porque es el titular o creador
                textoReserva = `Reservado - ${reserva.titular_nombre_completo}`;
              }

              celdaContenido.innerHTML = `
                <div class="badge text-bg-dark w-100 p-2">
                    <i class="bi bi-x-circle"></i> ${textoReserva}
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
    const celdas = document.querySelectorAll(
      "#calendario-mes tbody td[data-fecha]"
    );
    celdas.forEach((celda) => {
      const fechaParts = celda.dataset.fecha.split("-");
      const fecha = new Date(fechaParts[0], fechaParts[1] - 1, fechaParts[2]);
      const disponibilidad = this.obtenerDisponibilidadDia(fecha);

      if (disponibilidad.total > 0 && disponibilidad.disponibles > 0) {
        const indicador = document.createElement("div");
        indicador.className = "badge bg-success mt-1";
        indicador.style.fontSize = "0.7rem";
        indicador.innerHTML = `${disponibilidad.disponibles}h disponible${
          disponibilidad.disponibles !== 1 ? "s" : ""
        }`;
        celda.appendChild(indicador);
      } else if (disponibilidad.total > 0 && disponibilidad.disponibles === 0) {
        const indicador = document.createElement("div");
        indicador.className = "badge bg-danger mt-1";
        indicador.style.fontSize = "0.7rem";
        indicador.innerHTML = "Completo";
        celda.appendChild(indicador);
      }
    });
  }

  obtenerDisponibilidadDia(fecha) {
    const fechaString = this.formatearFechaISO(fecha);

    // Verificar si es un día en el pasado
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    if (fecha < hoy) {
      return { disponibles: 0, reservados: 0, total: 0 };
    }

    // Obtener horarios de la cancha para este día
    const diasSemana = [
      "Domingo",
      "Lunes",
      "Martes",
      "Miércoles",
      "Jueves",
      "Viernes",
      "Sábado",
    ];
    const diaNombre = diasSemana[fecha.getDay()];
    const horarioDia = this.horariosCancha?.find(
      (h) => h.dia_nombre?.trim().toLowerCase() === diaNombre.toLowerCase()
    );

    // Si no hay horario o está cerrado, no hay disponibilidad
    if (!horarioDia || !horarioDia.hora_apertura || !horarioDia.hora_cierre) {
      return { disponibles: 0, reservados: 0, total: 0 };
    }

    // Calcular horas totales del día
    const horaApertura = parseInt(horarioDia.hora_apertura.substring(0, 2));
    const horaCierre = parseInt(horarioDia.hora_cierre.substring(0, 2));
    const total = horaCierre - horaApertura;

    // Calcular horas reservadas usando disponibilidad
    let reservados = 0;
    if (this.disponibilidad && this.disponibilidad.length > 0) {
      this.disponibilidad.forEach((reserva) => {
        if (reserva.fecha === fechaString) {
          const horaInicio = parseInt(reserva.hora_inicio.substring(0, 2));
          const horaFin = parseInt(reserva.hora_fin.substring(0, 2));
          reservados += horaFin - horaInicio;
        }
      });
    }

    const disponibles = Math.max(0, total - reservados);

    return { disponibles, reservados, total };
  }

  /**
   * Verificar si una hora está dentro del horario de operación de la cancha
   */
  estaHoraDentroHorarioCancha(fecha, hora) {
    if (!this.horariosCancha || this.horariosCancha.length === 0) {
      return true; // Si no hay horarios definidos, permitir todas las horas
    }

    const fechaObj = new Date(fecha);
    const diaActual = fechaObj.getDay() === 0 ? 7 : fechaObj.getDay();
    const horarioHoy = this.horariosCancha.find((h) => h.id_dia === diaActual);

    if (!horarioHoy || !horarioHoy.hora_apertura || !horarioHoy.hora_cierre) {
      return false; // Cancha cerrada ese día
    }

    const horaNum = parseInt(hora.split(":")[0]);
    const aperturaNum = parseInt(horarioHoy.hora_apertura.split(":")[0]);
    const cierreNum = parseInt(horarioHoy.hora_cierre.split(":")[0]);

    return horaNum >= aperturaNum && horaNum < cierreNum;
  }

  esHorarioDisponible(fecha, hora) {
    const fechaString = this.formatearFechaISO(fecha);
    const hoy = new Date();

    // No permitir reservas en el pasado
    const fechaObj = new Date(fecha);
    if (
      fechaObj < hoy ||
      (fechaObj.toDateString() === hoy.toDateString() &&
        parseInt(hora) <= hoy.getHours())
    ) {
      return false;
    }

    // Verificar si está fuera de los horarios de la cancha
    if (!this.estaHoraDentroHorarioCancha(fecha, hora)) {
      return false;
    }

    // Verificar si ya está reservado usando disponibilidad (reservas confirmadas)
    if (this.disponibilidad && this.disponibilidad.length > 0) {
      const reservado = this.disponibilidad.some((reserva) => {
        if (reserva.fecha !== fechaString) return false;

        // Verificar si la hora está dentro del rango de la reserva
        const horaInicio = reserva.hora_inicio.substring(0, 5);
        const horaFin = reserva.hora_fin.substring(0, 5);
        const horaActual =
          typeof hora === "string"
            ? hora
            : `${hora.toString().padStart(2, "0")}:00`;
        return horaActual >= horaInicio && horaActual < horaFin;
      });

      return !reservado;
    }

    return true; // Si no hay disponibilidad cargada, asumir disponible
  }

  obtenerReserva(fecha, hora) {
    const fechaString =
      typeof fecha === "string" ? fecha : this.formatearFechaISO(fecha);

    return this.reservas.find((reserva) => {
      if (reserva.fecha !== fechaString) return false;

      const horaInicio = reserva.hora_inicio.substring(0, 5);
      const horaFin = reserva.hora_fin.substring(0, 5);
      return hora >= horaInicio && hora < horaFin;
    });
  }

  /**
   * Override para mostrar bloques "RESERVADO" en lugar de detalles
   */
  obtenerContenidoHora(fecha, hora) {
    const fechaStr = this.formatearFechaISO(fecha);
    const horaStr = hora.toString().padStart(2, "0") + ":00:00";

    let html = "";

    // Buscar en disponibilidad (reservas confirmadas)
    if (this.disponibilidad && this.disponibilidad.length > 0) {
      const reservaConfirmada = this.disponibilidad.find((reserva) => {
        return reserva.fecha === fechaStr && reserva.hora_inicio === horaStr;
      });

      if (reservaConfirmada) {
        const horaInicio = parseInt(
          reservaConfirmada.hora_inicio.substring(0, 2)
        );
        const horaFin = parseInt(reservaConfirmada.hora_fin.substring(0, 2));
        const duracion = horaFin - horaInicio;
        const altura = duracion * 40;

        const marginLeft = this.vistaActual === "dia" ? "32px" : "2px";
        const marginRight = this.vistaActual === "dia" ? "32px" : "2px";

        html += `
          <div class="reserva-bloque confirmada" 
               style="cursor: not-allowed; position: absolute; top: 0; left: ${marginLeft}; right: ${marginRight}; 
                      height: ${altura}px; z-index: 10; margin-top: 2px; margin-bottom: 2px;">
              <strong>RESERVADO</strong><br>
              <small>${reservaConfirmada.hora_inicio.substring(
                0,
                5
              )} - ${reservaConfirmada.hora_fin.substring(0, 5)}</small>
              ${
                duracion > 1
                  ? `<small class="d-block text-muted">(${duracion}h)</small>`
                  : ""
              }
          </div>
        `;
      }
    }

    return html;
  }

  seleccionarHorario(fecha, hora) {
    if (typeof fecha === "string") {
      this.fechaSeleccionada = fecha;
    } else {
      this.fechaSeleccionada = this.formatearFechaISO(fecha);
    }

    this.horarioSeleccionado = hora;
    /*
    console.log(
      "Horario seleccionado:",
      this.fechaSeleccionada,
      this.horarioSeleccionado
    );*/

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
    // Pre-llenar fecha si se proporciona
    const fechaComienzo = document.getElementById("fechaComienzo");
    const fechaFin = document.getElementById("fechaFin");
    const horaInicio = document.getElementById("horaInicio");
    const horaFin = document.getElementById("horaFin");

    if (fechaComienzo) {
      let fechaSeleccionada;
      if (fecha) {
        // Si fecha es un string ISO, usarlo directamente
        if (typeof fecha === "string") {
          fechaSeleccionada = fecha;
        } else {
          fechaSeleccionada = fecha.toISOString().split("T")[0];
        }
      } else {
        // Establecer mañana por defecto
        const hoy = new Date();
        const mañana = new Date(hoy);
        mañana.setDate(hoy.getDate() + 1);
        fechaSeleccionada = mañana.toISOString().split("T")[0];
      }
      fechaComienzo.value = fechaSeleccionada;
      if (fechaFin) fechaFin.value = fechaSeleccionada; // Por defecto, mismo día
    }

    // Cargar opciones de horarios basados en los horarios de la cancha
    this.cargarOpcionesHorarios(horaInicio, horaFin);

    // Pre-llenar hora si se proporciona
    if (horaInicio && hora) {
      horaInicio.value = hora.length === 5 ? hora : hora.substring(0, 5);
    }

    // Mostrar modal
    const modalElement = document.getElementById("modalReservarCancha");
    if (modalElement) {
      const modal = new bootstrap.Modal(modalElement);
      modal.show();
    }
  }

  /**
   * Cargar opciones de hora_inicio y hora_fin basadas en horarios de la cancha
   */
  cargarOpcionesHorarios(selectInicio, selectFin) {
    if (!selectInicio) return;

    // Obtener rango de horas de los horarios de la cancha
    let horaMin = 8; // Hora por defecto del sistema
    let horaMax = 22;

    if (this.horariosCancha && this.horariosCancha.length > 0) {
      // Encontrar hora mínima y máxima de apertura/cierre
      const horasApertura = this.horariosCancha
        .filter((h) => h.hora_apertura)
        .map((h) => parseInt(h.hora_apertura.substring(0, 2)));
      const horasCierre = this.horariosCancha
        .filter((h) => h.hora_cierre)
        .map((h) => parseInt(h.hora_cierre.substring(0, 2)));

      if (horasApertura.length > 0) horaMin = Math.min(...horasApertura);
      if (horasCierre.length > 0) horaMax = Math.max(...horasCierre);
    }

    // Generar opciones de hora
    let optionsHTML = '<option value="">Seleccionar hora</option>';
    for (let h = horaMin; h <= horaMax; h++) {
      const horaStr = h.toString().padStart(2, "0") + ":00";
      optionsHTML += `<option value="${horaStr}">${horaStr}</option>`;
    }

    selectInicio.innerHTML = optionsHTML;
    if (selectFin) selectFin.innerHTML = optionsHTML;
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

  async enviarSolicitudReserva() {
    // Obtener datos del formulario
    const fechaInicio = document.getElementById("fechaComienzo").value;
    const fechaFin = document.getElementById("fechaFin")?.value || fechaInicio;
    const horaInicio = document.getElementById("horaInicio").value;
    const horaFin = document.getElementById("horaFin").value;
    const titulo =
      document.getElementById("tituloReserva")?.value || "Reserva de jugador";
    const descripcion =
      document.getElementById("comentariosReserva").value || "";

    // Validar campos obligatorios
    if (!fechaInicio || !horaInicio || !horaFin) {
      showToast(
        "Por favor, complete todos los campos obligatorios (Fecha, Hora inicio, Hora fin)",
        "warning"
      );
      return;
    }

    // Validar que hora_fin sea mayor que hora_inicio
    if (horaFin <= horaInicio) {
      showToast(
        "La hora de fin debe ser posterior a la hora de inicio",
        "warning"
      );
      return;
    }

    // Preparar datos para enviar
    const formData = new FormData();
    formData.append("id_cancha", ID_CANCHA);
    formData.append("id_tipo_reserva", 1); // Tipo 1 por defecto (ajustar según necesidad)
    formData.append("fecha", fechaInicio);
    formData.append("fecha_fin", fechaFin);
    formData.append("hora_inicio", horaInicio);
    formData.append("hora_fin", horaFin);
    formData.append("titulo", titulo);
    formData.append("descripcion", descripcion);
    // El backend determinará el username desde la sesión

    try {
      const response = await fetch(POST_RESERVA, {
        method: "POST",
        body: formData,
      });

      // Obtener el texto de la respuesta primero para debug
      const responseText = await response.text();
      //console.log("Respuesta del servidor:", responseText);

      // Intentar parsear como JSON
      let data;
      try {
        data = JSON.parse(responseText);
      } catch (parseError) {
        //console.error("Error al parsear JSON. Respuesta recibida:", responseText);
        showToast(
          "Error: El servidor no devolvió un formato válido",
          "error",
          5000
        );
        return;
      }

      if (response.ok && data.success) {
        // Cerrar modal
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("modalReservarCancha")
        );
        modal.hide();

        // Mostrar mensaje de éxito
        showToast(
          "¡Solicitud enviada con éxito! El administrador revisará tu solicitud",
          "success",
          4000
        );

        // Limpiar formulario
        document.getElementById("formReservarCancha").reset();

        // Recargar disponibilidad
        await this.cargarDisponibilidad(ID_CANCHA);
        this.renderizarVistaActual();
      } else {
        // Mostrar error
        const errorMsg = data.error || "Error al crear la reserva";
        showToast(`Error: ${errorMsg}`, "error", 5000);
        //console.error("Error al crear reserva:", data);
      }
    } catch (error) {
      //console.error("Error al enviar solicitud:", error);
      showToast(
        "Error al enviar la solicitud. Por favor, intente nuevamente",
        "error",
        4000
      );
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
}

/**
 * Mostrar modal de confirmación simple
 * @param {string} mensaje - Mensaje a mostrar
 * @param {string} titulo - Título del modal (opcional)
 * @returns {Promise<boolean>} - true si confirma, false si cancela
 */
function mostrarConfirmacion(mensaje, titulo = "Confirmar") {
  return new Promise((resolve) => {
    const modal = document.getElementById("modalConfirmacion");
    const modalTitulo = document.getElementById("modalConfirmacionTitulo");
    const modalMensaje = document.getElementById("modalConfirmacionMensaje");
    const btnConfirmar = document.getElementById("btnConfirmarAccion");

    if (!modal || !modalTitulo || !modalMensaje || !btnConfirmar) {
      // Fallback a confirm nativo si el modal no existe
      resolve(confirm(mensaje));
      return;
    }

    // Establecer contenido
    modalTitulo.textContent = titulo;
    modalMensaje.textContent = mensaje;

    // Mostrar modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    // Handler para confirmar
    const handleConfirm = () => {
      bsModal.hide();
      btnConfirmar.removeEventListener("click", handleConfirm);
      modal.removeEventListener("hidden.bs.modal", handleCancel);
      resolve(true);
    };

    // Handler para cancelar
    const handleCancel = () => {
      btnConfirmar.removeEventListener("click", handleConfirm);
      modal.removeEventListener("hidden.bs.modal", handleCancel);
      resolve(false);
    };

    // Asignar eventos
    btnConfirmar.addEventListener("click", handleConfirm);
    modal.addEventListener("hidden.bs.modal", handleCancel, { once: true });
  });
}

// Función global para enviar solicitud de reserva (mantenida para compatibilidad)
function enviarSolicitudReserva() {
  if (calendarioJugador) {
    calendarioJugador.enviarSolicitudReserva();
  }
}

// Sobrescribir funciones globales del calendario para funcionalidad de jugador
function onCalendarioDiaClick(fecha) {
  //console.log("Día seleccionado:", fecha);
  if (calendarioJugador) {
    try {
      // Cambiar a vista diaria del día seleccionado
      calendarioJugador.fechaActual = new Date(fecha);
      calendarioJugador.cambiarVista("dia");
      //console.log("Vista cambiada a día:", fecha);
    } catch (error) {
      //console.error("Error al cambiar a vista diaria:", error);
    }
  } else {
    //console.warn("calendarioJugador no está inicializado");
  }
}

function onCalendarioHorarioClick(fecha, hora) {
  //console.log("Horario seleccionado:", fecha, hora);
  if (calendarioJugador) {
    try {
      // Abrir modal de reserva con la fecha y hora seleccionadas
      calendarioJugador.seleccionarHorario(fecha, hora);
      //console.log("Modal de reserva abierto para:", fecha, hora);
    } catch (error) {
      //console.error("Error al abrir modal de reserva:", error);
    }
  } else {
    //console.warn("calendarioJugador no está inicializado");
  }
}

// Instancia global para el calendario del jugador
let calendarioJugador;

// Inicialización cuando se carga la página
document.addEventListener("DOMContentLoaded", function () {
  calendarioJugador = new AplicacionCalendarioJugador();
});
