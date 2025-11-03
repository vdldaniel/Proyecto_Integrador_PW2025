/**
 * Funcionalidad para la página de exploración de canchas
 * Maneja búsqueda, filtros, vista de mapa y interacciones
 */

let map;
let markersGroup;
let vistaActual = 'listado'; // 'listado' o 'mapa'

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar funcionalidad
    inicializarBusqueda();
    inicializarFiltros();
    inicializarCambioVista();
    inicializarTooltips();
});

/**
 * Inicializa la funcionalidad de búsqueda
 */
function inicializarBusqueda() {
    const campoBusqueda = document.getElementById('busquedaCanchas');
    const btnLimpiarBusqueda = document.getElementById('limpiarBusqueda');

    // Búsqueda en tiempo real
    campoBusqueda.addEventListener('input', function() {
        const termino = this.value.toLowerCase().trim();
        filtrarCanchas();
    });

    // Limpiar búsqueda
    btnLimpiarBusqueda.addEventListener('click', function() {
        campoBusqueda.value = '';
        limpiarTodosFiltros();
        filtrarCanchas();
    });
}

/**
 * Inicializa la funcionalidad de filtros
 */
function inicializarFiltros() {
    const btnAplicarFiltros = document.getElementById('aplicarFiltros');
    const btnLimpiarFiltros = document.getElementById('limpiarFiltros');
    const btnLimpiarFiltrosModal = document.getElementById('limpiarFiltrosModal');

    // Aplicar filtros
    btnAplicarFiltros.addEventListener('click', function() {
        aplicarFiltros();
    });

    // Limpiar filtros (botón principal)
    btnLimpiarFiltros.addEventListener('click', function() {
        limpiarTodosFiltros();
        filtrarCanchas();
    });

    // Limpiar filtros (modal)
    btnLimpiarFiltrosModal.addEventListener('click', function() {
        limpiarFormularioFiltros();
        actualizarBadgesFiltros();
    });
}

/**
 * Inicializa la funcionalidad de cambio de vista
 */
function inicializarCambioVista() {
    const btnCambiarVista = document.getElementById('btnCambiarVista');
    
    if (btnCambiarVista) {
        btnCambiarVista.addEventListener('click', function() {
            if (vistaActual === 'listado') {
                cambiarAVistaMapa();
            } else {
                cambiarAVistaListado();
            }
        });
    }
}

/**
 * Cambia a la vista de mapa
 */
function cambiarAVistaMapa() {
    const vistaListado = document.getElementById('vistaListado');
    const vistaMapa = document.getElementById('vistaMapa');
    const btnCambiarVista = document.getElementById('btnCambiarVista');
    const iconoVista = document.getElementById('iconoVista');
    const textoVista = document.getElementById('textoVista');

    if (!vistaListado || !vistaMapa) return;

    // Animación de transición
    vistaListado.style.opacity = '0';
    
    setTimeout(() => {
        vistaListado.classList.add('d-none');
        vistaMapa.classList.remove('d-none');
        
        // Cambiar botón
        if (iconoVista) iconoVista.className = 'bi bi-list-ul';
        if (textoVista) textoVista.textContent = 'Listado';
        
        // Inicializar mapa si no existe
        if (!map) {
            inicializarMapa();
        }
        
        // Actualizar marcadores según filtros actuales
        actualizarMarcadoresMapa();
        
        vistaActual = 'mapa';
        vistaMapa.style.opacity = '1';
    }, 150);
}

/**
 * Cambia a la vista de listado
 */
function cambiarAVistaListado() {
    const vistaListado = document.getElementById('vistaListado');
    const vistaMapa = document.getElementById('vistaMapa');
    const btnCambiarVista = document.getElementById('btnCambiarVista');
    const iconoVista = document.getElementById('iconoVista');
    const textoVista = document.getElementById('textoVista');

    if (!vistaListado || !vistaMapa) return;

    // Animación de transición
    vistaMapa.style.opacity = '0';
    
    setTimeout(() => {
        vistaMapa.classList.add('d-none');
        vistaListado.classList.remove('d-none');
        
        // Cambiar botón
        if (iconoVista) iconoVista.className = 'bi bi-map';
        if (textoVista) textoVista.textContent = 'Mapa';
        
        vistaActual = 'listado';
        vistaListado.style.opacity = '1';
    }, 150);
}

/**
 * Inicializa el mapa con Leaflet
 */
function inicializarMapa() {
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;

    // Inicializar mapa centrado en Buenos Aires
    map = L.map('map').setView([-34.6037, -58.3816], 12);

    // Agregar tiles de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    // Crear grupo de marcadores
    markersGroup = L.layerGroup().addTo(map);

    // Ajustar tamaño del mapa después de que se muestre
    setTimeout(() => {
        map.invalidateSize();
    }, 100);
}

/**
 * Actualiza los marcadores del mapa según las canchas filtradas
 */
function actualizarMarcadoresMapa() {
    if (!map || !markersGroup) return;

    // Limpiar marcadores existentes
    markersGroup.clearLayers();

    // Datos de ejemplo de canchas con ubicaciones
    const canchasConUbicacion = [
        {
            id: 1,
            nombre: 'Mega Fútbol Central',
            ubicacion: 'Palermo, Buenos Aires',
            lat: -34.5875,
            lng: -58.3974,
            tipo: 'Fútbol 5',
            superficie: 'Sintético',
            precio: '$2500/h',
            disponible: true,
            rating: 4.2
        },
        {
            id: 2,
            nombre: 'Deportivo San Lorenzo',
            ubicacion: 'San Telmo, Buenos Aires',
            lat: -34.6294,
            lng: -58.3686,
            tipo: 'Fútbol 7',
            superficie: 'Césped natural',
            precio: '$3200/h',
            disponible: false,
            rating: 4.8
        },
        {
            id: 3,
            nombre: 'Futsal Elite',
            ubicacion: 'Recoleta, Buenos Aires',
            lat: -34.5922,
            lng: -58.3817,
            tipo: 'Fútbol Sala',
            superficie: 'Parquet',
            precio: '$2800/h',
            disponible: true,
            rating: 4.5
        }
    ];

    // Agregar marcadores para cada cancha
    canchasConUbicacion.forEach(cancha => {
        // Determinar color del marcador según disponibilidad
        const colorIcon = cancha.disponible ? 'green' : 'red';

        // Crear icono personalizado
        const customIcon = L.divIcon({
            html: `<div style="background-color: ${colorIcon}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.4);"></div>`,
            iconSize: [20, 20],
            className: 'custom-div-icon'
        });

        // Crear popup con información de la cancha
        const popupContent = `
            <div class="popup-cancha">
                <h6>${cancha.nombre}</h6>
                <p class="mb-2"><strong>${cancha.ubicacion}</strong></p>
                <div class="mb-2">
                    <span class="badge bg-success">${cancha.tipo}</span>
                    <span class="badge bg-info">${cancha.superficie}</span>
                </div>
                <p class="mb-1"><strong>${cancha.precio}</strong></p>
                <div class="mb-2">
                    <div class="text-warning">
                        ${'★'.repeat(Math.floor(cancha.rating))}${'☆'.repeat(5-Math.floor(cancha.rating))}
                        <small class="text-muted">(${cancha.rating})</small>
                    </div>
                </div>
                <p class="mb-2">
                    <span class="badge ${cancha.disponible ? 'bg-success' : 'bg-danger'}">
                        ${cancha.disponible ? 'Disponible' : 'No disponible'}
                    </span>
                </p>
                <button class="btn btn-sm btn-primary" onclick="verDetalleCancha(${cancha.id})">
                    Ver detalles
                </button>
            </div>
        `;

        // Crear marcador y agregarlo al mapa
        const marker = L.marker([cancha.lat, cancha.lng], { icon: customIcon })
            .bindPopup(popupContent)
            .addTo(markersGroup);
    });

    // Ajustar vista para mostrar todos los marcadores
    if (canchasConUbicacion.length > 0) {
        const group = new L.featureGroup(markersGroup.getLayers());
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

/**
 * Inicializa tooltips de Bootstrap
 */
function inicializarTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Aplica los filtros seleccionados
 */
function aplicarFiltros() {
    const filtrosSeleccionados = obtenerFiltrosSeleccionados();
    actualizarBadgesFiltros(filtrosSeleccionados);
    filtrarCanchas();
    
    // Mostrar notificación
    mostrarToast('Filtros aplicados correctamente', 'success');
}

/**
 * Obtiene los filtros seleccionados del formulario
 */
function obtenerFiltrosSeleccionados() {
    const filtros = {
        ubicacion: document.getElementById('filtroUbicacion').value.trim(),
        tipos: [],
        superficies: []
    };

    // Tipos de cancha
    const checkboxesTipos = document.querySelectorAll('input[type="checkbox"][id^="futbol"], input[type="checkbox"][id="futbolSala"]');
    checkboxesTipos.forEach(checkbox => {
        if (checkbox.checked) {
            filtros.tipos.push(checkbox.value);
        }
    });

    // Superficies
    const checkboxesSuperficies = document.querySelectorAll('#modalFiltros input[type="checkbox"][id^="sintetico"], #modalFiltros input[type="checkbox"][id^="cemento"], #modalFiltros input[type="checkbox"][id^="parquet"], #modalFiltros input[type="checkbox"][id^="cespedNatural"]');
    checkboxesSuperficies.forEach(checkbox => {
        if (checkbox.checked) {
            filtros.superficies.push(checkbox.value);
        }
    });

    return filtros;
}

/**
 * Actualiza los badges de filtros activos
 */
function actualizarBadgesFiltros(filtros = null) {
    const contenedorBadges = document.getElementById('badgesFiltros');
    const contenedorFiltros = document.getElementById('filtrosActivos');
    
    if (!filtros) {
        filtros = obtenerFiltrosSeleccionados();
    }
    
    contenedorBadges.innerHTML = '';
    
    let hayFiltros = false;
    
    // Badge de ubicación
    if (filtros.ubicacion) {
        const badge = crearBadgeFiltro('ubicacion', `📍 ${filtros.ubicacion}`, 'primary');
        contenedorBadges.appendChild(badge);
        hayFiltros = true;
    }
    
    // Badges de tipos
    filtros.tipos.forEach(tipo => {
        const nombreTipo = obtenerNombreTipo(tipo);
        const badge = crearBadgeFiltro('tipo', `⚽ ${nombreTipo}`, 'success');
        contenedorBadges.appendChild(badge);
        hayFiltros = true;
    });
    
    // Badges de superficies
    filtros.superficies.forEach(superficie => {
        const nombreSuperficie = obtenerNombreSuperficie(superficie);
        const badge = crearBadgeFiltro('superficie', `🏟️ ${nombreSuperficie}`, 'info');
        contenedorBadges.appendChild(badge);
        hayFiltros = true;
    });
    
    // Mostrar/ocultar contenedor de filtros
    if (hayFiltros) {
        contenedorFiltros.classList.remove('d-none');
    } else {
        contenedorFiltros.classList.add('d-none');
    }
}

/**
 * Crea un badge de filtro con botón de eliminar
 */
function crearBadgeFiltro(tipo, texto, color) {
    const badge = document.createElement('span');
    badge.className = `badge bg-${color} me-1 mb-1`;
    badge.innerHTML = `
        ${texto}
        <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.6rem;" onclick="eliminarFiltro('${tipo}', this)"></button>
    `;
    return badge;
}

/**
 * Elimina un filtro específico
 */
function eliminarFiltro(tipo, elemento) {
    elemento.closest('.badge').remove();
    
    // Limpiar el filtro correspondiente en el formulario
    if (tipo === 'ubicacion') {
        document.getElementById('filtroUbicacion').value = '';
    }
    // Para tipos y superficies, sería necesario más lógica específica
    
    // Verificar si quedan filtros
    const badges = document.querySelectorAll('#badgesFiltros .badge');
    if (badges.length === 0) {
        document.getElementById('filtrosActivos').classList.add('d-none');
    }
    
    filtrarCanchas();
}

/**
 * Obtiene el nombre legible del tipo de cancha
 */
function obtenerNombreTipo(tipo) {
    const tipos = {
        'futbol-5': 'Fútbol 5',
        'futbol-7': 'Fútbol 7',
        'futbol-11': 'Fútbol 11',
        'futbol-sala': 'Fútbol Sala',
        'futbol-playa': 'Fútbol Playa'
    };
    return tipos[tipo] || tipo;
}

/**
 * Obtiene el nombre legible de la superficie
 */
function obtenerNombreSuperficie(superficie) {
    const superficies = {
        'sintetico': 'Sintético',
        'cemento': 'Cemento',
        'parquet': 'Parquet',
        'cesped-natural': 'Césped natural'
    };
    return superficies[superficie] || superficie;
}

/**
 * Filtra las canchas según búsqueda y filtros activos
 */
function filtrarCanchas() {
    const termino = document.getElementById('busquedaCanchas').value.toLowerCase().trim();
    const filtros = obtenerFiltrosSeleccionados();
    const canchas = document.querySelectorAll('.cancha-item');
    const estadoVacio = document.getElementById('estadoVacio');
    
    let canchasVisibles = 0;
    
    canchas.forEach(cancha => {
        const nombre = cancha.dataset.nombre.toLowerCase();
        const ubicacion = cancha.dataset.ubicacion.toLowerCase();
        const tipo = cancha.dataset.tipo;
        const superficie = cancha.dataset.superficie;
        
        let mostrar = true;
        
        // Filtro de búsqueda por texto
        if (termino && !nombre.includes(termino) && !ubicacion.includes(termino)) {
            mostrar = false;
        }
        
        // Filtro de ubicación
        if (filtros.ubicacion && !ubicacion.includes(filtros.ubicacion.toLowerCase())) {
            mostrar = false;
        }
        
        // Filtro de tipos
        if (filtros.tipos.length > 0 && !filtros.tipos.includes(tipo)) {
            mostrar = false;
        }
        
        // Filtro de superficies
        if (filtros.superficies.length > 0 && !filtros.superficies.includes(superficie)) {
            mostrar = false;
        }
        
        // Mostrar/ocultar cancha
        if (mostrar) {
            cancha.style.display = 'block';
            cancha.classList.remove('filtrado');
            cancha.classList.add('visible');
            canchasVisibles++;
        } else {
            cancha.style.display = 'none';
            cancha.classList.add('filtrado');
            cancha.classList.remove('visible');
        }
    });
    
    // Mostrar estado vacío si no hay resultados
    if (canchasVisibles === 0) {
        estadoVacio.classList.remove('d-none');
        actualizarMensajeVacio(termino, filtros);
    } else {
        estadoVacio.classList.add('d-none');
    }
    
    // Actualizar mapa si está visible
    if (vistaActual === 'mapa') {
        actualizarMarcadoresMapa();
    }
}

/**
 * Actualiza el mensaje del estado vacío
 */
function actualizarMensajeVacio(termino, filtros) {
    const estadoVacio = document.getElementById('estadoVacio');
    let mensaje = 'No se encontraron canchas';
    
    if (termino || filtros.ubicacion || filtros.tipos.length > 0 || filtros.superficies.length > 0) {
        mensaje = 'No hay canchas que coincidan con los criterios de búsqueda';
    }
    
    const h5 = estadoVacio.querySelector('h5');
    if (h5) {
        h5.textContent = mensaje;
    }
}

/**
 * Limpia todos los filtros
 */
function limpiarTodosFiltros() {
    document.getElementById('busquedaCanchas').value = '';
    limpiarFormularioFiltros();
    document.getElementById('filtrosActivos').classList.add('d-none');
    document.getElementById('badgesFiltros').innerHTML = '';
}

/**
 * Limpia el formulario de filtros
 */
function limpiarFormularioFiltros() {
    document.getElementById('filtroUbicacion').value = '';
    
    // Limpiar checkboxes
    const checkboxes = document.querySelectorAll('#modalFiltros input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

/**
 * Muestra un toast de notificación
 */
function mostrarToast(mensaje, tipo = 'info') {
    // Crear el toast si no existe
    let toastContainer = document.getElementById('toastContainer');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1200';
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = 'toast align-items-center text-bg-' + (tipo === 'success' ? 'success' : 'primary');
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${mensaje}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Eliminar el toast después de que se oculte
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}

/**
 * Funciones específicas para interacción con canchas
 */

/**
 * Ver detalles de una cancha
 */
function verDetalleCancha(idCancha) {
    console.log('Viendo detalles de cancha:', idCancha);
    // Aquí iría la lógica para redirigir o mostrar modal de detalles
    mostrarToast(`Cargando detalles de la cancha ${idCancha}...`, 'info');
    
    // Simulación de carga
    setTimeout(() => {
        // Redirigir a página de detalles de cancha
        // window.location.href = `cancha-detalle.php?id=${idCancha}`;
        mostrarToast(`Función de detalles en desarrollo`, 'info');
    }, 1000);
}

/**
 * Reservar una cancha
 */
function reservarCancha(idCancha) {
    console.log('Reservando cancha:', idCancha);
    // Aquí iría la lógica para abrir modal de reserva
    mostrarToast(`Iniciando proceso de reserva para cancha ${idCancha}`, 'success');
}

/**
 * Funciones de utilidad para desarrollo futuro
 */

/**
 * Simula carga de datos desde el servidor
 */
function cargarCanchas(filtros = {}) {
    // Esta función se conectaría con el backend para obtener canchas
    // basadas en los filtros aplicados
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve([]);
        }, 500);
    });
}

/**
 * Actualiza la paginación según los resultados
 */
function actualizarPaginacion(totalCanchas, canchasPorPagina = 12) {
    const totalPaginas = Math.ceil(totalCanchas / canchasPorPagina);
    // Lógica para actualizar los botones de paginación
}