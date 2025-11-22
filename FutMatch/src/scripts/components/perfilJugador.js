// para las funciones async/await
const urlPerfil = `${GET_INFO_PERFIL}?id=${CURRENT_USER_ID}&tipo=${TIPO_PERFIL}`;
const urlPartidos = `${GET_PARTIDOS_JUGADOR}?id=${CURRENT_USER_ID}`;
const urlReseñas = `${GET_RESEÑAS_JUGADORES}?id=${CURRENT_USER_ID}`;
const urlEquipos = `${GET_EQUIPOS_JUGADOR}?id=${CURRENT_USER_ID}`;
const urlEstadisticas = `${GET_ESTADISTICAS_JUGADOR}?id=${CURRENT_USER_ID}`;

// Variables globales p/gestión de fotos
let fotoPerfilSeleccionada = null;
let fotoEliminada = false;

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
      configurarUploadFoto(
        "dropZoneFotoPerfil",
        "inputFotoPrincipal",
        "btnGuardarFotoPerfil",
        "fotoPerfil",
        "modalCambiarFotoPerfil",
        "inputFotoPrincipal",
        "btnEliminarFotoPerfil"
      ),
      configurarUploadFoto(
        "dropZoneBanner",
        "inputBanner",
        "btnGuardarBanner",
        "banner",
        "modalCambiarBanner",
        "inputBanner",
        "btnEliminarBanner"
      ),
    ]);

    cargarPerfil(info, partidos, reseñas, equipos, estadisticas);
  } catch (error) {
    console.error("Error al inicializar perfil:", error);
  }
}

function configurarUploadFoto(
  dropZoneID,
  inputFileID,
  btnGuardarID,
  tipoFoto,
  modalID,
  inputID,
  btnEliminarFotoID
) {
  const dropZone = document.getElementById(dropZoneID);
  const inputFile = document.getElementById(inputFileID);
  const btnGuardar = document.getElementById(btnGuardarID);
  const btnEliminarFoto = document.getElementById(btnEliminarFotoID);

  if (!dropZone || !inputFile || !btnGuardar) return;

  let fotoSeleccionada = null;

  // Click en la zona de drop
  dropZone.addEventListener("click", function () {
    inputFile.click();
  });

  // Drag and drop
  dropZone.addEventListener("dragover", function (e) {
    e.preventDefault();
    dropZone.classList.add("dragover");
  });

  dropZone.addEventListener("dragleave", function (e) {
    e.preventDefault();
    dropZone.classList.remove("dragover");
  });

  // Cuando se selecciona un archivo
  inputFile.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith("image/")) {
      mostrarPreviewFoto(file, dropZoneID, inputID);
      fotoSeleccionada = file;
      btnGuardar.disabled = false;
    }
  });

  dropZone.addEventListener("drop", function (e) {
    e.preventDefault();
    dropZone.classList.remove("dragover");

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith("image/")) {
      mostrarPreviewFoto(file, dropZoneID, inputID);
      fotoSeleccionada = file;
      // Actualizar el input file
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      inputFile.files = dataTransfer.files;
      btnGuardar.disabled = false;
    }
  });

  // Evento del botón guardar
  btnGuardar.addEventListener("click", function () {
    if (fotoSeleccionada) {
      guardarFoto(fotoSeleccionada, tipoFoto, modalID);
    }
  });

  // Evento del botón eliminar foto
  btnEliminarFoto.addEventListener("click", function () {
    guardarFoto(null, tipoFoto, modalID);
  });
}

async function guardarFoto(foto, tipoFoto, modalID) {
  try {
    const formData = new FormData();
    formData.append("foto", foto);
    formData.append("tipoFoto", tipoFoto);

    const response = await fetch(POST_FOTOS_JUGADOR, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      const errorData = await response.json();
      console.error("Error del servidor:", errorData);
      throw new Error(
        errorData.error || "Error en la solicitud: " + response.status
      );
    }

    const resultado = await response.json();
    console.log("Foto actualizada:", resultado);

    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById(modalID));
    modal.hide();
    location.reload();

    // Actualizar la imagen del avatar en la página
    const fotoImg = document.getElementById(tipoFoto); // "fotoPerfil" o "banner"
    const resultadoFoto =
      tipoFoto === "fotoPerfil" ? resultado.foto_perfil : resultado.banner;

    let tipo = tipoFoto === "fotoPerfil" ? "foto de perfil" : "foto de portada";

    // Mostrar mensaje de éxito
    if (tipoFoto === "fotoPerfil") {
      showToast("¡Has cambiado tu " + tipo + "!", "success");
    } else if (tipoFoto === "banner") {
      showToast("¡Has cambiado tu " + tipo + "!", "success");
    }
  } catch (error) {
    console.error("Error al cambiar tu " + tipo, error);
    showToast(
      "Error al cambiar tu " + tipo + ". Por favor, intenta nuevamente.",
      "error"
    );
  }
}

function mostrarPreviewFoto(file, dropZoneID, inputID) {
  const dropZone = document.getElementById(dropZoneID);
  const reader = new FileReader();

  reader.onload = function (e) {
    dropZone.innerHTML = `
      <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
      <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" id="btnEliminarFoto" style="z-index: 10; border-radius: 50%; width: 30px; height: 30px; padding: 0;">
        <i class="bi bi-x-lg"></i>
      </button>
      <div class="upload-icon" style="position: absolute; opacity: 0; transition: opacity 0.3s; background: rgba(0,0,0,0.5); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
        <i class="bi bi-pencil fs-3 text-white"></i>
      </div>
    `;

    // Evento para eliminar foto
    document
      .getElementById("btnEliminarFoto")
      .addEventListener("click", function (e) {
        e.stopPropagation(); // Evitar que se abra el selector de archivos
        eliminarFoto(dropZoneID, inputID);
      });

    // Mostrar el ícono de edición al hacer hover
    dropZone.addEventListener("mouseenter", function () {
      const icon = dropZone.querySelector(".upload-icon");
      if (icon) icon.style.opacity = "1";
    });

    dropZone.addEventListener("mouseleave", function () {
      const icon = dropZone.querySelector(".upload-icon");
      if (icon) icon.style.opacity = "0";
    });
  };

  reader.readAsDataURL(file);
}

function eliminarFoto(dropZoneID, inputID) {
  const dropZone = document.getElementById(dropZoneID);
  const inputFile = document.getElementById(inputID);

  // Resetear el input file
  if (inputFile) {
    inputFile.value = "";
  }

  // Resetear la variable global
  fotoPerfilSeleccionada = null;

  // Restaurar el estado inicial del dropzone
  dropZone.innerHTML = `
    <div class="upload-icon">
      <i class="bi bi-cloud-upload fs-1 text-muted"></i>
      <span class="upload-text">Click o arrastra una imagen</span>
    </div>
  `;
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

  // Cargar foto de perfil
  const avatarElement = document.getElementById("fotoPerfil");
  if (avatarElement) {
    avatarElement.src = info.foto_perfil
      ? BASE_URL + "public/" + info.foto_perfil
      : BASE_URL + "public/img/foto_perfil_jugador.png";
  }

  // Cargar banner
  const bannerElement = document.getElementById("banner");
  if (bannerElement && info.banner) {
    bannerElement.style.backgroundImage = `url('${BASE_URL}public/${info.banner}')`;
  }

  // CALIFICACIÓN (reutilizable)
  const reputacion = info.reputacion || 0;
  const calificacionHTML = generarEstrellasHTML(reputacion);

  if (reputacion === 0) {
    document.getElementById("calificacionJugador").innerHTML =
      '<small class="text-muted">Sin calificaciones</small>';
  } else {
    document.getElementById(
      "calificacionJugador"
    ).innerHTML = `${calificacionHTML}<small class ="ms-1">(${reputacion.toFixed(
      1
    )})</small>`;
  }

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
  const estrellas = Math.round((reputacion || 0) * 2) / 2;
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
  const promedioCalif = parseFloat(info.reputacion) || 0;

  if (promedioCalif === 0) {
    document.getElementById("estrellasCalificacion").innerHTML =
      '<small class="text-muted">Sin calificaciones</small>';
    document.getElementById("promedioCalificacion").textContent = "—";
    document.getElementById("basadoEnReseñas").textContent = "";
  } else {
    const estrellasHTML = generarEstrellasHTML(promedioCalif);
    document.getElementById("estrellasCalificacion").innerHTML = estrellasHTML;
    document.getElementById("promedioCalificacion").textContent =
      promedioCalif.toFixed(1);
    // BASADO EN: count reseñas donde id_jugador_evaluado = id_user
    const totalReseñas = reseñas ? reseñas.length : 0;
    document.getElementById(
      "basadoEnReseñas"
    ).textContent = `Basado en ${totalReseñas} calificaciones`;
  }

  // X PARTIDOS: count partidos
  const totalPartidos = partidos ? partidos.length : 0;
  if (totalPartidos === 0) {
    document.getElementById("totalPartidos").textContent = "—";
  } else {
    document.getElementById("totalPartidos").textContent = totalPartidos;
  }

  // X GOLES: sum goles from estadisticas
  let totalGoles = 0;
  if (!estadisticas || estadisticas.length === 0) {
    document.getElementById("totalGoles").textContent = "—";
  } else {
    totalGoles = estadisticas.reduce(
      (sum, est) => sum + (parseInt(est.goles) || 0),
      0
    );
    document.getElementById("totalGoles").textContent = totalGoles;
  }

  // X ASISTENCIAS: sum asistencias from estadisticas
  let totalAsistencias = 0;
  if (!estadisticas || estadisticas.length === 0) {
    document.getElementById("totalAsistencias").textContent = "—";
  } else {
    totalAsistencias = estadisticas.reduce(
      (sum, est) => sum + (parseInt(est.asistencias) || 0),
      0
    );
    document.getElementById("totalAsistencias").textContent = totalAsistencias;
  }
  // X% ASISTENCIA: asistencia a partidos
  // id_estado = 6 significa que el jugador NO asistió
  // id_estado = 3 significa que el jugador SÍ asistió
  let porcentajeAsistencia = 0;
  if (!partidos || partidos.length === 0) {
    document.getElementById("porcentajeAsistencia").textContent = "—";
    return;
  } else {
    const partidosAsistidos = partidos.filter(
      (p) => parseInt(p.id_estado) === 3
    ).length;
    porcentajeAsistencia = Math.round(
      (partidosAsistidos / partidos.length) * 100
    );
    document.getElementById(
      "porcentajeAsistencia"
    ).textContent = `${porcentajeAsistencia}%`;
  }
}
