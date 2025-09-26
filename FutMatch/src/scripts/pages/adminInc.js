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
