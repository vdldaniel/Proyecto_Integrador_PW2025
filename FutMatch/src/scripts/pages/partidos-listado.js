document.addEventListener("DOMContentLoaded", () => {

  // Modal para unirse a partido //
  const botonesUnirse = document.querySelectorAll('.btn-unirse'); //selecciona los botones "unirse"

  botonesUnirse.forEach(boton => { 
    boton.addEventListener('click', (e) => { 
      e.preventDefault();

      const modal = new bootstrap.Modal(document.getElementById('modalUnirse')); 
      modal.show(); //Muestra el modal
    });
  });
});
