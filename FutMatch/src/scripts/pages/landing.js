// Iniciar sesión
document.addEventListener('DOMContentLoaded', () => {
  const loginCard = document.getElementById('loginCard');
  const collapseEl = document.getElementById('loginCollapse');
  const emailInput = document.getElementById('email');

  // Instancia de Bootstrap Collapse (sin toggle automático)
  const bsCollapse = new bootstrap.Collapse(collapseEl, { toggle: false });

  // Alternar al click/touch en toda la tarjeta
  loginCard.addEventListener('click', (e) => {
    // Evita que clicks dentro del formulario vuelvan a cerrar
    if (collapseEl.contains(e.target)) return;

    const isOpen = collapseEl.classList.contains('show');
    isOpen ? bsCollapse.hide() : bsCollapse.show();
  });

  // Alternar al presionar Enter cuando la tarjeta tiene foco
  loginCard.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      const isOpen = collapseEl.classList.contains('show');
      isOpen ? bsCollapse.hide() : bsCollapse.show();
    }
  });

  // Sincroniza aria-expanded para el chevron/estilos
  collapseEl.addEventListener('show.bs.collapse', () => {
    loginCard.setAttribute('aria-expanded', 'true');
  });
  collapseEl.addEventListener('hide.bs.collapse', () => {
    loginCard.setAttribute('aria-expanded', 'false');
  });

  // focus mail
  collapseEl.addEventListener('shown.bs.collapse', () => {
    setTimeout(() => emailInput && emailInput.focus(), 50);
  });

  // posponemos la validación real hasta tener el back-end
  const form = document.getElementById('loginForm');

  if (form) {
    form.addEventListener('submit', (e) => {
      e.preventDefault(); // Evita validación real

      const email = (emailInput?.value || '').trim().toLowerCase();

      // Lógica de demo: depende del "usuario" ingresado
      if (email.includes('jugador')) {
        window.location.href = 'public/HTML/jugador/inicio-jugador.html';
      } 
      else if (email.includes('admin-cancha')) {
        window.location.href = 'public/HTML/admin-cancha/inicio-admin-cancha.html';
      } 
      else {
        alert("Para la demo, usá un usuario que contenga 'jugador' o 'admin-cancha'");
      }
    });
  }


});
