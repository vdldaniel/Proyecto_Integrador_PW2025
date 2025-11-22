document.addEventListener("DOMContentLoaded", function () {
  inicializarNotificaciones();
});

function inicializarNotificaciones() {
  // Lógica para cargar y mostrar las notificaciones del jugador
  inicializarReservas();
  inicializarSolicitantes();
  inicializarSolicitudesEquipos();
  inicializarTorneos();
}

async function inicializarReservas() {}

async function inicializarSolicitantes() {}

async function inicializarSolicitudesEquipos() {
  try {
    const response = await fetch(
      GET_EQUIPOS_JUGADOR + `?filtrar_solicitudes=true`
    );
    if (!response.ok) {
      console.error("Error al obtener solicitudes de equipos");
      return;
    }
    const equipos = await response.json();
    const solicitudesContainer = document.getElementById(
      "solicitudesEquiposContainer"
    );
    solicitudesContainer.innerHTML = "";
    if (equipos.length === 0) {
      solicitudesContainer.innerHTML =
        '<div class="alert alert-info" role="alert">No tienes solicitudes de equipos pendientes.</div>';
      return;
    }

    equipos.forEach((equipo) => {
      const item = document.createElement("div");
      item.classList.add("list-group-item");
      item.innerHTML = `
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-person-plus-fill text-success me-2"></i>
            <h6 class="mb-0">Invitación a Equipo</h6>
          </div>
          <p class="mb-1"><strong>${equipo.nombre_equipo}</strong> te invitó a unirte</p>
          <small class="text-muted">Líder: ${equipo.nombre_lider} ${equipo.apellido_lider} • ${equipo.cantidad_integrantes} integrantes • ${equipo.cantidad_torneos} torneos participados</small>
        </div>
        <div class="d-flex flex-column gap-1">
            <button class="btn btn-sm btn-success btn-aceptar-solicitud" data-id-equipo="${equipo.id_equipo}">
              <i class="bi bi-check-lg me-1"></i>Aceptar
            </button>
            <button class="btn btn-sm btn-danger btn-rechazar-solicitud" data-id-equipo="${equipo.id_equipo}">
              <i class="bi bi-x-lg me-1"></i>Rechazar
            </button>
        </div>
      </div>
    `;
      solicitudesContainer.appendChild(item);
    });
  } catch (error) {
    showToast("Error al cargar solicitudes de equipos", "error");
  }

  // Agregar eventos a los botones de aceptar solicitudes
  // Boton Aceptar
  document.querySelectorAll(".btn-aceptar-solicitud").forEach((button) => {
    button.addEventListener("click", async function () {
      const idEquipo = this.dataset.idEquipo;
      const formData = new FormData();
      formData.append("id_equipo", idEquipo);
      formData.append("accion", "aceptar_solicitud");

      try {
        const response = await fetch(UPDATE_JUGADORES_EQUIPOS, {
          method: "POST",
          body: formData, // sin headers
        });

        const raw = await response.text();

        if (!response.ok) throw new Error();

        showToast("Solicitud aceptada", "success");
        inicializarSolicitudesEquipos();
      } catch {
        showToast("Error al aceptar la solicitud", "error");
      }
    });
  });

  document.querySelectorAll(".btn-rechazar-solicitud").forEach((button) => {
    button.addEventListener("click", async function () {
      const idEquipo = this.dataset.idEquipo;
      const formData = new FormData();
      formData.append("id_equipo", idEquipo);
      formData.append("accion", "rechazar_solicitud");

      try {
        const response = await fetch(UPDATE_JUGADORES_EQUIPOS, {
          method: "POST",
          body: formData, // sin headers
        });

        const raw = await response.text();

        if (!response.ok) throw new Error();

        showToast("Solicitud rechazada", "success");
        inicializarSolicitudesEquipos();
      } catch {
        showToast("Error al rechazar la solicitud", "error");
      }
    });
  });
}

async function inicializarTorneos() {}
