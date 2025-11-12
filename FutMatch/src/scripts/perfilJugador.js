/**
 * Perfil de Jugador JavaScript
 * Funcionalidad completa para la gestión del perfil de jugador
 * Incluye upload de avatar, banner y todas las interacciones del perfil
 */

class PerfilJugador {
  constructor() {
    this.initializeEventListeners();
    this.initializeDragAndDrop();
    this.initImagePreview();
  }

  /**
   * Inicializar event listeners
   */
  initializeEventListeners() {
    // Avatar upload
    const inputAvatar = document.getElementById("inputAvatar");
    const btnGuardarAvatar = document.getElementById("btnGuardarAvatar");

    if (inputAvatar) {
      inputAvatar.addEventListener("change", (e) =>
        this.handleAvatarPreview(e)
      );
    }

    if (btnGuardarAvatar) {
      btnGuardarAvatar.addEventListener("click", () => this.saveAvatar());
    }

    // Banner upload
    const inputBanner = document.getElementById("inputBanner");
    const btnGuardarBanner = document.getElementById("btnGuardarBanner");
    const bannerUploadZone = document.getElementById("bannerUploadZone");

    if (inputBanner) {
      inputBanner.addEventListener("change", (e) =>
        this.handleBannerPreview(e)
      );
    }

    if (btnGuardarBanner) {
      btnGuardarBanner.addEventListener("click", () => this.saveBanner());
    }

    if (bannerUploadZone) {
      bannerUploadZone.addEventListener("click", () => inputBanner?.click());
    }

    // Avatar upload zone
    const avatarUploadZone = document.getElementById("avatarUploadZone");
    if (avatarUploadZone) {
      avatarUploadZone.addEventListener("click", () => inputAvatar?.click());
    }

    // Funcionalidades existentes
    const btnEditarPerfil = document.getElementById("btnEditarPerfil");
    if (btnEditarPerfil) {
      btnEditarPerfil.addEventListener("click", () => this.toggleEditMode());
    }

    const btnGuardarConfiguracion = document.getElementById(
      "btnGuardarConfiguracion"
    );
    if (btnGuardarConfiguracion) {
      btnGuardarConfiguracion.addEventListener("click", () =>
        this.guardarConfiguracion()
      );
    }

    const btnVerEstadisticas = document.getElementById("btnVerEstadisticas");
    if (btnVerEstadisticas) {
      btnVerEstadisticas.addEventListener("click", () =>
        this.mostrarEstadisticasCompletas()
      );
    }

    const btnEnviarReporte = document.getElementById("btnEnviarReporte");
    if (btnEnviarReporte) {
      btnEnviarReporte.addEventListener("click", () => this.enviarReporte());
    }
  }

  /**
   * Inicializar funcionalidad drag and drop para banner y avatar
   */
  initializeDragAndDrop() {
    // Drag and drop para banner
    this.setupDragAndDrop(
      "bannerUploadZone",
      "inputBanner",
      this.handleBannerPreview.bind(this)
    );

    // Drag and drop para avatar
    this.setupDragAndDrop(
      "avatarUploadZone",
      "inputAvatar",
      this.handleAvatarPreview.bind(this)
    );
  }

  /**
   * Configurar drag and drop para una zona específica
   */
  setupDragAndDrop(uploadZoneId, inputId, previewHandler) {
    const uploadZone = document.getElementById(uploadZoneId);
    const input = document.getElementById(inputId);

    if (!uploadZone || !input) return;

    // Prevenir comportamiento por defecto
    ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
      uploadZone.addEventListener(eventName, this.preventDefaults, false);
      document.body.addEventListener(eventName, this.preventDefaults, false);
    });

    // Highlight cuando se arrastra sobre la zona
    ["dragenter", "dragover"].forEach((eventName) => {
      uploadZone.addEventListener(
        eventName,
        () => {
          uploadZone.classList.add("dragover");
        },
        false
      );
    });

    ["dragleave", "drop"].forEach((eventName) => {
      uploadZone.addEventListener(
        eventName,
        () => {
          uploadZone.classList.remove("dragover");
        },
        false
      );
    });

    // Manejar el drop
    uploadZone.addEventListener(
      "drop",
      (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          input.files = files;
          previewHandler({ target: { files: files } });
        }
      },
      false
    );
  }

  /**
   * Inicializar preview de imágenes
   */
  initImagePreview() {
    const inputAvatar = document.getElementById("inputAvatar");
    if (inputAvatar) {
      inputAvatar.addEventListener("change", (e) =>
        this.handleAvatarPreview(e)
      );
    }
  }

  /**
   * Prevenir comportamiento por defecto de drag and drop
   */
  preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
  }

  /**
   * Manejar preview del avatar
   */
  handleAvatarPreview(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const previewContainer = document.getElementById(
          "avatarPreviewContainer"
        );
        const avatarPreview = document.getElementById("avatarPreview");
        const btnGuardarAvatar = document.getElementById("btnGuardarAvatar");

        if (avatarPreview) {
          avatarPreview.style.backgroundImage = `url(${e.target.result})`;
        }

        if (previewContainer) {
          previewContainer.style.display = "block";
        }

        if (btnGuardarAvatar) {
          btnGuardarAvatar.disabled = false;
        }
      };
      reader.readAsDataURL(file);
    }
  }

  /**
   * Manejar preview del banner
   */
  handleBannerPreview(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const previewContainer = document.getElementById(
          "bannerPreviewContainer"
        );
        const bannerPreview = document.getElementById("bannerPreview");
        const btnGuardarBanner = document.getElementById("btnGuardarBanner");

        if (bannerPreview) {
          bannerPreview.style.backgroundImage = `url(${e.target.result})`;
        }

        if (previewContainer) {
          previewContainer.style.display = "block";
        }

        if (btnGuardarBanner) {
          btnGuardarBanner.disabled = false;
        }
      };
      reader.readAsDataURL(file);
    }
  }

  /**
   * Guardar avatar
   */
  async saveAvatar() {
    const inputAvatar = document.getElementById("inputAvatar");
    const file = inputAvatar?.files[0];

    if (!file) {
      this.showMessage("Por favor selecciona una imagen", "warning");
      return;
    }

    // Validar tipo de archivo
    if (!file.type.startsWith("image/")) {
      this.showMessage(
        "Por favor selecciona un archivo de imagen válido",
        "error"
      );
      return;
    }

    // Validar tamaño (máximo 5MB)
    if (file.size > 5 * 1024 * 1024) {
      this.showMessage(
        "La imagen es demasiado grande. Máximo 5MB permitido",
        "error"
      );
      return;
    }

    try {
      this.showLoader("btnGuardarAvatar", true);

      // Simulación de guardado exitoso
      await this.delay(1000);

      // Actualizar avatar en la página
      const avatarJugador = document.getElementById("avatarJugador");
      const avatarPreview = document.getElementById("avatarPreview");
      if (avatarJugador && avatarPreview) {
        const backgroundImage = avatarPreview.style.backgroundImage;
        // Extraer URL de background-image y aplicar al src del avatar
        const imageUrl = backgroundImage.slice(4, -1).replace(/"/g, "");
        avatarJugador.src = imageUrl;
      }

      this.showMessage("Avatar actualizado correctamente", "success");

      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalCambiarAvatar")
      );
      modal?.hide();
    } catch (error) {
      console.error("Error al guardar avatar:", error);
      this.showMessage(
        "Error al guardar el avatar. Inténtalo de nuevo",
        "error"
      );
    } finally {
      this.showLoader("btnGuardarAvatar", false);
    }
  }

  /**
   * Guardar banner
   */
  async saveBanner() {
    const inputBanner = document.getElementById("inputBanner");
    const file = inputBanner?.files[0];

    if (!file) {
      this.showMessage("Por favor selecciona una imagen", "warning");
      return;
    }

    try {
      this.showLoader("btnGuardarBanner", true);

      // Simulación de guardado
      await this.delay(1000);

      // Actualizar banner en la página
      const bannerElement = document.querySelector(".profile-banner-image");
      const bannerPreview = document.getElementById("bannerPreview");

      if (bannerElement && bannerPreview) {
        const backgroundImage = bannerPreview.style.backgroundImage;
        bannerElement.style.backgroundImage = backgroundImage;
      }

      this.showMessage("Portada actualizada correctamente", "success");

      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalCambiarBanner")
      );
      modal?.hide();
    } catch (error) {
      console.error("Error al guardar banner:", error);
      this.showMessage(
        "Error al guardar la portada. Inténtalo de nuevo",
        "error"
      );
    } finally {
      this.showLoader("btnGuardarBanner", false);
    }
  }

  /**
   * Toggle modo edición
   */
  toggleEditMode() {
    const isEditing = document.body.classList.contains("edit-mode");

    if (isEditing) {
      this.exitEditMode();
    } else {
      this.enterEditMode();
    }
  }

  /**
   * Entrar en modo edición
   */
  enterEditMode() {
    document.body.classList.add("edit-mode");

    const btnEditarPerfil = document.getElementById("btnEditarPerfil");
    if (btnEditarPerfil) {
      btnEditarPerfil.innerHTML =
        '<i class="bi bi-check-circle"></i> Guardar Cambios';
      btnEditarPerfil.classList.remove("btn-primary");
      btnEditarPerfil.classList.add("btn-success");
    }

    this.makeFieldEditable("nombreJugador", "text");
    this.makeFieldEditable("emailJugador", "email");
    this.makeFieldEditable("telefonoJugador", "tel");
    this.makeFieldEditable("ubicacionJugador", "text");

    this.showMessage("Modo edición activado", "info");
  }

  /**
   * Salir del modo edición
   */
  exitEditMode() {
    document.body.classList.remove("edit-mode");

    const btnEditarPerfil = document.getElementById("btnEditarPerfil");
    if (btnEditarPerfil) {
      btnEditarPerfil.innerHTML =
        '<i class="bi bi-pencil-square"></i> Editar Perfil';
      btnEditarPerfil.classList.remove("btn-success");
      btnEditarPerfil.classList.add("btn-primary");
    }

    this.saveFieldChanges();
    this.showMessage("Cambios guardados exitosamente", "success");
  }

  /**
   * Hacer campo editable
   */
  makeFieldEditable(fieldId, type = "text") {
    const field = document.getElementById(fieldId);
    if (!field) return;

    const currentValue = field.textContent.trim();
    const input = document.createElement("input");
    input.type = type;
    input.value = currentValue;
    input.className = "form-control form-control-sm";
    input.id = fieldId + "_edit";
    input.dataset.originalValue = currentValue;

    field.innerHTML = "";
    field.appendChild(input);

    if (fieldId === "nombreJugador") {
      input.focus();
    }
  }

  /**
   * Guardar cambios de campos
   */
  saveFieldChanges() {
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

  /**
   * Guardar configuración
   */
  guardarConfiguracion() {
    const form = document.getElementById("formConfiguracion");
    const formData = new FormData(form);

    if (!form.checkValidity()) {
      form.classList.add("was-validated");
      return;
    }

    this.showMessage("Configuración guardada exitosamente", "success");
    this.updateProfileFromForm(formData);

    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalConfiguracion")
    );
    modal?.hide();
  }

  /**
   * Actualizar perfil desde formulario
   */
  updateProfileFromForm(formData) {
    const updates = {
      nombreJugador: formData.get("nombre"),
      usernameJugador: "@" + formData.get("username"),
      emailJugador: formData.get("email"),
      telefonoJugador: formData.get("telefono"),
      ubicacionJugador: formData.get("ubicacion"),
    };

    Object.entries(updates).forEach(([fieldId, value]) => {
      const field = document.getElementById(fieldId);
      if (field && value) {
        field.textContent = value;
      }
    });
  }

  /**
   * Mostrar estadísticas completas
   */
  mostrarEstadisticasCompletas() {
    this.showMessage("Función de estadísticas completas en desarrollo", "info");
  }

  /**
   * Enviar reporte de jugador
   */
  async enviarReporte() {
    const form = document.getElementById("formReportarJugador");
    if (!form) return;

    const formData = new FormData(form);
    const motivo = formData.get("motivo");
    const descripcion = formData.get("descripcion");

    if (!motivo || !descripcion) {
      this.showMessage("Por favor completa todos los campos", "warning");
      return;
    }

    try {
      this.showLoader("btnEnviarReporte", true);
      await this.delay(1000);

      this.showMessage(
        "Reporte enviado correctamente. Será revisado por nuestro equipo",
        "success"
      );

      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalReportarJugador")
      );
      modal?.hide();

      form.reset();
    } catch (error) {
      console.error("Error al enviar reporte:", error);
      this.showMessage(
        "Error al enviar el reporte. Inténtalo de nuevo",
        "error"
      );
    } finally {
      this.showLoader("btnEnviarReporte", false);
    }
  }

  /**
   * Mostrar loader en botón
   */
  showLoader(buttonId, show) {
    const button = document.getElementById(buttonId);
    if (!button) return;

    if (show) {
      button.disabled = true;
      button.innerHTML =
        '<i class="bi bi-arrow-clockwise spin me-2"></i>Guardando...';
    } else {
      button.disabled = false;
      const buttonTexts = {
        btnGuardarAvatar: "Guardar Foto",
        btnGuardarBanner: "Guardar Portada",
        btnEnviarReporte: "Enviar Reporte",
      };
      button.innerHTML = buttonTexts[buttonId] || "Guardar";
    }
  }

  /**
   * Mostrar mensaje de notificación
   */
  showMessage(message, type = "info") {
    const alertClasses = {
      success: "alert-success",
      error: "alert-danger",
      warning: "alert-warning",
      info: "alert-info",
    };

    const alertDiv = document.createElement("div");
    alertDiv.className = `alert ${alertClasses[type]} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText =
      "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
    alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
      if (alertDiv.parentNode) {
        alertDiv.remove();
      }
    }, 5000);
  }

  /**
   * Delay helper para simulaciones
   */
  delay(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }
}

// CSS para el spinner
const spinnerCSS = `
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
`;

// Agregar CSS al head
const style = document.createElement("style");
style.textContent = spinnerCSS;
document.head.appendChild(style);

// Inicialización cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  new PerfilJugador();
});

// Manejar cierre de modales para resetear formularios
document.addEventListener("hidden.bs.modal", function (e) {
  const form = e.target.querySelector("form");
  if (form) {
    form.reset();
    form.classList.remove("was-validated");
  }
});
