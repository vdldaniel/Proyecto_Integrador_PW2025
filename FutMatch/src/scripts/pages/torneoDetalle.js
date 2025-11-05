/**
 * Funcionalidad para la página de detalles del torneo
 * Maneja interacciones específicas del torneo y bracket
 */

document.addEventListener('DOMContentLoaded', function() {
    inicializarEventListeners();
    inicializarCambioVista();
});

/**
 * Inicializa todos los event listeners
 */
function inicializarEventListeners() {
    // Event listeners para botones "Ver Partido" con stopPropagation
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ver-partido-btn')) {
            e.stopPropagation();
            const boton = e.target.closest('.ver-partido-btn');
            const partidoId = boton.dataset.partidoId || 'demo';
            verDetallePartido(partidoId);
            return;
        }
        
        // Event listeners para elementos clickable-team (team-box y winner-box)
        if (e.target.closest('.clickable-team')) {
            const elemento = e.target.closest('.clickable-team');
            const teamUrl = elemento.dataset.teamUrl;
            if (teamUrl) {
                window.location.href = teamUrl;
            }
        }
    });
}

/**
 * Inicializa el cambio de vista entre tabla y tarjetas en la sección equipos
 */
function inicializarCambioVista() {
    const btnTabla = document.querySelector('[data-view="table"]');
    const btnTarjetas = document.querySelector('[data-view="cards"]');
    const vistaTabla = document.getElementById('equipos-table-view');
    const vistaTarjetas = document.getElementById('equipos-cards-view');

    if (btnTabla && btnTarjetas) {
        btnTabla.addEventListener('click', function() {
            // Cambiar clases activas
            btnTabla.classList.add('active');
            btnTarjetas.classList.remove('active');
            
            // Cambiar vistas
            vistaTabla.classList.remove('d-none');
            vistaTarjetas.classList.add('d-none');
        });

        btnTarjetas.addEventListener('click', function() {
            // Cambiar clases activas
            btnTarjetas.classList.add('active');
            btnTabla.classList.remove('active');
            
            // Cambiar vistas
            vistaTarjetas.classList.remove('d-none');
            vistaTabla.classList.add('d-none');
        });
    }
}

/**
 * Muestra los detalles de un partido
 */
function verDetallePartido(partidoId) {
    console.log('Viendo detalles del partido:', partidoId);
    
    // Aquí se podría abrir un modal o redirigir a una página de detalle
    // Por ahora, mostrar un alert como demo
    alert(`Ver detalles del partido ${partidoId}. En una implementación real esto abriría un modal o nueva página.`);
}