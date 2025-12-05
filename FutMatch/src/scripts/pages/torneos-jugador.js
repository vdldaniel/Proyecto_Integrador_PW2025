// Variables globales
let torneosTotales = [];
let torneosHistorialTotales = [];

window.onload = function () {
  cargarMisTorneos();
  configurarEventos();
};

function configurarEventos() {
  // Evento para búsqueda
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", (e) => {
      filtrarTorneos(e.target.value);
    });
  }

  // Evento para modal de historial (cargar al abrirse)
  const modalHistorial = document.getElementById("modalHistorialTorneos");
  if (modalHistorial) {
    modalHistorial.addEventListener("show.bs.modal", () => {
      cargarHistorialTorneos();
    });
  }
}

async function cargarMisTorneos() {
  try {
    const response = await fetch(GET_MIS_TORNEOS_JUGADOR, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const result = await response.json();

    if (result.status === "success") {
      torneosTotales = result.data;
      renderizarTorneos(torneosTotales);
    } else {
      showToast(result.message || "Error al cargar los torneos", "error");
      mostrarMensajeVacio("Error al cargar los torneos");
    }
  } catch (error) {
    showToast("Error al cargar tus torneos", "error");
    mostrarMensajeVacio("Error al cargar los torneos");
  }
}

function renderizarTorneos(torneos) {
  const contenedor = document.getElementById("torneosList");

  if (!torneos || torneos.length === 0) {
    mostrarMensajeVacio("No estás participando en ningún torneo");
    return;
  }

  contenedor.innerHTML = "";

  torneos.forEach((torneo) => {
    const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
      "es-AR",
      { day: "2-digit", month: "2-digit", year: "numeric" }
    );
    const fechaFin = new Date(torneo.fecha_fin).toLocaleDateString("es-AR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });

    const totalEquipos = torneo.total_equipos || 0;
    const maxEquipos = torneo.max_equipos || "∞";

    // Determinar badge de estado según id_estado_equipo
    let estadoBadge = "";
    let botonHTML = "";

    switch (parseInt(torneo.id_estado_equipo)) {
      case 1: // Pendiente
        estadoBadge =
          '<span class="badge text-bg-warning fs-6">Pendiente</span>';
        botonHTML = `
          <button class="btn btn-dark btn-sm" onclick="verDetalleTorneo(${torneo.id_torneo})">
            <i class="bi bi-eye"></i> Ver detalles
          </button>
        `;
        break;
      case 2: // Rechazado
        estadoBadge =
          '<span class="badge text-bg-danger fs-6">Rechazado</span>';
        botonHTML = `
          <button class="btn btn-dark btn-sm" onclick="verDetalleTorneo(${torneo.id_torneo})">
            <i class="bi bi-eye"></i> Ver detalles
          </button>
        `;
        break;
      case 3: // Aprobado
        estadoBadge = `<span class="badge text-bg-success fs-6">${torneo.etapa}</span>`;
        botonHTML = `
          <a href="${BASE_URL}public/HTML/jugador/torneoDetalle_Jugador.php?id=${torneo.id_torneo}" class="btn btn-primary btn-sm">
            <i class="bi bi-eye"></i> Ver Torneo
          </a>
        `;
        break;
      default:
        estadoBadge =
          '<span class="badge text-bg-secondary fs-6">Desconocido</span>';
        botonHTML = `
          <button class="btn btn-dark btn-sm" onclick="verDetalleTorneo(${torneo.id_torneo})">
            <i class="bi bi-eye"></i> Ver detalles
          </button>
        `;
    }

    const torneoCard = document.createElement("div");
    torneoCard.className = "col-12";
    torneoCard.innerHTML = `
      <div class="card shadow-sm border-0 mb-2">
        <div class="card-body">
          <div class="row align-items-center">
            <div class="col-md-2 text-center">
              <img src="${IMG_PATH}torneo_default.png" 
                   alt="${torneo.nombre}" 
                   class="rounded"
                   style="width: 60px; height: 60px; object-fit: cover;">
            </div>
            <div class="col-md-4">
              <h5 class="card-title mb-1">${torneo.nombre}</h5>
              <small class="text-muted">${fechaInicio} - ${fechaFin} • ${totalEquipos}/${maxEquipos} equipos</small>
            </div>
            <div class="col-md-3">
              ${estadoBadge}
            </div>
            <div class="col-md-3 text-end">
              ${botonHTML}
            </div>
          </div>
        </div>
      </div>
    `;

    contenedor.appendChild(torneoCard);
  });
}

function mostrarMensajeVacio(mensaje) {
  const contenedor = document.getElementById("torneosList");
  contenedor.innerHTML = `
    <div class="col-12 text-center py-5">
      <i class="bi bi-trophy" style="font-size: 4rem; color: #6c757d;"></i>
      <p class="text-muted mt-3">${mensaje}</p>
    </div>
  `;
}

function filtrarTorneos(busqueda) {
  if (!busqueda || busqueda.trim() === "") {
    renderizarTorneos(torneosTotales);
    return;
  }

  const busquedaLower = busqueda.toLowerCase();
  const torneosFiltrados = torneosTotales.filter((torneo) => {
    return (
      torneo.nombre.toLowerCase().includes(busquedaLower) ||
      torneo.etapa.toLowerCase().includes(busquedaLower) ||
      torneo.estado_solicitud.toLowerCase().includes(busquedaLower) ||
      new Date(torneo.fecha_inicio)
        .toLocaleDateString("es-AR")
        .includes(busquedaLower) ||
      new Date(torneo.fecha_fin)
        .toLocaleDateString("es-AR")
        .includes(busquedaLower)
    );
  });

  renderizarTorneos(torneosFiltrados);
}

// ===================================
// MODAL VER DETALLES (similar a torneosExplorar)
// ===================================

async function verDetalleTorneo(idTorneo) {
  // Buscar en torneos activos primero, luego en historial
  let torneo = torneosTotales.find((t) => t.id_torneo === idTorneo);
  if (!torneo) {
    torneo = torneosHistorialTotales.find((t) => t.id_torneo === idTorneo);
  }
  if (!torneo) {
    showToast("Torneo no encontrado", "error");
    return;
  }

  // Cargar canchas del organizador
  let canchasHTML = "Cargando ubicaciones...";
  let canchas = [];
  try {
    const response = await fetch(
      `${GET_LISTA_CANCHAS}?id_admin_cancha=${torneo.id_organizador}`,
      {
        method: "GET",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    if (response.ok) {
      const result = await response.json();
      // Check if response has data property (from controller) or is direct array
      canchas = result.data || result;

      if (canchas && canchas.length > 0) {
        canchasHTML = `
          <div class="table-responsive">
            <table class="table table-hover table-sm">
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Dirección</th>
                  <th>Mapa</th>
                  <th>Perfil</th>
                </tr>
              </thead>
              <tbody>
                ${canchas
                  .map(
                    (cancha) => `
                  <tr>
                    <td>${cancha.nombre}</td>
                    <td>${
                      cancha.direccion_completa
                    }<br><small class="text-muted">${cancha.localidad}, ${
                      cancha.provincia
                    }</small></td>
                    <td>
                      <button class="btn btn-sm btn-dark" onclick="verEnMapa(${
                        cancha.latitud
                      }, ${cancha.longitud}, '${cancha.nombre.replace(
                      /'/g,
                      "\\'"
                    )}')"
                        ${
                          !cancha.latitud || !cancha.longitud ? "disabled" : ""
                        }>
                        <i class="bi bi-map"></i> Ver mapa
                      </button>
                    </td>
                    <td>
                      <a href="perfilCancha.php?id=${
                        cancha.id_cancha
                      }" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i> Ver perfil
                      </a>
                    </td>
                  </tr>
                `
                  )
                  .join("")}
              </tbody>
            </table>
          </div>
        `;
      } else {
        canchasHTML =
          "<p class='text-muted text-center'>No hay ubicaciones disponibles</p>";
      }
    } else {
      throw new Error("Error al cargar canchas");
    }
  } catch (error) {
    showToast("Error al cargar las ubicaciones del torneo", "error");
    canchasHTML =
      "<p class='text-muted text-center'>Error al cargar ubicaciones</p>";
  }

  // Formatear fechas
  const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
    "es-AR",
    { day: "2-digit", month: "long", year: "numeric" }
  );
  const fechaFin = new Date(torneo.fecha_fin).toLocaleDateString("es-AR", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
  const finEstimativo = torneo.cierre_inscripciones
    ? new Date(torneo.cierre_inscripciones).toLocaleDateString("es-AR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      })
    : "No especificado";

  const totalEquipos = torneo.total_equipos || 0;
  const maxEquipos = torneo.max_equipos || "∞";

  // Construir modal
  const modalHTML = `
    <div class="modal fade" id="modalDetalleTorneo" tabindex="-1" aria-labelledby="modalDetalleTorneoLabel">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="modalDetalleTorneoLabel">
              <i class="bi bi-trophy-fill"></i> ${torneo.nombre}
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- FECHAS -->
            <div class="mb-3">
              <h6 class="fw-bold text-uppercase text-muted mb-2">
                <i class="bi bi-calendar-event"></i> Fechas
              </h6>
              <div class="ps-3">
                <p class="mb-1"><strong>Inicio:</strong> ${fechaInicio}</p>
                <p class="mb-1"><strong>Fin:</strong> ${fechaFin}</p>
                <p class="mb-1"><strong>Cierre de inscripciones:</strong> ${finEstimativo}</p>
              </div>
            </div>

            <!-- EQUIPOS -->
            <div class="mb-3">
              <h6 class="fw-bold text-uppercase text-muted mb-2">
                <i class="bi bi-people-fill"></i> Equipos
              </h6>
              <div class="ps-3">
                <p class="mb-1">
                  <strong>${totalEquipos}</strong> de <strong>${maxEquipos}</strong> equipos inscritos
                  <span class="badge text-bg-dark ms-2">${torneo.etapa}</span>
                </p>
              </div>
            </div>

            <!-- DESCRIPCIÓN -->
            ${
              torneo.descripcion
                ? `
            <div class="mb-3">
              <h6 class="fw-bold text-uppercase text-muted mb-2">
                <i class="bi bi-info-circle"></i> Descripción
              </h6>
              <div class="ps-3">
                <p class="text-muted">${torneo.descripcion}</p>
              </div>
            </div>
            `
                : ""
            }

            <!-- UBICACIONES -->
            <div class="mb-3">
              <h6 class="fw-bold text-uppercase text-muted mb-3">
                <i class="bi bi-geo-alt-fill"></i> Ubicaciones
              </h6>
              ${canchasHTML}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Eliminar modal anterior si existe
  const modalAnterior = document.getElementById("modalDetalleTorneo");
  if (modalAnterior) {
    modalAnterior.remove();
  }

  // Agregar modal al DOM
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalDetalleTorneo")
  );
  modal.show();

  // Eliminar modal del DOM cuando se cierre
  document
    .getElementById("modalDetalleTorneo")
    .addEventListener("hidden.bs.modal", function () {
      this.remove();
    });
}

// ===================================
// HISTORIAL DE TORNEOS
// ===================================

async function cargarHistorialTorneos() {
  try {
    const response = await fetch(`${GET_MIS_TORNEOS_JUGADOR}?historial=true`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const result = await response.json();

    if (result.status === "success") {
      torneosHistorialTotales = result.data;
      renderizarHistorial(torneosHistorialTotales);
    } else {
      showToast(result.message || "Error al cargar el historial", "error");
      mostrarMensajeVacioHistorial();
    }
  } catch (error) {
    showToast("Error al cargar el historial de torneos", "error");
    mostrarMensajeVacioHistorial();
  }
}

function renderizarHistorial(torneos) {
  // Filtrar por etapa
  const finalizados = torneos.filter((t) => parseInt(t.id_etapa) === 4);
  const cancelados = torneos.filter((t) => parseInt(t.id_etapa) === 5);

  // Renderizar finalizados
  const tbodyFinalizados = document.querySelector("#finalizados tbody");
  if (finalizados.length > 0) {
    tbodyFinalizados.innerHTML = finalizados
      .map((torneo) => {
        const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
          "es-AR",
          { day: "2-digit", month: "2-digit", year: "numeric" }
        );
        const fechaFin = new Date(torneo.fecha_fin).toLocaleDateString(
          "es-AR",
          { day: "2-digit", month: "2-digit", year: "numeric" }
        );

        return `
          <tr>
            <td>${torneo.nombre}</td>
            <td>${fechaInicio} - ${fechaFin}</td>
            <td><span class="badge text-bg-success">${torneo.etapa}</span></td>
            <td>
              <button class="btn btn-sm btn-dark" onclick="verDetalleTorneo(${torneo.id_torneo})">
                <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
              </button>
            </td>
          </tr>
        `;
      })
      .join("");
  } else {
    tbodyFinalizados.innerHTML = `
      <tr>
        <td colspan="4" class="text-center text-muted">No hay torneos finalizados</td>
      </tr>
    `;
  }

  // Renderizar cancelados
  const tbodyCancelados = document.querySelector("#cancelados tbody");
  if (cancelados.length > 0) {
    tbodyCancelados.innerHTML = cancelados
      .map((torneo) => {
        const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
          "es-AR",
          { day: "2-digit", month: "2-digit", year: "numeric" }
        );
        const fechaFin = new Date(torneo.fecha_fin).toLocaleDateString(
          "es-AR",
          { day: "2-digit", month: "2-digit", year: "numeric" }
        );

        return `
          <tr>
            <td>${torneo.nombre}</td>
            <td>${fechaInicio} - ${fechaFin}</td>
            <td><span class="text-muted">${
              torneo.descripcion || "Cancelado por organizador"
            }</span></td>
            <td>
              <button class="btn btn-sm btn-dark" onclick="verDetalleTorneo(${
                torneo.id_torneo
              })">
                <i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver detalle</span>
              </button>
            </td>
          </tr>
        `;
      })
      .join("");
  } else {
    tbodyCancelados.innerHTML = `
      <tr>
        <td colspan="4" class="text-center text-muted">No hay torneos cancelados</td>
      </tr>
    `;
  }
}

function mostrarMensajeVacioHistorial() {
  const tbodyFinalizados = document.querySelector("#finalizados tbody");
  const tbodyCancelados = document.querySelector("#cancelados tbody");

  tbodyFinalizados.innerHTML = `
    <tr>
      <td colspan="4" class="text-center text-muted">Error al cargar torneos finalizados</td>
    </tr>
  `;

  tbodyCancelados.innerHTML = `
    <tr>
      <td colspan="4" class="text-center text-muted">Error al cargar torneos cancelados</td>
    </tr>
  `;
}

// ===================================
// FUNCIÓN PARA VER EN MAPA
// ===================================

function verEnMapa(lat, lng, nombre) {
  if (!lat || !lng) {
    showToast("Coordenadas no disponibles", "warning");
    return;
  }

  // Crear modal de mapa
  const modalHTML = `
    <div class="modal fade" id="modalMapa" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-map"></i> ${nombre}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div id="mapaCancha" style="height: 400px; width: 100%;"></div>
          </div>
          <div class="modal-footer">
            <a href="https://www.google.com/maps?q=${lat},${lng}" target="_blank" class="btn btn-primary">
              <i class="bi bi-box-arrow-up-right"></i> Abrir en Google Maps
            </a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Eliminar modal anterior si existe
  const modalAnterior = document.getElementById("modalMapa");
  if (modalAnterior) modalAnterior.remove();

  // Insertar modal
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Mostrar modal
  const modal = new bootstrap.Modal(document.getElementById("modalMapa"));
  modal.show();

  // Inicializar mapa cuando el modal esté visible
  document.getElementById("modalMapa").addEventListener(
    "shown.bs.modal",
    function () {
      if (typeof L !== "undefined") {
        const map = L.map("mapaCancha").setView([lat, lng], 15);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          attribution: "© OpenStreetMap contributors",
        }).addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup(nombre).openPopup();
      }
    },
    { once: true }
  );
}
