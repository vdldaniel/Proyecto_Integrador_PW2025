/**
 * Torneos Jugador JavaScript
 * Funcionalidad para la página de torneos del jugador
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidades
    inicializarBusqueda();
    inicializarModales();
});

// Funcionalidad de búsqueda
function inicializarBusqueda() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function(e) {
        filtrarTorneos(e.target.value);
    });
}

function filtrarTorneos(termino) {
    const torneosList = document.getElementById('torneosList');
    if (!torneosList) return;

    const torneos = torneosList.querySelectorAll('.col-12');
    const terminoLower = termino.toLowerCase();

    torneos.forEach(torneo => {
        const nombre = torneo.querySelector('h5').textContent.toLowerCase();
        const estado = torneo.querySelector('.badge').textContent.toLowerCase();
        const fecha = torneo.querySelector('small').textContent.toLowerCase();

        const esVisible = nombre.includes(terminoLower) || 
                         estado.includes(terminoLower) || 
                         fecha.includes(terminoLower);

        if (esVisible) {
            torneo.style.display = '';
        } else {
            torneo.style.display = 'none';
        }
    });
}

// Inicializar modales
function inicializarModales() {
    // Modal de historial se maneja automáticamente por Bootstrap
    console.log('Modales de torneos jugador inicializados');
}