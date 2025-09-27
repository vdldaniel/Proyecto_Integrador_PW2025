// Descripción de secciones 

function showTooltip(section) {
  let content = document.getElementById("section-content");
  switch (section) {
    case "torneos":

      content.innerHTML = `
        <p>Podrás Crear, Modificar , Cancelar y Ver Historial  de Torneos.</p>

      `;
      break;

    case "canchas":
      content.innerHTML = `
        <p>Podrás Agregar, Eliminar, Modificar y Listar Canchas</p>
      `;
      break;

    case "reservas":
      content.innerHTML = `
        <p>Podrás Crear , Modificar, Eliminar  y Ver Historial de Reservas.</p>
      `;
      break;

    case "usuarios":
      content.innerHTML = `
        <p>Podrás Ver, Modificar y Eliminar una Cuenta de Usuario.</p>
      `;
      break;

    default:
      content.innerHTML = "<p>Selecciona una opción .</p>";
  }
}

function hideTooltip() {
  let content = document.getElementById("section-content");


  content.innerHTML = "<p>Selecciona una opción.</p>";

}

// Selección de opción 
function showSection(section) {
  let content = document.getElementById("section-content");
  switch (section) {
    case "torneos":
      window.location.href = "mis-torneos.html";
      break;

    case "canchas":
      window.location.href = "canchas-listado.html";
      break;

    case "reservas":
      window.location.href = "cancha-reservar.html";
      break;

    case "usuarios":
      window.location.href = "cuenta-jugador.html";
      break;

    default:
      content.innerHTML = "<p>Selecciona una opción.</p>";
  }
}

// Formulario para crear Torneo
function enviarFormularioTorneo() {

  const nombre = document.getElementById("nombre").value;
  const ubicacion = document.getElementById("ubicacion").value;
  const tamano = document.getElementById("tamano").value;
  const cierre = document.getElementById("cierre").value;
  const inicio = document.getElementById("inicio").value;
  const fin = document.getElementById("fin").value;
  const equipos = document.getElementById("equipos").value;

  // Validación simple
  if (!nombre || !ubicacion || !tamano || !cierre || !inicio || !fin || !equipos) {
    alert("Por favor, completa todos los campos requeridos.");
    return;
  } else {
    // Resetear formulario
    document.getElementById("torneoForm").reset();

  
    // Redirigir a pagina principal
      window.location.href = "mis-torneos.html";
    
  }
}

// Cancelar la creacion del formulario nuevo torneo
function cancelarFormularioTorneo() {
  if (confirm("¿Seguro que quieres cancelar la creación del torneo?")) {
    window.location.href = "mis-torneos.html"; // vuelve a la principal
  }
}

// Cancelar un torneo Activo o Cancelado
function cancelarTorneo(){
  if (confirm("¿Seguro que quieres cancelar el torneo?")) {
    window.location.href = "mis-torneos.html"; // vuelve a la principal
  }
}

// Botón visible de cancelar segun estado de Torneo
document.addEventListener("DOMContentLoaded", () => {
  const filas = document.querySelectorAll("table tbody tr");

  filas.forEach(fila => {
    const estado = fila.querySelector(".estado").textContent.trim();
    const botonCancelar = fila.querySelector(".btn-danger");

    if (estado === "Cancelado" && botonCancelar) {
      botonCancelar.style.display = "none"; // lo oculta
    }
  });
});