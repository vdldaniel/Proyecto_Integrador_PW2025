/**
 * REGISTRO JUGADOR - Validaciones del formulario
 * ==============================================
 * Validaciones en tiempo real y al submit
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formRegistro');
  const inputNombre = document.getElementById('inputNombre');
  const inputApellido = document.getElementById('inputApellido');
  const inputUsername = document.getElementById('inputUsername');
  const inputFecha = document.getElementById('inputFechaDeNacimiento');
  const inputEmail = document.getElementById('inputEmail');
  const inputTelefono = document.getElementById('inputTelefono');
  const inputPassword = document.getElementById('inputPassword');
  const inputPasswordConfirm = document.getElementById('inputPasswordConfirm');
  const checkTerminos = document.getElementById('checkTerminos');
  const inputGenero = document.getElementById('inputGenero');

  // ===================================
  // VALIDACIÓN: Nombre y Apellido - Solo letras y espacios
  // ===================================
  function validarSoloLetras(input) {
    input.addEventListener('input', function(e) {
      // Remover cualquier carácter que no sea letra o espacio
      this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, '');
    });

    input.addEventListener('keypress', function(e) {
      // Prevenir entrada de números y caracteres especiales
      const char = String.fromCharCode(e.which);
      if (!/[a-záéíóúñA-ZÁÉÍÓÚÑ\s]/.test(char)) {
        e.preventDefault();
      }
    });
  }

  validarSoloLetras(inputNombre);
  validarSoloLetras(inputApellido);

  // ===================================
  // VALIDACIÓN: Teléfono - Solo números y guiones
  // ===================================
  inputTelefono.addEventListener('input', function(e) {
    // Permitir solo números, guiones y espacios
    this.value = this.value.replace(/[^0-9\-\s]/g, '');
  });

  inputTelefono.addEventListener('keypress', function(e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9\-\s]/.test(char)) {
      e.preventDefault();
    }
  });

  // ===================================
  // VALIDACIÓN: Fecha de nacimiento - Mayor de 18 años
  // ===================================
  function validarEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }
    
    return edad >= 18;
  }

  inputFecha.addEventListener('change', function() {
    if (this.value && !validarEdad(this.value)) {
      this.setCustomValidity('Debes ser mayor de 18 años');
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    } else if (this.value) {
      this.setCustomValidity('');
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    }
  });

  // ===================================
  // VALIDACIÓN: Email
  // ===================================
  inputEmail.addEventListener('blur', function() {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailRegex.test(this.value)) {
      this.setCustomValidity('Ingresá un email válido');
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    } else if (this.value) {
      this.setCustomValidity('');
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    }
  });

  // ===================================
  // VALIDACIÓN: Teléfono - Formato válido
  // ===================================
  inputTelefono.addEventListener('blur', function() {
    // Mínimo 8 dígitos (sin contar guiones y espacios)
    const digitos = this.value.replace(/[\-\s]/g, '');
    if (this.value && digitos.length < 8) {
      this.setCustomValidity('El teléfono debe tener al menos 8 dígitos');
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    } else if (this.value) {
      this.setCustomValidity('');
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    }
  });

  // ===================================
  // VALIDACIÓN: Contraseña - Mínimo 8 chars, 1 minúscula, 1 número
  // ===================================
  function validarPassword(password) {
    // Al menos 8 caracteres, una minúscula y un número
    const minLength = password.length >= 8;
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    return minLength && hasLowercase && hasNumber;
  }

  inputPassword.addEventListener('input', function() {
    if (this.value) {
      if (validarPassword(this.value)) {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
      } else {
        this.setCustomValidity('Mínimo 8 caracteres, una minúscula y un número');
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      }
      
      // Revalidar confirmación si ya hay algo escrito
      if (inputPasswordConfirm.value) {
        validarConfirmacionPassword();
      }
    }
  });

  // ===================================
  // VALIDACIÓN: Confirmar contraseña
  // ===================================
  function validarConfirmacionPassword() {
    if (inputPasswordConfirm.value !== inputPassword.value) {
      inputPasswordConfirm.setCustomValidity('Las contraseñas no coinciden');
      inputPasswordConfirm.classList.add('is-invalid');
      inputPasswordConfirm.classList.remove('is-valid');
      return false;
    } else if (inputPasswordConfirm.value) {
      inputPasswordConfirm.setCustomValidity('');
      inputPasswordConfirm.classList.remove('is-invalid');
      inputPasswordConfirm.classList.add('is-valid');
      return true;
    }
  }

  inputPasswordConfirm.addEventListener('input', validarConfirmacionPassword);

  // ===================================
  // VALIDACIÓN: Género
  // ===================================
  inputGenero.addEventListener('change', function() {
    if (this.value) {
      this.classList.remove('is-invalid');
      this.classList.add('is-valid');
    } else {
      this.classList.add('is-invalid');
      this.classList.remove('is-valid');
    }
  });

  // ===================================
  // VALIDACIÓN GENERAL EN BLUR
  // ===================================
  const camposObligatorios = [inputNombre, inputApellido, inputUsername, inputFecha, inputEmail, inputTelefono, inputPassword, inputPasswordConfirm];
  
  camposObligatorios.forEach(campo => {
    campo.addEventListener('blur', function() {
      if (!this.value.trim()) {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      }
    });
  });

  // ===================================
  // SUBMIT DEL FORMULARIO
  // ===================================
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();

    // Resetear estados
    let isValid = true;

    // Validar nombre
    if (!inputNombre.value.trim()) {
      inputNombre.classList.add('is-invalid');
      isValid = false;
    }

    // Validar apellido
    if (!inputApellido.value.trim()) {
      inputApellido.classList.add('is-invalid');
      isValid = false;
    }

    // Validar username
    if (!inputUsername.value.trim()) {
      inputUsername.classList.add('is-invalid');
      isValid = false;
    }

    // Validar fecha de nacimiento
    if (!inputFecha.value || !validarEdad(inputFecha.value)) {
      inputFecha.classList.add('is-invalid');
      isValid = false;
    }

    // Validar género
    if (!inputGenero.value) {
      inputGenero.classList.add('is-invalid');
      isValid = false;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!inputEmail.value || !emailRegex.test(inputEmail.value)) {
      inputEmail.classList.add('is-invalid');
      isValid = false;
    }

    // Validar teléfono
    const digitos = inputTelefono.value.replace(/[\-\s]/g, '');
    if (!inputTelefono.value || digitos.length < 8) {
      inputTelefono.classList.add('is-invalid');
      isValid = false;
    }

    // Validar contraseña
    if (!inputPassword.value || !validarPassword(inputPassword.value)) {
      inputPassword.classList.add('is-invalid');
      isValid = false;
    }

    // Validar confirmación de contraseña
    if (inputPasswordConfirm.value !== inputPassword.value) {
      inputPasswordConfirm.classList.add('is-invalid');
      isValid = false;
    }

    // Validar términos y condiciones
    if (!checkTerminos.checked) {
      checkTerminos.classList.add('is-invalid');
      // Mostrar feedback en el checkbox
      const feedbackDiv = checkTerminos.parentElement.querySelector('.invalid-feedback');
      if (feedbackDiv) {
        feedbackDiv.style.display = 'block';
      }
      isValid = false;
    } else {
      checkTerminos.classList.remove('is-invalid');
      const feedbackDiv = checkTerminos.parentElement.querySelector('.invalid-feedback');
      if (feedbackDiv) {
        feedbackDiv.style.display = 'none';
      }
    }

    // Marcar formulario como validado
    form.classList.add('was-validated');

    // Si todo es válido, enviar
    if (isValid) {
      // TODO: Aquí iría la llamada AJAX al backend
      const formData = {
        nombre: inputNombre.value.trim(),
        apellido: inputApellido.value.trim(),
        username: inputUsername.value.trim(),
        fechaNacimiento: inputFecha.value,
        genero: inputGenero.value,
        email: inputEmail.value.trim(),
        telefono: inputTelefono.value.trim(),
        password: inputPassword.value,
        terminos: checkTerminos.checked,
        promociones: document.getElementById('checkPromociones').checked
      };

      console.log('Formulario válido. Datos:', formData);
      
      // Simular envío exitoso
      alert('¡Registro exitoso! (Esto es una simulación, falta conectar con el backend)');
      
      // Opcional: Resetear formulario
      // form.reset();
      // form.classList.remove('was-validated');
    } else {
      // Scroll al primer campo inválido
      const primerInvalido = form.querySelector('.is-invalid');
      if (primerInvalido) {
        primerInvalido.scrollIntoView({ behavior: 'smooth', block: 'center' });
        primerInvalido.focus();
      }
    }
  });

  // ===================================
  // VALIDACIÓN VISUAL DEL CHECKBOX
  // ===================================
  checkTerminos.addEventListener('change', function() {
    if (this.checked) {
      this.classList.remove('is-invalid');
      const feedbackDiv = this.parentElement.querySelector('.invalid-feedback');
      if (feedbackDiv) {
        feedbackDiv.style.display = 'none';
      }
    }
  });
});
