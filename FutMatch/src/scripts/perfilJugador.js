/**
 * JavaScript para Perfil de Jugador
 * Maneja las funcionalidades de perfil, edición y configuración
 */

// ===================================
// INICIALIZACIÓN
// ===================================
document.addEventListener("DOMContentLoaded", function () {
  initEventListeners();
  initImagePreview();
});

// ===================================
// EVENT LISTENERS
// ===================================
function initEventListeners() {
  // Botón editar perfil
  const btnEditarPerfil = document.getElementById("btnEditarPerfil");
  if (btnEditarPerfil) {
    btnEditarPerfil.addEventListener("click", toggleEditMode);
  }

  // Botón guardar configuración
  const btnGuardarConfiguracion = document.getElementById(
    "btnGuardarConfiguracion"
  );
  if (btnGuardarConfiguracion) {
    btnGuardarConfiguracion.addEventListener("click", guardarConfiguracion);
  }

  // Botón guardar avatar
  const btnGuardarAvatar = document.getElementById("btnGuardarAvatar");
  if (btnGuardarAvatar) {
    btnGuardarAvatar.addEventListener("click", guardarAvatar);
  }

  // Botón ver estadísticas
  const btnVerEstadisticas = document.getElementById("btnVerEstadisticas");
  if (btnVerEstadisticas) {
    btnVerEstadisticas.addEventListener("click", verEstadisticasCompletas);
  }

  // Botón enviar reporte (si existe)
  const btnEnviarReporte = document.getElementById("btnEnviarReporte");
  if (btnEnviarReporte) {
    btnEnviarReporte.addEventListener("click", enviarReporte);
  }
}

// ===================================
// FUNCIONALIDADES DE EDICIÓN
// ===================================
function toggleEditMode() {
  const isEditing = document.body.classList.contains("edit-mode");

  if (isEditing) {
    // Salir del modo edición
    exitEditMode();
  } else {
    // Entrar en modo edición
    enterEditMode();
  }
}

function enterEditMode() {
  document.body.classList.add("edit-mode");

  // Cambiar el texto del botón
  const btnEditarPerfil = document.getElementById("btnEditarPerfil");
  if (btnEditarPerfil) {
    btnEditarPerfil.innerHTML =
      '<i class="bi bi-check-circle"></i> Guardar Cambios';
    btnEditarPerfil.classList.remove("btn-primary");
    btnEditarPerfil.classList.add("btn-success");
  }

  // Hacer editables los campos principales
  makeFieldEditable("nombreJugador", "text");
  makeFieldEditable("emailJugador", "email");
  makeFieldEditable("telefonoJugador", "tel");
  makeFieldEditable("ubicacionJugador", "text");

  showMessage("Modo edición activado", "info");
}

function exitEditMode() {
  document.body.classList.remove("edit-mode");

  // Restaurar el botón
  const btnEditarPerfil = document.getElementById("btnEditarPerfil");
  if (btnEditarPerfil) {
    btnEditarPerfil.innerHTML =
      '<i class="bi bi-pencil-square"></i> Editar Perfil';
    btnEditarPerfil.classList.remove("btn-success");
    btnEditarPerfil.classList.add("btn-primary");
  }

  // Guardar cambios y restaurar campos
  saveFieldChanges();
  showMessage("Cambios guardados exitosamente", "success");
}

function makeFieldEditable(fieldId, type = "text") {
  const field = document.getElementById(fieldId);
  if (!field) return;

  const currentValue = field.textContent.trim();

  // Crear input
  const input = document.createElement("input");
  input.type = type;
  input.value = currentValue;
  input.className = "form-control form-control-sm";
  input.id = fieldId + "_edit";
  input.dataset.originalValue = currentValue;

  // Reemplazar el contenido
  field.innerHTML = "";
  field.appendChild(input);

  // Focus en el primer campo
  if (fieldId === "nombreJugador") {
    input.focus();
  }
}

function saveFieldChanges() {
  const editableFields = [
    "nombreJugador",
    "emailJugador",
    "telefonoJugador",
    "ubicacionJugador",
  ];

  editableFields.forEach((fieldId) => {
    const field = document.getElementById(fieldId);
    const input = document.getElementById(fieldId + "_edit");

    if (field && input) {
      field.textContent = input.value;
    }
  });
}

// ===================================
// GESTIÓN DE CONFIGURACIÓN
// ===================================
function guardarConfiguracion() {
  const form = document.getElementById("formConfiguracion");
  const formData = new FormData(form);

  // Validar formulario
  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return;
  }

  // Simular guardado
  showMessage("Configuración guardada exitosamente", "success");

  // Actualizar la información en el perfil
  updateProfileFromForm(formData);

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalConfiguracion")
  );
  if (modal) {
    modal.hide();
  }
}

function updateProfileFromForm(formData) {
  // Actualizar nombre
  const nombreField = document.getElementById("nombreJugador");
  if (nombreField) {
    nombreField.textContent = formData.get("nombre");
  }

  // Actualizar username
  const usernameField = document.getElementById("usernameJugador");
  if (usernameField) {
    usernameField.textContent = "@" + formData.get("username");
  }

  // Actualizar email
  const emailField = document.getElementById("emailJugador");
  if (emailField) {
    emailField.textContent = formData.get("email");
  }

  // Actualizar teléfono
  const telefonoField = document.getElementById("telefonoJugador");
  if (telefonoField) {
    telefonoField.textContent = formData.get("telefono");
  }

  // Actualizar ubicación
  const ubicacionField = document.getElementById("ubicacionJugador");
  if (ubicacionField) {
    ubicacionField.textContent = formData.get("ubicacion");
  }

  // Actualizar posición
  const posicionField = document.getElementById("posicionJugador");
  if (posicionField) {
    const posiciones = {
      arquero: "Arquero",
      defensor: "Defensor",
      mediocampista: "Mediocampista",
      delantero: "Delantero",
    };
    posicionField.textContent =
      posiciones[formData.get("posicion")] || "Mediocampista";
  }

  // Actualizar pie hábil
  const pieField = document.getElementById("pieJugador");
  if (pieField) {
    const pie = formData.get("pie");
    pieField.textContent = pie.charAt(0).toUpperCase() + pie.slice(1);
  }
}

// ===================================
// GESTIÓN DE AVATAR
// ===================================
function initImagePreview() {
  const inputAvatar = document.getElementById("inputAvatar");
  if (inputAvatar) {
    inputAvatar.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          const preview = document.getElementById("previewAvatar");
          if (preview) {
            preview.src = e.target.result;
          }
        };
        reader.readAsDataURL(file);
      }
    });
  }
}

function guardarAvatar() {
  const inputAvatar = document.getElementById("inputAvatar");
  const file = inputAvatar ? inputAvatar.files[0] : null;

  if (!file) {
    showMessage("Por favor selecciona una imagen", "warning");
    return;
  }

  // Validar tipo de archivo
  if (!file.type.startsWith("image/")) {
    showMessage("Por favor selecciona un archivo de imagen válido", "error");
    return;
  }

  // Validar tamaño (máximo 2MB)
  if (file.size > 2 * 1024 * 1024) {
    showMessage("La imagen debe ser menor a 2MB", "error");
    return;
  }

  // Simular guardado
  const reader = new FileReader();
  reader.onload = function (e) {
    // Actualizar avatar principal
    const avatarJugador = document.getElementById("avatarJugador");
    if (avatarJugador) {
      avatarJugador.src = e.target.result;
    }

    showMessage("Avatar actualizado exitosamente", "success");

    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalCambiarAvatar")
    );
    if (modal) {
      modal.hide();
    }
  };
  reader.readAsDataURL(file);
}

// ===================================
// ESTADÍSTICAS
// ===================================
function verEstadisticasCompletas() {
  // Redirigir a página de estadísticas o mostrar modal
  showMessage("Funcionalidad en desarrollo", "info");
}

// ===================================
// REPORTES
// ===================================
function enviarReporte() {
  const form = document.getElementById("formReportarJugador");
  const formData = new FormData(form);

  if (!form.checkValidity()) {
    form.classList.add("was-validated");
    return;
  }

  // Simular envío de reporte
  showMessage(
    "Reporte enviado exitosamente. Será revisado por nuestros moderadores.",
    "success"
  );

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalReportarJugador")
  );
  if (modal) {
    modal.hide();
  }

  // Limpiar formulario
  form.reset();
  form.classList.remove("was-validated");
}

// ===================================
// UTILIDADES
// ===================================
function showMessage(message, type = "info") {
  // Crear toast o alert
  const alertClass =
    {
      success: "alert-success",
      error: "alert-danger",
      warning: "alert-warning",
      info: "alert-info",
    }[type] || "alert-info";

  const alertDiv = document.createElement("div");
  alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
  alertDiv.style.cssText =
    "top: 20px; right: 20px; z-index: 9999; max-width: 400px;";
  alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

  document.body.appendChild(alertDiv);

  // Auto-remove después de 5 segundos
  setTimeout(() => {
    if (alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}

// Manejar cierre de modales para resetear formularios
document.addEventListener("hidden.bs.modal", function (e) {
  const form = e.target.querySelector("form");
  if (form) {
    form.reset();
    form.classList.remove("was-validated");
  }
});
