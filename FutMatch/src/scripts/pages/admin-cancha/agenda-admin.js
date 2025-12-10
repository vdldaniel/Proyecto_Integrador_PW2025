/**
 * Agenda Admin JavaScript
 * Funcionalidades específicas del admin de cancha que extienden el calendario base
 */

// Clase específica para admin de cancha que extiende CalendarioBase
class AplicacionAgendaAdmin extends CalendarioBase {
  constructor() {
    super();

    // Datos específicos del admin (se cargarán desde la BD)
    this.solicitudesPendientes = [];

    // Inicializar funcionalidades específicas del admin
    this.inicializarFuncionalidadesAdmin();
  }

  inicializarFuncionalidadesAdmin() {
    this.configurarEventosAdmin();
    this.renderizarSolicitudesModal();
    this.renderizarModalConfiguracion();
    this.actualizarContadorNotificaciones();
    this.configurarEventListenersModal();
    this.configurarModalNotificaciones();
    this.cargarCanchas();
    this.cargarTiposReserva();
    this.configurarBotonVerSolicitudes();
  }

  // Cargar canchas del admin desde el controller
  async cargarCanchas() {
    try {
      const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
      const resultado = await response.json();

      // El controller retorna {status, data}
      const canchas = resultado.data || resultado;

      // Llenar select del formulario de reservas
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

      // Llenar select del calendario
      const selectorCanchaCalendario =
        document.getElementById("selectorCancha");
      if (selectorCanchaCalendario && canchas.length > 0) {
        selectorCanchaCalendario.innerHTML =
          '<option value="">Seleccionar cancha</option>';
        canchas.forEach((cancha) => {
          const option = document.createElement("option");
          option.value = cancha.id_cancha;
          option.textContent = cancha.nombre;
          selectorCanchaCalendario.appendChild(option);
        });
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

      // Guardar en instancia para uso posterior
      this.tiposReserva = tipos;

      // Llenar select del formulario de crear reserva
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

  // Configurar botón Ver Solicitudes
  configurarBotonVerSolicitudes() {
    const botonVerSolicitudes = document.getElementById("botonVerSolicitudes");
    if (botonVerSolicitudes) {
      botonVerSolicitudes.addEventListener("click", () => {
        this.cargarNotificacionesNavbar();
      });
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

    // Configurar selector de cancha para cargar reservas
    const selectorCancha = document.getElementById("selectorCancha");
    if (selectorCancha) {
      selectorCancha.addEventListener("change", (e) => {
        const idCancha = e.target.value;
        if (idCancha && idCancha !== "Seleccionar cancha") {
          this.cargarReservas(idCancha);
        } else {
          this.reservas = [];
          this.canchaSeleccionada = null;
          this.renderizarVistaActual();
        }
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

  // Los métodos obtenerContenidoDia y obtenerContenidoHora se heredan de CalendarioBase
  // y ya están implementados correctamente con la estructura de vista_reservas

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

  // Cargar notificaciones para el modal de solicitudes
  async cargarNotificacionesNavbar() {
    // Obtener todas las reservas pendientes de todas las canchas del admin
    try {
      const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
      const resultado = await response.json();
      const canchas = resultado.data || resultado;

      let todasPendientes = [];

      // Cargar reservas pendientes de cada cancha
      for (const cancha of canchas) {
        const resReservas = await fetch(
          `${GET_RESERVAS}?id_cancha=${cancha.id_cancha}`
        );
        const dataReservas = await resReservas.json();
        console.log(
          "Reservas cargadas para cancha:",
          cancha.nombre,
          dataReservas
        );

        if (dataReservas.success && dataReservas.reservas) {
          const pendientes = dataReservas.reservas.filter(
            (r) => r.id_estado === 1
          );
          todasPendientes = todasPendientes.concat(
            pendientes.map((r) => ({
              ...r,
              nombre_cancha: cancha.nombre,
            }))
          );
        }
      }

      // Enriquecer con datos de torneo si aplica
      await this.enriquecerReservasConDatosTorneo(todasPendientes);

      // Renderizar en el modal de solicitudes pendientes
      this.renderizarSolicitudesEnModal(todasPendientes);
    } catch (error) {
      console.error("Error al cargar notificaciones:", error);
    }
  }

  // Enriquecer reservas con datos de torneo (sobrecarga para arrays externos)
  async enriquecerReservasConDatosTorneo(reservas = null) {
    const reservasAProcesar = reservas || this.reservas;

    if (!reservasAProcesar || reservasAProcesar.length === 0) return;

    // Filtrar solo reservas de tipo torneo
    const reservasTorneo = reservasAProcesar.filter(
      (r) => r.id_tipo_reserva === "torneo"
    );

    if (reservasTorneo.length === 0) return;

    // Obtener datos de torneo para cada reserva
    const promesas = reservasTorneo.map(async (reserva) => {
      try {
        const response = await fetch(
          `${GET_DATOS_TORNEO_RESERVA}?id_reserva=${reserva.id_reserva}`
        );
        const data = await response.json();

        if (data.status === "success" && data.es_torneo && data.datos) {
          // Enriquecer la reserva con los datos del torneo
          reserva.nombre_torneo = data.datos.nombre_torneo;
          reserva.fase_nombre = data.datos.fase_nombre;
          reserva.equipo_a_nombre = data.datos.equipo_a_nombre;
          reserva.equipo_b_nombre = data.datos.equipo_b_nombre;
          reserva.id_torneo = data.datos.id_torneo;
          reserva.id_partido = data.datos.id_partido;
          reserva.goles_equipo_A = data.datos.goles_equipo_A;
          reserva.goles_equipo_B = data.datos.goles_equipo_B;
        }
      } catch (error) {
        console.error(
          `Error al cargar datos de torneo para reserva ${reserva.id_reserva}:`,
          error
        );
      }
    });

    // Esperar a que todas las promesas se resuelvan
    await Promise.all(promesas);
  }

  // Renderizar solicitudes en el modal de Solicitudes Pendientes
  renderizarSolicitudesEnModal(reservasPendientes) {
    const contenedor = document.getElementById(
      "contenidoSolicitudesPendientes"
    );
    if (!contenedor) return;

    if (reservasPendientes.length === 0) {
      contenedor.innerHTML = `
        <div class="text-center text-muted py-5">
          <i class="bi bi-inbox" style="font-size: 3rem;"></i>
          <p class="mt-3">No hay solicitudes pendientes</p>
        </div>
      `;
      return;
    }

    let html = `<div class="list-group">`;

    reservasPendientes.forEach((reserva, index) => {
      // Determinar qué mostrar como titular
      let titularDisplay = "";

      if (reserva.id_tipo_reserva === "torneo") {
        // Formato: "Nombre Torneo - Fase - Equipo A vs Equipo B"
        const nombreTorneo = reserva.nombre_torneo || "Torneo";
        const fase = reserva.fase_nombre || "Fase";
        const equipoA = reserva.equipo_a_nombre || "Equipo A";
        const equipoB = reserva.equipo_b_nombre || "Equipo B";
        titularDisplay = `
          <h6 class="mb-1"><i class="bi bi-trophy text-warning me-2"></i>${nombreTorneo}</h6>
          <p class="mb-1 text-muted"><strong>${fase}</strong> - ${equipoA} vs ${equipoB}</p>
        `;
      } else {
        // Formato normal para reservas no-torneo
        titularDisplay = `
          <h6 class="mb-1">
            ${
              reserva.tipo_titular === "jugador"
                ? `<i class="bi bi-person-fill text-primary me-2"></i>`
                : `<i class="bi bi-person-badge text-secondary me-2"></i>`
            }
            ${reserva.titular_nombre_completo}
          </h6>
          <p class="mb-1 text-muted">
            ${
              reserva.tipo_titular === "jugador"
                ? `@${reserva.username_titular || "Usuario"}`
                : "Reserva Externa"
            }
          </p>
        `;
      }

      html += `
        <div class="list-group-item">
          <div class="d-flex w-100 justify-content-between align-items-start">
            <div class="flex-grow-1">
              ${titularDisplay}
              <p class="mb-1">
                <i class="bi bi-geo-alt-fill text-info me-2"></i><strong>${
                  reserva.nombre_cancha
                }</strong>
              </p>
              <p class="mb-1">
                <i class="bi bi-calendar3 me-2"></i>${new Date(
                  reserva.fecha
                ).toLocaleDateString("es-AR", {
                  weekday: "long",
                  day: "2-digit",
                  month: "long",
                  year: "numeric",
                })}
              </p>
              <p class="mb-0">
                <i class="bi bi-clock me-2"></i>${reserva.hora_inicio.substring(
                  0,
                  5
                )} - ${reserva.hora_fin.substring(0, 5)}
              </p>
              ${
                reserva.titulo
                  ? `<p class="mb-0 mt-2"><strong>Título:</strong> ${reserva.titulo}</p>`
                  : ""
              }
              ${
                reserva.descripcion
                  ? `<p class="mb-0 text-muted small">${reserva.descripcion}</p>`
                  : ""
              }
            </div>
            <div class="ms-3">
              <div class="btn-group-vertical" role="group">
                <button class="btn btn-success btn-sm mb-1" onclick="aplicacionAgenda.aceptarSolicitudNavbar(${
                  reserva.id_reserva
                })" title="Aceptar solicitud">
                  <i class="bi bi-check-lg"></i> Aceptar
                </button>
                <button class="btn btn-danger btn-sm" onclick="aplicacionAgenda.rechazarSolicitudNavbar(${
                  reserva.id_reserva
                })" title="Rechazar solicitud">
                  <i class="bi bi-x-lg"></i> Rechazar
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    html += `</div>`;

    contenedor.innerHTML = html;
  }

  // Renderizar notificaciones en la tabla del navbar (método legacy, puede ser removido si no se usa)
  renderizarNotificacionesNavbar(reservasPendientes) {
    const tbody = document.querySelector("#content-agenda tbody");
    if (!tbody) return;

    if (reservasPendientes.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="5" class="text-center text-muted">
            <i class="bi bi-inbox me-2"></i>No hay solicitudes pendientes
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = reservasPendientes
      .slice(0, 10)
      .map((reserva) => {
        // Determinar qué mostrar en la columna de titular
        let titularDisplay = "";

        if (reserva.id_tipo_reserva === "torneo") {
          // Formato: "Nombre Torneo - Fase - Equipo A vs Equipo B"
          const nombreTorneo = reserva.nombre_torneo || "Torneo";
          const fase = reserva.fase_nombre || "Fase";
          const equipoA = reserva.equipo_a_nombre || "Equipo A";
          const equipoB = reserva.equipo_b_nombre || "Equipo B";
          titularDisplay = `
              <div><strong>${nombreTorneo}</strong></div>
              <small class="text-muted">
                <i class="bi bi-trophy"></i> ${fase} - ${equipoA} vs ${equipoB}
              </small>
            `;
        } else {
          // Formato normal para reservas no-torneo
          titularDisplay = `
              <div>${reserva.titular_nombre_completo}</div>
              <small class="text-muted">
                ${
                  reserva.tipo_titular === "jugador"
                    ? `<i class="bi bi-person"></i> @${
                        reserva.username_titular || "Usuario"
                      }`
                    : '<i class="bi bi-person-badge"></i> Externo'
                }
              </small>
            `;
        }

        return `
            <tr>
              <td>${titularDisplay}</td>
              <td>${reserva.nombre_cancha}</td>
              <td>${new Date(reserva.fecha).toLocaleDateString("es-AR", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
              })}</td>
              <td>${reserva.hora_inicio.substring(0, 5)}</td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <button class="btn btn-success" onclick="aplicacionAgenda.aceptarSolicitudNavbar(${
                    reserva.id_reserva
                  })" title="Aceptar">
                    <i class="bi bi-check-lg"></i>
                  </button>
                  <button class="btn btn-danger" onclick="aplicacionAgenda.rechazarSolicitudNavbar(${
                    reserva.id_reserva
                  })" title="Rechazar">
                    <i class="bi bi-x-lg"></i>
                  </button>
                </div>
              </td>
            </tr>
          `;
      })
      .join("");
  }

  // Aceptar solicitud desde modal de solicitudes
  async aceptarSolicitudNavbar(idReserva) {
    try {
      await this.cambiarEstadoReserva(idReserva, 3);
      await this.cargarNotificacionesNavbar();

      // Si hay una cancha seleccionada, recargar su calendario
      const canchaSeleccionada =
        document.getElementById("selectorCancha")?.value;
      if (canchaSeleccionada) {
        await this.cargarReservas(canchaSeleccionada);
        this.renderizarVistaActual();
      }

      showToast("Solicitud aceptada exitosamente", "success");
    } catch (error) {
      console.error("Error al aceptar solicitud:", error);
      showToast("Error al aceptar la solicitud", "error");
    }
  }

  // Rechazar solicitud desde modal de solicitudes
  async rechazarSolicitudNavbar(idReserva) {
    try {
      await this.cambiarEstadoReserva(idReserva, 4);
      await this.cargarNotificacionesNavbar();

      // Si hay una cancha seleccionada, recargar su calendario
      const canchaSeleccionada =
        document.getElementById("selectorCancha")?.value;
      if (canchaSeleccionada) {
        await this.cargarReservas(canchaSeleccionada);
        this.renderizarVistaActual();
      }

      showToast("Solicitud rechazada", "info");
    } catch (error) {
      console.error("Error al rechazar solicitud:", error);
      showToast("Error al rechazar la solicitud", "error");
    }
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

  // Mostrar detalle de una reserva
  async verDetalleReserva(idReserva) {
    try {
      const response = await fetch(
        `${GET_RESERVA_DETALLE}?id_reserva=${idReserva}`
      );
      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || "Error al cargar detalle");
      }

      if (data.success) {
        this.mostrarModalDetalleReserva(data.reserva);
      } else {
        throw new Error(data.message || "Error al cargar detalle");
      }
    } catch (error) {
      console.error("Error al cargar detalle de reserva:", error);
      showToast("Error al cargar el detalle: " + error.message, "error");
    }
  }

  // Renderizar y mostrar modal con el detalle de la reserva
  mostrarModalDetalleReserva(reserva) {
    const contenedor = document.getElementById("contenidoDetalleReserva");
    if (!contenedor) return;

    // Determinar clases de estado
    // Mapeo de colores: 1=Pendiente(warning), 2=En revisión(info), 3=Aceptada(success), 4=Rechazada(secondary), 5=Cancelada(danger), 6=Ausente(dark)
    const estadoClass =
      reserva.id_estado === 1
        ? "warning"
        : reserva.id_estado === 2
        ? "info"
        : reserva.id_estado === 3
        ? "success"
        : reserva.id_estado === 4
        ? "secondary"
        : reserva.id_estado === 5
        ? "danger"
        : reserva.id_estado === 6
        ? "dark"
        : "secondary";

    const tipoTitularLabel =
      reserva.tipo_titular === "jugador"
        ? "Jugador Registrado"
        : "Persona Externa";

    // Construir HTML del detalle editable in-place
    const html = `
      <form id="formDetalleReserva">
        <input type="hidden" id="detailIdReserva" value="${reserva.id_reserva}">
        
        <!-- Información General -->
        <div class="card mb-3">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span><i class="bi bi-info-circle me-2"></i>Información General</span>
            <span class="badge bg-${estadoClass}">${
      reserva.estado_reserva
    }</span>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-2">
                <small class="text-muted d-block">Cancha</small>
                <strong>${reserva.nombre_cancha}</strong>
              </div>
              <div class="col-md-6 mb-2">
                <label class="text-muted d-block small">Tipo de Reserva</label>
                <select class="form-select form-select-sm" id="detailTipoReserva" disabled>
                  <!-- Se llena dinámicamente -->
                </select>
              </div>
              <div class="col-12 mb-2 mt-2">
                <label class="text-muted d-block small">Título del Evento</label>
                <input type="text" class="form-control form-control-sm" id="detailTitulo" value="${
                  reserva.titulo || ""
                }" disabled>
              </div>
              <div class="col-12 mb-2">
                <label class="text-muted d-block small">Descripción</label>
                <textarea class="form-control form-control-sm" id="detailDescripcion" rows="2" disabled>${
                  reserva.descripcion || ""
                }</textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Fecha y Horario -->
        <div class="card mb-3">
          <div class="card-header bg-info text-white">
            <i class="bi bi-calendar3 me-2"></i>Fecha y Horario
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6 mb-2">
                <label class="text-muted d-block small">Fecha Desde</label>
                <input type="date" class="form-control form-control-sm" id="detailFecha" value="${
                  reserva.fecha
                }" disabled>
              </div>
              <div class="col-md-6 mb-2">
                <label class="text-muted d-block small">Fecha Hasta</label>
                <input type="date" class="form-control form-control-sm" id="detailFechaHasta" value="${
                  reserva.fecha_fin || reserva.fecha
                }" disabled>
              </div>
              <div class="col-md-6 mb-2">
                <label class="text-muted d-block small">Hora Inicio</label>
                <input type="time" class="form-control form-control-sm" id="detailHoraInicio" value="${reserva.hora_inicio.substring(
                  0,
                  5
                )}" disabled>
              </div>
              <div class="col-md-6 mb-2">
                <label class="text-muted d-block small">Hora Fin</label>
                <input type="time" class="form-control form-control-sm" id="detailHoraFin" value="${reserva.hora_fin.substring(
                  0,
                  5
                )}" disabled>
              </div>
            </div>
          </div>
        </div>

      <!-- Titular de la Reserva -->
      <div class="card mb-3">
        <div class="card-header bg-success text-white">
          <i class="bi bi-person-check me-2"></i>Titular de la Reserva
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 mb-2">
              <small class="text-muted d-block">Tipo</small>
              <span class="badge bg-${
                reserva.tipo_titular === "jugador" ? "primary" : "secondary"
              }">${tipoTitularLabel}</span>
            </div>
            <div class="col-md-6 mb-2">
              <small class="text-muted d-block">Nombre</small>
              <strong>${
                reserva.id_tipo_reserva === 2
                  ? reserva.titulo
                  : reserva.titular_nombre_completo
              }</strong>
            </div>
            ${
              reserva.titular_telefono
                ? `
            <div class="col-12 mt-2">
              <small class="text-muted d-block">Teléfono</small>
              <a href="tel:${reserva.titular_telefono}" class="text-decoration-none">
                <i class="bi bi-telephone-fill text-success"></i> ${reserva.titular_telefono}
              </a>
            </div>
            `
                : ""
            }
          </div>
        </div>
      </div>

      <!-- Información Administrativa -->
      <div class="card">
        <div class="card-header bg-secondary text-white">
          <i class="bi bi-gear me-2"></i>Información Administrativa
        </div>
        <div class="card-body">
          <small class="text-muted d-block">Creado por</small>
          <strong>${reserva.creador_nombre_completo}</strong>
          <div class="mt-1">
            <small class="text-muted">
              <i class="bi bi-clock-history"></i> ${new Date(
                reserva.fecha_solicitud
              ).toLocaleString("es-AR", {
                dateStyle: "short",
                timeStyle: "short",
              })}
            </small>
          </div>
        </div>
      </div>

      </form>
      
      <!-- Botones de acción -->
      <div class="mt-3 d-flex gap-2 flex-wrap" id="botonesAccionReserva">
        <button type="button" class="btn btn-primary" id="btnToggleEdit" onclick="aplicacionAgenda.toggleModoEdicion()">
          <i class="bi bi-pencil"></i> Editar
        </button>
        <button type="button" class="btn btn-success d-none" id="btnGuardarEdit" onclick="aplicacionAgenda.guardarEdicionInPlace()">
          <i class="bi bi-save"></i> Guardar
        </button>
        <button type="button" class="btn btn-secondary d-none" id="btnCancelarEdit" onclick="aplicacionAgenda.cancelarEdicionInPlace()">
          <i class="bi bi-x"></i> Cancelar
        </button>
        ${this.obtenerBotonesEstado(reserva.id_estado, reserva.id_reserva)}
      </div>
    `;

    contenedor.innerHTML = html;

    // Guardar reserva actual para edición
    this.reservaActual = reserva;

    // Llenar select de tipos de reserva
    const selectTipoReserva = document.getElementById("detailTipoReserva");
    if (
      selectTipoReserva &&
      this.tiposReserva &&
      this.tiposReserva.length > 0
    ) {
      selectTipoReserva.innerHTML = "";
      this.tiposReserva.forEach((tipo) => {
        const option = document.createElement("option");
        option.value = tipo.id_tipo_reserva;
        option.textContent = tipo.nombre;
        if (tipo.id_tipo_reserva == reserva.id_tipo_reserva) {
          option.selected = true;
        }
        selectTipoReserva.appendChild(option);
      });
    }

    // Mostrar modal
    const modal = new bootstrap.Modal(
      document.getElementById("modalDetalleReserva")
    );
    modal.show();
  }

  // Obtener botones dinámicos según estado
  obtenerBotonesEstado(idEstado, idReserva) {
    if (idEstado === 3) {
      // Aceptada -> Cancelar
      return `
        <button type="button" class="btn btn-danger" onclick="aplicacionAgenda.cambiarEstadoReserva(${idReserva}, 5)">
          <i class="bi bi-x-circle"></i> Cancelar Reserva
        </button>
      `;
    } else if (idEstado === 1) {
      // Pendiente -> Aceptar y Rechazar
      return `
        <button type="button" class="btn btn-success" onclick="aplicacionAgenda.cambiarEstadoReserva(${idReserva}, 3)">
          <i class="bi bi-check-circle"></i> Aceptar
        </button>
        <button type="button" class="btn btn-danger" onclick="aplicacionAgenda.cambiarEstadoReserva(${idReserva}, 4)">
          <i class="bi bi-x-circle"></i> Rechazar
        </button>
      `;
    } else if (idEstado === 4 || idEstado === 5) {
      // Rechazada/Cancelada -> Restaurar
      return `
        <button type="button" class="btn btn-warning" onclick="aplicacionAgenda.cambiarEstadoReserva(${idReserva}, 1)">
          <i class="bi bi-arrow-counterclockwise"></i> Restaurar
        </button>
      `;
    }
    return "";
  }

  // Toggle modo edición in-place
  toggleModoEdicion() {
    const campos = [
      "detailTipoReserva",
      "detailTitulo",
      "detailDescripcion",
      "detailFecha",
      "detailFechaHasta",
      "detailHoraInicio",
      "detailHoraFin",
    ];
    const btnEdit = document.getElementById("btnToggleEdit");
    const btnGuardar = document.getElementById("btnGuardarEdit");
    const btnCancelar = document.getElementById("btnCancelarEdit");

    campos.forEach((id) => {
      const campo = document.getElementById(id);
      if (campo) campo.disabled = false;
    });

    btnEdit.classList.add("d-none");
    btnGuardar.classList.remove("d-none");
    btnCancelar.classList.remove("d-none");
  }

  // Cancelar edición in-place
  cancelarEdicionInPlace() {
    // Recargar el modal con datos originales
    if (this.reservaActual) {
      this.verDetalleReserva(this.reservaActual.id_reserva);
    }
  }

  // Guardar edición in-place
  async guardarEdicionInPlace() {
    const datos = {
      id_reserva: document.getElementById("detailIdReserva").value,
      fecha: document.getElementById("detailFecha").value,
      fecha_fin: document.getElementById("detailFechaHasta").value,
      hora_inicio: document.getElementById("detailHoraInicio").value + ":00",
      hora_fin: document.getElementById("detailHoraFin").value + ":00",
      id_tipo_reserva: document.getElementById("detailTipoReserva").value,
      titulo: document.getElementById("detailTitulo").value,
      descripcion: document.getElementById("detailDescripcion").value,
    };

    try {
      const response = await fetch(UPDATE_RESERVA, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(datos),
      });

      const resultado = await response.json();

      if (resultado.status === "success") {
        if (typeof showToast !== "undefined") {
          showToast("Reserva actualizada correctamente", "success");
        }

        const modal = bootstrap.Modal.getInstance(
          document.getElementById("modalDetalleReserva")
        );
        modal.hide();

        if (this.canchaSeleccionada) {
          await this.cargarReservas(this.canchaSeleccionada);
        }
      } else {
        throw new Error(resultado.message || "Error al actualizar reserva");
      }
    } catch (error) {
      console.error("Error al guardar edición:", error);
      if (typeof showToast !== "undefined") {
        showToast("Error al guardar cambios: " + error.message, "error");
      }
    }
  }

  // Cambiar estado de reserva (optimizado)
  async cambiarEstadoReserva(idReserva, nuevoEstado) {
    // Mostrar modal de confirmación
    const confirmed = await this.mostrarModalConfirmacion(
      "¿Está seguro de cambiar el estado de esta reserva?",
      "Cambiar Estado"
    );

    if (!confirmed) {
      return;
    }

    // Si se va a confirmar (estado 3), validar superposición
    if (nuevoEstado === 3) {
      const reservaACambiar = this.reservas.find(
        (r) => r.id_reserva === idReserva
      );
      if (reservaACambiar) {
        const haySuperposicion =
          this.validarSuperposicionReserva(reservaACambiar);
        if (haySuperposicion) {
          showToast(
            "No se puede confirmar: existe una reserva confirmada en este horario",
            "error"
          );
          return;
        }
      }
    }

    try {
      // Cerrar modal inmediatamente para evitar bloqueo
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalDetalleReserva")
      );
      if (modal) modal.hide();

      // Hacer request en paralelo
      const response = await fetch(UPDATE_RESERVA, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id_reserva: idReserva, id_estado: nuevoEstado }),
      });

      const resultado = await response.json();

      if (resultado.status === "success") {
        if (typeof showToast !== "undefined") {
          showToast("Estado actualizado correctamente", "success");
        }

        // Recargar reservas
        if (this.canchaSeleccionada) {
          await this.cargarReservas(this.canchaSeleccionada);
        }
      } else {
        throw new Error(resultado.message || "Error al actualizar estado");
      }
    } catch (error) {
      console.error("Error al cambiar estado:", error);
      if (typeof showToast !== "undefined") {
        showToast("Error al cambiar estado: " + error.message, "error");
      }
    }
  }

  // Ver reservas históricas de un horario
  async verReservasHistoricas(fecha, hora) {
    const reservasHistoricas = this.reservas.filter((reserva) => {
      return (
        reserva.fecha === fecha &&
        reserva.hora_inicio === hora &&
        (reserva.id_estado === 4 || reserva.id_estado === 5)
      );
    });

    if (reservasHistoricas.length === 0) {
      if (typeof showToast !== "undefined") {
        showToast("No hay reservas históricas en este horario", "info");
      }
      return;
    }

    // Mostrar con diseño de filas
    let html = `<div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th width="10%">Estado</th>
            <th width="15%">Horario</th>
            <th width="30%">Titular</th>
            <th width="25%">Tipo</th>
            <th width="20%" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>`;

    reservasHistoricas.forEach((reserva) => {
      const estadoBadge = reserva.id_estado === 4 ? "secondary" : "danger";
      const estadoTexto = reserva.id_estado === 4 ? "Rechazada" : "Cancelada";
      const estadoIcon = reserva.id_estado === 4 ? "x-circle" : "ban";

      html += `
        <tr>
          <td>
            <span class="badge bg-${estadoBadge}">
              <i class="bi bi-${estadoIcon}"></i>
            </span>
          </td>
          <td>
            <strong>${reserva.hora_inicio.substring(0, 5)}</strong><br>
            <small class="text-muted">${reserva.hora_fin.substring(
              0,
              5
            )}</small>
          </td>
          <td><strong>${
            reserva.id_tipo_reserva === 2
              ? reserva.titulo
              : reserva.titular_nombre_completo
          }</strong></td>
          <td>
            <span class="badge bg-info">${reserva.tipo_reserva}</span>
            ${
              reserva.titulo
                ? `<br><small class="text-muted">${reserva.titulo}</small>`
                : ""
            }
          </td>
          <td class="text-center">
            <div class="btn-group btn-group-sm" role="group">
              <button class="btn btn-dark" onclick="aplicacionAgenda.verDetalleReserva(${
                reserva.id_reserva
              }); bootstrap.Modal.getInstance(document.getElementById('modalReservasHistoricas')).hide();" title="Editar">
                <i class="bi bi-pencil"></i>
              </button>
              <button class="btn btn-dark" onclick="aplicacionAgenda.cambiarEstadoReserva(${
                reserva.id_reserva
              }, 1); bootstrap.Modal.getInstance(document.getElementById('modalReservasHistoricas')).hide();" title="Restaurar">
                <i class="bi bi-arrow-counterclockwise"></i>
              </button>
            </div>
          </td>
        </tr>
      `;
    });

    html += `</tbody></table></div>`;

    const contenedor = document.getElementById("contenidoReservasHistoricas");
    contenedor.innerHTML = html;

    const modal = new bootstrap.Modal(
      document.getElementById("modalReservasHistoricas")
    );
    modal.show();
  }

  // Ver solicitudes pendientes de un horario
  async verSolicitudesPendientes(fecha, hora) {
    const reservasPendientes = this.reservas.filter((reserva) => {
      return (
        reserva.fecha === fecha &&
        reserva.hora_inicio === hora &&
        reserva.id_estado === 1
      ); // Solo pendientes
    });

    if (reservasPendientes.length === 0) {
      if (typeof showToast !== "undefined") {
        showToast("No hay solicitudes pendientes en este horario", "info");
      }
      return;
    }

    // Construir HTML de la lista
    let html = `<div class="list-group">`;

    reservasPendientes.forEach((reserva, index) => {
      html += `
        <div class="list-group-item">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <h6 class="mb-1">
                <span class="badge bg-warning text-dark me-2">${
                  index + 1
                }</span>
                ${
                  reserva.id_tipo_reserva === 2
                    ? `<i class="bi bi-trophy text-warning me-1"></i>${
                        reserva.nombre_torneo || "Torneo"
                      } - ${reserva.fase_nombre || "Fase"} - ${
                        reserva.equipo_a_nombre || "Equipo A"
                      } vs ${reserva.equipo_b_nombre || "Equipo B"}`
                    : reserva.titular_nombre_completo
                }
              </h6>
              <p class="mb-1">
                <small class="text-muted">
                  <i class="bi bi-clock"></i> ${reserva.hora_inicio.substring(
                    0,
                    5
                  )} - ${reserva.hora_fin.substring(0, 5)}
                </small>
                ${
                  reserva.tipo_reserva
                    ? `<br><small><i class="bi bi-tag"></i> ${reserva.tipo_reserva}</small>`
                    : ""
                }
                ${
                  reserva.titular_telefono
                    ? `<br><small><i class="bi bi-telephone"></i> ${reserva.titular_telefono}</small>`
                    : ""
                }
              </p>
              ${
                reserva.titulo
                  ? `<p class="mb-1"><strong>${reserva.titulo}</strong></p>`
                  : ""
              }
              ${
                reserva.descripcion
                  ? `<p class="mb-1 text-muted small">${reserva.descripcion}</p>`
                  : ""
              }
            </div>
            <div class="btn-group btn-group-sm ms-2" role="group">
              <button class="btn btn-dark" onclick="aplicacionAgenda.cambiarEstadoReserva(${
                reserva.id_reserva
              }, 3); document.getElementById('btnCerrarPendientes').click();" title="Aceptar">
                <i class="bi bi-check-lg"></i>
              </button>
              <button class="btn btn-dark" onclick="aplicacionAgenda.cambiarEstadoReserva(${
                reserva.id_reserva
              }, 4); document.getElementById('btnCerrarPendientes').click();" title="Rechazar">
                <i class="bi bi-x-lg"></i>
              </button>
              <button class="btn btn-dark" onclick="aplicacionAgenda.verDetalleReserva(${
                reserva.id_reserva
              }); bootstrap.Modal.getInstance(document.getElementById('modalSolicitudesPendientes')).hide();" title="Ver detalles">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>
      `;
    });

    html += `</div>`;
    html += `<button id="btnCerrarPendientes" style="display:none" data-bs-dismiss="modal"></button>`;

    // Mostrar en modal
    const contenedor = document.getElementById(
      "contenidoSolicitudesPendientes"
    );
    contenedor.innerHTML = html;

    const modal = new bootstrap.Modal(
      document.getElementById("modalSolicitudesPendientes")
    );
    modal.show();
  }

  // Ver solicitudes pendientes de un día completo
  async verSolicitudesPendientesDia(fecha) {
    const reservasPendientes = this.reservas.filter((reserva) => {
      return reserva.fecha === fecha && reserva.id_estado === 1; // Solo pendientes del día
    });

    if (reservasPendientes.length === 0) {
      if (typeof showToast !== "undefined") {
        showToast("No hay solicitudes pendientes en este día", "info");
      }
      return;
    }

    // Construir HTML de la lista agrupada por horario
    let html = `<div class="list-group">`;

    reservasPendientes.forEach((reserva, index) => {
      html += `
        <div class="list-group-item">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <h6 class="mb-1">
                <span class="badge bg-warning text-dark me-2">${
                  index + 1
                }</span>
                ${
                  reserva.id_tipo_reserva === "torneo"
                    ? `<i class="bi bi-trophy text-warning me-1"></i>${
                        reserva.nombre_torneo || "Torneo"
                      } - ${reserva.fase_nombre || "Fase"} - ${
                        reserva.equipo_a_nombre || "Equipo A"
                      } vs ${reserva.equipo_b_nombre || "Equipo B"}`
                    : reserva.titular_nombre_completo
                }
              </h6>
              <p class="mb-1">
                <small class="text-muted">
                  <i class="bi bi-clock"></i> ${reserva.hora_inicio.substring(
                    0,
                    5
                  )} - ${reserva.hora_fin.substring(0, 5)}
                </small>
                ${
                  reserva.tipo_reserva
                    ? `<br><small><i class="bi bi-tag"></i> ${reserva.tipo_reserva}</small>`
                    : ""
                }
                ${
                  reserva.titular_telefono
                    ? `<br><small><i class="bi bi-telephone"></i> ${reserva.titular_telefono}</small>`
                    : ""
                }
              </p>
              ${
                reserva.titulo
                  ? `<p class="mb-1"><strong>${reserva.titulo}</strong></p>`
                  : ""
              }
              ${
                reserva.descripcion
                  ? `<p class="mb-1 text-muted small">${reserva.descripcion}</p>`
                  : ""
              }
            </div>
            <div class="btn-group btn-group-sm ms-2" role="group">
              <button class="btn btn-dark" onclick="aplicacionAgenda.cambiarEstadoReserva(${
                reserva.id_reserva
              }, 3); document.getElementById('btnCerrarPendientesDia').click();" title="Aceptar">
                <i class="bi bi-check-lg"></i>
              </button>
              <button class="btn btn-dark" onclick="aplicacionAgenda.cambiarEstadoReserva(${
                reserva.id_reserva
              }, 4); document.getElementById('btnCerrarPendientesDia').click();" title="Rechazar">
                <i class="bi bi-x-lg"></i>
              </button>
              <button class="btn btn-dark" onclick="aplicacionAgenda.verDetalleReserva(${
                reserva.id_reserva
              }); bootstrap.Modal.getInstance(document.getElementById('modalSolicitudesPendientes')).hide();" title="Ver detalles">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
        </div>
      `;
    });

    html += `</div>`;
    html += `<button id="btnCerrarPendientesDia" style="display:none" data-bs-dismiss="modal"></button>`;

    // Mostrar en modal
    const contenedor = document.getElementById(
      "contenidoSolicitudesPendientes"
    );
    contenedor.innerHTML = html;

    const modal = new bootstrap.Modal(
      document.getElementById("modalSolicitudesPendientes")
    );
    modal.show();
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
  // Configurar event listener para el modal de notificaciones
  configurarModalNotificaciones() {
    const modalNotificaciones = document.getElementById("modalNotificaciones");
    if (modalNotificaciones) {
      modalNotificaciones.addEventListener("show.bs.modal", () => {
        this.cargarNotificacionesNavbar();
      });
    }
  }

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

  async eliminarReserva() {
    const idReserva = parseInt(document.getElementById("idReserva").value);

    const confirmed = await this.mostrarModalConfirmacion(
      "¿Está seguro que desea eliminar esta reserva?",
      "Eliminar Reserva"
    );

    if (confirmed) {
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

  // Mostrar modal de confirmación genérico
  mostrarModalConfirmacion(mensaje, titulo = "Confirmación") {
    return new Promise((resolve) => {
      // Crear modal dinámicamente
      const modalId = "modalConfirmacionGenerica";
      let modalElement = document.getElementById(modalId);

      if (!modalElement) {
        modalElement = document.createElement("div");
        modalElement.id = modalId;
        modalElement.className = "modal fade";
        modalElement.tabIndex = -1;
        modalElement.innerHTML = `
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="${modalId}Title">${titulo}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body" id="${modalId}Body">
                ${mensaje}
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="${modalId}Cancelar">Cancelar</button>
                <button type="button" class="btn btn-primary" id="${modalId}Confirmar">Confirmar</button>
              </div>
            </div>
          </div>
        `;
        document.body.appendChild(modalElement);
      } else {
        document.getElementById(`${modalId}Title`).textContent = titulo;
        document.getElementById(`${modalId}Body`).textContent = mensaje;
      }

      const modal = new bootstrap.Modal(modalElement);
      const btnConfirmar = document.getElementById(`${modalId}Confirmar`);
      const btnCancelar = document.getElementById(`${modalId}Cancelar`);

      const handleConfirm = () => {
        modal.hide();
        resolve(true);
        cleanup();
      };

      const handleCancel = () => {
        modal.hide();
        resolve(false);
        cleanup();
      };

      const cleanup = () => {
        btnConfirmar.removeEventListener("click", handleConfirm);
        btnCancelar.removeEventListener("click", handleCancel);
        modalElement.removeEventListener("hidden.bs.modal", handleCancel);
      };

      btnConfirmar.addEventListener("click", handleConfirm);
      btnCancelar.addEventListener("click", handleCancel);
      modalElement.addEventListener("hidden.bs.modal", handleCancel, {
        once: true,
      });

      modal.show();
    });
  }

  // Validar superposición con reservas confirmadas
  validarSuperposicionReserva(reserva) {
    // Buscar reservas confirmadas que se superpongan
    const superposiciones = this.reservas.filter((r) => {
      // No comparar consigo misma
      if (r.id_reserva === reserva.id_reserva) return false;

      // Solo verificar contra reservas confirmadas (estado 3)
      if (r.id_estado !== 3) return false;

      // Misma cancha
      if (r.id_cancha !== reserva.id_cancha) return false;

      // Verificar superposición de fechas
      const fechaInicio1 = new Date(reserva.fecha);
      const fechaFin1 = new Date(reserva.fecha_fin || reserva.fecha);
      const fechaInicio2 = new Date(r.fecha);
      const fechaFin2 = new Date(r.fecha_fin || r.fecha);

      const fechasSeSuperponen =
        (fechaInicio1 >= fechaInicio2 && fechaInicio1 <= fechaFin2) ||
        (fechaFin1 >= fechaInicio2 && fechaFin1 <= fechaFin2) ||
        (fechaInicio1 <= fechaInicio2 && fechaFin1 >= fechaFin2);

      if (!fechasSeSuperponen) return false;

      // Verificar superposición de horas (formato HH:MM:SS)
      const horaInicio1 = reserva.hora_inicio;
      const horaFin1 = reserva.hora_fin;
      const horaInicio2 = r.hora_inicio;
      const horaFin2 = r.hora_fin;

      const horasSeSuperponen =
        (horaInicio1 >= horaInicio2 && horaInicio1 < horaFin2) ||
        (horaFin1 > horaInicio2 && horaFin1 <= horaFin2) ||
        (horaInicio1 <= horaInicio2 && horaFin1 >= horaFin2);

      return horasSeSuperponen;
    });

    return superposiciones.length > 0;
  }

  mostrarMensajeExito(mensaje) {
    showToast(mensaje, "success");
  }

  generarNuevoId() {
    return Math.max(...this.reservas.map((r) => r.id), 0) + 1;
  }

  // Generar datos de muestra para demostración
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

function verDetalleReserva(idReserva) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verDetalleReserva(idReserva);
  }
}

// Override de callbacks para funcionalidades del admin
function onReservaClick(idReserva) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verDetalleReserva(idReserva);
  }
}

function onPendientesClick(fecha, hora) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verSolicitudesPendientes(fecha, hora);
  }
}

function onPendientesDiaClick(fecha) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verSolicitudesPendientesDia(fecha);
  }
}

function onHistoricasClick(fecha, hora) {
  if (aplicacionAgenda) {
    aplicacionAgenda.verReservasHistoricas(fecha, hora);
  }
}

function onCalendarioDiaClick(fecha) {
  console.log("Admin - Día seleccionado:", fecha);
  if (aplicacionAgenda) {
    // Actualizar la fecha actual a la seleccionada
    aplicacionAgenda.fechaActual = new Date(fecha);
    aplicacionAgenda.actualizarDisplayFecha();
    aplicacionAgenda.cambiarVista("dia");
  }
}

function onCalendarioHorarioClick(fecha, hora) {
  console.log("Admin - Horario seleccionado:", fecha, hora);
  if (aplicacionAgenda) {
    aplicacionAgenda.crearReserva(fecha, hora);
  }
}
