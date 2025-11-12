/**
 * JavaScript para la página de Canchas Reportadas - Admin Sistema
 * Maneja los modales y funcionalidades de reportes de canchas
 */

// ===================================
// VARIABLES GLOBALES
// ===================================
let currentReportData = null;

// ===================================
// INICIALIZACIÓN
// ===================================
document.addEventListener("DOMContentLoaded", function () {
  initEventListeners();
  loadReportedCanchas();
});

// ===================================
// EVENT LISTENERS
// ===================================
function initEventListeners() {
  // Eventos para botones de acción en la tabla
  document.addEventListener("click", function (e) {
    // Ver detalle de reporte
    if (e.target.classList.contains("btn-ver-detalle-reporte")) {
      const canchaId = e.target.dataset.canchaId;
      const reporteId = e.target.dataset.reporteId;
      showReportDetailModal(canchaId, reporteId);
    }

    // Suspender cuenta
    if (e.target.classList.contains("btn-suspender-cuenta")) {
      const canchaId = e.target.dataset.canchaId;
      const canchaNombre = e.target.dataset.canchaNombre;
      showSuspenderCuentaModal(canchaId, canchaNombre);
    }

    // Reestablecer cuenta
    if (e.target.classList.contains("btn-reestablecer-cuenta")) {
      const canchaId = e.target.dataset.canchaId;
      const canchaNombre = e.target.dataset.canchaNombre;
      showRestablecerCuentaModal(canchaId, canchaNombre);
    }

    // Resolver reporte
    if (e.target.classList.contains("btn-resolver-reporte")) {
      const reporteId = e.target.dataset.reporteId;
      resolveReport(reporteId);
    }

    // Rechazar reporte
    if (e.target.classList.contains("btn-rechazar-reporte")) {
      const reporteId = e.target.dataset.reporteId;
      rejectReport(reporteId);
    }
  });

  // Event listeners para formularios de modales
  document
    .getElementById("formSuspenderCancha")
    ?.addEventListener("submit", handleSuspenderSubmit);
  document
    .getElementById("formRestablecerCancha")
    ?.addEventListener("submit", handleRestablecerSubmit);

  // Filtros y búsqueda
  document
    .getElementById("filtroEstado")
    ?.addEventListener("change", applyFilters);
  document
    .getElementById("searchInput")
    ?.addEventListener("input", debounce(applyFilters, 300));
}

// ===================================
// FUNCIONES DE DATOS
// ===================================
async function loadReportedCanchas() {
  try {
    showLoading(true);

    // Simulación de datos - reemplazar con llamada real al servidor
    const response = await fetch("/api/admin/canchas-reportadas");
    const data = await response.json();

    if (data.success) {
      renderReportedCanchas(data.canchas);
    } else {
      showError("Error al cargar las canchas reportadas: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

function renderReportedCanchas(canchas) {
  const tbody = document.getElementById("tablaCanchasReportadas");
  if (!tbody) return;

  tbody.innerHTML = "";

  canchas.forEach((cancha) => {
    const row = createCanchaRow(cancha);
    tbody.appendChild(row);
  });

  updateTotalCount(canchas.length);
}

function createCanchaRow(cancha) {
  const row = document.createElement("tr");
  row.innerHTML = `
        <td>
            <div class="d-flex align-items-center">
                <img src="${cancha.imagen || "/assets/img/default-cancha.jpg"}" 
                     alt="Cancha" class="rounded me-2" width="40" height="40">
                <div>
                    <div class="fw-bold">${cancha.nombre}</div>
                    <small class="text-muted">${cancha.ubicacion}</small>
                </div>
            </div>
        </td>
        <td>
            <div class="fw-medium">${cancha.propietario_nombre}</div>
            <small class="text-muted">${cancha.propietario_email}</small>
        </td>
        <td>
            <span class="badge text-bg-dark">
                ${cancha.total_reportes} reporte${
    cancha.total_reportes !== 1 ? "s" : ""
  }
            </span>
        </td>
        <td>
            <span class="badge ${getEstadoBadgeClass(
              cancha.estado
            )}">${getEstadoText(cancha.estado)}</span>
        </td>
        <td>
            <small class="text-muted">${formatDate(
              cancha.ultimo_reporte
            )}</small>
        </td>
        <td>
            <div class="btn-group" role="group">
                <button type="button" 
                        class="btn btn-sm btn-ver-detalle-reporte"
                        data-cancha-id="${cancha.id}"
                        data-reporte-id="${cancha.ultimo_reporte_id}"
                        title="Ver detalle del reporte">
                    <i class="fas fa-eye"></i>
                </button>
                ${
                  cancha.estado === "activa"
                    ? `
                    <button type="button" 
                            class="btn btn-sm btn-suspender-cuenta"
                            data-cancha-id="${cancha.id}"
                            data-cancha-nombre="${cancha.nombre}"
                            title="Suspender cuenta">
                        <i class="fas fa-ban"></i>
                    </button>
                `
                    : ""
                }
                ${
                  cancha.estado === "suspendida"
                    ? `
                    <button type="button" 
                            class="btn btn-sm btn-reestablecer-cuenta"
                            data-cancha-id="${cancha.id}"
                            data-cancha-nombre="${cancha.nombre}"
                            title="Reestablecer cuenta">
                        <i class="fas fa-check-circle"></i>
                    </button>
                `
                    : ""
                }
            </div>
        </td>
    `;

  return row;
}

// ===================================
// FUNCIONES DE MODALES
// ===================================
async function showReportDetailModal(canchaId, reporteId) {
  try {
    showLoading(true, "Cargando detalles del reporte...");

    // Simulación de llamada al servidor
    const response = await fetch(`/api/admin/reporte-cancha/${reporteId}`);
    const data = await response.json();

    if (data.success) {
      populateReportDetailModal(data.reporte);
      const modal = new bootstrap.Modal(
        document.getElementById("modalDetalleReporte")
      );
      modal.show();
    } else {
      showError("Error al cargar el detalle del reporte: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

function populateReportDetailModal(reporte) {
  // Información básica
  document.getElementById("reporteId").textContent = reporte.id;
  document.getElementById("reporteFecha").textContent = formatDateTime(
    reporte.fecha_reporte
  );
  document.getElementById("reporteEstado").innerHTML = `
        <span class="badge ${getEstadoBadgeClass(
          reporte.estado
        )}">${getEstadoText(reporte.estado)}</span>
    `;

  // Información del reportante
  document.getElementById("reportanteNombre").textContent =
    reporte.reportante.nombre;
  document.getElementById("reportanteEmail").textContent =
    reporte.reportante.email;
  document.getElementById("reportanteTelefono").textContent =
    reporte.reportante.telefono || "No especificado";

  // Información de la cancha
  document.getElementById("canchaNombre").textContent = reporte.cancha.nombre;
  document.getElementById("canchaUbicacion").textContent =
    reporte.cancha.ubicacion;
  document.getElementById("canchaPropietario").textContent =
    reporte.cancha.propietario;

  // Detalles del reporte
  document.getElementById("reporteMotivo").textContent = reporte.motivo;
  document.getElementById("reporteDescripcion").textContent =
    reporte.descripcion;
  document.getElementById("reportePrioridad").innerHTML = `
        <span class="badge ${getPrioridadBadgeClass(reporte.prioridad)}">${
    reporte.prioridad
  }</span>
    `;

  // Evidencias
  const evidenciasContainer = document.getElementById("reporteEvidencias");
  if (reporte.evidencias && reporte.evidencias.length > 0) {
    evidenciasContainer.innerHTML = reporte.evidencias
      .map(
        (evidencia) => `
            <div class="evidencia-item">
                <img src="${
                  evidencia.url
                }" alt="Evidencia" class="img-thumbnail" width="100">
                <small class="d-block text-muted">${
                  evidencia.descripcion || "Sin descripción"
                }</small>
            </div>
        `
      )
      .join("");
  } else {
    evidenciasContainer.innerHTML =
      '<p class="text-muted">No hay evidencias adjuntas</p>';
  }

  // Configurar botones de acción
  const btnResolver = document.getElementById("btnResolverReporte");
  const btnRechazar = document.getElementById("btnRechazarReporte");

  if (reporte.estado === "pendiente") {
    btnResolver.style.display = "block";
    btnRechazar.style.display = "block";
    btnResolver.dataset.reporteId = reporte.id;
    btnRechazar.dataset.reporteId = reporte.id;
  } else {
    btnResolver.style.display = "none";
    btnRechazar.style.display = "none";
  }
}

function showSuspenderCuentaModal(canchaId, canchaNombre) {
  document.getElementById("suspenderCanchaId").value = canchaId;
  document.getElementById("suspenderCanchaNombre").textContent = canchaNombre;

  // Limpiar formulario
  document.getElementById("formSuspenderCancha").reset();
  document.getElementById("motivoSuspension").value = canchaId;

  const modal = new bootstrap.Modal(
    document.getElementById("modalSuspenderCancha")
  );
  modal.show();
}

function showRestablecerCuentaModal(canchaId, canchaNombre) {
  document.getElementById("restablecerCanchaId").value = canchaId;
  document.getElementById("restablecerCanchaNombre").textContent = canchaNombre;

  // Limpiar formulario
  document.getElementById("formRestablecerCancha").reset();

  const modal = new bootstrap.Modal(
    document.getElementById("modalRestablecerCancha")
  );
  modal.show();
}

// ===================================
// MANEJADORES DE FORMULARIOS
// ===================================
async function handleSuspenderSubmit(e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const canchaId = formData.get("cancha_id");
  const motivo = formData.get("motivo_suspension");
  const duracion = formData.get("duracion_suspension");
  const comentarios = formData.get("comentarios_suspension");

  try {
    showLoading(true, "Suspendiendo cuenta...");

    const response = await fetch("/api/admin/suspender-cancha", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        cancha_id: canchaId,
        motivo: motivo,
        duracion: duracion,
        comentarios: comentarios,
      }),
    });

    const data = await response.json();

    if (data.success) {
      showSuccess("Cuenta de cancha suspendida exitosamente");
      bootstrap.Modal.getInstance(
        document.getElementById("modalSuspenderCancha")
      ).hide();
      loadReportedCanchas(); // Recargar la tabla
    } else {
      showError("Error al suspender la cuenta: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

async function handleRestablecerSubmit(e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const canchaId = formData.get("cancha_id");
  const comentarios = formData.get("comentarios_restablecimiento");

  try {
    showLoading(true, "Restableciendo cuenta...");

    const response = await fetch("/api/admin/reestablecer-cancha", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        cancha_id: canchaId,
        comentarios: comentarios,
      }),
    });

    const data = await response.json();

    if (data.success) {
      showSuccess("Cuenta de cancha restablecida exitosamente");
      bootstrap.Modal.getInstance(
        document.getElementById("modalRestablecerCancha")
      ).hide();
      loadReportedCanchas(); // Recargar la tabla
    } else {
      showError("Error al reestablecer la cuenta: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

// ===================================
// FUNCIONES DE ACCIONES
// ===================================
async function resolveReport(reporteId) {
  if (
    !confirm("¿Está seguro de que desea marcar este reporte como resuelto?")
  ) {
    return;
  }

  try {
    showLoading(true, "Resolviendo reporte...");

    const response = await fetch(`/api/admin/resolver-reporte/${reporteId}`, {
      method: "POST",
    });

    const data = await response.json();

    if (data.success) {
      showSuccess("Reporte marcado como resuelto");
      bootstrap.Modal.getInstance(
        document.getElementById("modalDetalleReporte")
      ).hide();
      loadReportedCanchas();
    } else {
      showError("Error al resolver el reporte: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

async function rejectReport(reporteId) {
  if (!confirm("¿Está seguro de que desea rechazar este reporte?")) {
    return;
  }

  try {
    showLoading(true, "Rechazando reporte...");

    const response = await fetch(`/api/admin/rechazar-reporte/${reporteId}`, {
      method: "POST",
    });

    const data = await response.json();

    if (data.success) {
      showSuccess("Reporte rechazado");
      bootstrap.Modal.getInstance(
        document.getElementById("modalDetalleReporte")
      ).hide();
      loadReportedCanchas();
    } else {
      showError("Error al rechazar el reporte: " + data.message);
    }
  } catch (error) {
    console.error("Error:", error);
  } finally {
    showLoading(false);
  }
}

// ===================================
// FUNCIONES DE FILTRADO
// ===================================
function applyFilters() {
  const searchTerm =
    document.getElementById("searchInput")?.value.toLowerCase() || "";
  const estadoFilter =
    document.getElementById("filtroEstado")?.value || "todos";

  const rows = document.querySelectorAll("#tablaCanchasReportadas tr");
  let visibleCount = 0;

  rows.forEach((row) => {
    const canchaNombre = row.cells[0]?.textContent.toLowerCase() || "";
    const propietarioNombre = row.cells[1]?.textContent.toLowerCase() || "";
    const estado =
      row.cells[3]?.querySelector(".badge")?.textContent.toLowerCase() || "";

    const matchesSearch =
      canchaNombre.includes(searchTerm) ||
      propietarioNombre.includes(searchTerm);
    const matchesEstado =
      estadoFilter === "todos" || estado.includes(estadoFilter.toLowerCase());

    if (matchesSearch && matchesEstado) {
      row.style.display = "";
      visibleCount++;
    } else {
      row.style.display = "none";
    }
  });

  updateTotalCount(visibleCount);
}

// ===================================
// FUNCIONES DE UTILIDAD
// ===================================
function getEstadoBadgeClass(estado) {
  switch (estado) {
    case "activa":
      return "bg-success";
    case "suspendida":
      return "bg-danger";
    case "pendiente_revision":
      return "bg-warning text-dark";
    case "verificacion":
      return "bg-info";
    default:
      return "bg-secondary";
  }
}

function getEstadoText(estado) {
  switch (estado) {
    case "activa":
      return "Activa";
    case "suspendida":
      return "Suspendida";
    case "pendiente_revision":
      return "Pendiente Revisión";
    case "verificacion":
      return "En Verificación";
    default:
      return "Desconocido";
  }
}

function getPrioridadBadgeClass(prioridad) {
  switch (prioridad) {
    case "alta":
      return "bg-danger";
    case "media":
      return "bg-warning text-dark";
    case "baja":
      return "bg-info";
    default:
      return "bg-secondary";
  }
}

function formatDate(dateString) {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("es-ES", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
}

function formatDateTime(dateString) {
  if (!dateString) return "N/A";
  const date = new Date(dateString);
  return date.toLocaleDateString("es-ES", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

function updateTotalCount(count) {
  const totalElement = document.getElementById("totalCanchasReportadas");
  if (totalElement) {
    totalElement.textContent = count;
  }
}

function showLoading(show, message = "Cargando...") {
  const loader = document.getElementById("loadingSpinner");
  if (loader) {
    if (show) {
      loader.textContent = message;
      loader.style.display = "block";
    } else {
      loader.style.display = "none";
    }
  }
}

function showSuccess(message) {
  // Implementar sistema de notificaciones
  console.log("Success:", message);
  alert(message); // Temporal - reemplazar con toast/notification
}

function showError(message) {
  // Implementar sistema de notificaciones
  console.error("Error:", message);
  alert("Error: " + message); // Temporal - reemplazar con toast/notification
}

function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}
