/**
 * Sistema de notificaciones Toast
 * Uso: showToast('mensaje', 'tipo', duración)
 * Tipos: 'success', 'error', 'warning', 'info'
 */

// Crear contenedor de toasts si no existe
function getToastContainer() {
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }
  return container;
}

// Iconos según tipo
const toastIcons = {
  success: "bi-check-circle-fill",
  error: "bi-x-circle-fill",
  warning: "bi-exclamation-triangle-fill",
  info: "bi-info-circle-fill",
};

/**
 * Mostrar notificación toast
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo: 'success', 'error', 'warning', 'info'
 * @param {number} duration - Duración en ms (default: 3000)
 */
function showToast(message, type = "info", duration = 3000) {
  const container = getToastContainer();
  const icon = toastIcons[type] || toastIcons.info;

  const toast = document.createElement("div");
  toast.className = `toast-notification ${type}`;
  toast.innerHTML = `
    <i class="bi ${icon} toast-icon"></i>
    <div class="toast-content">
      <p class="toast-message">${message}</p>
    </div>
  `;

  container.appendChild(toast);

  // Auto-eliminar después de la duración especificada
  setTimeout(() => {
    toast.classList.add("hiding");
    setTimeout(() => {
      toast.remove();
      // Eliminar contenedor si está vacío
      if (container.children.length === 0) {
        container.remove();
      }
    }, 300); // Esperar animación de salida
  }, duration);
}

// Exportar para uso global
window.showToast = showToast;
