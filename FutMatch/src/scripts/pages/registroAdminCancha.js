/**
 * REGISTRO ADMIN CANCHA - Validaciones del formulario
 * ====================================================
 * Validaciones en tiempo real y al submit
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formRegistroAdmin');
  const inputNombre = document.getElementById('inputNombre');
  const inputApellido = document.getElementById('inputApellido');
  const inputNombreCancha = document.getElementById('inputNombreCancha');
  const inputPais = document.getElementById('inputPais');
  const inputProvincia = document.getElementById('inputProvincia');
  const inputLocalidad = document.getElementById('inputLocalidad');
  const inputCalle = document.getElementById('inputCalle');
  const inputTelefono = document.getElementById('inputTelefono');
  const inputEmail = document.getElementById('inputEmail');
  const checkTerminos = document.getElementById('checkTerminos');
  const inputContacto = document.getElementById('inputContacto');
  const inputHorario = document.getElementById('inputHorario');

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
  // VALIDACIÓN: Selects (País, Provincia, Localidad, Contacto, Horario)
  // ===================================
  const selects = [inputPais, inputProvincia, inputLocalidad, inputContacto, inputHorario];
  
  selects.forEach(select => {
    select.addEventListener('change', function() {
      if (this.value) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
      } else {
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
      }
    });
  });

  // ===================================
  // VALIDACIÓN GENERAL EN BLUR
  // ===================================
  const camposObligatorios = [
    inputNombre, 
    inputApellido, 
    inputNombreCancha, 
    inputCalle, 
    inputTelefono, 
    inputEmail
  ];
  
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

    // Validar nombre de cancha
    if (!inputNombreCancha.value.trim()) {
      inputNombreCancha.classList.add('is-invalid');
      isValid = false;
    }

    // Validar país
    if (!inputPais.value) {
      inputPais.classList.add('is-invalid');
      isValid = false;
    }

    // Validar provincia
    if (!inputProvincia.value) {
      inputProvincia.classList.add('is-invalid');
      isValid = false;
    }

    // Validar localidad
    if (!inputLocalidad.value) {
      inputLocalidad.classList.add('is-invalid');
      isValid = false;
    }

    // Validar calle
    if (!inputCalle.value.trim()) {
      inputCalle.classList.add('is-invalid');
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

    // Validar términos y condiciones
    if (!checkTerminos.checked) {
      checkTerminos.classList.add('is-invalid');
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

    // Validar método de contacto
    if (!inputContacto.value) {
      inputContacto.classList.add('is-invalid');
      isValid = false;
    }

    // Validar horario de preferencia
    if (!inputHorario.value) {
      inputHorario.classList.add('is-invalid');
      isValid = false;
    }

    // Marcar formulario como validado
    form.classList.add('was-validated');

    // Si todo es válido, enviar
    if (isValid) {
      // Datos del formulario
      const formData = {
        nombre: inputNombre.value.trim(),
        apellido: inputApellido.value.trim(),
        nombreCancha: inputNombreCancha.value.trim(),
        pais: inputPais.value,
        provincia: inputProvincia.value,
        localidad: inputLocalidad.value,
        calle: inputCalle.value.trim(),
        detalle: document.getElementById('inputDetalle').value.trim(),
        email: inputEmail.value.trim(),
        telefono: inputTelefono.value.trim(),
        terminos: checkTerminos.checked,
        contacto: inputContacto.value,
        horario: inputHorario.value
      };

      console.log('Formulario válido. Datos:', formData);
      
      // TODO: Aquí iría la llamada AJAX al backend
      
      // Mensaje de confirmación y redirección
      alert('Gracias por tu solicitud. Serás contactado a la brevedad por uno de los asesores de FutMatch.');
      
      // Redireccionar a landing después del alert
      window.location.href = '/Proyecto_Integrador_PW2025/FutMatch/public/HTML/auth/landing.php';
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
