window.onload = function () {
  // Referencias
  /*-----------------------CAMPOS-----------------------------*/
  const inputNombre = document.getElementById("inputNombre");
  const inputApellido = document.getElementById("inputApellido");
  const inputEmail = document.getElementById("inputEmail");
  const inputTel = document.getElementById("inputTel");

  const oldPass = document.getElementById("inputContraseña");
  const newPass = document.getElementById("inputContraseñaNew");
  const confirmPass = document.getElementById("inputContraseñaConfirm");

  /*-----------------------ERRORES-----------------------------*/
  const errorNombre = document.getElementById("errorNombre");
  onst errorNombre = document.getElementById("errorApellido");
  const errorEmail = document.getElementById("errorEmail");
  const errorTel = document.getElementById("errorTel");
  const errorPass = document.getElementById("errorPass");

  /*-----------------------MODALES-----------------------------*/
  const modalGuardar = new bootstrap.Modal(
    document.getElementById("modalGuardar")
  );
  const modalEliminar1 = new bootstrap.Modal(
    document.getElementById("modalEliminar1")
  );
  const modalEliminar2 = new bootstrap.Modal(
    document.getElementById("modalEliminar2")
  );

  /*-----------------------BOTONES-----------------------------*/
  const btnGuardar = document.getElementById("btnGuardar");
  const btnEliminar = document.getElementById("btnEliminarCuenta");
  const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
  const btnCambiarPass = document.getElementById("btnCambiarPass");

  /*------------------------------FUNCIONES: VALIDACIONES-------------------------*/
  function validarCuenta() {
    let valido = true;

    // Nombre
    if (inputNombre.value.trim() === "") {
      errorNombre.textContent = "El nombre es obligatorio.";
      valido = false;
    } else {
      errorNombre.textContent = "";
    }

    // Apellido
    if (inputApellido.value.trim() === "") {
      document.getElementById("errorApellido").textContent =
        "El apellido es obligatorio.";
      valido = false;
    } else {
      document.getElementById("errorApellido").textContent = "";
    }

    // Email
    const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexEmail.test(inputEmail.value.trim())) {
      errorEmail.textContent = "El correo no es válido.";
      valido = false;
    } else {
      errorEmail.textContent = "";
    }

    // Teléfono
    if (inputTel.value.trim().length < 8) {
      errorTel.textContent = "El teléfono debe tener al menos 8 dígitos.";
      valido = false;
    } else {
      errorTel.textContent = "";
    }

    return valido;
  }

  function validarContraseña() {
    let valido = true;

    if (newPass.value.trim().length < 6) {
      errorPass.textContent =
        "La nueva contraseña debe tener al menos 6 caracteres.";
      valido = false;
    } else if (newPass.value !== confirmPass.value) {
      errorPass.textContent = "Las contraseñas no coinciden.";
      valido = false;
    } else {
      errorPass.textContent = "";
    }

    return valido;
  }

  /*--------------------------EVENTOS----------------------------*/

  // Eliminar cuenta
  btnEliminar.addEventListener("click", function (e) {
    e.preventDefault();
    modalEliminar1.show();
  });

  //Confirmar eliminación
  btnConfirmarEliminar.addEventListener("click", function () {
    modalEliminar2.show();
  });

  // Guardar cambios (muestra modal)
  btnGuardar.addEventListener("click", function (e) {
    e.preventDefault();
    if (validarCuenta()) {
      modalGuardar.show();
    }
  });

  // Cambiar contraseña
  btnCambiarPass.addEventListener("click", function (e) {
    e.preventDefault();
    if (validarContraseña()) {
      alert("Contraseña cambiada correctamente (simulado).");
      oldPass.value = "";
      newPass.value = "";
      confirmPass.value = "";
    }
  });
};
