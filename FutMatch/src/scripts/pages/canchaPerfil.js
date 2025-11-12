/**
 * JavaScript para la página de perfil de cancha
 * Maneja la edición del perfil y la funcionalidad del modal
 */

let modoEdicion = false;

// Esperar a que el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", function () {
  // Función para alternar modo edición
  const btnEditarPerfil = document.getElementById("btnEditarPerfil");
  if (btnEditarPerfil) {
    btnEditarPerfil.addEventListener("click", function () {
      modoEdicion = !modoEdicion;
      toggleModoEdicion();
    });
  }

  // Event listener para el botón "Ir a agenda"
  const btnIrAgenda = document.querySelector(".btn-success");
  if (btnIrAgenda) {
    btnIrAgenda.addEventListener("click", function () {
      // Obtener la URL desde el atributo data o desde PHP
      const agendaUrl =
        btnIrAgenda.getAttribute("data-url") || "../agenda_AdminCancha.php";
      window.location.href = agendaUrl;
    });
  }

  // Event listener para el botón "Guardar cambios" del modal
  const btnGuardarCambios = document.querySelector(
    "#modalEditarCancha .btn-primary"
  );
  if (btnGuardarCambios) {
    btnGuardarCambios.addEventListener("click", guardarCambiosCancha);
  }

  // Actualizar dropdown de cancha cuando se cambia
  document.querySelectorAll(".dropdown-menu a").forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const texto = this.textContent.trim();
      document.querySelector(".dropdown-toggle").innerHTML =
        '<i class="bi bi-building"></i> ' + texto;

      // Actualizar clase active
      document
        .querySelectorAll(".dropdown-menu a")
        .forEach((l) => l.classList.remove("active"));
      this.classList.add("active");

      // Aquí se podría cargar la información de la cancha seleccionada
    });
  });
});

function toggleModoEdicion() {
  const elementos = [
    { id: "nombreCancha", tipo: "text" },
    { id: "descripcionCancha", tipo: "textarea" },
    { id: "direccionCancha", tipo: "text" },
    {
      id: "tipoCancha",
      tipo: "select",
      opciones: ["Fútbol 5", "Fútbol 7", "Fútbol 11", "Fútbol Sala"],
    },
    {
      id: "superficieCancha",
      tipo: "select",
      opciones: ["Césped natural", "Césped sintético", "Parquet", "Cemento"],
    },
    { id: "capacidadCancha", tipo: "text" },
  ];

  const btnEditar = document.getElementById("btnEditarPerfil");

  if (modoEdicion) {
    // Activar modo edición
    btnEditar.innerHTML = '<i class="bi bi-check-circle"></i> Guardar cambios';
    btnEditar.className = "btn btn-success";

    elementos.forEach((elem) => {
      const elemento = document.getElementById(elem.id);
      const valorActual = elemento.textContent || elemento.innerText;

      if (elem.tipo === "textarea") {
        elemento.innerHTML = `<textarea class="form-control form-control-sm" id="${elem.id}_input">${valorActual}</textarea>`;
      } else if (elem.tipo === "select") {
        let options = "";
        elem.opciones.forEach((opcion) => {
          const selected = opcion === valorActual ? "selected" : "";
          options += `<option value="${opcion}" ${selected}>${opcion}</option>`;
        });
        elemento.innerHTML = `<select class="form-select form-select-sm" id="${elem.id}_input">${options}</select>`;
      } else {
        elemento.innerHTML = `<input type="text" class="form-control form-control-sm" id="${elem.id}_input" value="${valorActual}">`;
      }
    });
  } else {
    // Guardar cambios y desactivar modo edición
    btnEditar.innerHTML = '<i class="bi bi-pencil-square"></i> Editar perfil';
    btnEditar.className = "btn btn-primary";

    elementos.forEach((elem) => {
      const input = document.getElementById(elem.id + "_input");
      const elemento = document.getElementById(elem.id);

      if (input) {
        elemento.textContent = input.value;
      }
    });

    // Aquí se podría agregar una llamada AJAX para guardar en la base de datos
    alert("Cambios guardados correctamente");
  }
}

// Función para guardar cambios desde el modal de configuración
function guardarCambiosCancha() {
  // Actualizar los valores en el perfil con los del modal
  document.getElementById("nombreCancha").textContent =
    document.getElementById("editNombreCancha").value;
  document.getElementById("descripcionCancha").textContent =
    document.getElementById("editDescripcion").value;
  document.getElementById("direccionCancha").textContent =
    document.getElementById("editDireccion").value;

  const tipoSelect = document.getElementById("editTipoCancha");
  document.getElementById("tipoCancha").textContent =
    tipoSelect.options[tipoSelect.selectedIndex].text;

  const superficieSelect = document.getElementById("editSuperficie");
  document.getElementById("superficieCancha").textContent =
    superficieSelect.options[superficieSelect.selectedIndex].text;

  document.getElementById("capacidadCancha").textContent =
    document.getElementById("editCapacidad").value + " jugadores";

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalEditarCancha")
  );
  modal.hide();

  // Mostrar confirmación
  alert("Configuración de cancha actualizada correctamente");
}
