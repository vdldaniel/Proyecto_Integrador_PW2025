/**
 * Funcionalidad de la página Torneo Detalle - Admin Cancha
 * Manejo de vistas de equipos, creación de foros y navegación
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Alternar vistas de equipos (tabla/tarjetas)
    const viewButtons = document.querySelectorAll('[data-view]');
    viewButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const view = this.dataset.view;
            
            // Cambiar botones activos
            viewButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Referencias a las vistas
            const tableView = document.getElementById('equipos-table-view');
            const cardsView = document.getElementById('equipos-cards-view');
            
            // Mostrar/ocultar vistas
            if (view === 'table' && tableView && cardsView) {
                tableView.classList.remove('d-none');
                cardsView.classList.add('d-none');
            } else if (view === 'cards' && tableView && cardsView) {
                tableView.classList.add('d-none');
                cardsView.classList.remove('d-none');
            }
        });
    });

    // Crear foro
    const btnCrearForo = document.getElementById('btnCrearForo');
    if (btnCrearForo) {
        btnCrearForo.addEventListener('click', function() {
            // Aquí iría la lógica para crear el foro
            alert('Foro creado exitosamente');
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalCrearForo'));
            if (modal) {
                modal.hide();
            }
        });
    }

    // Agregar interactividad a las tarjetas de foro
    const forumButtons = document.querySelectorAll('.card .btn');
    forumButtons.forEach(btn => {
        if (btn.textContent.trim() === 'Ver Foro') {
            btn.addEventListener('click', function() {
                // Aquí se redirigiría al foro específico
                alert('Redirigiendo al foro...');
            });
        }
    });

});