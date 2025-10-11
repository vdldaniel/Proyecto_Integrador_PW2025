/**
 * FORGOT PASSWORD - Validaciones del formulario
 * ==============================================
 * Validación de email y envío de solicitud de recuperación
 */

document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('formForgot');
  const inputEmail = document.getElementById('inputEmail');

  // ===================================
  // VALIDACIÓN: Email en tiempo real
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
  // SUBMIT DEL FORMULARIO
  // ===================================
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let isValid = true;

    if (!inputEmail.value || !emailRegex.test(inputEmail.value)) {
      inputEmail.classList.add('is-invalid');
      inputEmail.setCustomValidity('Ingresá un email válido');
      isValid = false;
    } else {
      inputEmail.classList.remove('is-invalid');
      inputEmail.classList.add('is-valid');
    }

    // Marcar formulario como validado
    form.classList.add('was-validated');

    // Si es válido, enviar
    if (isValid) {
      const email = inputEmail.value.trim();

      console.log('Email de recuperación:', email);
      
      // TODO: Aquí iría la llamada AJAX al backend para enviar el email
      
      // Mensaje de confirmación
      alert('Si el email está registrado, recibirás un enlace de recuperación en tu bandeja de entrada. Por favor revisá también la carpeta de spam.');
      
      // Limpiar formulario
      form.reset();
      form.classList.remove('was-validated');
      inputEmail.classList.remove('is-valid');
      
      // Redireccionar a landing después de un breve delay
      setTimeout(function() {
        window.location.href = '/Proyecto_Integrador_PW2025/FutMatch/public/HTML/auth/landing.php';
      }, 2000);
    } else {
      // Focus en el email si hay error
      inputEmail.focus();
    }
  });

  // ===================================
  // LIMPIAR VALIDACIÓN AL ESCRIBIR
  // ===================================
  inputEmail.addEventListener('input', function() {
    if (this.classList.contains('is-invalid')) {
      this.classList.remove('is-invalid');
    }
  });
});
