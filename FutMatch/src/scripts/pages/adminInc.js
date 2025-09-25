function showSection(section) {
  let content = document.getElementById("section-content");
  switch (section) {
    case "torneos":
      content.innerHTML = `
        <h3>Gestión de Torneos</h3>
        <button class="btn btn-success m-2">Crear Torneo</button>
        <button class="btn btn-info m-2">Modificar Torneo</button>
        <button class="btn btn-danger m-2">Cancelar Torneo</button>
        <button class="btn btn-secondary m-2">Historial de Torneos</button>
      `;
      break;

    case "canchas":
      content.innerHTML = `
        <h3>Gestión de Canchas</h3>
        <button class="btn btn-success m-2">Agregar Cancha</button>
        <button class="btn btn-info m-2">Modificar Cancha</button>
        <button class="btn btn-danger m-2">Eliminar Cancha</button>
        <button class="btn btn-secondary m-2">Listar Canchas</button>
      `;
      break;

    case "reservas":
      content.innerHTML = `
        <h3>Gestión de Reservas</h3>
        <button class="btn btn-success m-2">Crear Reserva</button>
        <button class="btn btn-info m-2">Modificar Reserva</button>
        <button class="btn btn-danger m-2">Eliminar Reserva</button>
        <button class="btn btn-secondary m-2">Ver Historial</button>
      `;
      break;

    case "usuarios":
      content.innerHTML = `
        <h3>Gestión de Usuarios</h3>
        <button class="btn btn-info m-2">Ver Cuenta</button>
        <button class="btn btn-warning m-2">Modificar Perfil</button>
        <button class="btn btn-danger m-2">Eliminar Cuenta</button>
      `;
      break;

    default:
      content.innerHTML = "<p>Selecciona una opción para comenzar.</p>";
  }
}
