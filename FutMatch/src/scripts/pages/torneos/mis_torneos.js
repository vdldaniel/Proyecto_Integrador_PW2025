document.addEventListener("DOMContentLoaded", function () {
  // Endpoints
  const ENDPOINT_CREAR = BASE_URL + "src/controllers/torneos/torneo_crear.php";
  const ENDPOINT_LISTAR =
    BASE_URL + "src/controllers/torneos/lista_torneos.php";
  const ENDPOINT_CANCELAR =
    BASE_URL + "src/controllers/torneos/torneo_cancelar.php";
  const ENDPOINT_CANCELADOS =
    BASE_URL + "src/controllers/torneos/torneos_cancelados.php";
  const ENDPOINT_TORNEOS_FINALIZADOS =
    BASE_URL + "src/controllers/torneos/lista_torneos_finalizados.php";
  const ENDPOINT_ABRIR_INSCRIPCIONES =
    BASE_URL + "src/controllers/torneos/abrir_inscripciones.php";
  const ENDPOINT_GET_SOLICITUDES =
    BASE_URL + "src/controllers/torneos/getSolicitudesTorneos.php";
  const ENDPOINT_UPDATE_SOLICITUD =
    BASE_URL + "src/controllers/torneos/updateSolicitudTorneo.php";
  const ENDPOINT_INICIAR_TORNEO =
    BASE_URL + "src/controllers/torneos/iniciar_torneo.php";
  const ENDPOINT_MODIFICAR =
    BASE_URL + "src/controllers/torneos/torneo_modificar.php";

  // Contenedor principal de la lista de torneos
  const torneosListContainer = document.getElementById("torneosList");

  // Elemento de búsqueda (AÑADIDO PARA EL FILTRO)
  const searchInput = document.getElementById("searchInput");

  // Elementos del Modal de Creación
  const abrirInscripcionesCheckbox =
    document.getElementById("abrirInscripciones");
  const fechaCierreContainer = document.getElementById("fechaCierreContainer");
  const fechaCierreInput = document.getElementById("fechaCierreInscripciones");
  const btnCrearTorneo = document.getElementById("btnCrearTorneo");
  const formCrearTorneo = document.getElementById("formCrearTorneo");
  // Se asume que el modal está definido en el HTML principal
  const modalCrearTorneo = new bootstrap.Modal(
    document.getElementById("modalCrearTorneo")
  );

  // Resetear modal cuando se cierra
  document
    .getElementById("modalCrearTorneo")
    .addEventListener("hidden.bs.modal", function () {
      formCrearTorneo.reset();
      formCrearTorneo.classList.remove("was-validated");
      delete formCrearTorneo.dataset.torneoId;
      delete formCrearTorneo.dataset.modoEdicion;
      document.querySelector("#modalCrearTorneo .modal-title").textContent =
        "Crear Nuevo Torneo";
      document.getElementById("btnCrearTorneo").textContent = "Crear Torneo";
    });

  // Elementos del Modal de Cancelación
  const modalCancelarTorneoElement = document.getElementById(
    "modalCancelarTorneo"
  );
  const cancelarTorneoIdInput = document.getElementById("cancelarTorneoId");
  const btnConfirmarCancelar = document.getElementById("btnConfirmarCancelar");
  // Inicialización de la instancia de Modal
  const modalCancelarTorneo = new bootstrap.Modal(modalCancelarTorneoElement);

  // Elementos para modal de torneos finalizado y cancelados
  const modalTorneosCanceladosElement = document.getElementById(
    "modalTorneosCancelados"
  );
  const torneosCanceladosTableBody = document.getElementById(
    "torneosCanceladosTableBody"
  );

  // -------------------------------------------------

  // === Verificación de Nulos ===
  if (
    !abrirInscripcionesCheckbox ||
    !btnCrearTorneo ||
    !formCrearTorneo ||
    !torneosListContainer ||
    !modalCancelarTorneoElement ||
    !btnConfirmarCancelar ||
    !searchInput ||
    !modalTorneosCanceladosElement ||
    !torneosCanceladosTableBody
  ) {
    showToast("Error crítico: Faltan elementos esenciales en el DOM.", "error");
    if (btnCrearTorneo) btnCrearTorneo.disabled = true;
    return;
  }
  // =============================

  // Función para generar la tarjeta HTML de un torneo
  function createTorneoCardHtml(torneo) {
    // Formatear fecha a DD/MM/AAAA
    const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
      "es-ES",
      { day: "2-digit", month: "2-digit", year: "numeric" }
    );

    let badgeColor = "text-bg-secondary";
    let actionButton = "";
    const torneoLink =
      BASE_URL + "public/HTML/admin-cancha/misTorneosDetalle_AdminCancha.php";

    // Verificar si fecha cierre_inscripciones es menor a HOY y la etapa sigue siendo 2
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);
    const cierreInscripciones = torneo.cierre_inscripciones
      ? new Date(torneo.cierre_inscripciones)
      : null;
    console.log("Cierre Inscripciones:", cierreInscripciones);
    const inscripcionesCerradas =
      cierreInscripciones && cierreInscripciones < hoy && torneo.id_etapa === 2;

    // Determinar el nombre de etapa a mostrar
    let etapaNombre = torneo.etapa_nombre || "Desconocido";
    if (inscripcionesCerradas) {
      etapaNombre = "Inscripciones cerradas";
    }

    // Lógica de botones y colores basada en id_etapa
    if (torneo.id_etapa === 1) {
      // Borrador
      badgeColor = "text-bg-dark";
      actionButton = `<button class="btn btn-secondary btn-sm me-1 btn-modificar-torneo" data-torneo-id="${torneo.id_torneo}" data-bs-toggle="modal" data-bs-target="#modalCrearTorneo" title="Modificar"><i class="bi bi-pencil"></i><span class="d-none d-lg-inline ms-1">Modificar</span></button>
                      <button class="btn btn-dark btn-sm me-1 btn-abrir-inscripciones" data-bs-toggle="modal" data-bs-target="#modalAbrirInscripciones" data-torneo-id="${torneo.id_torneo}" title="Abrir inscripciones"><i class="bi bi-unlock"></i><span class="d-none d-lg-inline ms-1">Abrir inscripciones</span></button>`;
    } else if (torneo.id_etapa === 2) {
      // Inscripciones abiertas o cerradas
      if (inscripcionesCerradas) {
        // Inscripciones cerradas: cambiar badge y mostrar botones "Abrir inscripciones" e "Iniciar torneo"
        badgeColor = "text-bg-warning";
        actionButton = `<button class="btn btn-dark btn-sm me-1 btn-abrir-inscripciones" data-bs-toggle="modal" data-bs-target="#modalAbrirInscripciones" data-torneo-id="${torneo.id_torneo}" title="Abrir inscripciones"><i class="bi bi-unlock"></i><span class="d-none d-lg-inline ms-1">Abrir inscripciones</span></button>
                        <button class="btn btn-primary btn-sm me-1 btn-iniciar-torneo" data-torneo-id="${torneo.id_torneo}" title="Iniciar torneo"><i class="bi bi-play-circle"></i><span class="d-none d-lg-inline ms-1">Iniciar torneo</span></button>`;
      } else {
        // Inscripciones abiertas
        badgeColor = "text-bg-success";
        actionButton = `<button class="btn btn-dark btn-sm me-1 btn-ver-solicitudes" data-torneo-id="${torneo.id_torneo}" title="Ver solicitudes"><i class="bi bi-people"></i><span class="d-none d-lg-inline ms-1">Ver solicitudes</span></button>`;
      }
    } else if (torneo.id_etapa === 3) {
      // En curso
      badgeColor = "text-bg-primary";
      actionButton = `<a class="btn btn-dark btn-sm me-1" href="${torneoLink}?id=${torneo.id_torneo}" title="Ver Torneo"><i class="bi bi-eye"></i><span class="d-none d-lg-inline ms-1">Ver Torneo</span></a>`;
    } else if (torneo.id_etapa === 4) {
      // Finalizado - no aparece en lista principal
      return "";
    } else if (torneo.id_etapa === 5) {
      // Cancelado - no aparece en lista principal
      return "";
    }

    // Botón cancelar solo si etapa es 2 (inscripciones abiertas)
    const cancelButton =
      torneo.id_etapa === 2
        ? `<button class="btn btn-danger btn-sm btn-cancelar-torneo-trigger" data-torneo-id="${torneo.id_torneo}" title="Cancelar">
          <i class="bi bi-x-circle"></i>
          <span class="d-none d-lg-inline ms-1">Cancelar</span>
        </button>`
        : "";

    return `
            <div class="col-12" data-torneo-id="${torneo.id_torneo}">
                <div class="card shadow-sm border-0 mb-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #dee2e6;">
                                    <i class="bi bi-trophy text-muted" style="font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h5 class="card-title mb-1">
                                    <a href="${torneoLink}?id=${torneo.id_torneo}" class="text-decoration-none">${torneo.nombre}</a>
                                </h5>
                                <small class="text-muted">${fechaInicio}</small>
                            </div>
                            <div class="col-md-2">
                                <span class="badge ${badgeColor}">${etapaNombre}</span>
                            </div>
                            <div class="col-md-5 text-end">
                                ${actionButton}
                                ${cancelButton}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
  }

  // Función principal para cargar y renderizar torneos desde la BD
  async function loadTorneos() {
    torneosListContainer.innerHTML =
      '<div class="col-12 text-center py-5"><i class="bi bi-arrow-clockwise fa-spin me-2"></i> Cargando torneos...</div>';

    try {
      const response = await fetch(ENDPOINT_LISTAR);
      const data = await response.json();

      if (data.status === "success") {
        if (data.torneos.length > 0) {
          // Filtrar torneos: excluir finalizados (id_etapa=4) y cancelados (id_etapa=5)
          const torneosActivos = data.torneos.filter(
            (t) => t.id_etapa !== 4 && t.id_etapa !== 5
          );

          // Renderiza el HTML
          let html = torneosActivos.map(createTorneoCardHtml).join("");
          torneosListContainer.innerHTML =
            html ||
            '<div class="col-12 text-center py-5"><i class="bi bi-info-circle me-2"></i> No tienes torneos activos.</div>';

          // IMPORTANTE: Después de cargar los torneos, aplicamos el filtro
          // por si el usuario ya tenía texto en el campo de búsqueda.
          filterTorneos();
        } else {
          torneosListContainer.innerHTML =
            '<div class="col-12 text-center py-5"><i class="bi bi-info-circle me-2"></i> No has creado ningún torneo aún.</div>';
        }
      } else {
        console.error("Error al cargar torneos:", data.message);
        showToast("Error al cargar torneos: " + data.message, "error");
        torneosListContainer.innerHTML =
          '<div class="col-12 alert alert-danger">Error al cargar torneos: ' +
          data.message +
          "</div>";
      }
    } catch (error) {
      console.error("Error AJAX en loadTorneos:", error);
      showToast(
        "Error de conexión o formato de datos al cargar la lista de torneos.",
        "error"
      );
      torneosListContainer.innerHTML =
        '<div class="col-12 alert alert-danger">Error de conexión o formato de datos al cargar la lista de torneos.</div>';
    }
  }

  // --- Lógica del Filtro de Búsqueda (INTEGRADA) ---
  function filterTorneos() {
    const filter = searchInput.value.toLowerCase().trim();

    const cards = torneosListContainer.querySelectorAll(
      ".col-12[data-torneo-id]"
    );

    cards.forEach((card) => {
      const cardText = card.textContent.toLowerCase();

      // Comprobar si el texto de la tarjeta incluye la cadena de búsqueda
      if (cardText.includes(filter)) {
        // Mostrar la tarjeta
        card.style.display = "block";
      } else {
        // Ocultar la tarjeta
        card.style.display = "none";
      }
    });
  }

  // Agregar el event listener al campo de búsqueda para activar el filtro al escribir
  searchInput.addEventListener("input", filterTorneos);
  // --------------------------------------------------

  // --- Lógica del Modal de Creación ---

  // Toggle para la fecha de cierre
  abrirInscripcionesCheckbox.addEventListener("change", function () {
    if (this.checked) {
      fechaCierreContainer.classList.remove("d-none");
      if (fechaCierreInput)
        fechaCierreInput.setAttribute("required", "required");
    } else {
      fechaCierreContainer.classList.add("d-none");
      if (fechaCierreInput) {
        fechaCierreInput.removeAttribute("required");
        fechaCierreInput.value = "";
      }
    }
  });

  // Manejar el envío del formulario de Creación
  btnCrearTorneo.addEventListener("click", async function (e) {
    e.preventDefault();

    if (!formCrearTorneo.checkValidity()) {
      formCrearTorneo.classList.add("was-validated");
      return;
    }

    const data = new FormData(formCrearTorneo);
    data.append("idAdminCancha", 1);

    // Detectar si estamos en modo edición
    const modoEdicion = formCrearTorneo.dataset.modoEdicion === "true";
    const torneoId = formCrearTorneo.dataset.torneoId;

    if (modoEdicion && torneoId) {
      data.append("id_torneo", torneoId);
    }

    btnCrearTorneo.disabled = true;
    btnCrearTorneo.textContent = modoEdicion ? "Guardando..." : "Creando...";

    try {
      const endpoint = modoEdicion ? ENDPOINT_MODIFICAR : ENDPOINT_CREAR;
      const response = await fetch(endpoint, {
        method: "POST",
        body: data,
      });

      if (!response.ok) {
        const errorResult = await response.json();
        throw new Error(
          errorResult.message || `Error ${response.status} en la petición.`
        );
      }

      const result = await response.json();

      if (result.status === "success") {
        const mensaje = modoEdicion
          ? "Torneo modificado exitosamente."
          : "Torneo creado exitosamente.";
        showToast(mensaje, "success");
        formCrearTorneo.reset();
        formCrearTorneo.classList.remove("was-validated");
        delete formCrearTorneo.dataset.torneoId;
        delete formCrearTorneo.dataset.modoEdicion;
        modalCrearTorneo.hide();

        await loadTorneos();
      } else {
        showToast("Error: " + result.message, "error");
      }
    } catch (error) {
      console.error("Error AJAX (Crear/Modificar Torneo):", error);
      showToast(
        "Error al " +
          (modoEdicion ? "modificar" : "crear") +
          " torneo: " +
          error.message,
        "error"
      );
    } finally {
      btnCrearTorneo.disabled = false;
      btnCrearTorneo.textContent = modoEdicion
        ? "Guardar Cambios"
        : "Crear Torneo";
    }
  });

  // --- Lógica del Modal de Cancelación ---

  // 1. Listener delegado para botones generados dinámicamente
  torneosListContainer.addEventListener("click", function (event) {
    // Usa closest para encontrar el botón que disparó el evento
    const button = event.target.closest(".btn-cancelar-torneo-trigger");
    if (button) {
      const torneoId = button.getAttribute("data-torneo-id");
      cancelarTorneoIdInput.value = torneoId; // Almacenar el ID

      if (modalCancelarTorneo) {
        modalCancelarTorneo.show(); // Mostrar el modal manualmente
      } else {
        console.error(
          "La instancia del modal de cancelación no está definida."
        );
      }
    }
  });

  // 2. Manejar la confirmación de cancelación
  btnConfirmarCancelar.addEventListener("click", async function () {
    const torneoId = cancelarTorneoIdInput.value;
    if (!torneoId) {
      showToast("Error: No se encontró el ID del torneo a cancelar.", "error");
      return;
    }

    btnConfirmarCancelar.disabled = true;
    btnConfirmarCancelar.textContent = "Cancelando...";

    const data = new FormData();
    data.append("torneo_id", torneoId);

    try {
      const response = await fetch(ENDPOINT_CANCELAR, {
        method: "POST",
        body: data,
      });

      if (!response.ok) {
        // Leer el JSON de error del servidor
        const errorResult = await response.json();
        throw new Error(
          errorResult.message || `Error ${response.status} en la petición.`
        );
      }

      const result = await response.json();

      if (result.status === "success") {
        showToast("Torneo cancelado exitosamente.");
        modalCancelarTorneo.hide();
        await loadTorneos(); // Recargar la lista para que el torneo se muestre como 'cancelado'
      } else {
        showToast("Error: " + result.message, "error");
      }
    } catch (error) {
      console.error("Error AJAX (Cancelar Torneo):", error);
      showToast("Error al cancelar torneo: " + error.message, "error");
    } finally {
      btnConfirmarCancelar.disabled = false;
      btnConfirmarCancelar.textContent = "Sí, cancelar torneo";
    }
  });

  // ---  Historial de Torneos Cancelados ---

  /**
   * Función para generar una fila de tabla HTML para un torneo cancelado.
   * @param {Object} torneo - Objeto torneo con id_torneo, nombre, fecha_inicio, fecha_fin, motivo_cancelacion.
   * @returns {string} HTML de la fila de la tabla.
   */
  function createTorneoCanceladoRow(torneo) {
    const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
      "es-ES",
      {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
      }
    );

    const fechaFin = torneo.fecha_fin
      ? new Date(torneo.fecha_fin).toLocaleDateString("es-ES", {
          day: "2-digit",
          month: "2-digit",
          year: "numeric",
        })
      : "N/A";

    return `
        <tr>
            <td>${torneo.nombre}</td>
            <td>${fechaInicio}</td>
            <td>${fechaFin}</td>
        </tr>
    `;
  }

  async function loadTorneosCancelados() {
    torneosCanceladosTableBody.innerHTML = `
        <tr>
            <td colspan="3" class="text-center py-3">
                <i class="bi bi-arrow-clockwise fa-spin me-2"></i> Cargando historial...
            </td>
        </tr>
    `;

    try {
      const response = await fetch(ENDPOINT_CANCELADOS);
      const data = await response.json();

      if (data.status === "success") {
        if (data.torneos.length > 0) {
          torneosCanceladosTableBody.innerHTML = data.torneos
            .map(createTorneoCanceladoRow)
            .join("");
        } else {
          torneosCanceladosTableBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-3 text-muted">
                            <i class="bi bi-info-circle me-2"></i> No hay torneos cancelados registrados.
                        </td>
                    </tr>
                `;
        }
      } else {
        torneosCanceladosTableBody.innerHTML = `
                <tr><td colspan="3" class="text-center py-3 text-danger">Error: ${data.message}</td></tr>
            `;
      }
    } catch (error) {
      console.error("Error AJAX:", error);
      torneosCanceladosTableBody.innerHTML = `
            <tr><td colspan="3" class="text-center py-3 text-danger">Error al conectar con el servidor.</td></tr>
        `;
    }
  }

  // abre el modal
  modalTorneosCanceladosElement.addEventListener(
    "show.bs.modal",
    loadTorneosCancelados
  );

  // --- Historial de Torneos Finalizados ---

  function loadTorneosFinalizados() {
    fetch(ENDPOINT_TORNEOS_FINALIZADOS)
      .then((response) => response.json())
      .then((data) => {
        if (data.status !== "success") {
          console.error("Error backend:", data.message);
          return;
        }

        const tbody = document.getElementById("torneosFinalizadosTableBody");
        tbody.innerHTML = "";

        if (data.data.length === 0) {
          tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No hay torneos finalizados.
                        </td>
                    </tr>`;
          return;
        }

        const torneoLink =
          BASE_URL +
          "public/HTML/admin-cancha/misTorneosDetalle_AdminCancha.php";

        data.data.forEach((torneo) => {
          let row = `
                    <tr>
                        <td>${torneo.nombre}</td>
                        <td>${formatearFecha(torneo.fecha_inicio)}</td>
                        <td>${formatearFecha(torneo.fecha_fin)}</td>
                        <td>
                            <a href="${torneoLink}?id=${
            torneo.id_torneo
          }" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye"></i> Ver Torneo
                            </a>
                        </td>
                    </tr>`;

          tbody.insertAdjacentHTML("beforeend", row);
        });
      })
      .catch((error) => {
        console.error("Error AJAX:", error);
      });
  }

  function formatearFecha(fecha) {
    if (!fecha) return "-";
    return new Date(fecha).toLocaleDateString("es-AR");
  }

  const modalFinalizados = document.getElementById("modalTorneosFinalizados");

  if (modalFinalizados) {
    modalFinalizados.addEventListener("show.bs.modal", function () {
      loadTorneosFinalizados();
    });
  }

  // --- Ver Solicitudes --- //
  document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".btn-ver-solicitudes");
    if (!btn) return;

    const torneoId = btn.dataset.torneoId;

    // Abrir modal
    const modalEl = document.getElementById("modalSolicitudesTorneo");
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    // Cargar solicitudes
    await cargarSolicitudesTorneo(torneoId);
  });

  async function cargarSolicitudesTorneo(torneoId) {
    const participantesBody = document.getElementById(
      "equiposParticipantesBody"
    );
    const pendientesBody = document.getElementById("solicitudesPendientesBody");

    participantesBody.innerHTML =
      '<tr><td colspan="4" class="text-center">Cargando...</td></tr>';
    pendientesBody.innerHTML =
      '<tr><td colspan="4" class="text-center">Cargando...</td></tr>';

    try {
      const response = await fetch(
        `${ENDPOINT_GET_SOLICITUDES}?id_torneo=${torneoId}`
      );
      const data = await response.json();

      if (data.status === "success") {
        // Renderizar participantes
        if (data.data.participantes.length > 0) {
          participantesBody.innerHTML = data.data.participantes
            .map(
              (equipo) => `
            <tr>
              <td>${equipo.nombre_equipo}</td>
              <td>${equipo.lider_nombre}</td>
              <td>${equipo.total_integrantes}</td>
              <td>
                <button class="btn btn-sm btn-danger btn-cancelar-participacion" 
                        data-torneo-id="${torneoId}" 
                        data-equipo-id="${equipo.id_equipo}">
                  <i class="bi bi-x-circle"></i> Cancelar
                </button>
              </td>
            </tr>
          `
            )
            .join("");
        } else {
          participantesBody.innerHTML =
            '<tr><td colspan="4" class="text-center text-muted">No hay equipos participantes aún</td></tr>';
        }

        // Renderizar pendientes
        if (data.data.pendientes.length > 0) {
          pendientesBody.innerHTML = data.data.pendientes
            .map(
              (equipo) => `
            <tr>
              <td>${equipo.nombre_equipo}</td>
              <td>${equipo.lider_nombre}</td>
              <td>${equipo.total_integrantes}</td>
              <td>
                <button class="btn btn-sm btn-success me-1 btn-aceptar-solicitud" 
                        data-torneo-id="${torneoId}" 
                        data-equipo-id="${equipo.id_equipo}">
                  <i class="bi bi-check-circle"></i> Aceptar
                </button>
                <button class="btn btn-sm btn-danger btn-rechazar-solicitud" 
                        data-torneo-id="${torneoId}" 
                        data-equipo-id="${equipo.id_equipo}">
                  <i class="bi bi-x-circle"></i> Rechazar
                </button>
              </td>
            </tr>
          `
            )
            .join("");
        } else {
          pendientesBody.innerHTML =
            '<tr><td colspan="4" class="text-center text-muted">No hay solicitudes pendientes</td></tr>';
        }
      } else {
        showToast("Error al cargar solicitudes: " + data.message, "error");
        participantesBody.innerHTML =
          '<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>';
        pendientesBody.innerHTML =
          '<tr><td colspan="4" class="text-center text-danger">Error al cargar</td></tr>';
      }
    } catch (error) {
      console.error("Error al cargar solicitudes:", error);
      showToast("Error de conexión al cargar solicitudes", "error");
      participantesBody.innerHTML =
        '<tr><td colspan="4" class="text-center text-danger">Error de conexión</td></tr>';
      pendientesBody.innerHTML =
        '<tr><td colspan="4" class="text-center text-danger">Error de conexión</td></tr>';
    }
  }

  // Event listener para botones de aceptar/rechazar/cancelar solicitudes
  document.addEventListener("click", async function (e) {
    const btnAceptar = e.target.closest(".btn-aceptar-solicitud");
    const btnRechazar = e.target.closest(".btn-rechazar-solicitud");
    const btnCancelar = e.target.closest(".btn-cancelar-participacion");

    if (btnAceptar) {
      await actualizarSolicitud(
        btnAceptar.dataset.torneoId,
        btnAceptar.dataset.equipoId,
        "aceptar"
      );
    } else if (btnRechazar) {
      await actualizarSolicitud(
        btnRechazar.dataset.torneoId,
        btnRechazar.dataset.equipoId,
        "rechazar"
      );
    } else if (btnCancelar) {
      // Guardar datos y abrir modal de confirmación
      const modalCancelar = new bootstrap.Modal(
        document.getElementById("modalConfirmarCancelarParticipacion")
      );
      const btnConfirmar = document.getElementById(
        "btnConfirmarCancelarParticipacion"
      );

      // Remover listeners anteriores
      const newBtn = btnConfirmar.cloneNode(true);
      btnConfirmar.parentNode.replaceChild(newBtn, btnConfirmar);

      // Agregar nuevo listener
      newBtn.addEventListener("click", async function () {
        modalCancelar.hide();
        await actualizarSolicitud(
          btnCancelar.dataset.torneoId,
          btnCancelar.dataset.equipoId,
          "cancelar"
        );
      });

      modalCancelar.show();
    }
  });

  async function actualizarSolicitud(torneoId, equipoId, accion) {
    try {
      const response = await fetch(ENDPOINT_UPDATE_SOLICITUD, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          id_torneo: torneoId,
          id_equipo: equipoId,
          accion: accion,
        }),
      });

      const data = await response.json();

      if (data.status === "success") {
        showToast(data.message, "success");
        // Recargar solicitudes
        await cargarSolicitudesTorneo(torneoId);
      } else {
        showToast("Error: " + data.message, "error");
      }
    } catch (error) {
      console.error("Error al actualizar solicitud:", error);
      showToast("Error de conexión al actualizar solicitud", "error");
    }
  }

  // --- Iniciar Torneo --- //
  document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".btn-iniciar-torneo");
    if (!btn) return;

    const torneoId = btn.dataset.torneoId;

    // Abrir modal de confirmación
    const modalIniciar = new bootstrap.Modal(
      document.getElementById("modalConfirmarIniciarTorneo")
    );
    const btnConfirmar = document.getElementById("btnConfirmarIniciarTorneo");

    // Remover listeners anteriores
    const newBtn = btnConfirmar.cloneNode(true);
    btnConfirmar.parentNode.replaceChild(newBtn, btnConfirmar);

    // Agregar nuevo listener
    newBtn.addEventListener("click", async function () {
      modalIniciar.hide();

      try {
        const response = await fetch(ENDPOINT_INICIAR_TORNEO, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ torneo_id: torneoId }),
        });

        const data = await response.json();

        if (data.status === "success") {
          showToast(data.message, "success");
          loadTorneos(); // Recargar lista
        } else {
          showToast("Error: " + data.message, "error");
        }
      } catch (error) {
        console.error("Error al iniciar torneo:", error);
        showToast("Error de conexión al iniciar torneo", "error");
      }
    });

    modalIniciar.show();
  });

  // --- Modificar Torneo (Borrador) --- //
  document.addEventListener("click", async function (e) {
    const btn = e.target.closest(".btn-modificar-torneo");
    if (!btn) return;

    const torneoId = btn.dataset.torneoId;

    // Cargar datos del torneo
    try {
      const response = await fetch(`${ENDPOINT_LISTAR}`);
      const data = await response.json();

      if (data.status === "success") {
        const torneo = data.torneos.find((t) => t.id_torneo == torneoId);

        if (torneo) {
          // Llenar el formulario con los datos del torneo
          document.getElementById("nombreTorneo").value = torneo.nombre || "";
          document.getElementById("fechaInicio").value =
            torneo.fecha_inicio || "";
          document.getElementById("fechaFin").value = torneo.fecha_fin || "";
          document.getElementById("descripcionTorneo").value =
            torneo.descripcion || "";
          document.getElementById("maxEquipos").value =
            torneo.max_equipos || "";

          // Guardar el ID del torneo para actualizar en lugar de crear
          formCrearTorneo.dataset.torneoId = torneoId;
          formCrearTorneo.dataset.modoEdicion = "true";

          // Cambiar título del modal
          document.querySelector("#modalCrearTorneo .modal-title").textContent =
            "Modificar Torneo";
          document.getElementById("btnCrearTorneo").textContent =
            "Guardar Cambios";
        }
      }
    } catch (error) {
      console.error("Error al cargar torneo:", error);
      showToast("Error al cargar datos del torneo", "error");
    }
  });

  // --- Abrir Inscripciones --- //
  document.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-abrir-inscripciones");
    if (!btn) return;

    const torneoId = btn.dataset.torneoId;
    document.getElementById("abrirTorneoId").value = torneoId;

    // Abrir modal
    const modalEl = document.getElementById("modalAbrirInscripciones");
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });

  // --- Confirmar apertura --- //
  document
    .getElementById("btnConfirmarAbrirInscripciones")
    .addEventListener("click", function () {
      const torneoId = document.getElementById("abrirTorneoId").value;
      const fechaCierre = document.getElementById(
        "fechaCierreInscripcionesAbrir"
      ).value;

      if (!fechaCierre) {
        showToast("Debes seleccionar una fecha de cierre.", "danger");
        return;
      }

      fetch(ENDPOINT_ABRIR_INSCRIPCIONES, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          torneo_id: torneoId,
          fecha_cierre: fechaCierre,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status !== "success") {
            showToast(data.message || "Error al abrir inscripciones", "danger");
            return;
          }

          // Cerrar modal
          const modalEl = document.getElementById("modalAbrirInscripciones");
          const modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide();

          // Refrescar listado
          loadTorneos();

          showToast("Inscripciones abiertas correctamente.", "success");
        })
        .catch((err) => {
          console.error("Error AJAX:", err);
          showToast("Error de comunicación con el servidor.", "danger");
        });
    });

  // Llamada inicial para cargar los torneos al cargar la página
  loadTorneos();
});
