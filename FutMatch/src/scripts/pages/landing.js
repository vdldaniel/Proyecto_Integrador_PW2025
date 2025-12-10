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

});
