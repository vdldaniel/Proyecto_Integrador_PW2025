const urlPerfil = `${GET_INFO_PERFIL}?id=${CURRENT_USER_ID}&tipo=${TIPO_PERFIL}`;
const urlPartidos = `${GET_PARTIDOS_JUGADOR}?id=${CURRENT_USER_ID}`;

document.addEventListener("DOMContentLoaded", function () {
  getInfoPerfil();
  getPartidosJugador();
});

async function getInfoPerfil() {
  try {
    const response = await fetch(urlPerfil, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const perfil = await response.json();
    console.log("Información del perfil:", perfil);
    cargarPerfil(perfil);
  } catch (error) {
    console.error("Error al obtener la información del perfil:", error);
  }
}

function cargarPerfil(perfil) {
  document.getElementById("nombreJugador").textContent =
    perfil.nombre + " " + perfil.apellido || "";

  document.getElementById("usernameJugador").textContent =
    "@" + perfil.username || "";

  document.getElementById("estadoJugador").textContent =
    perfil.estado_usuario || "";

  const estrellas = Math.round(perfil.reputacion * 2) / 2;
  let calificacionHTML = "";

  for (let i = 1; i <= 5; i++) {
    if (i <= estrellas) {
      calificacionHTML += "★"; // llena
    } else if (i - estrellas === 0.5) {
      calificacionHTML += "⯪"; // media
    } else {
      calificacionHTML += "☆"; // vacía
    }
  }

  document.getElementById(
    "calificacionJugador"
  ).innerHTML = `${calificacionHTML}<small class ="ms-1">(${perfil.reputacion.toFixed(
    1
  )})</small>`;
}

async function getPartidosJugador() {
  try {
    const response = await fetch(urlPartidos, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const partidos = await response.json();
    console.log("Partidos del jugador:", partidos);
    cargarPartidosJugadorDOM(partidos);
  } catch (error) {
    console.error("Error al obtener los partidos del jugador:", error);
  }
}

function cargarPartidosJugadorDOM(partidos) {
  const lista = document.getElementById("listaPartidosRecientes");
  if (!lista) return console.error("No se encontró #listaPartidosRecientes");
  lista.innerHTML = "";

  // variables del partido
  /* p.fecha_partido
    p.nombre_cancha
    p.goles_mi_equipo
    p.goles_equipo_rival

  */

  partidos.forEach((p) => {
    let equipoGanador = 0; // 1 para ganado, 0 para empatado, -1 para perdido
    let resultado = "";

    if (p.goles_mi_equipo > p.goles_equipo_rival) {
      equipoGanador = 1;
      resultado = "Victoria";
    } else if (p.goles_mi_equipo < p.goles_equipo_rival) {
      equipoGanador = -1;
      resultado = "Derrota";
    } else {
      equipoGanador = 0;
      resultado = "Empate";
    }
    const container = document.createElement("div");
    container.className = "border-bottom p-4";

    const row = document.createElement("div");
    row.className = "d-flex align-items-center mb-3";

    const badge = document.createElement("div");

    if (equipoGanador === 1) {
      badge.className = "badge text-bg-success me-3 p-2";
      badge.innerHTML = '<i class="bi bi-trophy-fill"></i>';
    } else if (equipoGanador === 0) {
      badge.className = "badge text-bg-secondary me-3 p-2";
      badge.innerHTML = '<i class="bi bi-hand-thumbs-up-fill"></i>';
    } else {
      badge.className = "badge text-bg-danger me-3 p-2";
      badge.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
    }

    const main = document.createElement("div");
    main.className = "flex-grow-1";

    const h5 = document.createElement("h5");
    h5.className = "mb-1";
    h5.textContent =
      resultado + " " + p.goles_mi_equipo + " - " + p.goles_equipo_rival ||
      "Resultado";

    const pMeta = document.createElement("p");
    pMeta.className = "text-muted mb-0";
    pMeta.textContent = `${p.nombre_cancha || "—"} • ${p.fecha_partido || "—"}`;

    main.appendChild(h5);
    main.appendChild(pMeta);
    row.appendChild(badge);
    row.appendChild(main);

    const desc = document.createElement("p");
    desc.className = "mb-3";
    desc.textContent = p.descripcion || "Excelente partido...";

    const footer = document.createElement("div");
    footer.className = "d-flex justify-content-between align-items-center";
    footer.innerHTML = `<small class="text-muted">Equipo: ${
      p.equipo || "—"
    }</small>
                        <div><span class="text-warning">★★★★★</span>
                        <small class="text-muted ms-1">Calificación recibida</small></div>`;

    container.appendChild(row);
    container.appendChild(desc);
    container.appendChild(footer);

    lista.appendChild(container);
  });
}
