window.onload = function () {
  cargarPartidosJugador();
};

async function cargarPartidosJugador() {
  try {
    const response = await fetch(GET_PARTIDOS_JUGADOR, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    let partidos = await response.json();
    partidos = partidos.sort((a, b) => {
      // Ordenar por fecha y hora ascendente
      const [diaA, mesA, añoA] = a.fecha_partido.split("/");
      const [diaB, mesB, añoB] = b.fecha_partido.split("/");
      const fechaA = new Date(
        añoA,
        mesA - 1,
        diaA,
        ...a.hora_partido.split(":")
      );
      const fechaB = new Date(
        añoB,
        mesB - 1,
        diaB,
        ...b.hora_partido.split(":")
      );
      return fechaA - fechaB;
    });
    console.log(partidos);

    // Organizar partidos por secciones
    const hoy = new Date();
    const finEstaSemana = new Date(hoy);
    finEstaSemana.setDate(hoy.getDate() + (7 - hoy.getDay())); // Fin del domingo de esta semana

    const finProximaSemana = new Date(finEstaSemana);
    finProximaSemana.setDate(finEstaSemana.getDate() + 7); // Fin del domingo de próxima semana

    const estaSemana = [];
    const proximaSemana = [];
    const masAdelante = [];
    const fechaHoy = new Date();

    partidos.forEach((partido) => {
      // Validar que exista fecha_partido
      if (!partido.fecha_partido) {
        console.warn("Partido sin fecha:", partido);
        masAdelante.push(partido); // Poner partidos sin fecha en "más adelante"
        return;
      }

      // Convertir fecha del partido (formato dd/mm/yyyy)
      const [dia, mes, año] = partido.fecha_partido.split("/");
      const fechaPartido = new Date(año, mes - 1, dia);

      // Validar que la fecha sea válida
      if (isNaN(fechaPartido.getTime())) {
        console.warn("Fecha inválida en partido:", partido);
        masAdelante.push(partido);
        return;
      }

      if (partido.id_estado_reserva === 1 || partido.id_estado_reserva === 3) {
        if (fechaPartido >= fechaHoy) {
          if (fechaPartido <= finEstaSemana) {
            estaSemana.push(partido);
          } else if (fechaPartido <= finProximaSemana) {
            proximaSemana.push(partido);
          } else {
            masAdelante.push(partido);
          }
        }
      }
    });

    // Renderizar cada sección
    renderizarSeccion("estaSemana", estaSemana, "esta semana");
    renderizarSeccion("proximaSemana", proximaSemana, "próxima semana");
    renderizarSeccion("masAdelante", masAdelante, "más adelante");

    // Agregar event listeners UNA SOLA VEZ después de renderizar todas las secciones
    agregarEventListenersPartidos();
  } catch (error) {
    console.error("Error al cargar los partidos:", error);
  }
}

function renderizarSeccion(seccionId, partidos, nombreSeccion) {
  const contenedor = document.getElementById(seccionId);

  if (partidos.length === 0) {
    contenedor.innerHTML = `
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        No tenés partidos pendientes en ${nombreSeccion}
      </div>
    `;
    return;
  }

  let claseEstado = "";

  partidos.forEach((partido) => {
    let estado;

    if (partido.id_estado_reserva === 1) {
      estado = "Pendiente";
      claseEstado = "alert alert-warning";
    } else if (partido.id_estado_reserva === 3) {
      switch (partido.id_estado_participante) {
        case 1:
          estado = "Pendiente";
          claseEstado = "alert alert-warning";
          break;
        case 2:
          estado = "En revisión";
          claseEstado = "alert alert-info";
          break;
        case 3:
          estado = "Confirmado";
          claseEstado = "alert alert-success";
          break;
        case 4:
          estado = "Rechazado";
          claseEstado = "alert alert-danger";
          break;
        case 5:
          estado = "Cancelado";
          claseEstado = "alert alert-secondary";
          break;
        default:
          estado = "Desconocido";
          claseEstado = "alert alert-dark";
          break;
      }
    }

    // Determinar equipos
    let equipoAsignado;
    let equipoRival;
    let equiposDefinidos = false;
    let cantMiEquipo, cantEquipoRival;

    if (!partido.id_equipo_del_jugador) {
      // Si equipo NO definido
      if (partido.equipo_asignado === 1) {
        // Asignado a Equipo A
        equipoAsignado = "Equipo A";
        equipoRival = "Equipo B";
        cantMiEquipo = partido.cant_participantes_equipo_a;
        cantEquipoRival = partido.cant_participantes_equipo_b;
      } else if (partido.equipo_asignado === 2) {
        // Asignado a Equipo B
        equipoAsignado = "Equipo B";
        equipoRival = "Equipo A";
        cantMiEquipo = partido.cant_participantes_equipo_b;
        cantEquipoRival = partido.cant_participantes_equipo_a;
      } else if (!partido.equipo_asignado || partido.equipo_asignado === 0) {
        // No asignado a ningún equipo aún
        equipoAsignado = "Equipo A";
        equipoRival = "Equipo B";
        cantMiEquipo = partido.cant_participantes_equipo_a;
        cantEquipoRival = partido.cant_participantes_equipo_b;
      }
    } else {
      // Equipo definido
      equipoAsignado = partido.nombre_equipo_del_jugador;
      equipoRival = partido.nombre_equipo_rival || "Buscando rival...";
      equiposDefinidos = true;
      cantMiEquipo =
        partido.equipo_asignado === 1
          ? partido.cant_participantes_equipo_a
          : partido.cant_participantes_equipo_b;
      cantEquipoRival =
        partido.equipo_asignado === 1
          ? partido.cant_participantes_equipo_b
          : partido.cant_participantes_equipo_a;
    }

    const maxParticipantes = partido.max_participantes;

    // Determinar banner y botones
    let bannerHTML = "";
    let botonesHTML = "";

    if (partido.id_tipo_reserva === 1) {
      if (partido.id_rol === 1 && partido.id_estado_reserva === 3) {
        // ANFITRIÓN
        bannerHTML = `
          <div class="alert alert-info py-2 mb-0">
            <i class="bi bi-star-fill me-2"></i>
            <strong>Sos anfitrión</strong> de este partido
          </div>
        `;

        botonesHTML = `
          <button class="btn btn-sm btn-dark btn-gestionar-participantes" data-id-partido="${partido.id_partido}" data-abierto="${partido.abierto}">
            <i class="bi bi-people me-2"></i>Gestionar participantes
          </button>
          <button class="btn btn-sm btn-dark btn-cancelar-partido" data-id-partido="${partido.id_partido}" data-id-reserva="${partido.id_reserva}">
            <i class="bi bi-x-circle me-2"></i>Cancelar partido
          </button>
        `;
      } else if (partido.id_rol === 1 && partido.id_estado_reserva === 1) {
        // ANFITRIÓN PENDIENTE
        bannerHTML = `
        <div class= "alert alert-warning py-2 mb-0">
          <i class="bi bi-star-fill me-2"></i>
          <strong>Tu solicitud aún está pendiente</strong> de aprobación
        </div>
      `;
        botonesHTML = `
        <button class="btn btn-sm btn-dark btn-cancelar-partido" data-id-partido="${partido.id_partido}" data-id-reserva="${partido.id_reserva}">
          <i class="bi bi-x-circle me-2"></i>Cancelar partido
        </button>
      `;
      } else if (partido.id_estado_participante === 3) {
        // INVITADO O SOLICITANTE
        if (partido.id_rol === 2) {
          bannerHTML = `
            <div class="alert alert-primary py-2 mb-0">
              <i class="bi bi-person-check-fill me-2"></i>
              <strong>Sos invitado</strong> en este partido
            </div>
          `;
        } else if (partido.id_rol === 3) {
          bannerHTML = `
            <div class="alert alert-warning py-2 mb-0">
              <i class="bi bi-hourglass-split me-2"></i>
              <strong>Sos solicitante</strong> en este partido.
            </div>
          `;
        }

        botonesHTML = `
          <button class="btn btn-sm btn-dark btn-ver-participantes" data-id-partido="${partido.id_partido}">
            <i class="bi bi-people me-2"></i>Ver participantes
          </button>
          <button class="btn btn-sm btn-dark btn-cancelar-participacion" data-id-partido="${partido.id_partido}">
            <i class="bi bi-person-dash me-2"></i>Cancelar participación
          </button>
        `;
      } else if (partido.id_estado_participante === 1 && partido.id_rol === 3) {
        // SOLICITANTE PENDIENTE
        bannerHTML = `
        <div class="alert alert-warning py-2 mb-0">
          <i class="bi bi-hourglass-split me-2"></i>
          <strong>Sos solicitante</strong> en este partido (pendiente de aprobación)
        </div>
      `;
        botonesHTML = `
        <button class="btn btn-sm btn-dark btn-cancelar-participacion" data-id-partido="${partido.id_partido}">
          <i class="bi bi-person-dash me-2"></i>Cancelar participación
        </button>
      `;
      }
    } else if (partido.id_tipo_reserva === 2) {
      // TORNEO
      bannerHTML = `
        <div class="alert alert-primary py-2 mb-0">
          <i class="bi bi-trophy-fill me-2"></i>
          <strong>Tu equipo participará</strong> en este torneo
        </div>
      `;

      botonesHTML = `
        <a href="#" class="btn btn-sm btn-primary">
          <i class="bi bi-info-circle me-2"></i>Ver información del torneo
        </a>
        <a href="#" class="btn btn-sm btn-dark">
          <i class="bi bi-x-circle me-2"></i>No asistiré
        </a>
      `;
    }

    // Renderizar equipos
    let equipoAsignadoHTML;
    let equipoRivalHTML;

    if (equiposDefinidos) {
      equipoAsignadoHTML = `
        <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
          <i class="bi bi-people-fill me-1"></i>${equipoAsignado}
        </a>
      `;

      if (partido.nombre_equipo_rival) {
        equipoRivalHTML = `
          <a href="#" class="btn btn-sm btn-dark w-100 mb-1">
            <i class="bi bi-people-fill me-1"></i>${equipoRival}
          </a>
        `;
      } else {
        equipoRivalHTML = `
          <div class="btn btn-sm btn-dark w-100 mb-1 text-muted disabled">
            <i class="bi bi-search me-1"></i>Buscando...
          </div>
        `;
      }
    } else {
      equipoAsignadoHTML = `
        <div class="btn btn-sm btn-dark w-100 mb-1 disabled">
          <i class="bi bi-people-fill me-1"></i>${equipoAsignado}
        </div>
      `;
      equipoRivalHTML = `
        <div class="btn btn-sm btn-dark w-100 mb-1 disabled">
          <i class="bi bi-people-fill me-1"></i>${equipoRival}
        </div>
      `;
    }

    let claseRol = "";
    switch (partido.id_rol) {
      case 1:
        claseRol = "alert alert-success";
        break;
      case 2:
        claseRol = "alert alert-info";
        break;
      case 3:
        claseRol = "alert alert-warning";
        break;
      default:
        claseRol = "alert alert-dark";
        break;
    }

    // Color del contador
    const colorMiEquipo =
      cantMiEquipo === maxParticipantes ? "text-success" : "text-warning";
    const colorRival =
      cantEquipoRival === maxParticipantes ? "text-success" : "text-warning";

    let infoIconHTML = "";
    if (!partido.equipo_asignado && partido.id_rol === 3) {
      infoIconHTML = `<i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" 
															title="Sujeto a cambios por el anfitrión"></i>`;
    }

    const partidoItem = document.createElement("div");
    partidoItem.className = "partido-fila mb-3";
    partidoItem.innerHTML = `
      <div class="row">
        <!-- Fecha y hora -->
        <div class="col-12 col-md-2 partido-datetime">
          <div class="fw-bold">${partido.dia_semana}</div>
          <div class="fw-bold">${partido.fecha_partido}</div>
          <div class="fw-bold">${partido.hora_partido}</div>
        </div>

        <!-- Info de la cancha -->
        <div class="col-6 col-md-4 partido-cancha">
          <h6 class="fw-bold mb-1">${partido.nombre_cancha} - ${partido.tipo_partido}</h6>
          <span class="text-muted small">${partido.direccion_cancha}</span>
        </div>

        <!-- Botón mapa -->
        <div class="col-6 col-md-2 partido-mapa">
          <a href="https://www.google.com/maps?q=${partido.latitud_cancha},${partido.longitud_cancha}"
             class="btn btn-sm btn-dark"
             target="_blank" rel="noopener noreferrer">
            <i class="bi bi-geo-alt"></i> Ver en mapa
          </a>
        </div>

        <!-- Badges -->
        <div class="col-6 col-md-2 partido-chips">
          <span class="badge ${claseEstado}">${estado}</span>
          <span class="badge ${claseRol}">${partido.rol_usuario}</span>
        </div>

        <!-- Botón detalles -->
        <div class="col-6 col-md-2 partido-acciones">
          <button class="btn btn-sm btn-dark"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#detallesPartido${partido.id_partido}"
                  aria-expanded="false">
            <i class="bi bi-chevron-down"></i> Ver detalles
          </button>
        </div>
      </div>

      <!-- Detalles expandibles -->
      <div class="collapse mt-3" id="detallesPartido${partido.id_partido}">
        <div class="border-top pt-3">
          <div class="row g-3">
            <div class="col-md-6">
              <!-- Equipos -->
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <div class="text-center">
                    <small class="equipo-label text-muted d-block mb-1">Tu equipo ${infoIconHTML}
										</small>
                    ${equipoAsignadoHTML}
                    <small class="equipo-contador ${colorMiEquipo} fw-bold">${cantMiEquipo}/${maxParticipantes}</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="text-center">
                    <small class="equipo-label text-muted d-block mb-1">Equipo rival</small>
                    ${equipoRivalHTML}
                    <small class="equipo-contador ${colorRival} fw-bold">${cantEquipoRival}/${maxParticipantes}</small>
                  </div>
                </div>
              </div>
              ${bannerHTML}
            </div>
            <div class="col-md-6">
              <div class="d-grid gap-2">
                ${botonesHTML}
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    contenedor.appendChild(partidoItem);
  });
}

// Función para agregar event listeners a los botones (se llama UNA SOLA VEZ)
function agregarEventListenersPartidos() {
  // Agregar event listeners a los botones de gestionar participantes
  document.querySelectorAll(".btn-gestionar-participantes").forEach((btn) => {
    btn.addEventListener("click", function () {
      const idPartido = this.getAttribute("data-id-partido");
      const abierto = this.getAttribute("data-abierto");
      abrirModalGestionarParticipantes(idPartido, abierto);
    });
  });

  document.querySelectorAll(".btn-cancelar-partido").forEach((btn) => {
    btn.addEventListener("click", function () {
      const idPartido = this.getAttribute("data-id-partido");
      cancelarPartido(idPartido);
    });
  });

  // Agregar event listeners a los botones de ver participantes
  document.querySelectorAll(".btn-ver-participantes").forEach((btn) => {
    btn.addEventListener("click", function () {
      const idPartido = this.getAttribute("data-id-partido");
      abrirModalVerParticipantes(idPartido);
    });
  });

  // Agregar event listeners a los botones de cancelar participación
  document.querySelectorAll(".btn-cancelar-participacion").forEach((btn) => {
    btn.addEventListener("click", function () {
      const idPartido = this.getAttribute("data-id-partido");
      cancelarParticipacion(idPartido);
    });
  });
}

// Variables globales para el modal
let partidoActualId = null;
let jugadorBuscadoId = null;

// Función para cancelar partido
async function cancelarPartido(idPartido) {
  // Obtener datos del partido
  const partidoElement = document.querySelector(
    `[data-id-partido="${idPartido}"]`
  );
  if (!partidoElement) {
    showToast("No se pudo encontrar el partido", "error");
    return;
  }

  const idReserva = partidoElement.getAttribute("data-id-reserva");
  if (!idReserva) {
    showToast("No se pudo obtener la información de la reserva", "error");
    return;
  }

  // Mostrar modal de confirmación
  const confirmar = await mostrarConfirmacionPartido(
    "¿Estás seguro de que deseas cancelar este partido?",
    "Esta acción cambiará el estado de la reserva a cancelada y notificará a todos los participantes."
  );

  if (!confirmar) {
    return;
  }

  try {
    // Llamar a updateReserva.php para cambiar estado a cancelado (5)
    const response = await fetch(UPDATE_RESERVA, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_reserva: parseInt(idReserva),
        id_estado: 5, // Estado cancelado
      }),
    });

    const responseText = await response.text();

    let data;
    try {
      data = JSON.parse(responseText);
    } catch (parseError) {
      console.error("Error al parsear JSON:", responseText);
      showToast("Error: El servidor no devolvió un formato válido", "error");
      return;
    }

    if (response.ok && data.status === "success") {
      showToast("Partido cancelado exitosamente", "success", 3000);

      // Recargar la página después de 1.5 segundos
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(data.message || "Error al cancelar el partido", "error");
    }
  } catch (error) {
    console.error("Error al cancelar partido:", error);
    showToast("Error al cancelar el partido", "error");
  }
}

// Función para mostrar modal de confirmación personalizado
function mostrarConfirmacionPartido(mensaje, detalle) {
  return new Promise((resolve) => {
    // Crear modal dinámicamente
    const modalHTML = `
      <div class="modal fade" id="modalConfirmarCancelar" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                Confirmar cancelación
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <p class="fw-bold">${mensaje}</p>
              <p class="text-muted">${detalle}</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnCancelarAccion">
                No, volver
              </button>
              <button type="button" class="btn btn-danger" id="btnConfirmarAccion">
                Sí, cancelar partido
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    // Insertar modal en el DOM
    const tempDiv = document.createElement("div");
    tempDiv.innerHTML = modalHTML;
    document.body.appendChild(tempDiv.firstElementChild);

    const modalElement = document.getElementById("modalConfirmarCancelar");
    const modal = new bootstrap.Modal(modalElement);

    // Event listeners
    document
      .getElementById("btnConfirmarAccion")
      .addEventListener("click", () => {
        modal.hide();
        resolve(true);
      });

    document
      .getElementById("btnCancelarAccion")
      .addEventListener("click", () => {
        modal.hide();
        resolve(false);
      });

    // Limpiar modal después de cerrar
    modalElement.addEventListener("hidden.bs.modal", () => {
      modalElement.remove();
      // Limpiar backdrops
      const backdrops = document.querySelectorAll(".modal-backdrop");
      backdrops.forEach((backdrop) => backdrop.remove());
      document.body.classList.remove("modal-open");
      document.body.style.overflow = "";
      document.body.style.paddingRight = "";
    });

    modal.show();
  });
}

// Abrir modal de gestionar participantes
async function abrirModalGestionarParticipantes(idPartido, abierto) {
  partidoActualId = idPartido;

  // Configurar botón de toggle convocatoria
  const btnToggle = document.getElementById("btnToggleConvocatoria");
  const textoToggle = document.getElementById("textoToggleConvocatoria");
  const iconoToggle = btnToggle.querySelector("i");

  btnToggle.setAttribute("data-abierto", abierto);
  if (abierto == 1) {
    textoToggle.textContent = "Cerrar convocatoria";
    iconoToggle.className = "bi bi-door-closed me-2";
  } else {
    textoToggle.textContent = "Abrir convocatoria";
    iconoToggle.className = "bi bi-door-open me-2";
  }

  // Abrir modal - obtener o crear instancia
  const modalElement = document.getElementById("modalGestionarParticipantes");
  let modal = bootstrap.Modal.getInstance(modalElement);

  if (!modal) {
    modal = new bootstrap.Modal(modalElement);
  }

  modal.show();

  // Cargar participantes
  await cargarParticipantes(idPartido);
}

// Abrir modal de ver participantes (solo lectura)
async function abrirModalVerParticipantes(idPartido) {
  partidoActualId = idPartido;

  // Abrir modal
  const modalElement = document.getElementById("modalVerParticipantes");
  let modal = bootstrap.Modal.getInstance(modalElement);

  if (!modal) {
    modal = new bootstrap.Modal(modalElement);
  }

  modal.show();

  // Cargar solo participantes confirmados
  await cargarParticipantesVer(idPartido);
}

// Cargar participantes del partido (versión solo lectura)
async function cargarParticipantesVer(idPartido) {
  try {
    const response = await fetch(
      `${GET_PARTICIPANTES_PARTIDO}?id_partido=${idPartido}`
    );

    const responseText = await response.text();

    if (!response.ok) {
      console.error("Error HTTP:", response.status, responseText);
      throw new Error("Error al cargar participantes");
    }

    let data;
    try {
      data = JSON.parse(responseText);
    } catch (parseError) {
      console.error("Error al parsear JSON. Respuesta recibida:", responseText);
      return;
    }

    if (!data.success) {
      console.error("Error del servidor:", data);
      showToast("Error del servidor", "error");
      throw new Error(data.error || "Error desconocido");
    }

    const participantes = data.data;
    const confirmados = participantes.filter((p) => p.id_estado == 3);

    // Renderizar confirmados en la tabla de solo lectura
    renderizarConfirmadosVer(confirmados);
  } catch (error) {
    console.error("Error al cargar participantes:", error);
    showToast("Error al cargar participantes", "error");
  }
}

// Renderizar participantes confirmados (solo lectura)
function renderizarConfirmadosVer(confirmados) {
  const tbody = document.getElementById("tablaConfirmadosVer");

  if (confirmados.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="4" class="text-center text-muted">No hay participantes confirmados</td></tr>';
    return;
  }

  tbody.innerHTML = "";

  confirmados.forEach((p) => {
    const username =
      p.username_jugador || `<span class="text-muted">Externo</span>`;
    const nombreCompleto =
      p.nombre_invitado || `${p.nombre_jugador} ${p.apellido_jugador}`;
    const equipo =
      p.equipo == 1 ? "Equipo A" : p.equipo == 2 ? "Equipo B" : "Sin asignar";
    const rol = p.rol_participante;

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${username}</td>
      <td>${nombreCompleto}</td>
      <td><span class="badge bg-dark">${equipo}</span></td>
      <td>${rol}</td>
    `;

    tbody.appendChild(tr);
  });
}

// Cargar participantes del partido
async function cargarParticipantes(idPartido) {
  try {
    const response = await fetch(
      `${GET_PARTICIPANTES_PARTIDO}?id_partido=${idPartido}`
    );

    // Obtener el texto de la respuesta primero para debug
    const responseText = await response.text();
    //console.log("Respuesta del servidor (participantes):", responseText);

    if (!response.ok) {
      console.error("Error HTTP:", response.status, responseText);
      throw new Error("Error al cargar participantes");
    }

    // Intentar parsear como JSON
    let data;
    try {
      data = JSON.parse(responseText);
    } catch (parseError) {
      console.error("Error al parsear JSON. Respuesta recibida:", responseText);
      return;
    }

    if (!data.success) {
      console.error("Error del servidor:", data);
      showToast("Error del servidor", "error");
      throw new Error(data.error || "Error desconocido");
    }

    const participantes = data.data;
    console.log("Participantes cargados:", participantes);

    // Separar confirmados y pendientes
    const confirmados = participantes.filter((p) => p.id_estado == 3);
    const pendientes = participantes.filter((p) => p.id_estado == 1);
    console.log("Participantes pendientes:", pendientes);

    // Renderizar confirmados
    renderizarConfirmados(confirmados);

    // Renderizar pendientes
    renderizarPendientes(pendientes);
  } catch (error) {
    console.error("Error al cargar participantes:", error);
    showToast("Error al cargar participantes", "error");
  }
}

// Renderizar participantes confirmados
function renderizarConfirmados(confirmados) {
  const tbody = document.getElementById("tablaConfirmados");

  if (confirmados.length === 0) {
    tbody.innerHTML =
      '<tr><td colspan="5" class="text-center text-muted">No hay participantes confirmados</td></tr>';
    return;
  }

  tbody.innerHTML = "";

  confirmados.forEach((p) => {
    const username =
      p.username_jugador || `<span class="text-muted">Externo</span>`;
    const nombreCompleto =
      p.nombre_invitado || `${p.nombre_jugador} ${p.apellido_jugador}`;
    const equipo =
      p.equipo == 1 ? "Equipo A" : p.equipo == 2 ? "Equipo B" : "Sin asignar";
    const rol = p.rol_participante;

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${username}</td>
      <td>${nombreCompleto}</td>
      <td>
        <select class="form-select form-select-sm" data-id-participante="${
          p.id_participante
        }" onchange="cambiarEquipoParticipante(this)" ${
      p.id_rol == 1 ? "disabled" : ""
    }>
          <option value="1" ${p.equipo == 1 ? "selected" : ""}>Equipo A</option>
          <option value="2" ${p.equipo == 2 ? "selected" : ""}>Equipo B</option>
        </select>
      </td>
      <td>${rol}</td>
      <td>
        ${
          p.id_rol != 1
            ? `
          <button class="btn btn-sm btn-danger" onclick="eliminarParticipante(${p.id_participante})">
            <i class="bi bi-trash"></i>
          </button>
        `
            : '<span class="text-muted">Anfitrión</span>'
        }
      </td>
    `;

    tbody.appendChild(tr);
  });
}

// Renderizar solicitudes pendientes
function renderizarPendientes(pendientes) {
  const tbody = document.getElementById("tablaPendientes");
  const alertSin = document.getElementById("alertSinSolicitudes");
  const contenedorTabla = document.getElementById("contenedorTablaPendientes");

  if (pendientes.length === 0) {
    alertSin.style.display = "block";
    contenedorTabla.style.display = "none";
    return;
  }

  alertSin.style.display = "none";
  contenedorTabla.style.display = "block";
  tbody.innerHTML = "";

  pendientes.forEach((p) => {
    const username = p.username_jugador;
    const nombreCompleto = `${p.nombre_jugador} ${p.apellido_jugador}`;
    const rol = p.rol_participante;

    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td>${username}</td>
      <td>${nombreCompleto}</td>
      <td>
        <select class="form-select form-select-sm" id="equipoSolicitante${p.id_jugador}" required>
          <option value="">Seleccionar</option>
          <option value="1">Equipo A</option>
          <option value="2">Equipo B</option>
        </select>
        <div class="invalid-feedback">Selecciona un equipo</div>
      </td>
      <td>${rol}</td>
      <td>
        <button class="btn btn-sm btn-success me-1" onclick="aceptarSolicitante(${p.id_jugador})">
          <i class="bi bi-check-lg"></i> Aceptar
        </button>
        <button class="btn btn-sm btn-danger" onclick="rechazarSolicitante(${p.id_jugador})">
          <i class="bi bi-x-lg"></i> Rechazar
        </button>
      </td>
    `;

    tbody.appendChild(tr);
  });
}

// Event listeners del modal
document.addEventListener("DOMContentLoaded", function () {
  // Limpiar backdrop al cerrar el modal de gestionar
  const modalGestionar = document.getElementById("modalGestionarParticipantes");
  if (modalGestionar) {
    modalGestionar.addEventListener("hidden.bs.modal", function () {
      // Eliminar todos los backdrops huérfanos
      const backdrops = document.querySelectorAll(".modal-backdrop");
      backdrops.forEach((backdrop) => backdrop.remove());

      // Restaurar el scroll del body
      document.body.classList.remove("modal-open");
      document.body.style.overflow = "";
      document.body.style.paddingRight = "";
    });
  }

  // Limpiar backdrop al cerrar el modal de ver participantes
  const modalVer = document.getElementById("modalVerParticipantes");
  if (modalVer) {
    modalVer.addEventListener("hidden.bs.modal", function () {
      // Eliminar todos los backdrops huérfanos
      const backdrops = document.querySelectorAll(".modal-backdrop");
      backdrops.forEach((backdrop) => backdrop.remove());

      // Restaurar el scroll del body
      document.body.classList.remove("modal-open");
      document.body.style.overflow = "";
      document.body.style.paddingRight = "";
    });
  }

  // Checkbox externo
  const checkExterno = document.getElementById("checkExterno");
  const inputUsername = document.getElementById("inputUsername");
  const inputNombreApellido = document.getElementById("inputNombreApellido");

  checkExterno.addEventListener("change", function () {
    if (this.checked) {
      inputUsername.disabled = true;
      inputUsername.value = "";
      inputNombreApellido.disabled = false;
      jugadorBuscadoId = null;
    } else {
      inputUsername.disabled = false;
      inputNombreApellido.disabled = true;
      inputNombreApellido.value = "";
    }
  });

  // Buscar username
  inputUsername.addEventListener("blur", async function () {
    const username = this.value.trim();
    if (!username || checkExterno.checked) return;

    try {
      const response = await fetch(`${GET_USUARIOS}?username=${username}`);
      const data = await response.json();

      if (response.ok && data.id_jugador) {
        jugadorBuscadoId = data.id_jugador;
        inputNombreApellido.value = `${data.nombre} ${data.apellido}`;
        showToast("Jugador encontrado", "success", 2000);
      } else {
        jugadorBuscadoId = null;
        inputNombreApellido.value = "";
        showToast("Usuario no encontrado", "warning");
      }
    } catch (error) {
      console.error("Error al buscar usuario:", error);
      showToast("Error al buscar usuario", "error");
    }
  });

  // Botón agregar participante
  document
    .getElementById("btnAgregarParticipante")
    .addEventListener("click", async function () {
      const selectEquipo = document.getElementById("selectEquipoNuevo");
      const equipo = selectEquipo.value;
      const esExterno = checkExterno.checked;
      const nombreApellido = inputNombreApellido.value.trim();

      // Validar equipo
      if (!equipo) {
        selectEquipo.classList.add("is-invalid");
        showToast("Selecciona un equipo", "warning");
        return;
      }
      selectEquipo.classList.remove("is-invalid");

      // Validar datos según tipo
      if (esExterno) {
        if (!nombreApellido) {
          showToast("Ingresa el nombre y apellido", "warning");
          return;
        }
      } else {
        if (!jugadorBuscadoId) {
          showToast("Busca un jugador válido", "warning");
          return;
        }
      }

      // Enviar solicitud
      try {
        const body = {
          id_partido: partidoActualId,
          equipo: parseInt(equipo),
        };

        if (esExterno) {
          body.nombre_invitado = nombreApellido;
        } else {
          body.id_jugador = jugadorBuscadoId;
        }

        const response = await fetch(POST_PARTICIPANTE_PARTIDO, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(body),
        });

        const data = await response.json();

        if (response.ok && data.success) {
          showToast("Participante agregado exitosamente", "success");

          // Limpiar form
          inputUsername.value = "";
          inputNombreApellido.value = "";
          selectEquipo.value = "";
          checkExterno.checked = false;
          inputUsername.disabled = false;
          inputNombreApellido.disabled = true;
          jugadorBuscadoId = null;

          // Recargar participantes
          await cargarParticipantes(partidoActualId);
        } else {
          showToast(data.error || "Error al agregar participante", "error");
        }
      } catch (error) {
        console.error("Error al agregar participante:", error);
        showToast("Error al agregar participante", "error");
      }
    });

  // Botón toggle convocatoria
  document
    .getElementById("btnToggleConvocatoria")
    .addEventListener("click", async function () {
      const abierto = this.getAttribute("data-abierto") == "1" ? 0 : 1;

      try {
        const response = await fetch(UPDATE_PARTIDO, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            id_partido: partidoActualId,
            accion: "toggle_convocatoria",
            abierto: abierto,
          }),
        });

        const data = await response.json();

        if (response.ok && data.success) {
          showToast(data.message, "success");

          // Actualizar botón
          this.setAttribute("data-abierto", abierto);
          const texto = document.getElementById("textoToggleConvocatoria");
          const icono = this.querySelector("i");

          if (abierto == 1) {
            texto.textContent = "Cerrar convocatoria";
            icono.className = "bi bi-door-closed me-2";
          } else {
            texto.textContent = "Abrir convocatoria";
            icono.className = "bi bi-door-open me-2";
          }
        } else {
          showToast(data.error || "Error al cambiar convocatoria", "error");
        }
      } catch (error) {
        console.error("Error al cambiar convocatoria:", error);
        showToast("Error al cambiar convocatoria", "error");
      }
    });
});

// Funciones globales
async function cambiarEquipoParticipante(select) {
  const idParticipante = select.getAttribute("data-id-participante");
  const equipo = select.value;

  try {
    const response = await fetch(UPDATE_PARTIDO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: partidoActualId,
        accion: "cambiar_equipo",
        id_participante: parseInt(idParticipante),
        equipo: parseInt(equipo),
      }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      showToast("Equipo actualizado", "success", 2000);
    } else {
      showToast(data.error || "Error al cambiar equipo", "error");
    }
  } catch (error) {
    console.error("Error al cambiar equipo:", error);
    showToast("Error al cambiar equipo", "error");
  }
}

async function eliminarParticipante(idParticipante) {
  if (!confirm("¿Estás seguro de eliminar este participante?")) {
    return;
  }

  try {
    const response = await fetch(UPDATE_PARTIDO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: partidoActualId,
        accion: "eliminar_participante",
        id_participante: parseInt(idParticipante),
      }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      showToast("Participante eliminado", "success");
      await cargarParticipantes(partidoActualId);
    } else {
      showToast(data.error || "Error al eliminar participante", "error");
    }
  } catch (error) {
    console.error("Error al eliminar participante:", error);
    showToast("Error al eliminar participante", "error");
  }
}

async function aceptarSolicitante(idJugador) {
  const selectEquipo = document.getElementById(`equipoSolicitante${idJugador}`);
  const equipo = selectEquipo.value;

  if (!equipo) {
    selectEquipo.classList.add("is-invalid");
    showToast("Selecciona un equipo para aceptar", "warning");
    return;
  }

  selectEquipo.classList.remove("is-invalid");

  try {
    const response = await fetch(UPDATE_PARTIDO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: partidoActualId,
        accion: "aceptar_solicitante",
        id_jugador: parseInt(idJugador),
        equipo: parseInt(equipo),
      }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      showToast("Solicitante aceptado", "success");
      await cargarParticipantes(partidoActualId);
    } else {
      showToast(data.error || "Error al aceptar solicitante", "error");
    }
  } catch (error) {
    console.error("Error al aceptar solicitante:", error);
    showToast("Error al aceptar solicitante", "error");
  }
}

async function rechazarSolicitante(idJugador) {
  try {
    const response = await fetch(UPDATE_PARTIDO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: partidoActualId,
        accion: "rechazar_solicitante",
        id_jugador: parseInt(idJugador),
      }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      showToast("Solicitante rechazado", "success");
      await cargarParticipantes(partidoActualId);
    } else {
      showToast(data.error || "Error al rechazar solicitante", "error");
    }
  } catch (error) {
    console.error("Error al rechazar solicitante:", error);
    showToast("Error al rechazar solicitante", "error");
  }
}

// Cancelar participación en un partido
async function cancelarParticipacion(idPartido) {
  // Mostrar modal de confirmación
  const confirmar = await mostrarConfirmacionPartido(
    "¿Estás seguro de que deseas cancelar tu participación?",
    "Esta acción te eliminará de la lista de participantes confirmados."
  );

  if (!confirmar) {
    return;
  }

  try {
    const response = await fetch(UPDATE_PARTIDO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: parseInt(idPartido),
        accion: "cancelar_participacion",
      }),
    });

    const data = await response.json();

    if (response.ok && data.success) {
      showToast("Participación cancelada exitosamente", "success", 3000);

      // Recargar la página después de 1.5 segundos
      setTimeout(() => {
        window.location.reload();
      }, 1500);
    } else {
      showToast(data.error || "Error al cancelar participación", "error");
    }
  } catch (error) {
    console.error("Error al cancelar participación:", error);
    showToast("Error al cancelar participación", "error");
  }
}
