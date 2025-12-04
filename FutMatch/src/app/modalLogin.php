<?php

/**
 * Modal de Login - Componente Reutilizable
 * -----------------------------------------
 * Este modal puede ser incluido en cualquier página para permitir el login.
 * Incluir este archivo después del cierre del <body> principal.
 */
?>

<!-- Modal de Login -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="modalLoginLabel">
                    <i class="bi bi-person-circle me-2"></i>Iniciar Sesión
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4">
                <!-- Mensaje de error (oculto por defecto) -->
                <div class="alert alert-danger d-none" id="loginError" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <span id="loginErrorMessage"></span>
                </div>

                <form id="loginForm">
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="loginEmail" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input
                                type="email"
                                class="form-control"
                                id="loginEmail"
                                name="email"
                                placeholder="tu@email.com"
                                required
                                autocomplete="email" />
                        </div>
                        <div class="invalid-feedback" id="emailError"></div>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">
                        <label for="loginPassword" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input
                                type="password"
                                class="form-control"
                                id="loginPassword"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password" />
                        </div>
                        <div class="invalid-feedback" id="passwordError"></div>
                    </div>

                    <!-- Recordarme -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">
                            Recordarme
                        </label>
                    </div>

                    <!-- Botón de login -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                        </button>
                    </div>

                    <!-- Enlaces adicionales -->
                    <div class="text-center">
                        <a href="<?= PAGE_FORGOT_PHP ?>" class="text-decoration-none small">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <p class="text-muted small mb-0">
                    ¿No tienes cuenta?
                    <a href="<?= PAGE_REGISTRO_JUGADOR_PHP ?>" class="text-decoration-none">
                        Regístrate como jugador
                    </a>
                    o
                    <a href="<?= PAGE_REGISTRO_ADMIN_CANCHA_PHP ?>" class="text-decoration-none">
                        como administrador de cancha
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const loginError = document.getElementById('loginError');
        const loginErrorMessage = document.getElementById('loginErrorMessage');
        const emailInput = document.getElementById('loginEmail');
        const passwordInput = document.getElementById('loginPassword');
        const submitButton = loginForm.querySelector('button[type="submit"]');

        // Auto-focus en el campo de email cuando se abre el modal
        const modalLogin = document.getElementById('modalLogin');
        modalLogin.addEventListener('shown.bs.modal', function() {
            emailInput.focus();
            // Limpiar errores previos
            loginError.classList.add('d-none');
            emailInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');
        });

        // Manejar el envío del formulario con AJAX
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Limpiar errores previos
            loginError.classList.add('d-none');
            emailInput.classList.remove('is-invalid');
            passwordInput.classList.remove('is-invalid');

            // Deshabilitar botón durante el proceso
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Iniciando...';

            const formData = new FormData(loginForm);

            try {
                const response = await fetch('<?= CONTROLLER_LOGIN ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    // Login exitoso - esperar un momento y recargar la página forzando
                    setTimeout(() => {
                        window.location.reload(true);
                    }, 300);
                } else {
                    // Mostrar error
                    loginErrorMessage.textContent = result.message || 'Email o contraseña incorrectos';
                    loginError.classList.remove('d-none');

                    // Marcar campos como inválidos
                    emailInput.classList.add('is-invalid');
                    passwordInput.classList.add('is-invalid');

                    // Re-habilitar botón
                    submitButton.disabled = false;
                    submitButton.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
                }
            } catch (error) {
                console.error('Error al procesar login:', error);
                loginErrorMessage.textContent = 'Error al procesar la solicitud. Intente nuevamente.';
                loginError.classList.remove('d-none');

                // Re-habilitar botón
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión';
            }
        });
    });
</script>