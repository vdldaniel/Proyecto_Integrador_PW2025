/**
 * AUTH-REQUIRED.JS
 * Script para manejar acciones que requieren autenticación
 * Muestra modal de login cuando usuario guest intenta acceder a funciones protegidas
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Detectar si el usuario está logueado (desde atributo data-* en body)
    const isLoggedIn = document.body.dataset.loggedIn === 'true';
    
    // Si ya está logueado, no hacer nada
    if (isLoggedIn) {
        return;
    }
    
    /**
     * Interceptar clicks en elementos que requieren autenticación
     * Usar atributo data-requires-auth="true" en botones/links
     */
    const authRequiredElements = document.querySelectorAll('[data-requires-auth="true"]');
    
    authRequiredElements.forEach(element => {
        element.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Guardar la acción que el usuario quería hacer
            const targetUrl = this.href || this.dataset.targetUrl;
            const action = this.dataset.action || 'acceder a esta función';
            
            // Mostrar modal de login
            const modalLogin = new bootstrap.Modal(document.getElementById('modalLogin'));
            
            // Guardar URL de destino en el input hidden
            const redirectInput = document.getElementById('redirectUrl');
            if (redirectInput && targetUrl) {
                redirectInput.value = targetUrl;
            }
            
            // Opcional: Mostrar mensaje personalizado
            const modalTitle = document.querySelector('#modalLogin .modal-title');
            if (modalTitle) {
                modalTitle.innerHTML = `
                    <i class="bi bi-lock me-2"></i>
                    Inicia sesión para ${action}
                `;
            }
            
            // Abrir modal
            modalLogin.show();
        });
    });
    
    /**
     * Interceptar envíos de formularios que requieren autenticación
     */
    const authRequiredForms = document.querySelectorAll('form[data-requires-auth="true"]');
    
    authRequiredForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Guardar datos del formulario en sessionStorage
            const formData = new FormData(form);
            const formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            sessionStorage.setItem('pendingFormData', JSON.stringify(formObject));
            sessionStorage.setItem('pendingFormAction', form.action);
            
            // Mostrar modal de login
            const modalLogin = new bootstrap.Modal(document.getElementById('modalLogin'));
            modalLogin.show();
        });
    });
    
    /**
     * Restaurar formulario pendiente después de login exitoso
     */
    window.addEventListener('load', function() {
        const pendingFormData = sessionStorage.getItem('pendingFormData');
        const pendingFormAction = sessionStorage.getItem('pendingFormAction');
        
        if (pendingFormData && pendingFormAction && isLoggedIn) {
            // Usuario recién logueado, enviar formulario pendiente
            const formData = JSON.parse(pendingFormData);
            
            // Crear formulario temporal
            const tempForm = document.createElement('form');
            tempForm.method = 'POST';
            tempForm.action = pendingFormAction;
            
            // Añadir campos
            Object.keys(formData).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = formData[key];
                tempForm.appendChild(input);
            });
            
            // Añadir al DOM y enviar
            document.body.appendChild(tempForm);
            tempForm.submit();
            
            // Limpiar storage
            sessionStorage.removeItem('pendingFormData');
            sessionStorage.removeItem('pendingFormAction');
        }
    });
    
    /**
     * Mostrar tooltips en elementos protegidos (opcional)
     */
    authRequiredElements.forEach(element => {
        if (!element.hasAttribute('title')) {
            element.setAttribute('title', 'Necesitas iniciar sesión');
            element.setAttribute('data-bs-toggle', 'tooltip');
            element.setAttribute('data-bs-placement', 'top');
        }
    });
    
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

/**
 * Función helper para verificar autenticación desde otros scripts
 */
function requireAuth(message = 'Necesitas iniciar sesión') {
    const isLoggedIn = document.body.dataset.loggedIn === 'true';
    
    if (!isLoggedIn) {
        const modalLogin = new bootstrap.Modal(document.getElementById('modalLogin'));
        
        // Personalizar mensaje si se proporciona
        if (message) {
            const modalTitle = document.querySelector('#modalLogin .modal-title');
            if (modalTitle) {
                modalTitle.innerHTML = `<i class="bi bi-lock me-2"></i>${message}`;
            }
        }
        
        modalLogin.show();
        return false;
    }
    
    return true;
}

/**
 * EJEMPLOS DE USO:
 * 
 * 1. En HTML - Botón que requiere auth:
 *    <button data-requires-auth="true" 
 *            data-action="unirte al partido"
 *            data-target-url="/partidos/unirse/123">
 *        Unirse al partido
 *    </button>
 * 
 * 2. En HTML - Formulario que requiere auth:
 *    <form data-requires-auth="true" action="/equipos/crear" method="POST">
 *        ...
 *    </form>
 * 
 * 3. En JavaScript - Validar auth manualmente:
 *    document.getElementById('btnCrearEquipo').addEventListener('click', function() {
 *        if (!requireAuth('Inicia sesión para crear un equipo')) {
 *            return;
 *        }
 *        // Continuar con la acción...
 *    });
 * 
 * 4. En PHP - Añadir atributo data-logged-in al body:
 *    <body data-logged-in="<?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>">
 */
