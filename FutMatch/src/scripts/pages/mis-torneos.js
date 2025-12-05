/**
 * Funcionalidad de la página Mis Torneos - Admin Cancha
 * Manejo de modales, validaciones y acciones de torneos
 */

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar búsqueda de torneos
  inicializarBusqueda();

  // Mostrar/ocultar campo de fecha de cierre al marcar checkbox
  const abrirInscripciones = document.getElementById("abrirInscripciones");
  if (abrirInscripciones) {
    abrirInscripciones.addEventListener("change", function () {
      const fechaCierreContainer = document.getElementById(
        "fechaCierreContainer"
      );
      const fechaCierreInput = document.getElementById(
        "fechaCierreInscripciones"
      );

      if (this.checked) {
        fechaCierreContainer.classList.remove("d-none");
        fechaCierreInput.required = true;
      } else {
        fechaCierreContainer.classList.add("d-none");
        fechaCierreInput.required = false;
      }
    });
  }

  // Capturar ID de torneo al abrir modal de inscripciones
  document
    .querySelectorAll('[data-bs-target="#modalAbrirInscripciones"]')
    .forEach((btn) => {
      btn.addEventListener("click", function () {
        const torneoId = this.dataset.torneoId;
        const input = document.getElementById("abrirTorneoId");
        if (input && torneoId) {
          input.value = torneoId;
        }
      });
    });

  // Capturar ID de torneo al abrir modal de solicitudes
  document
    .querySelectorAll('[data-bs-target="#modalSolicitudesTorneo"]')
    .forEach((btn) => {
      btn.addEventListener("click", function () {
        const torneoId = this.dataset.torneoId;
        const input = document.getElementById("solicitudesTorneoId");
        if (input && torneoId) {
          input.value = torneoId;
        }
      });
    });

  // Capturar ID de torneo al abrir modal de cancelación
  document
    .querySelectorAll('[data-bs-target="#modalCancelarTorneo"]')
    .forEach((btn) => {
      btn.addEventListener("click", function () {
        const torneoId = this.dataset.torneoId;
        const input = document.getElementById("cancelarTorneoId");
        if (input && torneoId) {
          input.value = torneoId;
        }
      });
    });

  // Validación de fechas en formulario crear torneo
  const fechaInicio = document.getElementById("fechaInicio");
  const fechaFin = document.getElementById("fechaFin");

  if (fechaInicio) {
    fechaInicio.addEventListener("change", function () {
      if (fechaFin) {
        fechaFin.min = this.value;
      }
    });
  }

  if (fechaFin) {
    fechaFin.addEventListener("change", function () {
      const fechaInicioValue = fechaInicio ? fechaInicio.value : "";
      if (fechaInicioValue && this.value < fechaInicioValue) {
        showToast(
          "La fecha de fin no puede ser anterior a la fecha de inicio",
          "error"
        );
        this.value = "";
      }
    });
  }

  // Lógica para los botones principales
  const btnCrearTorneo = document.getElementById("btnCrearTorneo");
  if (btnCrearTorneo) {
    btnCrearTorneo.addEventListener("click", function () {
      // Aquí iría la lógica para crear el torneo
      showToast("Torneo creado exitosamente", "success");
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalCrearTorneo")
      );
      if (modal) {
        modal.hide();
      }
    });
  }

  const btnConfirmarAbrirInscripciones = document.getElementById(
    "btnConfirmarAbrirInscripciones"
  );
  if (btnConfirmarAbrirInscripciones) {
    btnConfirmarAbrirInscripciones.addEventListener("click", function () {
      // Aquí iría la lógica para abrir inscripciones
      alert("Inscripciones abiertas exitosamente");
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalAbrirInscripciones")
      );
      if (modal) {
        modal.hide();
      }
    });
  }

  const btnConfirmarCancelar = document.getElementById("btnConfirmarCancelar");
  if (btnConfirmarCancelar) {
    btnConfirmarCancelar.addEventListener("click", function () {
      // Aquí iría la lógica para cancelar el torneo
      alert("Torneo cancelado");
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("modalCancelarTorneo")
      );
      if (modal) {
        modal.hide();
      }
    });
  }
});

/**
 * Inicializa la funcionalidad de búsqueda de torneos
 */
function inicializarBusqueda() {
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      filtrarTorneos(this.value);
    });
  }
}

/**
 * Filtra los torneos basado en el término de búsqueda
 */
function filtrarTorneos(termino) {
  const cards = document.querySelectorAll("#torneosList .col-12");

  cards.forEach((card) => {
    const texto = card.textContent.toLowerCase();
    if (texto.includes(termino.toLowerCase())) {
      card.style.display = "block";
    } else {
      card.style.display = "none";
    }
  });
}
