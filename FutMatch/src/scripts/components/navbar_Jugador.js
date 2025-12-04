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

// Función para confirmar suspensión
function confirmarSuspension() {
  const fecha = document.getElementById("fechaReactivacion").value;
  const hora = document.getElementById("horaReactivacion").value;

  if (!fecha || !hora) {
    showToast("Por favor, complete la fecha y hora de reactivación", "warning");
    return;
  }

  if (
    confirm(
      "¿Está seguro que desea suspender su cuenta hasta el " +
        fecha +
        " a las " +
        hora +
        "? Sus partidos programados serán cancelados."
    )
  ) {
    // TODO: Llamada AJAX para suspender cuenta
    showToast("Cuenta suspendida exitosamente", "success");
    location.reload();
  }
}

// Función para confirmar eliminación
function confirmarEliminacion() {
  if (
    confirm(
      "¿Está seguro que desea eliminar su cuenta permanentemente? Esta acción NO se puede deshacer."
    )
  ) {
    if (
      confirm(
        "ÚLTIMA CONFIRMACIÓN: ¿Realmente desea eliminar su cuenta? Sus partidos programados serán cancelados."
      )
    ) {
      // TODO: Llamada AJAX para eliminar cuenta
      showToast(
        "Su cuenta ha sido marcada para eliminación. Recibirá un correo de confirmación.",
        "success"
      );
      window.location.href = "<?= PAGE_LANDING_PHP ?>";
    }
  }
}
