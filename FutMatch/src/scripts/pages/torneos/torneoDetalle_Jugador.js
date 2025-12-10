document.addEventListener("DOMContentLoaded", function () {
  // Obtener ID del torneo desde la URL
  const urlParams = new URLSearchParams(window.location.search);
  const ID_TORNEO = urlParams.get("id");

  if (!ID_TORNEO) {
    showToast("No se especificó un torneo válido", "error");
    setTimeout(() => {
      window.location.href =
        BASE_URL + "public/HTML/jugador/torneosExplorar_Jugador.php";
    }, 2000);
    return;
  }

  // Endpoints
  const ENDPOINT_DETALLE_TORNEO =
    BASE_URL + "src/controllers/torneos/getDetalleTorneo.php";
  const ENDPOINT_PARTIDOS_TORNEO =
    BASE_URL + "src/controllers/torneos/getPartidosTorneo.php";
  const ENDPOINT_EQUIPOS_TORNEO =
    BASE_URL + "src/controllers/torneos/getEquiposTorneo.php";

  // Cargar datos iniciales
  cargarDatosTorneo();
  cargarBracketTorneo();
  cargarEquiposTorneo();

  /**
   * Cargar información general del torneo
   */
  function cargarDatosTorneo() {
    fetch(`${ENDPOINT_DETALLE_TORNEO}?id_torneo=${ID_TORNEO}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          const torneo = data.data;

          // Actualizar header
          document.getElementById("torneo-nombre").textContent = torneo.nombre;
          document.getElementById(
            "torneo-fechas"
          ).textContent = `${torneo.fecha_inicio} - ${torneo.fecha_fin}`;
          document.getElementById("torneo-estado-badge").textContent =
            torneo.etapa_nombre;

          // Si hay modal de detalles, actualizarlo
          if (document.getElementById("detalle-nombre")) {
            document.getElementById("detalle-nombre").textContent =
              torneo.nombre;
            document.getElementById("detalle-fecha-inicio").textContent =
              torneo.fecha_inicio;
            document.getElementById("detalle-fecha-fin").textContent =
              torneo.fecha_fin;
            document.getElementById("detalle-estado").textContent =
              torneo.etapa_nombre;
            document.getElementById("detalle-equipos-registrados").textContent =
              torneo.equipos_registrados;
            document.getElementById("detalle-max-equipos").textContent =
              torneo.max_equipos;
            document.getElementById("detalle-descripcion").textContent =
              torneo.descripcion || "Sin descripción";
          }
        } else {
          showToast("Error al cargar información del torneo", "error");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        showToast("Error de conexión", "error");
      });
  }

  /**
   * Cargar bracket del torneo
   */
  function cargarBracketTorneo() {
    const bracketContainer = document.getElementById("bracketContainer");

    fetch(`${ENDPOINT_PARTIDOS_TORNEO}?id_torneo=${ID_TORNEO}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success" && data.data.length > 0) {
          renderizarBracket(data.data);
        } else {
          bracketContainer.innerHTML = `
            <div class="text-center py-5">
              <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
              <p class="text-muted mt-3">No hay partidos programados aún</p>
            </div>
          `;
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        bracketContainer.innerHTML = `
          <div class="text-center py-5">
            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">Error al cargar los partidos</p>
          </div>
        `;
      });
  }

  /**
   * Renderizar bracket del torneo
   */
  function renderizarBracket(partidos) {
    const bracketContainer = document.getElementById("bracketContainer");

    // Agrupar partidos por fase
    const fases = {};
    partidos.forEach((partido) => {
      if (!fases[partido.id_fase]) {
        fases[partido.id_fase] = {
          nombre: partido.fase_nombre,
          partidos: [],
        };
      }
      fases[partido.id_fase].partidos.push(partido);
    });

    // Ordenar fases de mayor a menor ID (más temprana a más tardía)
    const fasesOrdenadas = Object.keys(fases)
      .map((id) => parseInt(id))
      .sort((a, b) => b - a);

    // Generar HTML del bracket con estructura similar al admin
    let html = '<div class="row">';

    fasesOrdenadas.forEach((idFase) => {
      const fase = fases[idFase];
      const colSize = 12 / fasesOrdenadas.length;

      html += `<div class="col-${colSize} bracket-branch">`;
      html += `<div class="branch-header"><h5 class="branch-title">${fase.nombre}</h5></div>`;
      html += `<div class="branch-body">`;

      fase.partidos.forEach((partido) => {
        html += renderizarPartido(partido);
      });

      html += `</div></div>`;
    });

    html += "</div>";
    bracketContainer.innerHTML = html;
  }

  /**
   * Renderizar un partido individual (sin interacción)
   */
  function renderizarPartido(partido) {
    const equipoA = partido.equipo_A_nombre || "Por determinar";
    const equipoB = partido.equipo_B_nombre || "Por determinar";
    const golesA = partido.goles_equipo_A ?? "-";
    const golesB = partido.goles_equipo_B ?? "-";

    // Determinar estado del partido
    let estadoClase = "pending";
    let estadoIcono = "bi-clock";
    let estadoTexto = "Sin programar";

    if (partido.id_reserva) {
      if (partido.goles_equipo_A !== null && partido.goles_equipo_B !== null) {
        estadoClase = "completed";
        estadoIcono = "bi-check-circle";
        estadoTexto = "Finalizado";
      } else {
        estadoClase = "scheduled";
        estadoIcono = "bi-calendar-check";
        estadoTexto = "Programado";
      }
    }

    return `
      <div class="branch-match ${estadoClase}">
        <div class="match-team equipo-a">
          <span class="team-name">${equipoA}</span>
          <span class="team-score">${golesA}</span>
        </div>
        <div class="match-team equipo-b">
          <span class="team-name">${equipoB}</span>
          <span class="team-score">${golesB}</span>
        </div>
        <small class="text-muted">
          <i class="${estadoIcono}"></i> ${estadoTexto}
        </small>
      </div>
    `;
  }

  /**
   * Cargar equipos del torneo
   */
  function cargarEquiposTorneo() {
    const equiposContainer = document.getElementById("equiposContainer");

    fetch(`${ENDPOINT_EQUIPOS_TORNEO}?id_torneo=${ID_TORNEO}`)
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success" && data.data.length > 0) {
          renderizarEquipos(data.data);
        } else {
          equiposContainer.innerHTML = `
            <div class="col-12 text-center py-5">
              <i class="bi bi-people" style="font-size: 3rem;"></i>
              <p class="text-muted mt-3">No hay equipos inscritos aún</p>
            </div>
          `;
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        equiposContainer.innerHTML = `
          <div class="col-12 text-center py-5">
            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">Error al cargar los equipos</p>
          </div>
        `;
      });
  }

  /**
   * Renderizar lista de equipos
   */
  function renderizarEquipos(equipos) {
    const equiposContainer = document.getElementById("equiposContainer");
    const equiposCount = document.getElementById("equipos-count");

    equiposCount.textContent = equipos.length;

    let html = "";
    equipos.forEach((equipo) => {
      const estadoBadge =
        equipo.estado_equipo === "ganador"
          ? '<span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> ¡CAMPEÓN!</span>'
          : equipo.estado_equipo === "continua"
          ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Continúa en competencia</span>'
          : '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Eliminado</span>';

      const golesFavor = equipo.goles_favor || 0;
      const golesContra = equipo.goles_contra || 0;
      const diferencia = golesFavor - golesContra;

      html += `
        <div class="col-12">
          <div class="card equipo-card mb-3">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-md-1 text-center">
                  <img src="${equipo.logo_url || IMG_EQUIPO_DEFAULT}" 
                       alt="${equipo.nombre_equipo}" 
                       class="rounded-circle" 
                       style="width: 50px; height: 50px; object-fit: cover;">
                </div>
                <div class="col-md-4">
                  <h5 class="mb-0">${equipo.nombre_equipo}</h5>
                </div>
                <div class="col-md-2">
                  ${estadoBadge}
                </div>
                <div class="col-md-3 text-center">
                  <div class="d-flex justify-content-center gap-3">
                    <div>
                      <small class="text-muted d-block">Goles a favor</small>
                      <span class="badge text-bg-success">${golesFavor}</span>
                    </div>
                    <div>
                      <small class="text-muted d-block">Goles en contra</small>
                      <span class="badge text-bg-danger">${golesContra}</span>
                    </div>
                    <div>
                      <small class="text-muted d-block">Diferencia</small>
                      <span class="badge text-bg-dark">${
                        diferencia > 0 ? "+" : ""
                      }${diferencia}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-2 text-end">
                  <a href="${BASE_URL}public/HTML/jugador/perfilEquipo_Jugador.php?id=${
        equipo.id_equipo
      }" 
                     class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> Ver equipo
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;
    });

    equiposContainer.innerHTML = html;
  }

  /**
   * Función auxiliar para mostrar toast
   */
  function showToast(message, type = "info") {
    // Si existe una función global de toast, usarla
    if (typeof window.showToast === "function") {
      window.showToast(message, type);
    } else {
      // Fallback: alert simple
      alert(message);
    }
  }
});
