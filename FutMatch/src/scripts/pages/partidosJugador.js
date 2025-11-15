window.onload = function () {
  cargarPartidosJugador();
};

async function cargarPartidosJugador() {
  try {
    const response = await fetch(API_PARTIDOS, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    let partidos = await response.json();
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

    partidos.forEach((partido) => {
      // Convertir fecha del partido (formato dd/mm/yyyy)
      const [dia, mes, año] = partido.fecha_partido.split("/");
      const fechaPartido = new Date(año, mes - 1, dia);

      if (fechaPartido <= finEstaSemana) {
        estaSemana.push(partido);
      } else if (fechaPartido <= finProximaSemana) {
        proximaSemana.push(partido);
      } else {
        masAdelante.push(partido);
      }
    });

    // Renderizar cada sección
    renderizarSeccion("estaSemana", estaSemana, "esta semana");
    renderizarSeccion("proximaSemana", proximaSemana, "próxima semana");
    renderizarSeccion("masAdelante", masAdelante, "más adelante");
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

  partidos.forEach((partido) => {
    let estadoPartido;
    switch (partido.id_estado) {
      case 1:
        estadoPartido = "Pendiente";
        break;
      case 2:
        estadoPartido = "En revisión";
        break;
      case 3:
        estadoPartido = "Confirmado";
        break;
      case 4:
        estadoPartido = "Rechazado";
        break;
      case 5:
        estadoPartido = "Cancelado";
        break;
      default:
        estadoPartido = "Desconocido";
        break;
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
      } else if (!partido.equipo_asignado) {
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
      if (partido.id_rol === 1) {
        // ANFITRIÓN
        bannerHTML = `
          <div class="alert alert-info py-2 mb-0">
            <i class="bi bi-star-fill me-2"></i>
            <strong>Sos anfitrión</strong> de este partido
          </div>
        `;

        botonesHTML = `
          <a href="#" class="btn btn-sm btn-dark">
            <i class="bi bi-people me-2"></i>Gestionar participantes
          </a>
          <a href="#" class="btn btn-sm btn-dark">
            <i class="bi bi-x-circle me-2"></i>Cancelar partido
          </a>
        `;

        if (!equiposDefinidos) {
          if (partido.abierto) {
            botonesHTML += `
              <a href="#" class="btn btn-sm btn-dark">
                <i class="bi bi-door-closed me-2"></i>Cerrar convocatoria
              </a>
              <a href="#" class="btn btn-sm btn-dark">
                <i class="bi bi-envelope-open me-2"></i>Ver solicitudes
              </a>
            `;
          } else {
            botonesHTML += `
              <a href="#" class="btn btn-sm btn-success">
                <i class="bi bi-door-open me-2"></i>Abrir convocatoria
              </a>
            `;
          }
        }
      } else {
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
              <strong>Solicitud pendiente</strong> de aprobación
            </div>
          `;
        }

        botonesHTML = `
          <a href="#" class="btn btn-sm btn-dark">
            <i class="bi bi-people me-2"></i>Ver participantes
          </a>
          <a href="#" class="btn btn-sm btn-dark">
            <i class="bi bi-person-dash me-2"></i>Cancelar participación
          </a>
        `;

        if (partido.abierto) {
          botonesHTML += `
            <a href="#" class="btn btn-sm btn-dark">
              <i class="bi bi-person-plus me-2"></i>Invitar jugador
            </a>
          `;
        }
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
          <span class="badge text-bg-dark">${estadoPartido}</span>
          <span class="badge text-bg-dark">${partido.rol_usuario}</span>
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
