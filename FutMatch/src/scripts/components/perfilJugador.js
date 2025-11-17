/**
 * INFO:
 * apellido: "Martínez"
banner: null
email: "ana.martinez@email.com"
estado_usuario: "Activo"
fecha_nacimiento: "1996-09-12" --------------------- "Edad"
fecha_registro: "2025-11-13 00:16:20" -------------- "Miembro desde"
foto_perfil: null
id_jugador: 4
id_sexo: 1
id_usuario: 4
nombre: "Ana"
reputacion: 4.5
sexo: "femenino"
telefono: "+541123456792" --------------------------- "Telefono"
username: "anam"

* PARTIDOS
abierto: 1
cant_participantes_equipo_a: 4
cant_participantes_equipo_b: 1
descripcion_equipo_A: null
descripcion_equipo_B: null
dia_semana: "Lunes"
direccion_cancha: "San Martín 567, La Plata"
equipo_asignado: "Equipo B"
estado_solicitud: "Pendiente"
etapa_torneo: null
fecha_partido: "17/11/2025"
foto_equipo_A: null
foto_equipo_B: null
goles_equipo_A: 3
goles_equipo_B: 0
goles_equipo_rival: 3
goles_mi_equipo: 0
hora_fin: "20:00"
hora_partido: "19:00"
id_anfitrion: 3
id_cancha: 2
id_equipo_A: null
id_equipo_B: null
id_estado: 1
id_fase: null
id_jugador: 4
id_partido: 2
id_reserva: 3
id_rol: 3
id_tipo_partido: 1
id_tipo_reserva: 1
id_torneo: null
latitud_cancha: "-34.92131200"
longitud_cancha: "-57.95456700"
max_participantes: 10
mi_username: "anam"
min_participantes: 8
nombre_cancha: "Cancha Norte"
nombre_equipo_A: null
nombre_equipo_B: null
nombre_torneo: null
orden_en_fase: null
rol_usuario: "Solicitante"
tipo_partido: "Fútbol 5"

RESEÑAS
comentario: "Excelente partido! Me encanta jugar con Ana."
id_calificacion: 1
id_jugador_evaluado: 4
id_jugador_evaluador: 3
id_partido: 4
puntuacion: 4
reportado: 0

EQUIPOS
abierto: 1
apellido_lider: "Martínez"
cantidad_integrantes: 4
clave: null
descripcion: "Nos juntamos a jugar futbol los domingos en CABA"
foto_equipo: "uploads/equipos/equipo_1763336255_691a603f90291.jpg"
id_equipo: 4
id_jugador: 4
id_lider: 4
nombre_equipo: "Domingueross"
nombre_lider: "Ana"
partidos_jugados: 0
torneos_participados: 0


ESTADISTICAS
asistencias: 0
faltas: 0
goles: 1
id_estadistica: 1
id_jugador: 4
id_participante: 3
id_partido: 4

 */

// para las funciones async/await
const urlPerfil = `${GET_INFO_PERFIL}?id=${CURRENT_USER_ID}&tipo=${TIPO_PERFIL}`;
const urlPartidos = `${GET_PARTIDOS_JUGADOR}?id=${CURRENT_USER_ID}`;
const urlReseñas = `${GET_RESEÑAS_JUGADORES}?id=${CURRENT_USER_ID}`;
const urlEquipos = `${GET_EQUIPOS_JUGADOR}?id=${CURRENT_USER_ID}`;
const urlEstadisticas = `${GET_ESTADISTICAS_JUGADOR}?id=${CURRENT_USER_ID}`;

document.addEventListener("DOMContentLoaded", function () {
  inicializarPerfil();
});

async function inicializarPerfil() {
  try {
    const [info, partidos, reseñas, equipos, estadisticas] = await Promise.all([
      getInfoPerfil(),
      getPartidosJugador(),
      getReseñasJugador(),
      getEquiposJugador(),
      getEstadisticasJugador(),
    ]);

    cargarPerfil(info, partidos, reseñas, equipos, estadisticas);
  } catch (error) {
    console.error("Error al inicializar perfil:", error);
  }
}

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
    return perfil;
  } catch (error) {
    console.error("Error al obtener la información del perfil:", error);
    return null;
  }
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
      const errorData = await response.json();
      console.error("Error del servidor:", errorData);
      throw new Error("Error en la solicitud: " + response.status);
    }

    const partidos = await response.json();
    console.log("Partidos del jugador:", partidos);
    return partidos;
  } catch (error) {
    console.error("Error al obtener los partidos del jugador:", error);
    return [];
  }
}

async function getReseñasJugador() {
  try {
    const response = await fetch(urlReseñas, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const reseñas = await response.json();
    console.log("Reseñas del jugador:", reseñas);
    return reseñas;
  } catch (error) {
    console.error("Error al obtener las reseñas del jugador:", error);
    return [];
  }
}

async function getEquiposJugador() {
  try {
    const response = await fetch(urlEquipos, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const equipos = await response.json();
    console.log("Equipos del jugador:", equipos);
    return equipos;
  } catch (error) {
    console.error("Error al obtener los equipos del jugador:", error);
    return [];
  }
}

async function getEstadisticasJugador() {
  try {
    const response = await fetch(urlEstadisticas, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const estadisticas = await response.json();
    console.log("Estadisticas del jugador:", estadisticas);
    return estadisticas;
  } catch (error) {
    console.error("Error al obtener las estadisticas del jugador:", error);
    return [];
  }
}

function cargarPerfil(info, partidos, reseñas, equipos, estadisticas) {
  if (info) cargarInfo(info);
  if (partidos) cargarPartidosJugadorDOM(partidos, reseñas);
  if (equipos) cargarEquiposJugadorDOM(equipos);
  if (estadisticas)
    cargarEstadisticasJugadorDOM(info, partidos, estadisticas, reseñas);
}

function cargarInfo(info) {
  // PERFIL:
  document.getElementById("nombreJugador").textContent =
    info.nombre + " " + info.apellido || "";

  document.getElementById("usernameJugador").textContent =
    "@" + info.username || "";

  document.getElementById("estadoJugador").textContent =
    info.estado_usuario || "";

  // CALIFICACIÓN (reutilizable)
  const calificacionHTML = generarEstrellasHTML(info.reputacion);
  document.getElementById(
    "calificacionJugador"
  ).innerHTML = `${calificacionHTML}<small class ="ms-1">(${info.reputacion.toFixed(
    1
  )})</small>`;

  // INFORMACIÓN PERSONAL:
  document.getElementById("emailJugador").textContent = info.email || "—";
  document.getElementById("telefonoJugador").textContent = info.telefono || "—";

  // Calcular edad desde fecha_nacimiento: "1996-09-12"
  if (info.fecha_nacimiento) {
    const edad = calcularEdad(info.fecha_nacimiento);
    document.getElementById("edadJugador").textContent = `${edad} años`;
  }

  // Calcular "Miembro desde" con fecha_registro: "2025-11-13 00:16:20"
  const miembroDesdeElement = document.getElementById("miembroDesdeJugador");
  if (miembroDesdeElement && info.fecha_registro) {
    const fecha = new Date(info.fecha_registro);
    const opciones = { year: "numeric", month: "long" };
    const fechaFormateada = fecha.toLocaleDateString("es-ES", opciones);
    miembroDesdeElement.textContent = fechaFormateada;
  }
}

// Función auxiliar para generar estrellas (reutilizable)
function generarEstrellasHTML(reputacion) {
  const estrellas = Math.round(reputacion * 2) / 2;
  let html = "";

  for (let i = 1; i <= 5; i++) {
    if (i <= estrellas) {
      html += "★"; // llena
    } else if (i - estrellas === 0.5) {
      html += "⯪"; // media
    } else {
      html += "☆"; // vacía
    }
  }
  return html;
}

// Función auxiliar para calcular edad
function calcularEdad(fechaNacimiento) {
  const hoy = new Date();
  const nacimiento = new Date(fechaNacimiento);
  let edad = hoy.getFullYear() - nacimiento.getFullYear();
  const mes = hoy.getMonth() - nacimiento.getMonth();

  if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
    edad--;
  }

  return edad;
}

function cargarPartidosJugadorDOM(partidos, reseñas) {
  const lista = document.getElementById("listaPartidosRecientes");
  if (!lista) return console.error("No se encontró #listaPartidosRecientes");
  lista.innerHTML = "";

  if (!partidos || partidos.length === 0) {
    lista.innerHTML = `
      <div class="p-4 text-center text-muted">
        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
        <p>No hay partidos registrados aún</p>
      </div>
    `;
    return;
  }

  partidos.forEach((p) => {
    let equipoGanador = 0; // 1 para ganado, 0 para empatado, -1 para perdido
    let resultado = "";

    // Determinar goles del equipo del jugador
    if (p.equipo_asignado === "Equipo A") {
      p.goles_mi_equipo = p.goles_equipo_A;
      p.goles_equipo_rival = p.goles_equipo_B;
    } else {
      p.goles_mi_equipo = p.goles_equipo_B;
      p.goles_equipo_rival = p.goles_equipo_A;
    }

    // Determinar resultado
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

    // Buscar reseña del partido
    let calificacion = null;
    if (reseñas && reseñas.length > 0) {
      calificacion = reseñas.find(
        (r) =>
          r.id_partido === p.id_partido &&
          r.id_jugador_evaluado === parseInt(CURRENT_USER_ID)
      );
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
    h5.textContent = `${resultado} ${p.goles_mi_equipo} - ${p.goles_equipo_rival}`;

    const pMeta = document.createElement("p");
    pMeta.className = "text-muted mb-0";
    pMeta.textContent = `${p.nombre_cancha || "—"} • ${
      p.fecha_partido || "—"
    } • ${p.hora_partido || "—"}`;

    main.appendChild(h5);
    main.appendChild(pMeta);

    const aside = document.createElement("div");
    aside.className = "text-end";
    aside.innerHTML = `
      <small class="text-muted d-block">${p.tipo_partido || "—"}</small>
      <small class="text-muted">${p.dia_semana || "—"}</small>
    `;

    row.appendChild(badge);
    row.appendChild(main);
    row.appendChild(aside);

    const desc = document.createElement("p");
    desc.className = "mb-3";
    desc.textContent =
      calificacion?.comentario || "Sin comentarios sobre este partido.";

    const footer = document.createElement("div");
    footer.className = "d-flex justify-content-between align-items-center";

    // Generar estrellas de calificación
    let estrellasHTML = "";
    if (calificacion) {
      const puntuacion = parseInt(calificacion.puntuacion);
      for (let i = 1; i <= 5; i++) {
        if (i <= puntuacion) {
          estrellasHTML += "★";
        } else {
          estrellasHTML += "☆";
        }
      }
    } else {
      estrellasHTML = "Sin calificar";
    }

    footer.innerHTML = `
      <small class="text-muted">${p.equipo_asignado || "—"}</small>
      <div>
        <span class="text-warning">${estrellasHTML}</span>
        <small class="text-muted ms-1">${
          calificacion ? "Calificación recibida" : ""
        }</small>
      </div>
    `;

    container.appendChild(row);
    container.appendChild(desc);
    container.appendChild(footer);

    lista.appendChild(container);
  });
}

function cargarEquiposJugadorDOM(equipos) {
  const lista = document.getElementById("listaEquiposJugador");
  if (!lista) return console.error("No se encontró #listaEquiposJugador");
  lista.innerHTML = "";

  if (!equipos || equipos.length === 0) {
    lista.innerHTML = `
      <div class="col-12">
        <div class="p-4 text-center text-muted">
          <i class="bi bi-people-fill fs-1 d-block mb-2"></i>
          <p>No hay equipos registrados aún</p>
        </div>
      </div>
    `;
    return;
  }

  equipos.forEach((equipo) => {
    const col = document.createElement("div");
    col.className = "col-md-6 mb-3";

    const card = document.createElement("div");
    card.className = "card h-100";

    // Construir la URL de la foto
    let fotoURL = `${BASE_URL}public/img/foto_perfil_equipo_default.png`; // Foto por defecto
    if (equipo.foto_equipo) {
      // La foto puede venir como "uploads/equipos/..." o "public/uploads/equipos/..."
      if (equipo.foto_equipo.startsWith("public/")) {
        fotoURL = `${BASE_URL}${equipo.foto_equipo}`;
      } else if (equipo.foto_equipo.startsWith("uploads/")) {
        fotoURL = `${BASE_URL}public/${equipo.foto_equipo}`;
      } else {
        fotoURL = `${BASE_URL}${equipo.foto_equipo}`;
      }
    }

    card.innerHTML = `
      <div class="card-body">
        <!-- Presentación: Foto + Nombre/Descripción -->
        <div class="d-flex align-items-start mb-3">
          <!-- Foto cuadrada (1/3 en pantallas grandes) -->
          <div class="flex-shrink-0 me-3" style="width: 80px; height: 80px;">
            <img src="${fotoURL}" 
                 class="rounded" 
                 alt="${equipo.nombre_equipo || "Equipo"}"
                 style="width: 100%; height: 100%; object-fit: cover;"
                 onerror="this.src='${BASE_URL}public/img/foto_perfil_equipo_default.png'">
          </div>
          
          <!-- Nombre y descripción (2/3 en pantallas grandes) -->
          <div class="flex-grow-1">
            <h6 class="card-title mb-1">${
              equipo.nombre_equipo || "Sin nombre"
            }</h6>
            <small class="text-muted d-block">${
              equipo.descripcion || "Sin descripción"
            }</small>
          </div>
        </div>

        <!-- Información adicional -->
        <div class="mb-2">
          <small class="text-muted">
            <i class="bi bi-people-fill me-1"></i>${
              equipo.cantidad_integrantes || 0
            } integrantes
          </small>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="d-block text-muted">
              <i class="bi bi-trophy-fill me-1"></i>${
                equipo.torneos_participados || 0
              } torneos
            </small>
            <small class="text-muted">
              <i class="bi bi-calendar-event me-1"></i>${
                equipo.partidos_jugados || 0
              } partidos
            </small>
          </div>
          <span class="badge ${equipo.abierto ? "bg-success" : "bg-secondary"}">
            ${equipo.abierto ? "Activo" : "Cerrado"}
          </span>
        </div>
      </div>
    `;

    col.appendChild(card);
    lista.appendChild(col);
  });
}

function cargarEstadisticasJugadorDOM(info, partidos, estadisticas, reseñas) {
  // CALIFICACIÓN PROMEDIO (reutilizar función)
  const promedioCalif = info.reputacion || 0;
  const estrellasHTML = generarEstrellasHTML(promedioCalif);
  document.getElementById("estrellasCalificacion").innerHTML = estrellasHTML;
  document.getElementById("promedioCalificacion").textContent =
    promedioCalif.toFixed(1);

  // BASADO EN: count reseñas donde id_jugador_evaluado = id_user
  const totalReseñas = reseñas ? reseñas.length : 0;
  document.getElementById(
    "basadoEnReseñas"
  ).textContent = `Basado en ${totalReseñas} calificaciones`;

  // X PARTIDOS: count partidos
  const totalPartidos = partidos ? partidos.length : 0;
  document.getElementById("totalPartidos").textContent = totalPartidos;

  // X GOLES: sum goles from estadisticas
  let totalGoles = 0;
  if (estadisticas && estadisticas.length > 0) {
    totalGoles = estadisticas.reduce(
      (sum, est) => sum + (parseInt(est.goles) || 0),
      0
    );
  }
  document.getElementById("totalGoles").textContent = totalGoles;

  // X ASISTENCIAS: sum asistencias from estadisticas
  let totalAsistencias = 0;
  if (estadisticas && estadisticas.length > 0) {
    totalAsistencias = estadisticas.reduce(
      (sum, est) => sum + (parseInt(est.asistencias) || 0),
      0
    );
  }
  document.getElementById("totalAsistencias").textContent = totalAsistencias;

  // X% ASISTENCIA: asistencia a partidos
  // id_estado = 6 significa que el jugador NO asistió
  // id_estado = 3 significa que el jugador SÍ asistió
  let porcentajeAsistencia = 0;
  if (partidos && partidos.length > 0) {
    const partidosAsistidos = partidos.filter(
      (p) => parseInt(p.id_estado) === 3
    ).length;
    porcentajeAsistencia = Math.round(
      (partidosAsistidos / partidos.length) * 100
    );
  }
  document.getElementById(
    "porcentajeAsistencia"
  ).textContent = `${porcentajeAsistencia}%`;
}
