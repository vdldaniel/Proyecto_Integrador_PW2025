/**
 * Agenda Admin JavaScript
 * Funcionalidades específicas del admin de cancha que extienden el calendario base
 */

// Clase específica para admin de cancha que extiende CalendarioBase
class AplicacionAgendaAdmin extends CalendarioBase {
  constructor() {
    super();

    // Datos específicos del admin
    this.solicitudesPendientes = [
      {
        id: 1,
        jugador: {
          nombre: "Juan Pérez",
          telefono: "+54 11 2345-6789",
          id: 101,
        },
        cancha: {
          id: 1,
          nombre: "Cancha A",
        },
        fecha: "2025-11-05",
        hora: "16:00",
        fechaSolicitud: "2025-11-04",
        estado: "pending",
      },
      {
        id: 2,
        jugador: {
          nombre: "María González",
          telefono: "+54 11 8765-4321",
          id: 102,
        },
        cancha: {
          id: 2,
          nombre: "Cancha B",
        },
        fecha: "2025-11-06",
        hora: "14:00",
        fechaSolicitud: "2025-11-04",
        estado: "pending",
      },
    ];

    this.configuracionCancha = {
      horaApertura: "08:00",
      horaCierre: "22:00",
      diasOperacion: {
        lunes: true,
        martes: true,
        miercoles: true,
        jueves: true,
        viernes: true,
        sabado: true,
        domingo: true,
      },
      canchaInfo: {
        nombre: "Complejo Deportivo Central",
        direccion: "Av. Principal 123, Buenos Aires",
        telefono: "+54 11 1234-5678",
      },
    };

    // Inicializar funcionalidades específicas del admin
    this.inicializarFuncionalidadesAdmin();
  }

  inicializarFuncionalidadesAdmin() {
    this.configurarEventosAdmin();
    this.renderizarSolicitudesModal();
    this.renderizarModalConfiguracion();
    this.actualizarContadorNotificaciones();
    this.configurarEventListenersModal();
    this.generarDatosMuestra();
    this.cargarCanchas();
    this.cargarTiposReserva();
  }

  // Cargar canchas del admin desde el controller
  async cargarCanchas() {
    try {
      const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
      const resultado = await response.json();

      // El controller retorna {status, data}
      const canchas = resultado.data || resultado;

      const selectCancha = document.getElementById("canchaReserva");
      if (selectCancha && canchas.length > 0) {
        selectCancha.innerHTML =
          '<option value="">Seleccione una cancha...</option>';
        canchas.forEach((cancha) => {
          const option = document.createElement("option");
          option.value = cancha.id_cancha;
          option.textContent = cancha.nombre;
          selectCancha.appendChild(option);
        });
      } else if (selectCancha) {
        selectCancha.innerHTML =
          '<option value="">No hay canchas disponibles</option>';
      }
    } catch (error) {
      console.error("Error al cargar canchas:", error);
    }
  }

  // Cargar tipos de reserva desde el controller
  async cargarTiposReserva() {
    try {
      const response = await fetch(GET_TIPOS_RESERVA);
      const tipos = await response.json();

      const selectTipo = document.getElementById("tipoReserva");
      if (selectTipo && tipos.length > 0) {
        selectTipo.innerHTML =
          '<option value="">Seleccione un tipo...</option>';
        tipos.forEach((tipo) => {
          const option = document.createElement("option");
          option.value = tipo.id_tipo_reserva;
          option.textContent = tipo.nombre;
          if (tipo.descripcion) {
            option.title = tipo.descripcion;
          }
          selectTipo.appendChild(option);
        });
      }
    } catch (error) {
      console.error("Error al cargar tipos de reserva:", error);
    }
  }

  // Configurar eventos específicos del admin
  configurarEventosAdmin() {
    // Configurar botón "Crear Reserva"
    const botonCrearReserva = document.getElementById("botonCrearReserva");
    if (botonCrearReserva) {
      botonCrearReserva.addEventListener("click", () => {
        this.crearReserva();
      });
    }

    // Configurar búsqueda de reservas
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
      searchInput.addEventListener("input", (e) => {
        this.filtrarReservas(e.target.value);
      });
    }

    // Configurar reset del modal al cerrarse
    const modal = document.getElementById("modalGestionReserva");
    if (modal) {
      modal.addEventListener("hidden.bs.modal", () => {
        this.limpiarFormularioReserva();
      });
    }
  }

  // Override de callbacks del componente calendario para funcionalidades del admin
  obtenerContenidoDia(fecha) {
    const fechaStr = this.formatearFechaISO(fecha);
    const reservasDelDia = this.reservas.filter((r) => r.fecha === fechaStr);

    if (reservasDelDia.length === 0) return "";

    return `
            <div class="day-reservas">
                ${reservasDelDia
                  .slice(0, 2)
                  .map(
                    (reserva) => `
                    <div class="reserva-mini ${
                      reserva.estado
                    }" onclick="verEditarReserva(${reserva.id})" title="${
                      reserva.cliente
                    } - ${reserva.hora}">
                        ${reserva.hora.substring(0, 5)}
                    </div>
                `
                  )
                  .join("")}
                ${
                  reservasDelDia.length > 2
                    ? `<div class="reserva-mini mas">+${
                        reservasDelDia.length - 2
                      }</div>`
                    : ""
                }
            </div>
        `;
  }

  obtenerContenidoHora(fecha, hora) {
    const fechaStr = this.formatearFechaISO(fecha);
    const horaStr = `${hora.toString().padStart(2, "0")}:00`;
    const reserva = this.reservas.find(
      (r) => r.fecha === fechaStr && r.hora === horaStr
    );

    if (!reserva) return "";

    return `
            <div class="reserva-bloque ${reserva.estado}" onclick="verEditarReserva(${reserva.id})">
                <div class="reserva-cliente">${reserva.cliente}</div>
                <div class="reserva-cancha">${reserva.cancha}</div>
            </div>
        `;
  }

  // Renderizar solicitudes en el modal dinámicamente
  renderizarSolicitudesModal() {
    const listaSolicitudes = document.getElementById("listaSolicitudes");
    const sinSolicitudes = document.getElementById("sinSolicitudes");

    if (!listaSolicitudes) return;

    if (this.solicitudesPendientes.length === 0) {
      listaSolicitudes.classList.add("d-none");
      if (sinSolicitudes) sinSolicitudes.classList.remove("d-none");
      return;
    }

    listaSolicitudes.classList.remove("d-none");
    if (sinSolicitudes) sinSolicitudes.classList.add("d-none");

    listaSolicitudes.innerHTML = this.solicitudesPendientes
      .map((solicitud) => {
        const fechaSolicitud = new Date(solicitud.fecha);
        const fechaTexto = this.formatearFechaSolicitud(fechaSolicitud);

        return `
                <div class="card mb-3 border-warning" data-solicitud-id="${solicitud.id}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${solicitud.jugador.nombre}</strong> - <span class="text-warning">${solicitud.cancha.nombre}</span>
                            <br><small class="text-muted">${fechaTexto} a las ${solicitud.hora}</small>
                        </div>
                        <div>
                            <button class="btn btn-success btn-sm me-1" onclick="aceptarSolicitud(${solicitud.id})">
                                <i class="bi bi-check-lg"></i> Aceptar
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="rechazarSolicitud(${solicitud.id})">
                                <i class="bi bi-x-lg"></i> Rechazar
                            </button>
                        </div>
                    </div>
                    <div class="collapse" id="detallesSolicitud${solicitud.id}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Teléfono:</strong> ${solicitud.jugador.telefono}
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-dark btn-sm" onclick="verPerfilJugador(${solicitud.jugador.id})">
                                        <i class="bi bi-person"></i> Ver perfil jugador
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-link btn-sm p-0" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#detallesSolicitud${solicitud.id}">
                            <i class="bi bi-chevron-down"></i> Ver detalles
                        </button>
                    </div>
                </div>
            `;
      })
      .join("");
  }

  // Formatear fecha para mostrar en solicitudes
  formatearFechaSolicitud(fecha) {
    const hoy = new Date();
    const manana = new Date(hoy);
    manana.setDate(hoy.getDate() + 1);

    if (fecha.toDateString() === hoy.toDateString()) {
      return (
        "Hoy " +
        fecha.toLocaleDateString("es-AR", { day: "2-digit", month: "2-digit" })
      );
    } else if (fecha.toDateString() === manana.toDateString()) {
      return (
        "Mañana " +
        fecha.toLocaleDateString("es-AR", { day: "2-digit", month: "2-digit" })
      );
    } else {
      return fecha.toLocaleDateString("es-AR", {
        day: "2-digit",
        month: "2-digit",
      });
    }
  }

  // Actualizar contador de notificaciones
  actualizarContadorNotificaciones() {
    const badges = document.querySelectorAll(".badge.bg-danger");
    const cantidad = this.solicitudesPendientes.length;

    badges.forEach((badge) => {
      if (cantidad > 0) {
        badge.textContent = cantidad.toString();
        badge.classList.remove("d-none");
      } else {
        badge.classList.add("d-none");
      }
    });
  }

  // Configurar modal de configuración con datos y validación
  renderizarModalConfiguracion() {
    this.cargarCanchasConfiguracion();
    this.configurarEventosConfiguracion();
  }

  // Cargar canchas en el select de configuración
  async cargarCanchasConfiguracion() {
    try {
      const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
      const resultado = await response.json();
      const canchas = resultado.data || resultado;

      const selectCancha = document.getElementById("canchaConfiguracion");
      if (selectCancha && canchas.length > 0) {
        selectCancha.innerHTML =
          '<option value="">Seleccione una cancha...</option>';
        canchas.forEach((cancha) => {
          const option = document.createElement("option");
          option.value = cancha.id_cancha;
          option.textContent = cancha.nombre;
          option.dataset.info = JSON.stringify(cancha);
          selectCancha.appendChild(option);
        });
      }
    } catch (error) {
      console.error("Error al cargar canchas:", error);
    }
  }

  // Configurar eventos del modal de configuración
  configurarEventosConfiguracion() {
    const selectCancha = document.getElementById("canchaConfiguracion");
    if (selectCancha) {
      selectCancha.addEventListener("change", () => {
        this.cargarConfiguracionCancha(selectCancha.value);
      });
    }

    const botonGuardar = document.getElementById("botonGuardarConfiguracion");
    if (botonGuardar) {
      botonGuardar.addEventListener("click", () => {
        this.guardarConfiguracionCancha();
      });
    }

    const botonPoliticas = document.getElementById("botonPoliticasReservas");
    if (botonPoliticas) {
      botonPoliticas.addEventListener("click", () => {
        this.abrirModalPoliticas();
      });
    }

    const botonGuardarPoliticas = document.getElementById(
      "botonGuardarPoliticas"
    );
    if (botonGuardarPoliticas) {
      botonGuardarPoliticas.addEventListener("click", () => {
        this.guardarPoliticasReservas();
      });
    }

    const botonVerPerfil = document.getElementById("botonVerPerfilCancha");
    if (botonVerPerfil) {
      botonVerPerfil.addEventListener("click", () => {
        const selectCancha = document.getElementById("canchaConfiguracion");
        const idCancha = selectCancha.value;
        if (idCancha) {
          window.location.href = `${PAGE_MIS_PERFILES_ADMIN_CANCHA}?id_cancha=${idCancha}`;
        }
      });
    }
  }

  // Cargar configuración de la cancha seleccionada
  async cargarConfiguracionCancha(idCancha) {
    if (!idCancha) {
      document.getElementById("horariosContainer").innerHTML = `
        <div class="text-muted text-center py-3">
          <i class="bi bi-info-circle me-2"></i>
          Seleccione una cancha para configurar sus horarios
        </div>`;
      document.getElementById("seccionInfoCancha").style.display = "none";
      return;
    }

    try {
      // Obtener horarios de la cancha
      const responseHorarios = await fetch(
        `${GET_HORARIOS_CANCHAS}?id_cancha=${idCancha}`
      );
      const resultadoHorarios = await responseHorarios.json();
      const horarios = resultadoHorarios.data || [];

      // Obtener info de la cancha del select
      const selectCancha = document.getElementById("canchaConfiguracion");
      const optionSeleccionada =
        selectCancha.options[selectCancha.selectedIndex];
      const infoCancha = JSON.parse(optionSeleccionada.dataset.info || "{}");

      // Mostrar información de la cancha
      document.getElementById("nombreCancha").textContent =
        infoCancha.nombre || "-";
      document.getElementById("direccionCancha").textContent =
        infoCancha.direccion_completa || "-";
      document.getElementById("telefonoCancha").textContent =
        infoCancha.telefono || "-";
      document.getElementById("seccionInfoCancha").style.display = "block";

      // Renderizar horarios por día
      this.renderizarHorariosDias(horarios);
    } catch (error) {
      console.error("Error al cargar configuración:", error);
      showToast("Error al cargar la configuración de la cancha", "error");
    }
  }

  // Renderizar horarios por día de la semana
  renderizarHorariosDias(horarios) {
    const dias = [
      { id: 1, nombre: "Lunes" },
      { id: 2, nombre: "Martes" },
      { id: 3, nombre: "Miércoles" },
      { id: 4, nombre: "Jueves" },
      { id: 5, nombre: "Viernes" },
      { id: 6, nombre: "Sábado" },
      { id: 7, nombre: "Domingo" },
    ];

    const container = document.getElementById("horariosContainer");
    container.innerHTML = "";

    dias.forEach((dia) => {
      const horarioDia = horarios.find((h) => h.id_dia === dia.id);

      // Un día está activo si existe Y tiene horarios (no NULL)
      const activo =
        horarioDia !== undefined &&
        horarioDia.hora_apertura !== null &&
        horarioDia.hora_cierre !== null;

      const horaApertura =
        horarioDia && horarioDia.hora_apertura
          ? horarioDia.hora_apertura.substring(0, 5)
          : "";
      const horaCierre =
        horarioDia && horarioDia.hora_cierre
          ? horarioDia.hora_cierre.substring(0, 5)
          : "";

      const diaHtml = `
        <div class="row mb-3 align-items-center" data-dia-id="${dia.id}">
          <div class="col-md-3">
            <div class="form-check">
              <input class="form-check-input dia-checkbox" type="checkbox" 
                     id="dia_${dia.id}" ${activo ? "checked" : ""}>
              <label class="form-check-label" for="dia_${dia.id}">
                ${dia.nombre}
              </label>
            </div>
          </div>
          <div class="col-md-3">
            <input type="time" class="form-control hora-apertura" 
                   value="${activo ? horaApertura : ""}" ${
        !activo ? "disabled" : ""
      }>
          </div>
          <div class="col-md-1 text-center">-</div>
          <div class="col-md-3">
            <input type="time" class="form-control hora-cierre" 
                   value="${activo ? horaCierre : ""}" ${
        !activo ? "disabled" : ""
      }>
            <div class="invalid-feedback">
              La hora de cierre debe ser posterior a la de apertura
            </div>
          </div>
          <div class="col-md-2 text-center">
            <i class="bi bi-exclamation-triangle-fill text-warning icono-cerrado ${
              activo ? "d-none" : ""
            }" 
               data-bs-toggle="tooltip" data-bs-placement="top" title="Cancha cerrada"></i>
          </div>
        </div>
      `;
      container.insertAdjacentHTML("beforeend", diaHtml);
    });

    // Configurar eventos para habilitar/deshabilitar campos
    container.querySelectorAll(".dia-checkbox").forEach((checkbox) => {
      checkbox.addEventListener("change", (e) => {
        const row = e.target.closest(".row");
        const horaApertura = row.querySelector(".hora-apertura");
        const horaCierre = row.querySelector(".hora-cierre");
        const iconoCerrado = row.querySelector(".icono-cerrado");

        if (e.target.checked) {
          // Habilitar campos y ocultar icono
          horaApertura.disabled = false;
          horaCierre.disabled = false;
          iconoCerrado.classList.add("d-none");

          // Poner horarios por defecto si están vacíos
          if (!horaApertura.value) horaApertura.value = "08:00";
          if (!horaCierre.value) horaCierre.value = "22:00";
        } else {
          // Deshabilitar campos, limpiarlos y mostrar icono
          horaApertura.disabled = true;
          horaCierre.disabled = true;
          horaApertura.value = "";
          horaCierre.value = "";
          horaCierre.classList.remove("is-invalid");
          iconoCerrado.classList.remove("d-none");
        }
      });
    });

    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = container.querySelectorAll(
      '[data-bs-toggle="tooltip"]'
    );
    [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );

    // Configurar validación de horarios
    container
      .querySelectorAll(".hora-apertura, .hora-cierre")
      .forEach((input) => {
        input.addEventListener("change", () => {
          this.validarHorarioDia(input.closest(".row"));
        });
      });
  }

  // Validar horario de un día específico
  validarHorarioDia(row) {
    const horaApertura = row.querySelector(".hora-apertura");
    const horaCierre = row.querySelector(".hora-cierre");
    const checkbox = row.querySelector(".dia-checkbox");

    if (!checkbox.checked) {
      horaCierre.classList.remove("is-invalid");
      return true;
    }

    if (horaApertura.value >= horaCierre.value) {
      horaCierre.classList.add("is-invalid");
      return false;
    } else {
      horaCierre.classList.remove("is-invalid");
      return true;
    }
  }

  // Guardar configuración de horarios
  async guardarConfiguracionCancha() {
    const idCancha = document.getElementById("canchaConfiguracion").value;

    if (!idCancha) {
      showToast("Seleccione una cancha", "warning");
      return;
    }

    // Recopilar horarios (incluyendo días cerrados con NULL)
    const horarios = [];
    const container = document.getElementById("horariosContainer");
    const rows = container.querySelectorAll(".row[data-dia-id]");

    let valido = true;
    rows.forEach((row) => {
      const checkbox = row.querySelector(".dia-checkbox");
      const idDia = parseInt(row.dataset.diaId);

      if (checkbox.checked) {
        // Día abierto - validar horarios
        if (!this.validarHorarioDia(row)) {
          valido = false;
          return;
        }

        const horaApertura = row.querySelector(".hora-apertura").value;
        const horaCierre = row.querySelector(".hora-cierre").value;

        horarios.push({
          id_dia: idDia,
          hora_apertura: horaApertura + ":00",
          hora_cierre: horaCierre + ":00",
        });
      } else {
        // Día cerrado - enviar con NULL
        horarios.push({
          id_dia: idDia,
          hora_apertura: null,
          hora_cierre: null,
        });
      }
    });

    if (!valido) {
      showToast("Corrija los errores en los horarios", "error");
      return;
    }

    try {
      const response = await fetch(UPDATE_HORARIOS_CANCHAS, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id_cancha: parseInt(idCancha),
          horarios: horarios,
        }),
      });

      const resultado = await response.json();

      if (response.ok) {
        showToast("Horarios actualizados exitosamente", "success");
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("modalConfigurarHorarios")
        );
        modal.hide();
      } else {
        showToast(resultado.error || "Error al actualizar horarios", "error");
      }
    } catch (error) {
      console.error("Error al guardar configuración:", error);
      showToast("Error de conexión al guardar configuración", "error");
    }
  }

  // Abrir modal de políticas de reservas
  abrirModalPoliticas() {
    const selectCancha = document.getElementById("canchaConfiguracion");
    const optionSeleccionada = selectCancha.options[selectCancha.selectedIndex];
    const infoCancha = JSON.parse(optionSeleccionada.dataset.info || "{}");

    const textarea = document.getElementById("politicasReservasTexto");
    textarea.value = infoCancha.politicas_reservas || "";

    const modal = new bootstrap.Modal(
      document.getElementById("modalPoliticasReservas")
    );
    modal.show();
  }

  // Guardar políticas de reservas
  async guardarPoliticasReservas() {
    const idCancha = document.getElementById("canchaConfiguracion").value;
    const politicas = document
      .getElementById("politicasReservasTexto")
      .value.trim();

    if (!idCancha) {
      showToast("Seleccione una cancha", "warning");
      return;
    }

    try {
      const response = await fetch(UPDATE_POLITICAS_CANCHA, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id_cancha: parseInt(idCancha),
          politicas_reservas: politicas,
        }),
      });

      const resultado = await response.json();

      if (response.ok) {
        showToast("Políticas actualizadas exitosamente", "success");

        // Actualizar la info en el dataset del select
        const selectCancha = document.getElementById("canchaConfiguracion");
        const optionSeleccionada =
          selectCancha.options[selectCancha.selectedIndex];
        const infoCancha = JSON.parse(optionSeleccionada.dataset.info || "{}");
        infoCancha.politicas_reservas = politicas;
        optionSeleccionada.dataset.info = JSON.stringify(infoCancha);

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("modalPoliticasReservas")
        );
        modal.hide();
      } else {
        showToast(resultado.error || "Error al actualizar políticas", "error");
      }
    } catch (error) {
      console.error("Error al guardar políticas:", error);
      showToast("Error de conexión al guardar políticas", "error");
    }
  }

  // Gestión de reservas
  crearReserva(fechaSeleccionada = null, horaSeleccionada = null) {
    this.abrirModalReserva("crear", null, fechaSeleccionada, horaSeleccionada);
  }

  verEditarReserva(idReserva) {
    const reserva = this.reservas.find((r) => r.id === idReserva);
    if (reserva) {
      this.abrirModalReserva("editar", reserva);
    }
  }

  abrirModalReserva(
    modo,
    reserva = null,
    fechaSeleccionada = null,
    horaSeleccionada = null
  ) {
    const modal = new bootstrap.Modal(
      document.getElementById("modalGestionReserva")
    );

    // Configurar título y botones según el modo
    this.configurarModalSegunModo(modo, reserva);

    if (modo === "crear") {
      this.prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada);
    } else if (modo === "editar") {
      this.prellenarFormularioEditar(reserva);
    }

    modal.show();
  }

  configurarModalSegunModo(modo, reserva) {
    const tituloModal = document.getElementById("tituloModal");
    const textoBoton = document.getElementById("textoBoton");
    const botonEliminar = document.getElementById("botonEliminarReserva");
    const seccionEstado = document.getElementById("seccionEstado");

    if (modo === "crear") {
      if (tituloModal) tituloModal.textContent = "Crear Nueva Reserva";
      if (textoBoton) textoBoton.textContent = "Crear Reserva";
      if (botonEliminar) botonEliminar.classList.add("d-none");
      if (seccionEstado) seccionEstado.classList.add("d-none");
    } else if (modo === "editar") {
      if (tituloModal)
        tituloModal.textContent = reserva?.esExterna
          ? "Reserva Externa - Detalles"
          : "Reserva App - Detalles";
      if (textoBoton) textoBoton.textContent = "Guardar Cambios";
      if (botonEliminar) botonEliminar.classList.remove("d-none");
      if (seccionEstado) seccionEstado.classList.remove("d-none");
    }
  }

  prellenarFormularioCrear(fechaSeleccionada, horaSeleccionada) {
    if (fechaSeleccionada) {
      const fechaInput = document.getElementById("fechaComienzo");
      if (fechaInput) fechaInput.value = fechaSeleccionada;
      // No auto-completar fecha_fin aquí - se hace con blur event
    }

    if (horaSeleccionada) {
      const horaInput = document.getElementById("horaComienzo");
      if (horaInput) horaInput.value = horaSeleccionada;
    }
  }

  prellenarFormularioEditar(reserva) {
    if (!reserva) return;

    // Llenar todos los campos con los datos de la reserva
    const campos = {
      idReserva: reserva.id,
      estadoReserva: reserva.estado,
      fechaCreacion: reserva.fechaCreacion || "",
      username: reserva.username || "",
      nombreExterno: reserva.nombreExterno || "",
      telefonoExterno: reserva.telefonoExterno || "",
      canchaReserva: reserva.idCancha || "",
      fechaComienzo: reserva.fecha,
      horaComienzo: reserva.hora,
      comentarioReserva: reserva.comentario || "",
    };

    Object.entries(campos).forEach(([id, valor]) => {
      const elemento = document.getElementById(id);
      if (elemento) {
        if (elemento.type === "checkbox") {
          elemento.checked = valor;
        } else {
          elemento.value = valor;
        }
      }
    });

    // Configurar checkbox de reserva externa
    const reservaExterna = document.getElementById("reservaExterna");
    if (reservaExterna) {
      reservaExterna.checked = reserva.esExterna || false;
      this.alternarCamposExternos();
    }
  }

  // Configurar event listeners del modal
  configurarEventListenersModal() {
    // Flag para rastrear si fecha_fin fue modificada manualmente
    this.fechaFinModificadaManualmente = false;

    // Checkbox de reserva externa
    const reservaExterna = document.getElementById("reservaExterna");
    if (reservaExterna) {
      reservaExterna.addEventListener("change", () => {
        this.alternarCamposExternos();
      });
    }

    // Validación de username con autocompletado
    const username = document.getElementById("username");
    if (username) {
      username.addEventListener("blur", async () => {
        const usernameValue = username.value.trim();
        if (
          usernameValue &&
          !document.getElementById("reservaExterna").checked
        ) {
          await this.validarYAutocompletarJugador(usernameValue);
        }
      });
    }

    // Autocompletar fecha_fin cuando el cursor abandona fecha_comienzo
    const fechaComienzo = document.getElementById("fechaComienzo");
    const fechaFin = document.getElementById("fechaFin");

    if (fechaComienzo && fechaFin) {
      // Cuando el usuario abandona el campo fecha_comienzo
      fechaComienzo.addEventListener("blur", () => {
        // Solo autocompletar si fecha_fin no fue modificada manualmente
        if (!this.fechaFinModificadaManualmente && fechaComienzo.value) {
          fechaFin.value = fechaComienzo.value;
        }
      });

      // Marcar como modificada manualmente cuando el usuario cambia fecha_fin
      fechaFin.addEventListener("input", () => {
        this.fechaFinModificadaManualmente = true;
      });

      // Si se borra fecha_fin, permitir autocompletado nuevamente
      fechaFin.addEventListener("change", () => {
        if (!fechaFin.value) {
          this.fechaFinModificadaManualmente = false;
        }
      });
    }

    // Botón guardar reserva
    const botonGuardarReserva = document.getElementById("botonGuardarReserva");
    if (botonGuardarReserva) {
      botonGuardarReserva.addEventListener("click", () => {
        this.procesarFormularioReserva();
      });
    }

    // Botón eliminar reserva
    const botonEliminarReserva = document.getElementById(
      "botonEliminarReserva"
    );
    if (botonEliminarReserva) {
      botonEliminarReserva.addEventListener("click", () => {
        this.eliminarReserva();
      });
    }
  }

  async validarYAutocompletarJugador(username) {
    try {
      const response = await fetch(
        `${GET_USUARIOS}?username=${encodeURIComponent(username)}`
      );

      const usernameInput = document.getElementById("username");
      const usernameFeedback = document.getElementById("usernameFeedback");

      if (!response.ok) {
        // Usuario no encontrado
        usernameInput.classList.add("is-invalid");
        usernameInput.classList.remove("is-valid");
        usernameFeedback.textContent = "Usuario no encontrado en el sistema.";
        document.getElementById("nombreExterno").value = "";
        document.getElementById("telefonoExterno").value = "";
        return false;
      }

      const jugador = await response.json();

      // Autocompletar nombre y teléfono SOLO para mostrar información (display)
      // Estos campos NO se enviarán si hay username
      const nombreCompleto = `${jugador.nombre} ${jugador.apellido}`;
      document.getElementById("nombreExterno").value = nombreCompleto;
      document.getElementById("telefonoExterno").value = jugador.telefono || "";

      // Agregar feedback visual
      usernameInput.classList.add("is-valid");
      usernameInput.classList.remove("is-invalid");
      setTimeout(() => {
        usernameInput.classList.remove("is-valid");
      }, 2000);

      return true;
    } catch (error) {
      console.error("Error al validar username:", error);
      const usernameInput = document.getElementById("username");
      const usernameFeedback = document.getElementById("usernameFeedback");
      usernameInput.classList.add("is-invalid");
      usernameInput.classList.remove("is-valid");
      usernameFeedback.textContent =
        "Error al validar el usuario. Intente nuevamente.";
      return false;
    }
  }

  alternarCamposExternos() {
    const reservaExterna = document.getElementById("reservaExterna");
    const nombreExterno = document.getElementById("nombreExterno");
    const telefonoExterno = document.getElementById("telefonoExterno");
    const username = document.getElementById("username");

    if (reservaExterna && reservaExterna.checked) {
      // Habilitar campos externos para edición manual
      nombreExterno.disabled = false;
      telefonoExterno.disabled = false;
      nombreExterno.required = false; // Opcional para reservas externas
      telefonoExterno.required = false; // Opcional para reservas externas

      // Deshabilitar campo username
      username.disabled = true;
      username.required = false;
      username.value = "";

      // Limpiar campos de nombre y teléfono para permitir entrada manual
      nombreExterno.value = "";
      telefonoExterno.value = "";
    } else {
      // Mantener campos disabled (se usan para mostrar info autocompletada)
      nombreExterno.disabled = true;
      telefonoExterno.disabled = true;
      nombreExterno.required = false;
      telefonoExterno.required = false;

      // NO limpiar campos - mantenerlos para display si fueron autocompletados

      // Habilitar campo username
      username.disabled = false;
      username.required = true;
    }
  }

  async procesarFormularioReserva() {
    const idReserva = document.getElementById("idReserva").value;
    const esEdicion = idReserva !== "";

    const esReservaExterna = document.getElementById("reservaExterna").checked;

    // Recopilar datos del formulario según el tipo de reserva
    const datosReserva = {
      reserva_externa: esReservaExterna,
      id_cancha: document.getElementById("canchaReserva").value,
      id_tipo_reserva: document.getElementById("tipoReserva").value,
      fecha: document.getElementById("fechaComienzo").value,
      fecha_fin: document.getElementById("fechaFin").value,
      hora_inicio: document.getElementById("horaComienzo").value + ":00",
      hora_fin: document.getElementById("horaFin").value + ":00",
      titulo: document.getElementById("tituloReserva").value,
      descripcion: document.getElementById("comentarioReserva").value,
    };

    // Validación crítica: determinar si es usuario registrado o persona externa
    const usernameValue = document.getElementById("username").value.trim();

    if (usernameValue && !esReservaExterna) {
      // Usuario registrado: SOLO enviar username
      datosReserva.username = usernameValue;
      // NO enviar nombre_externo ni telefono_externo
    } else if (esReservaExterna) {
      // Persona externa: SOLO enviar datos externos
      datosReserva.nombre_externo =
        document.getElementById("nombreExterno").value;
      datosReserva.telefono_externo =
        document.getElementById("telefonoExterno").value;
      // NO enviar username
    }

    // Validar datos básicos antes de enviar
    if (!this.validarDatosReservaBasico(datosReserva)) {
      return;
    }

    if (esEdicion) {
      // TODO: Implementar actualización
      showToast("Funcionalidad de edición pendiente de implementar", "info");
    } else {
      await this.crearNuevaReserva(datosReserva);
    }
  }

  validarDatosReservaBasico(datos) {
    let esValido = true;

    // Validar cancha
    const canchaSelect = document.getElementById("canchaReserva");
    if (!datos.id_cancha) {
      canchaSelect.classList.add("is-invalid");
      esValido = false;
    } else {
      canchaSelect.classList.remove("is-invalid");
    }

    // Validar tipo de reserva
    const tipoSelect = document.getElementById("tipoReserva");
    if (!datos.id_tipo_reserva) {
      tipoSelect.classList.add("is-invalid");
      esValido = false;
    } else {
      tipoSelect.classList.remove("is-invalid");
    }

    // Validar fecha de inicio
    const fechaComienzoInput = document.getElementById("fechaComienzo");
    if (!datos.fecha) {
      fechaComienzoInput.classList.add("is-invalid");
      esValido = false;
    } else {
      fechaComienzoInput.classList.remove("is-invalid");
    }

    // Validar fecha de fin
    const fechaFinInput = document.getElementById("fechaFin");
    if (!datos.fecha_fin) {
      fechaFinInput.classList.add("is-invalid");
      esValido = false;
    } else {
      fechaFinInput.classList.remove("is-invalid");
    }

    // Validar hora de inicio
    const horaComienzoInput = document.getElementById("horaComienzo");
    if (!datos.hora_inicio) {
      horaComienzoInput.classList.add("is-invalid");
      esValido = false;
    } else {
      horaComienzoInput.classList.remove("is-invalid");
    }

    // Validar hora de fin
    const horaFinInput = document.getElementById("horaFin");
    if (!datos.hora_fin) {
      horaFinInput.classList.add("is-invalid");
      esValido = false;
    } else {
      horaFinInput.classList.remove("is-invalid");
    }

    // Validar username para reservas de app
    const usernameInput = document.getElementById("username");
    const usernameFeedback = document.getElementById("usernameFeedback");
    if (!datos.reserva_externa) {
      if (!datos.username) {
        usernameInput.classList.add("is-invalid");
        usernameFeedback.textContent =
          "Para reservas de la app, ingrese el username del jugador.";
        esValido = false;
      } else {
        usernameInput.classList.remove("is-invalid");
      }
    }

    // Advertencia para reservas externas sin datos opcionales
    if (
      datos.reserva_externa &&
      !datos.nombre_externo &&
      !datos.telefono_externo
    ) {
      showToast(
        "No se ingresó nombre ni teléfono para la reserva externa.",
        "warning"
      );
    }

    return esValido;
  }

  async crearNuevaReserva(datosReserva) {
    try {
      // Deshabilitar botón mientras se procesa
      const botonGuardar = document.getElementById("botonGuardarReserva");
      const textoOriginal = botonGuardar.innerHTML;
      botonGuardar.disabled = true;
      botonGuardar.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Creando...';

      // Enviar datos al controller
      const formData = new FormData();
      Object.keys(datosReserva).forEach((key) => {
        if (datosReserva[key] !== null && datosReserva[key] !== undefined) {
          formData.append(key, datosReserva[key]);
        }
      });

      const response = await fetch(POST_RESERVA, {
        method: "POST",
        body: formData,
      });

      const resultado = await response.json();

      if (response.ok) {
        // Éxito
        showToast(
          resultado.message || "Reserva creada exitosamente",
          "success"
        );
        // Restaurar botón antes de cerrar modal
        botonGuardar.disabled = false;
        botonGuardar.innerHTML = textoOriginal;
        this.cerrarModal();
        // Recargar el calendario para mostrar la nueva reserva
        this.renderizarVistaActual();
      } else {
        // Error del servidor
        if (response.status === 409 && resultado.superposiciones) {
          // Error de superposición - mostrar alerta detallada
          this.mostrarAlertaSuperposicion(resultado.superposiciones);
        } else {
          // Otro error
          showToast(
            "Error al crear la reserva: " +
              (resultado.error || "Error desconocido"),
            "error"
          );
        }
        botonGuardar.disabled = false;
        botonGuardar.innerHTML = textoOriginal;
      }
    } catch (error) {
      console.error("Error al crear reserva:", error);
      showToast(
        "Error de conexión al crear la reserva. Intente nuevamente.",
        "error"
      );
      const botonGuardar = document.getElementById("botonGuardarReserva");
      botonGuardar.disabled = false;
      botonGuardar.innerHTML =
        '<i class="bi bi-check-circle me-2"></i><span id="textoBoton">Crear Reserva</span>';
    }
  }

  mostrarAlertaSuperposicion(superposiciones) {
    let mensaje =
      "La reserva se superpone con las siguientes reservas existentes:\n\n";

    superposiciones.forEach((reserva, index) => {
      mensaje += `${index + 1}. ${reserva.titulo}\n`;
      mensaje += `   Fecha: ${reserva.fecha}`;
      if (reserva.fecha_fin && reserva.fecha !== reserva.fecha_fin) {
        mensaje += ` al ${reserva.fecha_fin}`;
      }
      mensaje += `\n   Horario: ${reserva.hora_inicio} - ${reserva.hora_fin}\n\n`;
    });

    mensaje += "Por favor, seleccione otro horario o fecha.";

    showToast(mensaje, "warning", 6000);
  }

  eliminarReserva() {
    const idReserva = parseInt(document.getElementById("idReserva").value);

    if (confirm("¿Está seguro que desea eliminar esta reserva?")) {
      this.reservas = this.reservas.filter((r) => r.id !== idReserva);
      showToast("Reserva eliminada exitosamente", "success");
      this.cerrarModal();
    }
  }

  cerrarModal() {
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalGestionReserva")
    );
    modal.hide();
    this.limpiarFormularioReserva();
    this.renderizarVistaActual();
  }

  limpiarFormularioReserva() {
    const formulario = document.getElementById("formularioGestionReserva");
    if (formulario) {
      formulario.reset();

      // Limpiar campos ocultos
      const idReserva = document.getElementById("idReserva");
      if (idReserva) idReserva.value = "";

      // Limpiar todas las clases de validación
      const campos = formulario.querySelectorAll(".form-control, .form-select");
      campos.forEach((campo) => {
        campo.classList.remove("is-valid", "is-invalid");
      });

      // Restaurar botón a su estado original
      const botonGuardar = document.getElementById("botonGuardarReserva");
      if (botonGuardar) {
        botonGuardar.disabled = false;
        botonGuardar.innerHTML =
          '<i class="bi bi-check-circle me-2"></i><span id="textoBoton">Crear Reserva</span>';
      }

      // Resetear flag de fecha_fin modificada
      this.fechaFinModificadaManualmente = false;

      // Restaurar estado inicial de los campos
      const nombreExterno = document.getElementById("nombreExterno");
      const telefonoExterno = document.getElementById("telefonoExterno");
      const username = document.getElementById("username");

      if (nombreExterno) {
        nombreExterno.disabled = true;
        nombreExterno.required = false;
      }
      if (telefonoExterno) {
        telefonoExterno.disabled = true;
        telefonoExterno.required = false;
      }
      if (username) {
        username.disabled = false;
        username.required = true;
      }
    }
  }

  filtrarReservas(termino) {
    console.log("Filtrando reservas con término:", termino);
    // TODO: Implementar filtrado real
  }

  mostrarMensajeExito(mensaje) {
    showToast(mensaje, "success");
  }

  generarNuevoId() {
    return Math.max(...this.reservas.map((r) => r.id), 0) + 1;
  }

  // Generar datos de muestra para demostración
  generarDatosMuestra() {
    this.reservas = [
      {
        id: 1,
        fecha: "2025-11-04",
        hora: "18:00",
        duracion: 2,
        cancha: "Cancha A",
        cliente: "Los Cracks FC",
        estado: "confirmed",
        telefono: "+54 11 1234-5678",
        idCancha: "1",
        esExterna: false,
        username: "juanpe",
      },
      {
        id: 2,
        fecha: "2025-11-05",
        hora: "19:00",
        duracion: 1,
        cancha: "Cancha B",
        cliente: "Racing Amateur",
        estado: "pending",
        telefono: "+54 11 2345-6789",
        idCancha: "2",
        esExterna: true,
        nombreExterno: "Racing Amateur",
        telefonoExterno: "+54 11 2345-6789",
      },
    ];
  }
}

// Inicialización
let aplicacionAgenda;

document.addEventListener("DOMContentLoaded", function () {
  aplicacionAgenda = new AplicacionAgendaAdmin();
});

// Funciones globales para callbacks de los modales
function aceptarSolicitud(idSolicitud) {
  const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(
    (s) => s.id === idSolicitud
  );
  if (solicitudIndex === -1) return;

  const solicitud = aplicacionAgenda.solicitudesPendientes[solicitudIndex];

  // Crear nueva reserva a partir de la solicitud
  const nuevaReserva = {
    id: aplicacionAgenda.generarNuevoId(),
    fecha: solicitud.fecha,
    hora: solicitud.hora,
    cancha: solicitud.cancha.nombre,
    cliente: solicitud.jugador.nombre,
    estado: "confirmed",
    telefono: solicitud.jugador.telefono,
    idCancha: solicitud.cancha.id.toString(),
    esExterna: false,
    username: solicitud.jugador.id.toString(),
  };

  aplicacionAgenda.reservas.push(nuevaReserva);

  // Remover de solicitudes pendientes
  aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);

  // Actualizar la interfaz
  aplicacionAgenda.renderizarSolicitudesModal();
  aplicacionAgenda.actualizarContadorNotificaciones();
  aplicacionAgenda.renderizarVistaActual();

  showToast("Solicitud aceptada exitosamente", "success");
}

function rechazarSolicitud(idSolicitud) {
  const solicitudIndex = aplicacionAgenda.solicitudesPendientes.findIndex(
    (s) => s.id === idSolicitud
  );
  if (solicitudIndex === -1) return;

  // Remover de solicitudes pendientes
  aplicacionAgenda.solicitudesPendientes.splice(solicitudIndex, 1);

  // Actualizar la interfaz
  aplicacionAgenda.renderizarSolicitudesModal();
  aplicacionAgenda.actualizarContadorNotificaciones();

  showToast("Solicitud rechazada", "info");
}

function verPerfilJugador(username) {
  showToast(
    `Función no implementada: Ver perfil del jugador ID ${username}`,
    "info"
  );
}

function verEditarReserva(idReserva) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verEditarReserva(idReserva);
  }
}

// Override de callbacks para funcionalidades del admin
function onCalendarioDiaClick(fecha) {
  console.log("Admin - Día seleccionado:", fecha);
  if (aplicacionAgenda) {
    aplicacionAgenda.cambiarVista("dia");
  }
}

function onCalendarioHorarioClick(fecha, hora) {
  console.log("Admin - Horario seleccionado:", fecha, hora);
  if (aplicacionAgenda) {
    aplicacionAgenda.crearReserva(fecha, hora);
  }
}
