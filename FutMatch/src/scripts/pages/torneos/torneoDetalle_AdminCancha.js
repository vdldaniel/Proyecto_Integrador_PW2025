document.addEventListener("DOMContentLoaded", function () {
  const ENDPOINT_CREAR_RESERVA_PARTIDO =
    BASE_URL + "src/controllers/torneos/crear_reserva_partido.php";

  // Elementos del modal
  const modalCrearReservaPartido = new bootstrap.Modal(
    document.getElementById("modalCrearReservaPartido")
  );
  const formCrearReservaPartido = document.getElementById(
    "formCrearReservaPartido"
  );
  const btnGuardarReservaPartido = document.getElementById(
    "btnGuardarReservaPartido"
  );
  const selectCancha = document.getElementById("selectCancha");
  const fechaPartido = document.getElementById("fechaPartido");
  const rangoFechasTorneo = document.getElementById("rangoFechasTorneo");

  // Variables temporales para el torneo
  let torneoActual = {
    id_torneo: null,
    nombre: "",
    fecha_inicio: "",
    fecha_fin: "",
  };

  // Cargar canchas del admin al iniciar
  cargarCanchas();

  // NOTA: El event listener de clicks en partidos está manejado por onclick en renderizarPartido()
  // No necesitamos otro listener aquí para evitar conflictos

  // Función para abrir el modal con datos prellenados
  function abrirModalCrearReserva(idPartido, fase, equipoA, equipoB) {
    // Obtener datos del torneo desde variable global
    const nombreTorneo =
      document.getElementById("torneo-nombre")?.textContent || "";
    const fechaInicioElem = document.getElementById(
      "detalle-fecha-inicio"
    )?.textContent;
    const fechaFinElem =
      document.getElementById("detalle-fecha-fin")?.textContent;

    // Establecer valores en el formulario
    document.getElementById("idPartido").value = idPartido;
    document.getElementById("idTorneo").value = ID_TORNEO;

    // Generar título automático: "Fase - Equipo A vs Equipo B"
    const titulo = `${fase} - ${equipoA} vs ${equipoB}`;
    document.getElementById("tituloPartido").value = titulo;

    // Establecer descripción con nombre del torneo
    document.getElementById("descripcionPartido").value = nombreTorneo;

    // Configurar restricciones de fecha
    if (fechaInicioElem && fechaFinElem) {
      const fechaInicio = convertirFecha(fechaInicioElem);
      const fechaFin = convertirFecha(fechaFinElem);

      fechaPartido.setAttribute("min", fechaInicio);
      fechaPartido.setAttribute("max", fechaFin);
      rangoFechasTorneo.textContent = `Entre ${fechaInicioElem} y ${fechaFinElem}`;
    }

    // Resetear validación
    formCrearReservaPartido.classList.remove("was-validated");

    // Mostrar modal
    modalCrearReservaPartido.show();
  }

  // Event listener para guardar reserva
  btnGuardarReservaPartido.addEventListener("click", async function () {
    if (!formCrearReservaPartido.checkValidity()) {
      formCrearReservaPartido.classList.add("was-validated");
      return;
    }

    const formData = new FormData(formCrearReservaPartido);
    const data = Object.fromEntries(formData.entries());

    btnGuardarReservaPartido.disabled = true;
    btnGuardarReservaPartido.innerHTML =
      '<i class="bi bi-hourglass-split"></i> Guardando...';

    try {
      const response = await fetch(ENDPOINT_CREAR_RESERVA_PARTIDO, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await response.json();

      if (result.status === "success") {
        showToast("Partido programado exitosamente", "success");

        // Cerrar modal correctamente
        const modalElement = document.getElementById(
          "modalCrearReservaPartido"
        );
        const modalInstance = bootstrap.Modal.getInstance(modalElement);
        if (modalInstance) {
          modalInstance.hide();
        }

        // Limpiar formulario
        formCrearReservaPartido.reset();
        formCrearReservaPartido.classList.remove("was-validated");

        // Recargar página para mostrar actualización
        setTimeout(() => location.reload(), 1500);
      } else {
        showToast("Error: " + result.message, "error");
      }
    } catch (error) {
      console.error("Error al crear reserva:", error);
      showToast("Error de conexión al crear la reserva", "error");
    } finally {
      btnGuardarReservaPartido.disabled = false;
      btnGuardarReservaPartido.innerHTML =
        '<i class="bi bi-check-circle"></i> Programar Partido';
    }
  });

  // Función para cargar canchas del admin
  async function cargarCanchas() {
    try {
      const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
      const data = await response.json();
      console.log("Canchas del admin cargadas:", data);

      if (data.status === "success" && data.data) {
        selectCancha.innerHTML =
          '<option value="">Seleccione una cancha...</option>';
        data.data.forEach((cancha) => {
          const option = document.createElement("option");
          option.value = cancha.id_cancha;

          // Mostrar nombre de cancha con tipo de partido
          const tipoPartido = cancha.tipo_nombre
            ? ` (${cancha.tipo_nombre})`
            : "";
          option.textContent = `${cancha.nombre}${tipoPartido}`;

          selectCancha.appendChild(option);
        });
      } else {
        showToast("Error al cargar canchas", "error");
      }
    } catch (error) {
      console.error("Error al cargar canchas:", error);
      showToast("Error de conexión al cargar canchas", "error");
    }
  }

  // Función auxiliar para convertir fecha DD/MM/YYYY a YYYY-MM-DD
  function convertirFecha(fechaTexto) {
    const partes = fechaTexto.split("/");
    if (partes.length === 3) {
      return `${partes[2]}-${partes[1]}-${partes[0]}`;
    }
    return fechaTexto;
  }

  // Función auxiliar para formatear fecha YYYY-MM-DD a DD/MM/YYYY
  function formatearFecha(fecha) {
    const partes = fecha.split("-");
    if (partes.length === 3) {
      return `${partes[2]}/${partes[1]}/${partes[0]}`;
    }
    return fecha;
  }
});

// Función global para cargar canchas disponibles
async function cargarCanchasDisponibles() {
  try {
    const response = await fetch(GET_CANCHAS_ADMIN_CANCHA);
    const data = await response.json();

    if (data.status === "success" && data.data) {
      const select = document.getElementById("selectCancha");
      select.innerHTML = '<option value="">Seleccione una cancha...</option>';

      data.data.forEach((cancha) => {
        const option = document.createElement("option");
        option.value = cancha.id_cancha;
        const tipoPartido = cancha.tipo_nombre
          ? ` (${cancha.tipo_nombre})`
          : "";
        option.textContent = `${cancha.nombre}${tipoPartido}`;
        select.appendChild(option);
      });
    } else {
      showToast("Error al cargar canchas", "error");
    }
  } catch (error) {
    console.error("Error al cargar canchas:", error);
    showToast("Error de conexión al cargar canchas", "error");
  }
}

// Cargar datos del torneo al iniciar
document.addEventListener("DOMContentLoaded", function () {
  cargarDatosTorneo();
  cargarEquiposTorneo();
  cargarBracketTorneo();
});

function cargarDatosTorneo() {
  fetch(GET_DETALLE_TORNEO + "?id_torneo=" + ID_TORNEO)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        const torneo = data.data;

        // Actualizar título de la página
        document.getElementById("torneo-nombre").textContent = torneo.nombre;
        document.getElementById("torneo-fechas").textContent =
          formatearFecha(torneo.fecha_inicio) +
          " - " +
          formatearFecha(torneo.fecha_fin);

        // Actualizar modal de detalles
        document.getElementById("detalle-nombre").textContent = torneo.nombre;
        document.getElementById("detalle-fecha-inicio").textContent =
          formatearFecha(torneo.fecha_inicio);
        document.getElementById("detalle-fecha-fin").textContent =
          formatearFecha(torneo.fecha_fin);
        document.getElementById("detalle-estado").textContent =
          torneo.etapa_nombre;
        document.getElementById("detalle-equipos-registrados").textContent =
          torneo.equipos_registrados;
        document.getElementById("detalle-max-equipos").textContent =
          torneo.max_equipos;
        document.getElementById("detalle-cierre-inscripciones").textContent =
          formatearFecha(torneo.cierre_inscripciones);
        document.getElementById("detalle-descripcion").textContent =
          torneo.descripcion || "Sin descripción";

        document.title = torneo.nombre + " - FutMatch";
      }
    })
    .catch((error) => console.error("Error al cargar torneo:", error));
}

function cargarEquiposTorneo() {
  fetch(GET_EQUIPOS_TORNEO + "?id_torneo=" + ID_TORNEO)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        renderizarEquipos(data.data);
      }
    })
    .catch((error) => console.error("Error al cargar equipos:", error));
}

function renderizarEquipos(equipos) {
  const container = document.getElementById("equiposContainer");
  if (!container) return;

  if (equipos.length === 0) {
    container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No hay equipos participantes aún</p>
                    </div>
                `;
    return;
  }

  container.innerHTML = equipos
    .map(
      (equipo, index) => `
                <div class="col-12">
                    <div class="card border-0 mb-2 team-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <span class="badge text-bg-dark fs-6 team-position-badge">${
                                      index + 1
                                    }°</span>
                                </div>
                                <div class="col-md-1 text-center">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center team-avatar">
                                        <i class="bi bi-people text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h5 class="card-title mb-1">${escapeHtml(
                                      equipo.nombre_equipo
                                    )}</h5>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-person"></i> Líder: ${escapeHtml(
                                          equipo.lider_nombre +
                                            " " +
                                            equipo.lider_apellido
                                        )}
                                    </small>
                                    <small class="text-muted d-block">
                                        <i class="bi bi-people"></i> ${
                                          equipo.total_integrantes
                                        } integrantes
                                    </small>
                                </div>
                                <div class="col-md-2">
                                    ${
                                      equipo.estado_equipo === "ganador"
                                        ? '<span class="badge bg-warning text-dark"><i class="bi bi-trophy-fill"></i> ¡CAMPEÓN!</span>'
                                        : equipo.estado_equipo === "continua"
                                        ? '<span class="badge bg-success"><i class="bi bi-check-circle"></i> Continúa en competencia</span>'
                                        : '<span class="badge bg-danger"><i class="bi bi-x-circle"></i> Eliminado</span>'
                                    }
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="d-flex justify-content-center gap-3">
                                        <div>
                                            <small class="text-muted d-block">Goles a favor</small>
                                            <span class="badge text-bg-success fs-6">${
                                              equipo.goles_favor || 0
                                            }</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Goles en contra</small>
                                            <span class="badge text-bg-danger fs-6">${
                                              equipo.goles_contra || 0
                                            }</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Diferencia</small>
                                            <span class="badge text-bg-dark fs-6">${
                                              (equipo.goles_favor || 0) -
                                              (equipo.goles_contra || 0)
                                            }</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-1 text-end d-none">
                                    <a href="<?= PAGE_PERFIL_EQUIPO_ADMIN_CANCHA ?>?id=${
                                      equipo.id_equipo
                                    }" 
                                       class="btn btn-dark btn-sm"
                                       data-bs-toggle="tooltip" 
                                       title="Ver perfil del equipo">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `
    )
    .join("");

  // Actualizar badge de cantidad de equipos
  const equiposCount = document.getElementById("equipos-count");
  if (equiposCount) {
    equiposCount.textContent = equipos.length;
  }
}

function formatearFecha(fecha) {
  const d = new Date(fecha);
  const dia = String(d.getDate()).padStart(2, "0");
  const mes = String(d.getMonth() + 1).padStart(2, "0");
  const anio = d.getFullYear();
  return `${dia}/${mes}/${anio}`;
}

function escapeHtml(text) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, (m) => map[m]);
}

function cargarBracketTorneo() {
  fetch(GET_PARTIDOS_TORNEO + "?id_torneo=" + ID_TORNEO)
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        // Organizar partidos por fase
        const partidosPorFase = {};
        data.data.forEach((partido) => {
          const idFase = partido.id_fase;
          if (!partidosPorFase[idFase]) {
            partidosPorFase[idFase] = {
              fase_nombre: partido.fase_nombre,
              partidos: [],
            };
          }
          partidosPorFase[idFase].partidos.push(partido);
        });
        renderizarBracket(data.max_equipos, partidosPorFase);
      }
    })
    .catch((error) => console.error("Error al cargar bracket:", error));
}

function renderizarBracket(maxEquipos, partidosPorFase) {
  const container = document.getElementById("bracketContainer");
  if (!container) return;

  // Determinar qué fases mostrar según max_equipos
  let fasesConfig = [];

  if (maxEquipos === 16) {
    fasesConfig = [
      {
        id: 5,
        nombre: "Octavos de Final",
        clase: "octavos",
        cantPartidos: 8,
      },
      {
        id: 4,
        nombre: "Cuartos de Final",
        clase: "cuartos",
        cantPartidos: 4,
      },
      {
        id: 3,
        nombre: "Semifinal",
        clase: "semis",
        cantPartidos: 2,
      },
      {
        id: 2,
        nombre: "Final",
        clase: "final",
        cantPartidos: 1,
      },
    ];
  } else if (maxEquipos === 8) {
    fasesConfig = [
      {
        id: 4,
        nombre: "Cuartos de Final",
        clase: "cuartos",
        cantPartidos: 4,
      },
      {
        id: 3,
        nombre: "Semifinal",
        clase: "semis",
        cantPartidos: 2,
      },
      {
        id: 2,
        nombre: "Final",
        clase: "final",
        cantPartidos: 1,
      },
    ];
  } else if (maxEquipos === 4) {
    fasesConfig = [
      {
        id: 3,
        nombre: "Semifinal",
        clase: "semis",
        cantPartidos: 2,
      },
      {
        id: 2,
        nombre: "Final",
        clase: "final",
        cantPartidos: 1,
      },
    ];
  }

  // Generar HTML del bracket
  let bracketHTML = "";

  fasesConfig.forEach((fase) => {
    const partidosFase = partidosPorFase[fase.id]?.partidos || [];

    bracketHTML += `
                    <div class="col-${12 / fasesConfig.length} bracket-branch ${
      fase.clase
    }">
                        <div class="branch-header">
                            <h5 class="branch-title">${fase.nombre}</h5>
                        </div>
                        <div class="branch-body">
                `;

    // Generar partidos de la fase
    for (let i = 0; i < fase.cantPartidos; i++) {
      const partido = partidosFase[i] || null;

      if (fase.clase === "octavos") {
        // Para octavos, agrupar de 2 en 2
        if (i % 2 === 0) {
          bracketHTML += '<div class="branch-block">';
        }
      } else if (fase.clase === "cuartos") {
        // Para cuartos, agrupar en branch-pair dentro de branch-block
        if (i % 2 === 0) {
          bracketHTML += '<div class="branch-block"><div class="branch-pair">';
        } else {
          bracketHTML += '<div class="branch-pair">';
        }
      } else {
        // Para semis y final, usar branch-pair
        bracketHTML += '<div class="branch-block"><div class="branch-pair">';
      }

      // Renderizar el partido
      bracketHTML += renderizarPartido(partido);

      if (fase.clase === "octavos") {
        if (i % 2 === 1) {
          bracketHTML += "</div>"; // cierra branch-block
        }
      } else if (fase.clase === "cuartos") {
        bracketHTML += "</div>"; // cierra branch-pair
        if (i % 2 === 1) {
          bracketHTML += "</div>"; // cierra branch-block
        }
      } else {
        bracketHTML += "</div></div>"; // cierra branch-pair y branch-block
      }
    }

    bracketHTML += `
                        </div>
                    </div>
                `;
  });

  container.innerHTML = bracketHTML;
}

function renderizarPartido(partido) {
  if (!partido) {
    return `
                    <div class="branch-match pending">
                        <div class="match-team">
                            <span class="team-name text-muted">Por definir</span>
                            <span class="team-score">-</span>
                        </div>
                        <div class="match-team">
                            <span class="team-name text-muted">Por definir</span>
                            <span class="team-score">-</span>
                        </div>
                    </div>
                `;
  }

  const equipoA = partido.equipo_A_nombre || "Por definir";
  const equipoB = partido.equipo_B_nombre || "Por definir";
  const golesA =
    partido.goles_equipo_A !== null ? partido.goles_equipo_A : null;
  const golesB =
    partido.goles_equipo_B !== null ? partido.goles_equipo_B : null;
  const tieneReserva = partido.id_reserva !== null;
  const claseEstado = tieneReserva ? "scheduled" : "pending";

  return `
                <div class="branch-match ${claseEstado}" 
                     data-partido-id="${partido.id_partido}"
                     data-equipo-a="${escapeHtml(equipoA)}"
                     data-equipo-b="${escapeHtml(equipoB)}"
                     data-fase="${escapeHtml(partido.fase_nombre)}"
                     data-tiene-reserva="${tieneReserva}"
                     data-goles-a="${golesA}"
                     data-goles-b="${golesB}"
                     style="cursor: pointer;"
                     onclick="abrirModalPartido(${
                       partido.id_partido
                     }, '${escapeHtml(equipoA).replace(
    /'/g,
    "\\'"
  )}', '${escapeHtml(equipoB).replace(/'/g, "\\'")}', '${escapeHtml(
    partido.fase_nombre
  ).replace(/'/g, "\\'")}', ${tieneReserva}, ${golesA}, ${golesB})">
                    <div class="match-team equipo-a">
                        <span class="team-name">${escapeHtml(equipoA)}</span>
                        <span class="team-score">${
                          golesA !== null ? golesA : "-"
                        }</span>
                    </div>
                    <div class="match-team equipo-b">
                        <span class="team-name">${escapeHtml(equipoB)}</span>
                        <span class="team-score">${
                          golesB !== null ? golesB : "-"
                        }</span>
                    </div>
                    ${
                      tieneReserva
                        ? `<small class="text-success"><i class="bi bi-check-circle"></i> Programado</small>`
                        : `<small class="text-warning"><i class="bi bi-clock"></i> Sin programar</small>`
                    }
                </div>
            `;
}

function abrirModalPartido(
  idPartido,
  equipoA,
  equipoB,
  fase,
  tieneReserva,
  golesA,
  golesB
) {
  if (!tieneReserva) {
    // Si no tiene reserva, abrir modal de crear reserva
    const form = document.getElementById("formCrearReservaPartido");

    // Primero resetear el formulario
    form.reset();
    form.classList.remove("was-validated");

    // Luego establecer los valores (después del reset)
    document.getElementById("idPartido").value = idPartido;
    document.getElementById("idTorneo").value = ID_TORNEO;
    document.getElementById(
      "tituloPartido"
    ).value = `${fase} - ${equipoA} vs ${equipoB}`;

    // Obtener nombre del torneo para la descripción
    const nombreTorneo =
      document.getElementById("torneo-nombre")?.textContent || "Torneo";
    document.getElementById("descripcionPartido").value = nombreTorneo;

    // Cargar canchas disponibles
    cargarCanchasDisponibles();

    // Obtener o crear instancia del modal
    const modalElement = document.getElementById("modalCrearReservaPartido");
    let modal = bootstrap.Modal.getInstance(modalElement);
    if (!modal) {
      modal = new bootstrap.Modal(modalElement);
    }
    modal.show();
  } else {
    // Si ya tiene reserva, abrir modal de resultado
    document.getElementById("idPartidoResultado").value = idPartido;
    document.getElementById("fasePartidoResultado").textContent = fase;
    document.getElementById("equipoANombreResultado").textContent = equipoA;
    document.getElementById("equipoBNombreResultado").textContent = equipoB;

    // Limpiar valores anteriores
    document.getElementById("golesEquipoA").value = "";
    document.getElementById("golesEquipoB").value = "";

    // Pre-cargar los goles si ya existen
    if (golesA !== null && golesA !== undefined && golesA !== "null") {
      document.getElementById("golesEquipoA").value = golesA;
    }
    if (golesB !== null && golesB !== undefined && golesB !== "null") {
      document.getElementById("golesEquipoB").value = golesB;
    }

    // Obtener o crear instancia del modal
    const modalElement = document.getElementById("modalGanadorPartido");
    let modal = bootstrap.Modal.getInstance(modalElement);
    if (!modal) {
      modal = new bootstrap.Modal(modalElement);
    }
    modal.show();
  }
}

// Handler para guardar resultado del partido
document.addEventListener("DOMContentLoaded", function () {
  const btnGuardarResultado = document.getElementById("btnGuardarResultado");

  if (btnGuardarResultado) {
    btnGuardarResultado.addEventListener("click", function () {
      const form = document.getElementById("formGanadorPartido");

      if (!form.checkValidity()) {
        form.classList.add("was-validated");
        return;
      }

      const idPartido = document.getElementById("idPartidoResultado").value;
      const golesA = document.getElementById("golesEquipoA").value;
      const golesB = document.getElementById("golesEquipoB").value;

      // Validar que no sea empate
      if (golesA === golesB) {
        showToast(
          "No se permiten empates en torneos de eliminación directa",
          "error"
        );
        return;
      }

      btnGuardarResultado.disabled = true;
      btnGuardarResultado.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Guardando...';

      fetch(ENDPOINT_ACTUALIZAR_RESULTADO_PARTIDO, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id_partido: idPartido,
          goles_equipo_A: parseInt(golesA),
          goles_equipo_B: parseInt(golesB),
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Cerrar modal
            const modal = bootstrap.Modal.getInstance(
              document.getElementById("modalGanadorPartido")
            );
            modal.hide();

            // Limpiar formulario
            form.reset();
            form.classList.remove("was-validated");

            // Recargar bracket
            cargarBracketTorneo();

            // Mostrar mensaje de éxito
            showToast("Resultado guardado exitosamente", "success");
          } else {
            showToast("Error: " + data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showToast("Error al guardar el resultado", "error");
        })
        .finally(() => {
          btnGuardarResultado.disabled = false;
          btnGuardarResultado.innerHTML =
            '<i class="bi bi-check-circle"></i> Guardar Resultado';
        });
    });
  }
});

// Handler para avanzar de fase
document.addEventListener("DOMContentLoaded", function () {
  const btnAvanzarFase = document.getElementById("btnAvanzarFaseTorneo");
  const btnConfirmarAvanzarFase = document.getElementById(
    "btnConfirmarAvanzarFase"
  );
  const modalConfirmarAvanzarFase = document.getElementById(
    "modalConfirmarAvanzarFase"
  );

  // Abrir modal de confirmación
  if (btnAvanzarFase) {
    btnAvanzarFase.addEventListener("click", function () {
      const modal = new bootstrap.Modal(modalConfirmarAvanzarFase);
      modal.show();
    });
  }

  // Confirmar avance de fase
  if (btnConfirmarAvanzarFase) {
    btnConfirmarAvanzarFase.addEventListener("click", function () {
      // Cerrar modal de confirmación
      const modal = bootstrap.Modal.getInstance(modalConfirmarAvanzarFase);
      modal.hide();

      // Deshabilitar botones
      btnAvanzarFase.disabled = true;
      btnAvanzarFase.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Avanzando...';
      btnConfirmarAvanzarFase.disabled = true;

      fetch(ENDPOINT_AVANZAR_FASE_TORNEO, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          torneo_id: ID_TORNEO,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            if (data.finalizado) {
              showToast("¡Torneo finalizado! " + data.message, "success");
              // Recargar página para actualizar estado
              setTimeout(() => window.location.reload(), 1500);
            } else {
              showToast(data.message, "success");
              // Recargar bracket, equipos y datos del torneo
              cargarBracketTorneo();
              cargarEquiposTorneo();
              cargarDatosTorneo();
            }
          } else {
            showToast("Error: " + data.message, "error");
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          showToast("Error al avanzar de fase", "error");
        })
        .finally(() => {
          btnAvanzarFase.disabled = false;
          btnAvanzarFase.innerHTML =
            '<i class="bi bi-arrow-right-circle"></i> Avanzar de fase';
          btnConfirmarAvanzarFase.disabled = false;
        });
    });
  }
});
