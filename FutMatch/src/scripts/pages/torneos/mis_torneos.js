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

  // Elementos del Toast
  const appToastElement = document.getElementById("appToast");
  const toastBodyElement = document.getElementById("toastBody");
  const appToast = appToastElement
    ? new bootstrap.Toast(appToastElement)
    : null;

  // === Función de Toast  ===
  function showToast(message, type = "success") {
    if (!appToast || !appToastElement || !toastBodyElement) {
      console.error(
        "Toast elements not found. Falling back to alert:",
        message
      );

      return;
    }

    // Remover clases de color previas
    appToastElement.classList.remove(
      "bg-success",
      "bg-danger",
      "bg-warning",
      "bg-info"
    );

    // Asignar clase de color según el tipo
    let bgColor = "bg-success";
    if (type === "error") bgColor = "bg-danger";
    else if (type === "warning") bgColor = "bg-warning";
    else if (type === "info") bgColor = "bg-info";

    appToastElement.classList.add(bgColor);
    toastBodyElement.textContent = message;
    appToast.show();
  }
  // ===============================================

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

  // Función para generar la tarjeta HTML de un torneo (Se mantiene igual)
  function createTorneoCardHtml(torneo) {
    // Formatear fecha a DD/MM/AAAA
    const fechaInicio = new Date(torneo.fecha_inicio).toLocaleDateString(
      "es-ES",
      { day: "2-digit", month: "2-digit", year: "numeric" }
    );

    let badgeColor = "text-bg-secondary";
    let actionButton = "";
    // Esta variable se asume definida en el entorno PHP que incluye este JS
    const torneoLink = "<?php echo PAGE_MIS_TORNEOS_DETALLE_ADMIN_CANCHA; ?>";

    // Lógica de botones y colores basada en el nombre de la etapa (de la tabla etapas_torneo)
    if (torneo.etapa_nombre === "inscripciones abiertas") {
      badgeColor = "text-bg-success";
      actionButton = `<button class="btn btn-dark btn-sm me-1" data-bs-toggle="modal" data-bs-target="#modalSolicitudesTorneo" data-torneo-id="${torneo.id_torneo}" title="Ver solicitudes"><i class="bi bi-people"></i><span class="d-none d-lg-inline ms-1">Solicitudes</span></button>`;
    } else if (torneo.etapa_nombre === "borrador") {
      badgeColor = "text-bg-dark";
      actionButton = `<button class="btn btn-dark btn-sm me-1 btn-abrir-inscripciones" data-bs-toggle="modal" data-bs-target="#modalAbrirInscripciones" data-torneo-id="${torneo.id_torneo}" title="Abrir inscripciones"><i class="bi bi-unlock"></i><span class="d-none d-lg-inline ms-1">Abrir inscripciones</span></button>`;
    } else if (torneo.etapa_nombre === "en curso") {
      badgeColor = "text-bg-primary";
      actionButton = `<a class="btn btn-dark btn-sm me-1" href="${torneoLink}?id=${torneo.id_torneo}" title="Gestionar torneo"><i class="bi bi-gear"></i><span class="d-none d-lg-inline ms-1">Gestionar</span></a>`;
    } else if (torneo.etapa_nombre === "finalizado") {
      badgeColor = "text-bg-info";
    } else if (torneo.id_etapa === 5 || torneo.etapa_nombre === "cancelado") {
      // Etapa Cancelado (ID 5 asumido en el PHP)
      badgeColor = "text-bg-danger";
    }

    // Mostrar u ocultar el botón de cancelar
    const isCanceled =
      torneo.id_etapa === 5 || torneo.etapa_nombre === "cancelado";
    const cancelButton = isCanceled
      ? "" // No mostrar si ya está cancelado
      : // CLAVE: Se añade la clase 'btn-cancelar-torneo-trigger' y se eliminan data-bs-toggle/target
        `<button class="btn btn-dark btn-sm btn-cancelar-torneo-trigger" data-torneo-id="${torneo.id_torneo}" title="Cancelar">
                <i class="bi bi-x-circle"></i>
                <span class="d-none d-lg-inline ms-1">Cancelar</span>
            </button>`;

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
                                <span class="badge ${badgeColor}">${torneo.etapa_nombre || "Desconocido"}</span>
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
          // Renderiza el HTML
          let html = data.torneos.map(createTorneoCardHtml).join("");
          torneosListContainer.innerHTML = html;

          // IMPORTANTE: Después de cargar los torneos, aplicamos el filtro
          // por si el usuario ya tenía texto en el campo de búsqueda.
          filterTorneos();
        } else {
            fechaCierreContainer.classList.add('d-none');
            if (fechaCierreInput) {
                fechaCierreInput.removeAttribute('required');
                fechaCierreInput.value = '';
            }
        }
    });

    // Manejar el envío del formulario de Creación
    btnCrearTorneo.addEventListener('click', async function (e) {
        e.preventDefault();

        if (!formCrearTorneo.checkValidity()) {
            formCrearTorneo.classList.add('was-validated');
            return;
        }

        const data = new FormData(formCrearTorneo);
        

        btnCrearTorneo.disabled = true;
        btnCrearTorneo.textContent = 'Creando...';

        try {
            const response = await fetch(ENDPOINT_CREAR, { method: 'POST', body: data });

            if (!response.ok) {
                const errorResult = await response.json();
                throw new Error(errorResult.message || `Error ${response.status} en la petición.`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                showToast('Torneo creado exitosamente.');
                formCrearTorneo.reset();
                formCrearTorneo.classList.remove('was-validated');
                modalCrearTorneo.hide();

                await loadTorneos();
            } else {
                showToast('Error: ' + result.message, 'error');
            }
        } catch (error) {
            console.error('Error AJAX (Crear Torneo):', error);
            showToast('Error al crear torneo: ' + error.message, 'error');
        } finally {
            btnCrearTorneo.disabled = false;
            btnCrearTorneo.textContent = 'Crear Torneo';
        }
    });


    // --- Lógica del Modal de Cancelación ---

    // 1. Listener delegado para botones generados dinámicamente
    torneosListContainer.addEventListener('click', function (event) {
        // Usa closest para encontrar el botón que disparó el evento
        const button = event.target.closest('.btn-cancelar-torneo-trigger');
        if (button) {
            const torneoId = button.getAttribute('data-torneo-id');
            cancelarTorneoIdInput.value = torneoId; // Almacenar el ID

            if (modalCancelarTorneo) {
                modalCancelarTorneo.show(); // Mostrar el modal manualmente
            } else {
                console.error('La instancia del modal de cancelación no está definida.');
            }
        }
    });

    // 2. Manejar la confirmación de cancelación
    btnConfirmarCancelar.addEventListener('click', async function () {
        const torneoId = cancelarTorneoIdInput.value;
        if (!torneoId) {
            showToast('Error: No se encontró el ID del torneo a cancelar.', 'error');
            return;
        }

        btnConfirmarCancelar.disabled = true;
        btnConfirmarCancelar.textContent = 'Cancelando...';

        const data = new FormData();
        data.append('torneo_id', torneoId);

        try {
            const response = await fetch(ENDPOINT_CANCELAR, {
                method: 'POST',
                body: data
            });

            if (!response.ok) {
                // Leer el JSON de error del servidor
                const errorResult = await response.json();
                throw new Error(errorResult.message || `Error ${response.status} en la petición.`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                showToast('Torneo cancelado exitosamente.');
                modalCancelarTorneo.hide();
                await loadTorneos(); // Recargar la lista para que el torneo se muestre como 'cancelado'
            } else {
                showToast('Error: ' + result.message, 'error');
            }

        } catch (error) {
            console.error('Error AJAX (Cancelar Torneo):', error);
            showToast('Error al cancelar torneo: ' + error.message, 'error');
        } finally {
            btnConfirmarCancelar.disabled = false;
            btnConfirmarCancelar.textContent = 'Sí, cancelar torneo';
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

    btnCrearTorneo.disabled = true;
    btnCrearTorneo.textContent = "Creando...";

    try {
      const response = await fetch(ENDPOINT_CREAR, {
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
        showToast("Torneo creado exitosamente.", "success");
        formCrearTorneo.reset();
        formCrearTorneo.classList.remove("was-validated");
        modalCrearTorneo.hide();

        await loadTorneos();
      } else {
        showToast("Error: " + result.message, "error");
      }
    } catch (error) {
      console.error("Error AJAX (Crear Torneo):", error);
      showToast("Error al crear torneo: " + error.message, "error");
    } finally {
      btnCrearTorneo.disabled = false;
      btnCrearTorneo.textContent = "Crear Torneo";
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
                        <td colspan="3" class="text-center text-muted">
                            No hay torneos finalizados.
                        </td>
                    </tr>`;
          return;
        }

        data.data.forEach((torneo) => {
          let row = `
                    <tr>
                        <td>${torneo.nombre}</td>
                        <td>${formatearFecha(torneo.fecha_inicio)}</td>
                        <td>${formatearFecha(torneo.fecha_fin)}</td>
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
