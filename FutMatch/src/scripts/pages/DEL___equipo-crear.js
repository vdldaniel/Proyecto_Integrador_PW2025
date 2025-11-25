document.addEventListener('DOMContentLoaded', () => {
  const minInput = document.getElementById('edadMinima');
  const maxInput = document.getElementById('edadMaxima');

  [minInput, maxInput].forEach(i => i && i.addEventListener('input', (e) => {
    if (e.target.value === '') return;
    // Clamp a >= 0 y entero
    let v = Math.floor(Number(e.target.value));
    if (isNaN(v)) v = '';
    if (v !== '') v = Math.max(0, v);
    e.target.value = v;
  }));

  // Validación simple antes de submit
  const form = document.getElementById('crearEquipoForm');
  if (form) form.addEventListener('submit', (ev) => {
    const a = Number(minInput.value || 0);
    const b = Number(maxInput.value || 0);
    if (a > b) {
      ev.preventDefault();
      minInput.setCustomValidity('La edad mínima no puede ser mayor que la máxima.');
      minInput.reportValidity();
    } else {
      minInput.setCustomValidity('');
    }
  });
});