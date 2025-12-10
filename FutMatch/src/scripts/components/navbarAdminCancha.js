// Inicializar tooltips de Bootstrap
document.addEventListener("DOMContentLoaded", function () {
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// Función para editar campos
function editarCampo(inputId) {
  const input = document.getElementById(inputId);
  if (input.hasAttribute("readonly")) {
    input.removeAttribute("readonly");
    input.focus();
    input.select();
  } else {
    input.setAttribute("readonly", "readonly");
    // TODO: Aquí iría la llamada AJAX para guardar los cambios
    showToast("Cambios guardados correctamente", "success");
  }
}

// Función para eliminar notificación
function eliminarNotificacion(button) {
  const listItem = button.closest(".list-group-item");
  if (confirm("¿Desea eliminar esta notificación?")) {
    // TODO: Llamada AJAX para eliminar notificación
    listItem.style.transition = "opacity 0.3s ease";
    listItem.style.opacity = "0";
    setTimeout(() => {
      listItem.remove();
    }, 300);
  }
}
