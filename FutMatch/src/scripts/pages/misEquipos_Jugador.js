window.onload = function () {
  cargarEquiposJugador();
  configurarEventosModal();
};

function configurarEventosModal() {
  // Configurar el primer input de jugador
  const inputInicial = document.querySelector(".input-jugador-inicial");
  if (inputInicial) {
    inputInicial.addEventListener("blur", function () {
      validarUsername(this);
    });
  }

  // Evento para agregar jugador al crear equipo
  const btnAgregarJugador = document.getElementById("btnAgregarJugador");
  if (btnAgregarJugador) {
    btnAgregarJugador.addEventListener("click", agregarJugadorInput);
  }

  // Evento para el botón crear equipo
  const btnCrearEquipoSubmit = document.getElementById("btnCrearEquipoSubmit");
  if (btnCrearEquipoSubmit) {
    btnCrearEquipoSubmit.addEventListener("click", validarEquipo);
  }
}

function agregarJugadorInput() {
  const container = document.getElementById("jugadoresContainer");

  // Obtener el último input para validar antes de agregar uno nuevo
  const inputs = container.querySelectorAll('input[name="jugador[]"]');
  const ultimoInput = inputs[inputs.length - 1];

  // Validar que el último input no esté vacío
  if (ultimoInput && ultimoInput.value.trim() === "") {
    alert("Completa el username anterior antes de agregar otro");
    ultimoInput.focus();
    return;
  }

  const nuevoInputGroup = document.createElement("div");
  nuevoInputGroup.className = "input-group mb-2";
  nuevoInputGroup.innerHTML = `
    <input type="text" class="form-control" placeholder="Username del jugador" name="jugador[]">
    <button class="btn btn-dark remove-jugador-btn" type="button">
      <i class="bi bi-dash"></i>
    </button>
  `;

  // Agregar evento para validar el username cuando se escribe
  const input = nuevoInputGroup.querySelector('input[name="jugador[]"]');
  input.addEventListener("blur", function () {
    validarUsername(this);
  });

  // Agregar evento para remover
  const btnRemove = nuevoInputGroup.querySelector(".remove-jugador-btn");
  btnRemove.addEventListener("click", function () {
    nuevoInputGroup.remove();
  });

  container.appendChild(nuevoInputGroup);
}

async function validarUsername(inputElement) {
  const username = inputElement.value.trim();

  if (username === "") {
    limpiarValidacion(inputElement);
    return;
  }

  try {
    const response = await fetch(
      `${GET_USUARIOS}?username=${encodeURIComponent(username)}`
    );

    if (!response.ok) {
      if (response.status === 404) {
        mostrarError(inputElement, "Usuario no encontrado");
      } else {
        mostrarError(inputElement, "Error al verificar usuario");
      }
      return;
    }

    const jugador = await response.json();

    // Verificar si es el usuario actual
    if (jugador.es_usuario_actual) {
      mostrarAdvertenciaUsuarioActual(inputElement);
      return;
    }

    // Usuario válido
    mostrarExito(inputElement, jugador);
  } catch (error) {
    console.error("Error al validar username:", error);
    mostrarError(inputElement, "Error de conexión");
  }
}

function mostrarError(inputElement, mensaje) {
  limpiarValidacion(inputElement);
  inputElement.classList.add("is-invalid");

  const inputGroup = inputElement.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "invalid-feedback d-block";
  feedback.textContent = mensaje;
  inputGroup.appendChild(feedback);

  // Deshabilitar el botón de agregar
  const btnAdd = inputGroup.querySelector("button");
  if (btnAdd && btnAdd.classList.contains("remove-jugador-btn")) {
    btnAdd.disabled = false;
  }
}

function mostrarExito(inputElement, jugador) {
  limpiarValidacion(inputElement);
  inputElement.classList.add("is-valid");
  inputElement.dataset.idJugador = jugador.id_jugador;

  const inputGroup = inputElement.closest(".input-group");
  const feedback = document.createElement("div");
  feedback.className = "valid-feedback d-block";
  feedback.textContent = `✓ ${jugador.nombre} ${jugador.apellido}`;
  inputGroup.appendChild(feedback);
}

function mostrarAdvertenciaUsuarioActual(inputElement) {
  limpiarValidacion(inputElement);
  inputElement.classList.add("is-invalid");
  inputElement.disabled = true;

  const inputGroup = inputElement.closest(".input-group");

  // Crear el mensaje de advertencia debajo del input
  const feedback = document.createElement("div");
  feedback.className = "invalid-feedback d-block";
  feedback.innerHTML =
    '<i class="bi bi-exclamation-circle me-1"></i> Ya sos parte del equipo.';
  inputGroup.appendChild(feedback);

  // Deshabilitar botón de remover
  const btnRemove = inputGroup.querySelector(".remove-jugador-btn");
  if (btnRemove) {
    btnRemove.disabled = true;
  }
}

function limpiarValidacion(inputElement) {
  inputElement.classList.remove("is-valid", "is-invalid");
  inputElement.disabled = false;
  const inputGroup = inputElement.closest(".input-group");

  // Remover feedback anterior
  const feedbacks = inputGroup.querySelectorAll(
    ".valid-feedback, .invalid-feedback, .text-warning, .bg-info"
  );
  feedbacks.forEach((f) => f.remove());

  // Rehabilitar botón
  const btnRemove = inputGroup.querySelector(".remove-jugador-btn");
  if (btnRemove) {
    btnRemove.disabled = false;
  }
}

async function cargarEquiposJugador() {
  try {
    const response = await fetch(GET_EQUIPOS_JUGADOR);
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const equipos = await response.json();
    console.log(equipos);
    //mostrarEquipos(equipos);
  } catch (error) {
    console.error("Error al cargar los equipos: ", error);
  }
}

const validarEquipo = async function (e) {
  e.preventDefault();

  // Obtener todos los inputs de jugadores válidos (solo los que tienen is-valid)
  const inputsJugadores = document.querySelectorAll(
    'input[name="jugador[]"].is-valid'
  );

  // Crear array con los id_jugador
  const jugadores = Array.from(inputsJugadores)
    .map((input) => parseInt(input.dataset.idJugador))
    .filter((id) => !isNaN(id));

  console.log("Jugadores válidos:", jugadores);

  let data = {
    nombreEquipo: document.getElementById("nombreEquipo").value,
    fotoEquipo: document.getElementById("fotoEquipo").value,
    descripcionEquipo: document.getElementById("descripcionEquipo").value,
    jugadores: jugadores,
  };

  console.log("Datos a enviar:", data);

  // Llamar a la función para crear el equipo
  // crearEquipo(data);
};

async function crearEquipo(equipoData) {
  e.preventDefault();
  try {
    const response = await fetch(POST_EQUIPO_JUGADOR);
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const nuevoEquipo = await response.json();
    console.log(nuevoEquipo);
    // Agregar el nuevo equipo a la lista
    //mostrarEquipos([nuevoEquipo]);
  } catch (error) {
    console.error("Error al crear el equipo: ", error);
  }
}

function mostrarEquipos(equipos) {
  const equiposList = document.getElementById("equiposList");
  equiposList.innerHTML = "";
  equipos.forEach((equipo) => {
    // Crear la tarjeta del equipo
  });
}
