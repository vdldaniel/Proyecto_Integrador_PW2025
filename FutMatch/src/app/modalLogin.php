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
                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <?= htmlspecialchars($_SESSION['login_error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <form id="loginForm" action="<?= CONTROLLER_LOGIN ?>" method="POST">
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
                                autocomplete="email"
                            />
                        </div>
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
                                autocomplete="current-password"
                            />
                        </div>
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
// Auto-abrir modal si hay error de login
document.addEventListener('DOMContentLoaded', function() {
    <?php if (isset($_SESSION['login_error'])): ?>
        var loginModal = new bootstrap.Modal(document.getElementById('modalLogin'));
        loginModal.show();
    <?php endif; ?>
    
    // Auto-focus en el campo de email cuando se abre el modal
    var modalLogin = document.getElementById('modalLogin');
    modalLogin.addEventListener('shown.bs.modal', function () {
        document.getElementById('loginEmail').focus();
    });
});
</script>
