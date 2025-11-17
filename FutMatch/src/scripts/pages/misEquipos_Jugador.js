// Array global para almacenar jugadores invitados
let jugadoresInvitados = [];
let fotoEquipoSeleccionada = null;
let fotoEliminada = false; // Flag para indicar si se eliminó la foto en edición

window.onload = function () {
  cargarEquiposJugador();
  configurarEventosModal();
  configurarUploadFoto();

  // Inicializar botón de invitar jugador en el modal de crear equipo
  const container = document.getElementById("invitarJugadoresSection");
  if (container && container.innerHTML.trim() === "") {
    mostrarBotonInvitar();
  }
};

function configurarUploadFoto() {
  const dropZone = document.getElementById("dropZoneFoto");
  const inputFile = document.getElementById("inputFotoPrincipal");

  if (!dropZone || !inputFile) return;

  // Click en la zona de drop
  dropZone.addEventListener("click", function () {
    inputFile.click();
  });

  // Cuando se selecciona un archivo
  inputFile.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith("image/")) {
      mostrarPreviewFoto(file);
      fotoEquipoSeleccionada = file;
    }
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

  dropZone.addEventListener("drop", function (e) {
    e.preventDefault();
    dropZone.classList.remove("dragover");

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith("image/")) {
      mostrarPreviewFoto(file);
      fotoEquipoSeleccionada = file;
      // Actualizar el input file
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      inputFile.files = dataTransfer.files;
    }
  });
}

function mostrarPreviewFoto(file) {
  const dropZone = document.getElementById("dropZoneFoto");
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
        eliminarFoto();
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

function eliminarFoto() {
  const dropZone = document.getElementById("dropZoneFoto");
  const inputFile = document.getElementById("inputFotoPrincipal");

  // Resetear el input file
  if (inputFile) {
    inputFile.value = "";
  }

  // Resetear la variable global
  fotoEquipoSeleccionada = null;

  // Si estamos en modo edición, marcar que se eliminó la foto
  if (modoEdicion) {
    fotoEliminada = true;
  }

  // Restaurar el estado inicial del dropzone
  dropZone.innerHTML = `
    <div class="upload-icon">
      <i class="bi bi-cloud-upload fs-1 text-muted"></i>
      <span class="upload-text">Click o arrastra una imagen</span>
    </div>
  `;
}

function configurarEventosModal() {
  const modalCrearEquipo = document.getElementById("modalCrearEquipo");

  // Resetear el modal cuando se cierra (no cuando se abre, para permitir edición)
  modalCrearEquipo.addEventListener("hidden.bs.modal", function () {
    // Resetear modo
    modoEdicion = false;
    equipoActualId = null;
    equipoActual = null;
    nuevoLiderId = null;
    liderazgoConfigurado = false;
    fotoEliminada = false;

    // Resetear jugadores y foto
    jugadoresInvitados = [];
    fotoEquipoSeleccionada = null; // Cambiar título del modal a crear
    document.getElementById("tituloModalEquipo").textContent =
      "Crear un equipo";

    const container = document.getElementById("invitarJugadoresSection");
    container.innerHTML = "";
    mostrarBotonInvitar();

    // Limpiar formulario
    document.getElementById("inputIdEquipo").value = "";
    document.getElementById("inputNombreEquipo").value = "";
    document.getElementById("inputDescripcionEquipo").value = "";
    const inputFoto = document.getElementById("inputFotoPrincipal");
    if (inputFoto) inputFoto.value = "";

    // Ocultar sección de transferir liderazgo
    const seccionTransferir = document.getElementById(
      "seccionTransferirLiderazgo"
    );
    if (seccionTransferir) seccionTransferir.classList.add("d-none");

    const inputTransferir = document.getElementById("inputTransferirLiderazgo");
    if (inputTransferir) inputTransferir.classList.add("d-none");

    const inputNuevoLider = document.getElementById("inputNuevoLider");
    if (inputNuevoLider) inputNuevoLider.value = "";

    // Resetear preview de foto
    const dropZone = document.getElementById("dropZoneFoto");
    if (dropZone) {
      dropZone.innerHTML = `
        <div class="upload-icon">
          <i class="bi bi-cloud-upload fs-1 text-muted"></i>
          <span class="upload-text">Click o arrastra una imagen</span>
        </div>
      `;
    }
  });

  // Evento para el botón crear equipo
  const btnCrearEquipoSubmit = document.getElementById("btnCrearEquipoSubmit");
  if (btnCrearEquipoSubmit) {
    btnCrearEquipoSubmit.addEventListener("click", validarEquipo);
  }
}

function mostrarBotonInvitar() {
  const container = document.getElementById("invitarJugadoresSection");
  container.innerHTML = `
    <button type="button" class="btn btn-outline-primary w-100" id="btnInvitarJugador">
      <i class="bi bi-person-plus"></i> Invitar jugador
    </button>
  `;

  document
    .getElementById("btnInvitarJugador")
    .addEventListener("click", function () {
      this.remove();
      agregarInputJugador();
    });
}

function agregarInputJugador() {
  agregarInput("invitarJugadoresSection", "jugador[]", mostrarBotonInvitar);
}

function agregarInputJugadorModal() {
  agregarInput(
    "seccionInvitarNuevos",
    "jugadorInvitar[]",
    mostrarBotonInvitarModal
  );
}

// Función genérica refactorizada para agregar inputs de jugador
function agregarInput(containerId, inputName, mostrarBotonCallback) {
  const container = document.getElementById(containerId);

  const nuevoInputGroup = document.createElement("div");
  nuevoInputGroup.className = "input-group mb-2";
  nuevoInputGroup.innerHTML = `
    <input type="text" class="form-control" placeholder="Username del jugador" name="${inputName}">
    <button class="btn btn-success btn-agregar" type="button" disabled>
      <i class="bi bi-plus-lg"></i>
    </button>
  `;

  container.appendChild(nuevoInputGroup);

  const input = nuevoInputGroup.querySelector(`input[name="${inputName}"]`);
  const btnAgregar = nuevoInputGroup.querySelector(".btn-agregar");

  // Habilitar botón cuando hay texto
  input.addEventListener("input", function () {
    btnAgregar.disabled = this.value.trim() === "";
    limpiarValidacion(input);
  });

  // Variable para almacenar el jugador agregado
  let jugadorAgregado = null;

  // Función para agregar jugador
  const funcionAgregar = async function () {
    const username = input.value.trim();
    const resultadoValidacion = await validarUsername(username);

    if (resultadoValidacion.valido) {
      // Guardar referencia al jugador
      jugadorAgregado = resultadoValidacion.jugador;

      // Agregar al array
      jugadoresInvitados.push(jugadorAgregado);

      // Convertir botón + a -
      btnAgregar.classList.remove("btn-success", "btn-agregar");
      btnAgregar.classList.add("btn-danger", "btn-remover");
      btnAgregar.innerHTML = '<i class="bi bi-dash-lg"></i>';
      btnAgregar.disabled = false;

      // Deshabilitar input
      input.disabled = true;

      // Mostrar feedback de éxito
      mostrarExito(input, jugadorAgregado);

      // Remover evento de agregar
      btnAgregar.removeEventListener("click", funcionAgregar);

      // Agregar evento de remover
      btnAgregar.addEventListener("click", funcionRemover);

      // Agregar un nuevo input
      agregarInput(containerId, inputName, mostrarBotonCallback);
    } else {
      // Mostrar error según el tipo
      const mensajes = {
        es_usuario_actual: "Ya sos parte del equipo",
        duplicado: "Este jugador ya fue agregado",
        no_encontrado: "Usuario no encontrado",
        vacio: "El campo no puede estar vacío",
        conexion: "Error al verificar usuario",
        invitacion_pendiente: "Ya enviaste esta invitación",
        ya_es_miembro: "Este jugador ya es miembro del equipo",
      };
      mostrarError(
        input,
        mensajes[resultadoValidacion.error] || "Error desconocido"
      );
      btnAgregar.disabled = true;
    }
  };

  // Función para remover jugador
  const funcionRemover = function () {
    // Remover del array usando el jugador guardado
    if (jugadorAgregado) {
      jugadoresInvitados = jugadoresInvitados.filter(
        (j) => j.id_jugador !== jugadorAgregado.id_jugador
      );
    }

    // Remover el input group
    nuevoInputGroup.remove();

    // Si no quedan inputs, mostrar botón invitar
    const inputs = container.querySelectorAll(`input[name="${inputName}"]`);
    if (inputs.length === 0) {
      mostrarBotonCallback();
    }
  };

  // Agregar evento inicial (agregar)
  btnAgregar.addEventListener("click", funcionAgregar);
}

async function validarUsername(username) {
  if (username === "") {
    return { valido: false, error: "vacio" };
  }

  try {
    const response = await fetch(
      `${GET_USUARIOS}?username=${encodeURIComponent(username)}`
    );

    if (!response.ok) {
      // No mostrar error 404 en consola, manejarlo silenciosamente
      return { valido: false, error: "no_encontrado" };
    }

    const jugador = await response.json();

    // Verificar si es el usuario actual
    if (jugador.es_usuario_actual) {
      return { valido: false, error: "es_usuario_actual" };
    }

    // Verificar si ya está en el array
    const yaAgregado = jugadoresInvitados.some(
      (j) => j.id_jugador === jugador.id_jugador
    );
    if (yaAgregado) {
      return { valido: false, error: "duplicado" };
    }

    // Verificar si ya tiene invitación pendiente en el equipo actual
    if (equipoActualId && jugadoresEquipoActual.length > 0) {
      const tienePendiente = jugadoresEquipoActual.some(
        (j) =>
          j.id_jugador === jugador.id_jugador &&
          (parseInt(j.estado_solicitud) === 1 ||
            parseInt(j.estado_solicitud) === 2)
      );
      if (tienePendiente) {
        return { valido: false, error: "invitacion_pendiente" };
      }

      // Verificar si ya es miembro aceptado
      const yaEsMiembro = jugadoresEquipoActual.some(
        (j) =>
          j.id_jugador === jugador.id_jugador &&
          parseInt(j.estado_solicitud) === 3
      );
      if (yaEsMiembro) {
        return { valido: false, error: "ya_es_miembro" };
      }
    }

    return { valido: true, jugador: jugador };
  } catch (error) {
    // No mostrar errores de red en consola
    return { valido: false, error: "conexion" };
  }
}

function mostrarError(inputElement, mensaje) {
  limpiarValidacion(inputElement);
  inputElement.classList.add("is-invalid");

  const inputGroup = inputElement.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "invalid-feedback d-block";
  feedback.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i> ${mensaje}`;
  inputGroup.appendChild(feedback);
}

function mostrarExito(inputElement, jugador) {
  limpiarValidacion(inputElement);
  inputElement.classList.add("is-valid");

  const inputGroup = inputElement.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "valid-feedback d-block";
  feedback.innerHTML = `<i class="bi bi-check-circle me-1"></i> ${jugador.nombre} ${jugador.apellido} - <span class="fst-italic">Se enviará la invitación</span>`;
  inputGroup.appendChild(feedback);
}

function limpiarValidacion(inputElement) {
  inputElement.classList.remove("is-valid", "is-invalid");
  const inputGroup = inputElement.closest(".input-group");

  // Remover feedback anterior
  const feedbacks = inputGroup.querySelectorAll(
    ".valid-feedback, .invalid-feedback"
  );
  feedbacks.forEach((f) => f.remove());
}

async function cargarEquiposJugador() {
  try {
    const response = await fetch(GET_EQUIPOS_JUGADOR);
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const equipos = await response.json();
    // console.log(equipos);
    mostrarEquipos(equipos);
  } catch (error) {
    console.error("Error al cargar los equipos: ", error);
    mostrarMensajeError();
  }
}

function mostrarEquipos(equipos) {
  const equiposList = document.getElementById("equiposList");
  equiposList.innerHTML = "";

  // Si no hay equipos
  if (!equipos || equipos.length === 0) {
    equiposList.innerHTML = `
      <div class="col-12">
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle me-2"></i>
          Todavía no formas parte de ningún equipo. ¡Crea uno o únete a uno existente!
        </div>
      </div>
    `;
    return;
  }

  // Renderizar cada equipo como una tarjeta
  equipos.forEach((equipo) => {
    const tarjetaEquipo = crearTarjetaEquipo(equipo);
    equiposList.innerHTML += tarjetaEquipo;
  });
}

function crearTarjetaEquipo(equipo) {
  // Determinar el badge de rol del usuario
  let badgeRol = "";
  const esLider = equipo.id_lider == CURRENT_USER_ID;

  if (esLider) {
    badgeRol =
      '<span class="badge bg-warning text-dark ms-2"><i class="bi bi-star-fill me-1"></i>Líder</span>';
  }

  // URL de la foto o placeholder
  const fotoEquipo = equipo.foto_equipo
    ? BASE_URL + "public/" + equipo.foto_equipo
    : IMG_EQUIPO_DEFAULT;
  return `
    <div class="col-12 mb-3">
      <div class="card border-0 shadow-sm hover-shadow">
        <div class="card-body">
          <div class="row align-items-center">
            <!-- Logo del equipo -->
            <div class="col-auto">
              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" 
                   style="width: 80px; height: 80px; overflow: hidden;">
                <img src="${fotoEquipo}" alt="${equipo.nombre_equipo}" 
                     class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
              </div>
            </div>

            <!-- Información del equipo -->
            <div class="col-md-4">
              <h5 class="card-title mb-1">
                <a href="perfilEquipo_Jugador.php?id=${
                  equipo.id_equipo
                }" class="text-decoration-none">
                  ${equipo.nombre_equipo}
                </a>
                ${badgeRol}
              </h5>
              <p class="text-muted mb-0 small">
                ${equipo.descripcion || "Sin descripción"}
              </p>
            </div>

            <!-- Estadísticas -->
            <div class="col-md-2">
              <small class="text-muted d-block">
                <div class "row"><i class="bi bi-people me-1"></i>${
                  equipo.cantidad_integrantes || 0
                }</div>
                    <div class "row">integrantes</div>
              </small>
            </div>
            <div class="col-md-2">
              <small class="text-muted d-block">
                    <div class "row"><i class="bi bi-trophy me-1"></i>${
                      equipo.torneos_activos || 0
                    }</div>
                    <div class "row">torneos</div>
              </small>
            </div>
            <div class="col-md-2">
              <small class="text-muted d-block">
                <div class "row"><i class="bi bi-calendar-event me-1"></i>${
                  equipo.partidos_proximos || 0
                }</div>
                    <div class "row">partidos</div>
              </small>
            </div>

            <!-- Acciones -->
            <div class="col-auto ms-auto">
              <a href="perfilEquipo_Jugador.php?id=${
                equipo.id_equipo
              }" class="btn btn-dark btn-sm">
                <i class="bi bi-eye me-1"></i>Ver perfil
              </a>
              <button class="btn btn-dark btn-sm ms-1" onclick="abrirModalInvitarJugadores(${
                equipo.id_equipo
              })">
                <i class="bi bi-people"></i>
              </button>
              ${
                esLider
                  ? `
              <button class="btn btn-dark btn-sm ms-1" onclick="abrirModalEditarEquipo(${equipo.id_equipo})">
                <i class="bi bi-pencil"></i>
              </button>
              `
                  : ""
              }
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
}

function mostrarMensajeError() {
  const equiposList = document.getElementById("equiposList");
  equiposList.innerHTML = `
    <div class="col-12">
      <div class="alert alert-danger text-center">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Error al cargar los equipos. Por favor, intenta nuevamente.
      </div>
    </div>
  `;
}

async function validarEquipo(e) {
  e.preventDefault();

  const nombreEquipo = document
    .getElementById("inputNombreEquipo")
    .value.trim();
  const descripcionEquipo = document
    .getElementById("inputDescripcionEquipo")
    .value.trim();

  // Usar la variable global en lugar de leer el input directamente
  const fotoEquipo = fotoEquipoSeleccionada;

  if (!nombreEquipo) {
    showToast("Debes ingresar un nombre para el equipo", "warning");
    return;
  }

  // Extraer solo los IDs de los jugadores invitados
  const idsJugadores = jugadoresInvitados.map((j) => j.id_jugador);

  // Preparar FormData para enviar archivo
  const formData = new FormData();
  formData.append("nombre", nombreEquipo);
  formData.append("descripcion", descripcionEquipo || "");
  formData.append("abierto", 1);
  formData.append("jugadores", JSON.stringify(idsJugadores));

  // Solo agregar foto si hay una nueva seleccionada
  if (fotoEquipo) {
    formData.append("foto", fotoEquipo);
  }

  // Si estamos en modo edición, agregar el ID del equipo y nuevo líder si aplica
  if (modoEdicion) {
    formData.append("id_equipo", equipoActualId);
    if (nuevoLiderId) {
      formData.append("nuevo_lider", nuevoLiderId);
    }
    // Si se eliminó la foto, enviar flag (solo si no hay nueva foto)
    if (fotoEliminada && !fotoEquipo) {
      formData.append("eliminar_foto", "1");
    }
  }

  // console.log("Datos a enviar:", {
  //   modo: modoEdicion ? "edición" : "creación",
  //   nombre: nombreEquipo,
  //   descripcion: descripcionEquipo,
  //   foto: fotoEquipo
  //     ? fotoEquipo.name
  //     : fotoEliminada
  //     ? "eliminada"
  //     : "sin cambio",
  //   jugadores: idsJugadores,
  //   nuevoLider: nuevoLiderId || "sin cambio",
  // });

  if (modoEdicion) {
    actualizarEquipo(formData);
  } else {
    crearEquipo(formData);
  }
}

async function crearEquipo(formData) {
  try {
    const response = await fetch(POST_EQUIPO_JUGADOR, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const resultado = await response.json();
    // console.log("Equipo creado:", resultado);

    // Cerrar modal y recargar equipos
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalCrearEquipo")
    );
    modal.hide();

    // Mostrar mensaje de éxito
    showToast("¡Equipo creado exitosamente!", "success");

    // Recargar lista de equipos
    cargarEquiposJugador();
  } catch (error) {
    console.error("Error al crear el equipo:", error);
    showToast(
      "Error al crear el equipo. Por favor, intenta nuevamente.",
      "error"
    );
  }
}

async function actualizarEquipo(formData) {
  try {
    const response = await fetch(UPDATE_EQUIPO_JUGADOR, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const resultado = await response.json();
    // console.log("Equipo actualizado:", resultado);

    // Cerrar modal y recargar equipos
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalCrearEquipo")
    );
    modal.hide();

    // Mostrar mensaje de éxito
    showToast("¡Equipo actualizado exitosamente!", "success");

    // Recargar lista de equipos
    cargarEquiposJugador();
  } catch (error) {
    console.error("Error al actualizar el equipo:", error);
    showToast(
      "Error al actualizar el equipo. Por favor, intenta nuevamente.",
      "error"
    );
  }
}

// Variable global para el equipo actual
let equipoActualId = null;
let equipoActual = null;
let modoEdicion = false;
let jugadoresEquipoActual = []; // Para almacenar los jugadores actuales del equipo

async function abrirModalInvitarJugadores(idEquipo) {
  equipoActualId = idEquipo;
  jugadoresInvitados = [];

  try {
    // Obtener información del equipo para mostrar jugadores actuales
    const response = await fetch(`${GET_EQUIPO_JUGADOR}?id=${idEquipo}`);
    if (response.ok) {
      const equipoData = await response.json();
      jugadoresEquipoActual = equipoData.jugadores || [];
    }
  } catch (error) {
    console.error("Error al cargar jugadores del equipo:", error);
    jugadoresEquipoActual = [];
  }

  const modalInvitar = new bootstrap.Modal(
    document.getElementById("modalInvitarJugador")
  );

  // Resetear el contenedor
  const container = document.getElementById("invitarJugadoresSectionModal");
  container.innerHTML = "";

  // Mostrar jugadores actuales si hay
  if (jugadoresEquipoActual.length > 0) {
    const seccionActuales = document.createElement("div");
    seccionActuales.className = "mb-3";
    seccionActuales.innerHTML = `
      <label class="form-label fw-bold">Miembros del equipo:</label>
      <div id="jugadoresActualesLista"></div>
      <hr class="my-3">
      <label class="form-label fw-bold">Gestionar jugadores:</label>
    `;
    container.appendChild(seccionActuales);

    // Esperar a que el DOM se actualice antes de buscar el elemento
    const listaActuales = seccionActuales.querySelector(
      "#jugadoresActualesLista"
    );
    jugadoresEquipoActual.forEach((jugador) => {
      // Determinar clase de borde según estado_solicitud
      // Convertir a número para evitar problemas de comparación
      const estadoSolicitud = parseInt(jugador.estado_solicitud);
      let claseBorde = "";
      let badgeEstado = "";
      let descripcionEstado = "";

      if (estadoSolicitud === 3) {
        claseBorde = "border-success"; // Verde para aceptados
      } else if (estadoSolicitud === 1 || estadoSolicitud === 2) {
        claseBorde = "border-warning"; // Naranja para pendientes
        badgeEstado =
          estadoSolicitud === 1
            ? '<span class="badge bg-warning text-dark ms-2">Invitación pendiente</span>'
            : '<span class="badge bg-warning text-dark ms-2">En revisión</span>';
        descripcionEstado =
          '<small class="text-muted d-block fst-italic">Aún no acepta la invitación</small>';
      }

      const itemJugador = document.createElement("div");
      itemJugador.className = `d-flex align-items-center justify-content-between mb-2 p-2 rounded border border-2 ${claseBorde}`;

      // Verificar si es el usuario actual
      const esUsuarioActual =
        parseInt(jugador.id_jugador) === parseInt(CURRENT_USER_ID);

      itemJugador.innerHTML = `
        <div>
          <strong>${jugador.username}</strong>
          <small class="text-muted d-block">${jugador.nombre} ${
        jugador.apellido
      }</small>
          ${descripcionEstado}
          ${
            jugador.es_lider
              ? '<span class="badge bg-primary">Líder</span>'
              : ""
          }
          ${
            esUsuarioActual ? '<span class="badge bg-secondary">Vos</span>' : ""
          }
          ${badgeEstado}
        </div>
        ${
          !jugador.es_lider
            ? `
          <button class="btn btn-sm btn-outline-danger" onclick="confirmarEliminarJugador(${jugador.id_jugador}, '${jugador.username}', ${idEquipo})">
            <i class="bi bi-trash"></i>
          </button>
        `
            : ""
        }
      `;
      listaActuales.appendChild(itemJugador);
    });
  }

  // Agregar sección para invitar nuevos jugadores
  const seccionInvitar = document.createElement("div");
  seccionInvitar.id = "seccionInvitarNuevos";
  container.appendChild(seccionInvitar);

  // Mostrar botón para agregar el primer jugador
  mostrarBotonInvitarModal();

  modalInvitar.show();

  // Configurar el botón de confirmar
  const btnConfirmar = document.getElementById("btnConfirmarInvitaciones");
  btnConfirmar.onclick = confirmarInvitaciones;
}

function mostrarBotonInvitarModal() {
  const container = document.getElementById("seccionInvitarNuevos");
  if (!container) return;

  container.innerHTML = `
    <button type="button" class="btn btn-outline-primary w-100" id="btnInvitarJugadorModal">
      <i class="bi bi-person-plus"></i> Invitar jugador
    </button>
  `;

  document
    .getElementById("btnInvitarJugadorModal")
    .addEventListener("click", function () {
      this.remove();
      agregarInputJugadorModal();
    });
}

function confirmarEliminarJugador(idJugador, username, idEquipo) {
  if (
    confirm(`¿Estás seguro de que deseas eliminar a ${username} del equipo?`)
  ) {
    eliminarJugadorDeEquipo(idJugador, idEquipo);
  }
}

async function eliminarJugadorDeEquipo(idJugador, idEquipo) {
  try {
    const formData = new FormData();
    formData.append("id_equipo", idEquipo);
    formData.append("id_jugador", idJugador);
    formData.append("eliminar_jugador", "1");

    const response = await fetch(UPDATE_EQUIPO_JUGADOR, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error al eliminar jugador");
    }

    showToast("Jugador eliminado exitosamente", "success");

    // Cerrar modal y recargar
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalInvitarJugador")
    );
    modal.hide();
    cargarEquiposJugador();
  } catch (error) {
    console.error("Error al eliminar jugador:", error);
    showToast("Error al eliminar el jugador del equipo", "error");
  }
}

async function confirmarInvitaciones() {
  if (jugadoresInvitados.length === 0) {
    showToast("Debes agregar al menos un jugador para invitar", "warning");
    return;
  }

  const idsJugadores = jugadoresInvitados.map((j) => j.id_jugador);

  const formData = new FormData();
  formData.append("id_equipo", equipoActualId);
  formData.append("jugadores", JSON.stringify(idsJugadores));

  try {
    const response = await fetch(POST_INVITAR_JUGADOR, {
      method: "POST",
      body: formData,
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const resultado = await response.json();
    // console.log("Invitaciones enviadas:", resultado);

    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalInvitarJugador")
    );
    modal.hide();

    let mensaje = resultado.message || "¡Invitaciones enviadas exitosamente!";
    let tipo = "success";

    // Si hay jugadores con invitación pendiente, mostrar advertencia
    if (
      resultado.jugadores_pendientes &&
      resultado.jugadores_pendientes.length > 0
    ) {
      mensaje += ` ${resultado.jugadores_pendientes.length} jugador(es) ya tienen invitación pendiente.`;
      tipo = "warning";
    }

    showToast(mensaje, tipo, 4000);

    cargarEquiposJugador();
  } catch (error) {
    console.error("Error al enviar invitaciones:", error);
    showToast(
      "Error al enviar las invitaciones. Por favor, intenta nuevamente.",
      "error"
    );
  }
}

async function abrirModalEditarEquipo(idEquipo) {
  try {
    // Obtener información del equipo
    const response = await fetch(`${GET_EQUIPO_JUGADOR}?id=${idEquipo}`);
    if (!response.ok) {
      throw new Error("Error al cargar el equipo");
    }

    equipoActual = await response.json();
    equipoActualId = idEquipo;
    modoEdicion = true;

    // Resetear jugadores invitados y cargar los existentes
    jugadoresInvitados = equipoActual.jugadores
      .filter((j) => !j.es_lider)
      .map((j) => ({
        id_jugador: j.id_jugador,
        username: j.username,
        nombre: j.nombre,
        apellido: j.apellido,
      }));

    // Cambiar título del modal
    document.getElementById("tituloModalEquipo").textContent = "Editar equipo";

    // Llenar campos
    document.getElementById("inputIdEquipo").value = equipoActual.id_equipo;
    document.getElementById("inputNombreEquipo").value =
      equipoActual.nombre || "";
    document.getElementById("inputDescripcionEquipo").value =
      equipoActual.descripcion || "";

    // Cargar foto si existe
    if (equipoActual.foto) {
      const dropZone = document.getElementById("dropZoneFoto");
      const fotoURL = BASE_URL + "public/" + equipoActual.foto;
      dropZone.innerHTML = `
        <img src="${fotoURL}" alt="Foto del equipo" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" id="btnEliminarFoto" style="z-index: 10; border-radius: 50%; width: 30px; height: 30px; padding: 0;">
          <i class="bi bi-x-lg"></i>
        </button>
        <div class="upload-icon" style="position: absolute; opacity: 0; transition: opacity 0.3s; background: rgba(0,0,0,0.5); width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
          <i class="bi bi-pencil fs-3 text-white"></i>
        </div>
      `;

      document
        .getElementById("btnEliminarFoto")
        .addEventListener("click", function (e) {
          e.stopPropagation();
          eliminarFoto();
        });
    }

    // Mostrar jugadores invitados
    const container = document.getElementById("invitarJugadoresSection");
    container.innerHTML = "";

    jugadoresInvitados.forEach((jugador) => {
      // Determinar el estado de la solicitud
      const jugadorCompleto = equipoActual.jugadores.find(
        (j) => j.id_jugador === jugador.id_jugador
      );
      const estadoSolicitud = jugadorCompleto
        ? parseInt(jugadorCompleto.estado_solicitud)
        : 3;

      // Determinar clase y feedback según estado
      let claseValidacion = "is-valid";
      let feedbackClass = "valid-feedback";
      let feedbackIcon = "check-circle";
      let feedbackTexto = `${jugador.nombre} ${jugador.apellido}`;

      if (estadoSolicitud === 1 || estadoSolicitud === 2) {
        claseValidacion = "is-warning";
        feedbackClass = "text-warning";
        feedbackIcon = "clock";
        feedbackTexto = `${jugador.nombre} ${jugador.apellido} - Invitación pendiente`;
      }

      // Crear contenedor wrapper para input-group y feedback
      const wrapper = document.createElement("div");
      wrapper.className = "mb-2";

      const inputGroup = document.createElement("div");
      inputGroup.className = "input-group";
      inputGroup.innerHTML = `
        <input type="text" class="form-control ${claseValidacion}" value="${jugador.username}" disabled>
        <button class="btn btn-danger btn-remover" type="button">
          <i class="bi bi-dash-lg"></i>
        </button>
      `;

      const feedback = document.createElement("div");
      feedback.className = `${feedbackClass} d-block small mt-1`;
      feedback.innerHTML = `<i class="bi bi-${feedbackIcon} me-1"></i> ${feedbackTexto}`;

      wrapper.appendChild(inputGroup);
      wrapper.appendChild(feedback);
      container.appendChild(wrapper);

      // Agregar evento para remover
      const btnRemover = inputGroup.querySelector(".btn-remover");
      btnRemover.addEventListener("click", function () {
        const index = jugadoresInvitados.findIndex(
          (j) => j.id_jugador === jugador.id_jugador
        );
        if (index > -1) jugadoresInvitados.splice(index, 1);
        wrapper.remove();

        if (container.querySelectorAll(".input-group").length === 0) {
          mostrarBotonInvitar();
        }
      });
    });

    // Agregar botón para invitar más jugadores
    if (jugadoresInvitados.length === 0) {
      mostrarBotonInvitar();
    } else {
      agregarInputJugador();
    }

    // Mostrar sección de transferir liderazgo si es líder
    if (equipoActual.es_lider) {
      document
        .getElementById("seccionTransferirLiderazgo")
        .classList.remove("d-none");
      configurarTransferirLiderazgo();
    }

    // Abrir modal
    const modal = new bootstrap.Modal(
      document.getElementById("modalCrearEquipo")
    );
    modal.show();
  } catch (error) {
    console.error("Error al cargar el equipo:", error);
    showToast("Error al cargar la información del equipo", "error");
  }
}

let nuevoLiderId = null;
let liderazgoConfigurado = false;

function configurarTransferirLiderazgo() {
  // Evitar configurar eventos múltiples veces
  if (liderazgoConfigurado) {
    return;
  }
  liderazgoConfigurado = true;

  const btnMostrar = document.getElementById("btnMostrarTransferirLiderazgo");
  const inputSection = document.getElementById("inputTransferirLiderazgo");
  const inputNuevoLider = document.getElementById("inputNuevoLider");
  const btnValidar = document.getElementById("btnValidarNuevoLider");

  if (!btnMostrar || !inputSection || !inputNuevoLider || !btnValidar) {
    console.error("Elementos de transferir liderazgo no encontrados");
    return;
  }

  // Mostrar input al hacer clic en el botón
  btnMostrar.onclick = function () {
    inputSection.classList.toggle("d-none");
    if (!inputSection.classList.contains("d-none")) {
      inputNuevoLider.focus();
    }
  };

  // Habilitar botón de validar cuando hay texto
  inputNuevoLider.oninput = function () {
    btnValidar.disabled = this.value.trim() === "";
    limpiarValidacionLider();
  };

  // Función para validar
  async function funcionValidar() {
    const username = inputNuevoLider.value.trim();
    const resultado = await validarNuevoLider(username);

    if (resultado.valido) {
      nuevoLiderId = resultado.jugador.id_jugador;
      mostrarExitoLider(resultado.jugador);
      btnValidar.className = "btn btn-danger";
      btnValidar.innerHTML = '<i class="bi bi-x-lg"></i>';
      btnValidar.disabled = false;

      // Cambiar funcionalidad del botón a "cancelar"
      btnValidar.onclick = function () {
        nuevoLiderId = null;
        inputNuevoLider.value = "";
        inputNuevoLider.disabled = false;
        btnValidar.className = "btn btn-success";
        btnValidar.innerHTML = '<i class="bi bi-check-lg"></i>';
        btnValidar.disabled = true;
        limpiarValidacionLider();
        btnValidar.onclick = funcionValidar;
      };
    } else {
      const mensajes = {
        vacio: "El campo no puede estar vacío",
        conexion: "No se pudo verificar el username",
        notfound: "Usuario no encontrado",
        usuario_actual: "No puedes transferir el liderazgo a ti mismo",
        no_es_miembro: "Este usuario no es miembro del equipo",
        invitacion_no_aceptada:
          "Este jugador aún no acepta la invitación al equipo",
      };
      mostrarErrorLider(
        mensajes[resultado.error] || "Error al validar usuario"
      );
    }
  }

  // Validar nuevo líder
  btnValidar.onclick = funcionValidar;
}

async function validarNuevoLider(username) {
  if (username === "") {
    return { valido: false, error: "vacio" };
  }

  try {
    const response = await fetch(
      `${GET_USUARIOS}?username=${encodeURIComponent(username)}`
    );

    if (!response.ok) {
      return { valido: false, error: "notfound" };
    }

    const jugador = await response.json();

    // Verificar si es el usuario actual
    if (jugador.es_usuario_actual) {
      return { valido: false, error: "usuario_actual" };
    }

    // Verificar si es miembro del equipo con invitación aceptada
    const miembroAceptado = equipoActual.jugadores.find(
      (j) => j.id_jugador === jugador.id_jugador
    );

    if (!miembroAceptado) {
      return { valido: false, error: "no_es_miembro" };
    }

    console.log("Miembro aceptado:", miembroAceptado);
    console.log("Estado solicitud:", miembroAceptado.estado_solicitud);

    // Verificar que tenga invitación aceptada (estado_solicitud debe ser 3)
    const estadoSolicitud = parseInt(miembroAceptado.estado_solicitud);
    if (estadoSolicitud !== 3) {
      return { valido: false, error: "invitacion_no_aceptada" };
    }

    return { valido: true, jugador: jugador };
  } catch (error) {
    return { valido: false, error: "conexion" };
  }
}

function mostrarErrorLider(mensaje) {
  limpiarValidacionLider();
  const inputNuevoLider = document.getElementById("inputNuevoLider");
  inputNuevoLider.classList.add("is-invalid");

  const inputGroup = inputNuevoLider.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "invalid-feedback d-block";
  feedback.innerHTML = `<i class="bi bi-exclamation-circle me-1"></i> ${mensaje}`;
  inputGroup.parentElement.appendChild(feedback);
}

function mostrarExitoLider(jugador) {
  limpiarValidacionLider();
  const inputNuevoLider = document.getElementById("inputNuevoLider");
  inputNuevoLider.classList.add("is-valid");
  inputNuevoLider.disabled = true;

  const inputGroup = inputNuevoLider.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "valid-feedback d-block";
  feedback.innerHTML = `<i class="bi bi-check-circle me-1"></i> ${jugador.nombre} ${jugador.apellido} será el nuevo líder`;
  inputGroup.parentElement.appendChild(feedback);
}

function limpiarValidacionLider() {
  const inputNuevoLider = document.getElementById("inputNuevoLider");
  inputNuevoLider.classList.remove("is-valid", "is-invalid");
  inputNuevoLider.disabled = false;

  const feedbacks = document
    .getElementById("inputTransferirLiderazgo")
    .querySelectorAll(".valid-feedback, .invalid-feedback");
  feedbacks.forEach((f) => f.remove());
}
