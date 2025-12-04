/**
 * UPDATE USUARIO - Gestión de configuración de usuario
 * Maneja la actualización de datos personales y cambio de contraseña
 */

document.addEventListener("DOMContentLoaded", function () {
  cargarDatosUsuario();
  inicializarEventListeners();
});

/**
 * Cargar datos del usuario al abrir el modal
 */
async function cargarDatosUsuario() {
  const modalConfiguracion = document.getElementById("modalConfiguracion");

  if (modalConfiguracion) {
    modalConfiguracion.addEventListener("shown.bs.modal", async function () {
      try {
        const formData = new FormData();
        formData.append("accion", "obtener_datos");

        const response = await fetch(UPDATE_USUARIO_URL, {
          method: "POST",
          body: formData,
        });

        if (!response.ok) throw new Error("Error al cargar datos");

        const result = await response.json();

        if (result.success) {
          document.getElementById("inputNombre").value =
            result.data.nombre || "";
          document.getElementById("inputApellido").value =
            result.data.apellido || "";
          document.getElementById("inputEmail").value = result.data.email || "";
          document.getElementById("inputTelefono").value =
            result.data.telefono || "";
        } else {
          showToast(result.error || "Error al cargar datos", "error");
        }
      } catch (error) {
        console.error("Error:", error);
        showToast("Error al cargar los datos del usuario", "error");
      }
    });
  }
}

/**
 * Inicializar event listeners
 */
function inicializarEventListeners() {
  // Botón Guardar Datos
  const btnGuardarDatos = document.getElementById("btnGuardarDatos");
  if (btnGuardarDatos) {
    btnGuardarDatos.addEventListener("click", guardarDatosUsuario);
  }

  // Botón Cambiar Contraseña (toggle)
  const btnCambiarPassword = document.getElementById("btnCambiarPassword");
  if (btnCambiarPassword) {
    btnCambiarPassword.addEventListener("click", function () {
      const formPassword = document.getElementById("formCambiarPassword");
      if (formPassword) {
        formPassword.classList.toggle("d-none");
      }
    });
  }

  // Botón Guardar Contraseña
  const btnGuardarPassword = document.getElementById("btnGuardarPassword");
  if (btnGuardarPassword) {
    btnGuardarPassword.addEventListener("click", cambiarPassword);
  }

  // Limpiar formulario de contraseña al cerrar modal
  const modalConfiguracion = document.getElementById("modalConfiguracion");
  if (modalConfiguracion) {
    modalConfiguracion.addEventListener("hidden.bs.modal", function () {
      limpiarFormularioPassword();
    });
  }
}

/**
 * Guardar datos personales del usuario
 */
async function guardarDatosUsuario() {
  const nombre = document.getElementById("inputNombre").value.trim();
  const apellido = document.getElementById("inputApellido").value.trim();
  const email = document.getElementById("inputEmail").value.trim();
  const telefono = document.getElementById("inputTelefono").value.trim();

  // Validaciones
  if (!nombre || !apellido || !email) {
    showToast("Nombre, apellido y email son obligatorios", "warning");
    return;
  }

  if (!validarEmail(email)) {
    showToast("El email no es válido", "warning");
    return;
  }

  try {
    const formData = new FormData();
    formData.append("accion", "actualizar_datos");
    formData.append("nombre", nombre);
    formData.append("apellido", apellido);
    formData.append("email", email);
    formData.append("telefono", telefono);

    const response = await fetch(UPDATE_USUARIO_URL, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      showToast(result.message, "success");
      // Actualizar el nombre en la sesión si está visible
      actualizarNombreEnInterfaz(nombre, apellido);
    } else {
      showToast(result.error || "Error al actualizar datos", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al guardar los datos", "error");
  }
}

/**
 * Cambiar contraseña
 */
async function cambiarPassword() {
  const passwordActual = document
    .getElementById("inputPasswordActual")
    .value.trim();
  const passwordNueva = document
    .getElementById("inputPasswordNueva")
    .value.trim();
  const passwordConfirmar = document
    .getElementById("inputPasswordConfirmar")
    .value.trim();

  // Validaciones
  if (!passwordActual || !passwordNueva || !passwordConfirmar) {
    showToast("Todos los campos de contraseña son obligatorios", "warning");
    return;
  }

  if (passwordNueva !== passwordConfirmar) {
    showToast("Las contraseñas nuevas no coinciden", "warning");
    return;
  }

  if (passwordNueva.length < 6) {
    showToast(
      "La nueva contraseña debe tener al menos 6 caracteres",
      "warning"
    );
    return;
  }

  try {
    const formData = new FormData();
    formData.append("accion", "cambiar_password");
    formData.append("password_actual", passwordActual);
    formData.append("password_nueva", passwordNueva);
    formData.append("password_confirmar", passwordConfirmar);

    const response = await fetch(UPDATE_USUARIO_URL, {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (result.success) {
      showToast(result.message, "success");
      limpiarFormularioPassword();
      document.getElementById("formCambiarPassword").classList.add("d-none");
    } else {
      showToast(result.error || "Error al cambiar contraseña", "error");
    }
  } catch (error) {
    console.error("Error:", error);
    showToast("Error al cambiar la contraseña", "error");
  }
}

/**
 * Limpiar formulario de contraseña
 */
function limpiarFormularioPassword() {
  const inputs = [
    "inputPasswordActual",
    "inputPasswordNueva",
    "inputPasswordConfirmar",
  ];
  inputs.forEach((id) => {
    const input = document.getElementById(id);
    if (input) input.value = "";
  });
}

/**
 * Validar formato de email
 */
function validarEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

/**
 * Actualizar nombre visible en la interfaz
 */
function actualizarNombreEnInterfaz(nombre, apellido) {
  // Puedes agregar lógica para actualizar el nombre en navbar si es visible
  const nombreCompleto = `${nombre} ${apellido}`;
  console.log("Nombre actualizado:", nombreCompleto);
}
