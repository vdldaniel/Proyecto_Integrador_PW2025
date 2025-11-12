/**
 * Perfil de Jugador JavaScript
 * Funcionalidad para la gestión del perfil de jugador
 * Incluye upload de avatar, banner y interacciones del perfil
 */

class PerfilJugador {
  constructor() {
    this.initializeEventListeners();
    this.initializeDragAndDrop();
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

    // Botón ver estadísticas
    const btnVerEstadisticas = document.getElementById("btnVerEstadisticas");
    if (btnVerEstadisticas) {
      btnVerEstadisticas.addEventListener("click", () =>
        this.mostrarEstadisticasCompletas()
      );
    }

    // Reportar jugador
    const btnEnviarReporte = document.getElementById("btnEnviarReporte");
    if (btnEnviarReporte) {
      btnEnviarReporte.addEventListener("click", () => this.enviarReporte());
    }
  }

  /**
   * Inicializar funcionalidad drag and drop para el banner
   */
  initializeDragAndDrop() {
    const uploadZone = document.getElementById("bannerUploadZone");
    const inputBanner = document.getElementById("inputBanner");

    if (!uploadZone || !inputBanner) return;

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
          inputBanner.files = files;
          this.handleBannerPreview({ target: { files: files } });
        }
      },
      false
    );
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
        const previewAvatar = document.getElementById("previewAvatar");
        if (previewAvatar) {
          previewAvatar.src = e.target.result;
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

      const formData = new FormData();
      formData.append("avatar", file);

      // Aquí iría la llamada AJAX real al servidor
      // const response = await fetch('/api/perfil/avatar', {
      //     method: 'POST',
      //     body: formData
      // });

      // Simulación de guardado exitoso
      await this.delay(1000);

      // Actualizar avatar en la página
      const avatarJugador = document.getElementById("avatarJugador");
      const previewAvatar = document.getElementById("previewAvatar");
      if (avatarJugador && previewAvatar) {
        avatarJugador.src = previewAvatar.src;
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

      const formData = new FormData();
      formData.append("banner", file);

      // Aquí iría la llamada AJAX real al servidor
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
   * Mostrar estadísticas completas
   */
  mostrarEstadisticasCompletas() {
    // Aquí se podría abrir un modal con estadísticas detalladas
    // o redirigir a una página específica
    console.log("Mostrando estadísticas completas...");
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

      // Aquí iría la llamada AJAX real al servidor
      await this.delay(1000);

      this.showMessage(
        "Reporte enviado correctamente. Será revisado por nuestro equipo",
        "success"
      );

      // Cerrar modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalReportarJugador")
      );
      modal?.hide();

      // Limpiar formulario
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
      // Restaurar texto original basado en el botón
      switch (buttonId) {
        case "btnGuardarAvatar":
          button.innerHTML = "Guardar";
          break;
        case "btnGuardarBanner":
          button.innerHTML = "Guardar Portada";
          break;
        case "btnEnviarReporte":
          button.innerHTML = "Enviar Reporte";
          break;
        default:
          button.innerHTML = "Guardar";
      }
    }
  }

  /**
   * Mostrar mensaje de notificación
   */
  showMessage(message, type = "info") {
    // Crear elemento de notificación
    const alertClass =
      type === "success"
        ? "alert-success"
        : type === "error"
        ? "alert-danger"
        : type === "warning"
        ? "alert-warning"
        : "alert-info";

    const alertDiv = document.createElement("div");
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText =
      "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";
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

// Inicializar cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", function () {
  new PerfilJugador();
});
