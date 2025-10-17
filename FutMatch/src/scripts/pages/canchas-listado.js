document.addEventListener("DOMContentLoaded", () => {

  // Modal para crear reserva //
  const botonesReservar = document.querySelectorAll('.btn-crear-reserva'); //selecciona los botones "reserva"

  botonesReservar.forEach(boton => { 
    boton.addEventListener('click', (e) => { 
      e.preventDefault();

      const modal = new bootstrap.Modal(document.getElementById('modalGestionReserva')); 
      modal.show(); //Muestra el modal
    });
  });
});

