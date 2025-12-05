// Variables globales para paginación
let torneosPaginados = [];
let paginaActual = 0;
const TORNEOS_POR_PAGINA = 6;
let todosLosTorneos = [];

window.onload = function () {
  cargarTorneosDisponibles();
  configurarEventos();
};

function configurarEventos() {
  // Evento para búsqueda
  const busquedaTorneos = document.getElementById("busquedaTorneos");
  if (busquedaTorneos) {
    busquedaTorneos.addEventListener("input", (e) => {
      filtrarTorneos(e.target.value);
    });
  }
}

async function cargarTorneosDisponibles() {
  try {
    const response = await fetch(GET_TORNEOS_EXPLORAR, {
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
      todosLosTorneos = result.data;

      // Para cada torneo, obtener información de canchas
      for (let torneo of todosLosTorneos) {
        await cargarInfoCanchas(torneo);
      }

      // Dividir torneos en páginas
      paginarTorneos(todosLosTorneos);
    }
  } catch (error) {
    console.error("Error al cargar los torneos disponibles:", error);
  }
}

async function cargarInfoCanchas(torneo) {
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
      if (result.status === "success") {
        torneo.canchas = result.data;
      }
    }
  } catch (error) {
    console.error("Error al cargar canchas del torneo:", error);
    torneo.canchas = [];
  }
}

function paginarTorneos(torneos) {
  let paginado = [[]];
  let pagina = 0;

  torneos.forEach((torneo) => {
    const paginaActualArray = paginado[pagina];

    if (paginaActualArray.length === TORNEOS_POR_PAGINA) {
      paginado.push([]);
      pagina++;
    }

    paginado[pagina].push(torneo);
  });

  torneosPaginados = paginado;
  paginaActual = 0;

  if (torneosPaginados.length > 0) {
    renderizarPaginaTorneos(torneosPaginados[0], 0);
    actualizarControlesPaginacion();
  }
}

function renderizarPaginaTorneos(torneos, numeroPagina) {
  const contenedor = document.getElementById("listaTorneos");
  contenedor.innerHTML = "";

  torneos.forEach((torneo) => {
    const imagenTorneo = IMG_PATH + "torneo_default.png";

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
    const finEstimativo = new Date(torneo.fin_estimativo).toLocaleDateString(
      "es-AR",
      { day: "2-digit", month: "long", year: "numeric" }
    );

    // Información de ubicación
    let ubicacionHTML = "";
    if (torneo.canchas && torneo.canchas.length > 0) {
      const primerCancha = torneo.canchas[0];
      ubicacionHTML = `${primerCancha.nombre} - ${primerCancha.localidad}, ${primerCancha.provincia}`;

      if (torneo.canchas.length > 1) {
        ubicacionHTML += `<br><small class="text-muted" style="cursor: pointer;" onclick="mostrarCanchasModal(${
          torneo.id_torneo
        })">
          <i class="bi bi-plus-circle"></i> y ${
            torneo.canchas.length - 1
          } ubicación(es) más
        </small>`;
      }
    } else {
      ubicacionHTML = "Ubicación por confirmar";
    }

    // Total de equipos
    const totalEquipos = torneo.total_equipos || 0;
    const maxEquipos = torneo.max_equipos || "∞";

    const torneoCard = document.createElement("div");
    torneoCard.className = "col-12 col-md-6 col-lg-4 mb-4 torneo-item";
    torneoCard.innerHTML = `
      <div class="card h-100 shadow">
        <img src="${imagenTorneo}" class="tarjeta-imagen" alt="${torneo.nombre}">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">${torneo.nombre}</h5>
          <p class="card-text text-muted mb-2">
            <i class="bi bi-geo-alt"></i> ${ubicacionHTML}
          </p>
          <div class="mb-2">
            <span class="badge text-bg-dark me-1">${totalEquipos}/${maxEquipos} equipos</span>
          </div>
          <p class="card-text small text-muted mb-2">
            <i class="bi bi-calendar-event"></i> Inicio: ${fechaInicio}
          </p>
          <p class="card-text small text-muted mb-3">
            <i class="bi bi-calendar-check"></i> Cierre de inscripciones: ${finEstimativo}
          </p>
          <div class="mt-auto">
            <div class="d-grid gap-2 d-md-flex">
              <button class="btn btn-dark btn-sm flex-fill" onclick="verDetalleTorneo(${torneo.id_torneo})">
                <i class="bi bi-eye"></i> Ver detalles
              </button>
              <button class="btn btn-success btn-sm flex-fill" onclick="inscribirseTorneo(${torneo.id_torneo})">
                <i class="bi bi-trophy"></i> Inscribirse
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    contenedor.appendChild(torneoCard);
  });
}

function actualizarControlesPaginacion() {
  const paginacion = document.querySelector(".pagination");
  if (!paginacion) return;

  paginacion.innerHTML = "";

  // Botón anterior
  const anteriorLi = document.createElement("li");
  anteriorLi.className = `page-item ${paginaActual === 0 ? "disabled" : ""}`;
  anteriorLi.innerHTML = `<a class="page-link" href="#" tabindex="-1">Anterior</a>`;
  anteriorLi.addEventListener("click", (e) => {
    e.preventDefault();
    if (paginaActual > 0) {
      paginaActual--;
      renderizarPaginaTorneos(torneosPaginados[paginaActual], paginaActual);
      actualizarControlesPaginacion();
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });
  paginacion.appendChild(anteriorLi);

  // Números de página
  torneosPaginados.forEach((_, index) => {
    const paginaLi = document.createElement("li");
    paginaLi.className = `page-item ${index === paginaActual ? "active" : ""}`;
    paginaLi.innerHTML = `<a class="page-link" href="#">${index + 1}</a>`;
    paginaLi.addEventListener("click", (e) => {
      e.preventDefault();
      paginaActual = index;
      renderizarPaginaTorneos(torneosPaginados[paginaActual], paginaActual);
      actualizarControlesPaginacion();
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
    paginacion.appendChild(paginaLi);
  });

  // Botón siguiente
  const siguienteLi = document.createElement("li");
  siguienteLi.className = `page-item ${
    paginaActual === torneosPaginados.length - 1 ? "disabled" : ""
  }`;
  siguienteLi.innerHTML = `<a class="page-link" href="#">Siguiente</a>`;
  siguienteLi.addEventListener("click", (e) => {
    e.preventDefault();
    if (paginaActual < torneosPaginados.length - 1) {
      paginaActual++;
      renderizarPaginaTorneos(torneosPaginados[paginaActual], paginaActual);
      actualizarControlesPaginacion();
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  });
  paginacion.appendChild(siguienteLi);
}

function filtrarTorneos(busqueda) {
  const terminoBusqueda = busqueda.toLowerCase().trim();

  if (terminoBusqueda === "") {
    paginarTorneos(todosLosTorneos);
    return;
  }

  const torneosFiltrados = todosLosTorneos.filter((torneo) => {
    // Buscar en nombre
    if (torneo.nombre.toLowerCase().includes(terminoBusqueda)) return true;

    // Buscar en descripción
    if (
      torneo.descripcion &&
      torneo.descripcion.toLowerCase().includes(terminoBusqueda)
    )
      return true;

    // Buscar en ubicaciones
    if (torneo.canchas && torneo.canchas.length > 0) {
      return torneo.canchas.some(
        (cancha) =>
          cancha.nombre.toLowerCase().includes(terminoBusqueda) ||
          (cancha.localidad &&
            cancha.localidad.toLowerCase().includes(terminoBusqueda)) ||
          (cancha.provincia &&
            cancha.provincia.toLowerCase().includes(terminoBusqueda))
      );
    }

    return false;
  });

  paginarTorneos(torneosFiltrados);
}

function verDetalleTorneo(idTorneo) {
  const torneo = todosLosTorneos.find((t) => t.id_torneo === idTorneo);
  if (!torneo) return;

  // Formatear fechas
  const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
    "es-AR",
    {
      day: "2-digit",
      month: "long",
      year: "numeric",
    }
  );
  const fechaFin = new Date(torneo.fecha_fin).toLocaleDateString("es-AR", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
  const finEstimativo = torneo.fin_estimativo
    ? new Date(torneo.fin_estimativo).toLocaleDateString("es-AR", {
        day: "2-digit",
        month: "long",
        year: "numeric",
      })
    : "No especificado";

  // Ubicaciones en formato tabla
  const ubicacionesHTML =
    torneo.canchas && torneo.canchas.length > 0
      ? `
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
              ${torneo.canchas
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
                      ${!cancha.latitud || !cancha.longitud ? "disabled" : ""}>
                      <i class="bi bi-map"></i> Ver mapa
                    </button>
                  </td>
                  <td>
                    <a href="${PAGE_PERFIL_CANCHA_JUGADOR}?id=${
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
      `
      : "<span class='text-muted'>Ubicación por confirmar</span>";

  // Crear modal de detalles
  const modalHTML = `
    <div class="modal fade" id="modalDetalleTorneo" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title"><i class="bi bi-trophy-fill"></i> ${
              torneo.nombre
            }</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <h6 class="text-muted mb-2">FECHAS</h6>
              <div class="ps-3">
                <p class="mb-1"><i class="bi bi-calendar-event"></i> <strong>Inicio:</strong> ${fechaInicio}</p>
                <p class="mb-1"><i class="bi bi-calendar-x"></i> <strong>Fin:</strong> ${fechaFin}</p>
                <p class="mb-1"><i class="bi bi-calendar-check"></i> <strong>Cierre de inscripciones:</strong> ${finEstimativo}</p>
              </div>
            </div>

            <div class="mb-3">
              <h6 class="text-muted mb-2">EQUIPOS</h6>
              <div class="ps-3">
                <p class="mb-1"><i class="bi bi-people-fill"></i> ${
                  torneo.total_equipos || 0
                } / ${torneo.max_equipos || "∞"} equipos inscritos</p>
                <span class="badge bg-success"><i class="bi bi-flag-fill"></i> ${
                  torneo.etapa_nombre
                }</span>
              </div>
            </div>

            ${
              torneo.descripcion
                ? `
            <div class="mb-3">
              <h6 class="text-muted mb-2">DESCRIPCIÓN</h6>
              <div class="ps-3">
                <p class="mb-0">${torneo.descripcion}</p>
              </div>
            </div>
            `
                : ""
            }

            <div class="mb-3">
              <h6 class="text-muted mb-3">UBICACIONES</h6>
              ${ubicacionesHTML}
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" onclick="inscribirseTorneo(${
              torneo.id_torneo
            })" data-bs-dismiss="modal">
              <i class="bi bi-trophy"></i> Inscribirse
            </button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Eliminar modal anterior si existe
  const modalAnterior = document.getElementById("modalDetalleTorneo");
  if (modalAnterior) modalAnterior.remove();

  // Insertar nuevo modal
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalDetalleTorneo")
  );
  modal.show();
}

function inscribirseTorneo(idTorneo) {
  const torneo = todosLosTorneos.find((t) => t.id_torneo === idTorneo);
  if (!torneo) return;

  // Verificar si hay usuario logueado
  if (typeof CURRENT_USER_ID === "undefined" || !CURRENT_USER_ID) {
    showToast("Debes iniciar sesión para inscribirte a un torneo", "warning");
    return;
  }

  // Cargar equipos del usuario donde es líder
  cargarEquiposLider(idTorneo);
}

async function cargarEquiposLider(idTorneo) {
  try {
    const response = await fetch(`${GET_EQUIPOS_JUGADOR}?es_lider=true`, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Error al cargar equipos");
    }

    const equipos = await response.json();

    if (equipos.length === 0) {
      showToast(
        "Debes ser líder de un equipo para inscribirte a un torneo",
        "warning"
      );
      return;
    }

    mostrarModalInscripcion(idTorneo, equipos);
  } catch (error) {
    console.error("Error al cargar equipos:", error);
    showToast("Error al cargar tus equipos", "error");
  }
}

function mostrarModalInscripcion(idTorneo, equipos) {
  const torneo = todosLosTorneos.find((t) => t.id_torneo === idTorneo);

  const equiposHTML = equipos
    .map(
      (equipo) => `
    <div class="form-check mb-2">
      <input class="form-check-input" type="radio" name="equipoSeleccionado" 
             id="equipo${equipo.id_equipo}" value="${equipo.id_equipo}">
      <label class="form-check-label" for="equipo${equipo.id_equipo}">
        <strong>${equipo.nombre_equipo}</strong>
        <br><small class="text-muted">${equipo.cantidad_integrantes} jugadores</small>
      </label>
    </div>
  `
    )
    .join("");

  const modalHTML = `
    <div class="modal fade" id="modalInscripcionTorneo" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-trophy"></i> Inscribirse a ${torneo.nombre}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Selecciona el equipo que deseas inscribir al torneo:</p>
            <div id="listaEquiposInscripcion">
              ${equiposHTML}
            </div>
            <div class="alert alert-info mt-3">
              <i class="bi bi-info-circle"></i> Solo los líderes de equipos pueden inscribir a sus equipos en torneos.
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-success" onclick="confirmarInscripcion(${idTorneo})">
              <i class="bi bi-send"></i> Enviar solicitud
            </button>
          </div>
        </div>
      </div>
    </div>
  `;

  // Eliminar modal anterior si existe
  const modalAnterior = document.getElementById("modalInscripcionTorneo");
  if (modalAnterior) modalAnterior.remove();

  // Insertar nuevo modal
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalInscripcionTorneo")
  );
  modal.show();
}

async function confirmarInscripcion(idTorneo) {
  const equipoSeleccionado = document.querySelector(
    'input[name="equipoSeleccionado"]:checked'
  );

  if (!equipoSeleccionado) {
    showToast("Debes seleccionar un equipo", "warning");
    return;
  }

  const idEquipo = parseInt(equipoSeleccionado.value);

  try {
    const response = await fetch(POST_INSCRIPCION_TORNEO, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_equipo: idEquipo,
        id_torneo: idTorneo,
      }),
    });

    const result = await response.json();

    if (result.status === "success") {
      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalInscripcionTorneo")
      );
      modal.hide();

      showToast(result.message, "success");

      // Recargar torneos para actualizar el contador
      cargarTorneosDisponibles();
    } else {
      showToast(result.message || "Error al inscribirse al torneo", "error");
    }
  } catch (error) {
    console.error("Error al inscribirse:", error);
    showToast("Error al procesar la solicitud", "error");
  }
}

function mostrarCanchasModal(idTorneo) {
  const torneo = todosLosTorneos.find((t) => t.id_torneo === idTorneo);
  if (!torneo || !torneo.canchas || torneo.canchas.length === 0) return;

  const canchasTablaHTML = torneo.canchas
    .map(
      (cancha) => `
    <tr>
      <td>${cancha.nombre}</td>
      <td>${cancha.direccion_completa}</td>
      <td>
        <button class="btn btn-sm btn-dark" onclick="verEnMapa(${cancha.latitud}, ${cancha.longitud}, '${cancha.nombre}')">
          <i class="bi bi-map"></i> Ver en mapa
        </button>
      </td>
      <td>
        <a href="${PAGE_PERFIL_CANCHA_JUGADOR}?id=${cancha.id_cancha}" class="btn btn-sm btn-primary">
          <i class="bi bi-eye"></i> Ver perfil
        </a>
      </td>
    </tr>
  `
    )
    .join("");

  const modalHTML = `
    <div class="modal fade" id="modalCanchasTorneo" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-geo-alt"></i> Ubicaciones de ${torneo.nombre}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nombre</th>
                    <th>Dirección completa</th>
                    <th>Mapa</th>
                    <th>Perfil</th>
                  </tr>
                </thead>
                <tbody>
                  ${canchasTablaHTML}
                </tbody>
              </table>
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
  const modalAnterior = document.getElementById("modalCanchasTorneo");
  if (modalAnterior) modalAnterior.remove();

  // Insertar nuevo modal
  document.body.insertAdjacentHTML("beforeend", modalHTML);

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalCanchasTorneo")
  );
  modal.show();
}

function verEnMapa(lat, lng, nombre) {
  // Abrir Google Maps en nueva pestaña
  const url = `https://www.google.com/maps?q=${lat},${lng}`;
  window.open(url, "_blank");
}
