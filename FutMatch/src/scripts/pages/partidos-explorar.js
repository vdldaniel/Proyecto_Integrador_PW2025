/**
 * Funcionalidad para la página de exploración de partidos
 * Maneja búsqueda, filtros, vista de mapa y interacciones
 */

let map;
let markersGroup;
let vistaActual = "listado"; // 'listado' o 'mapa'

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar funcionalidad
  inicializarBusqueda();
  inicializarFiltros();
  inicializarCambioVista();
  inicializarTooltips();
  inicializarEventListeners();
});

/**
 * Inicializa los event listeners para los botones de partidos
 */
function inicializarEventListeners() {
  // Event listeners para botones de ver detalle
  document.addEventListener("click", function (e) {
    if (e.target.closest(".ver-detalle-btn")) {
      e.preventDefault();
      const boton = e.target.closest(".ver-detalle-btn");
      const partidoId = boton.dataset.partidoId;
      const tipoPartido = boton.dataset.tipoPartido;

      if (tipoPartido === "equipo") {
        abrirModalUnirseEquipo(partidoId);
      } else {
        abrirModalSolicitarUnirse(partidoId);
      }
    }
  });
}

/**
 * Inicializa la funcionalidad de búsqueda
 */
function inicializarBusqueda() {
  const campoBusqueda = document.getElementById("busquedaPartidos");
  const btnLimpiarBusqueda = document.getElementById("limpiarBusqueda");

  // Búsqueda en tiempo real
  campoBusqueda.addEventListener("input", function () {
    const termino = this.value.toLowerCase().trim();
    filtrarPartidos();
  });

  // Limpiar búsqueda
  if (btnLimpiarBusqueda) {
    btnLimpiarBusqueda.addEventListener("click", function () {
      campoBusqueda.value = "";
      limpiarTodosFiltros();
      filtrarPartidos();
    });
  }
}

/**
 * Inicializa la funcionalidad de filtros
 */
function inicializarFiltros() {
  const btnAplicarFiltros = document.getElementById("aplicarFiltros");
  const btnLimpiarFiltros = document.getElementById("limpiarFiltros");
  const btnLimpiarFiltrosModal = document.getElementById("limpiarFiltrosModal");

  // Aplicar filtros
  btnAplicarFiltros.addEventListener("click", function () {
    aplicarFiltros();
  });

  // Limpiar filtros (botón principal)
  btnLimpiarFiltros.addEventListener("click", function () {
    limpiarTodosFiltros();
    filtrarPartidos();
  });

  // Limpiar filtros (modal)
  btnLimpiarFiltrosModal.addEventListener("click", function () {
    limpiarFormularioFiltros();
    actualizarBadgesFiltros();
  });
}

/**
 * Inicializa la funcionalidad de cambio de vista
 */
function inicializarCambioVista() {
  const btnCambiarVista = document.getElementById("btnCambiarVista");

  btnCambiarVista.addEventListener("click", function () {
    if (vistaActual === "listado") {
      cambiarAVistaMapa();
    } else {
      cambiarAVistaListado();
    }
  });
}

/**
 * Cambia a la vista de mapa
 */
function cambiarAVistaMapa() {
  const vistaListado = document.getElementById("vistaListado");
  const vistaMapa = document.getElementById("vistaMapa");
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  const iconoVista = document.getElementById("iconoVista");
  const textoVista = document.getElementById("textoVista");

  // Animación de transición
  vistaListado.style.opacity = "0";

  setTimeout(() => {
    vistaListado.classList.add("d-none");
    vistaMapa.classList.remove("d-none");

    // Cambiar botón
    iconoVista.className = "bi bi-list-ul";
    textoVista.textContent = "Listado";

    // Inicializar mapa si no existe
    if (!map) {
      inicializarMapa();
    }

    // Actualizar marcadores según filtros actuales
    actualizarMarcadoresMapa();

    vistaActual = "mapa";
    vistaMapa.style.opacity = "1";
  }, 150);
}

/**
 * Cambia a la vista de listado
 */
function cambiarAVistaListado() {
  const vistaListado = document.getElementById("vistaListado");
  const vistaMapa = document.getElementById("vistaMapa");
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  const iconoVista = document.getElementById("iconoVista");
  const textoVista = document.getElementById("textoVista");

  // Animación de transición
  vistaMapa.style.opacity = "0";

  setTimeout(() => {
    vistaMapa.classList.add("d-none");
    vistaListado.classList.remove("d-none");

    // Cambiar botón
    iconoVista.className = "bi bi-map";
    textoVista.textContent = "Mapa";

    vistaActual = "listado";
    vistaListado.style.opacity = "1";
  }, 150);
}

/**
 * Inicializa el mapa con Leaflet
 */
function inicializarMapa() {
  // Inicializar mapa centrado en Buenos Aires
  map = L.map("map").setView([-34.6037, -58.3816], 12);

  // Agregar tiles de OpenStreetMap
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
  }).addTo(map);

  // Crear grupo de marcadores
  markersGroup = L.layerGroup().addTo(map);

  // Ajustar tamaño del mapa después de que se muestre
  setTimeout(() => {
    map.invalidateSize();
  }, 100);
}

/**
 * Actualiza los marcadores del mapa según los partidos filtrados
 */
function actualizarMarcadoresMapa() {
  if (!map || !markersGroup) return;

  // Limpiar marcadores existentes
  markersGroup.clearLayers();

  // Datos de ejemplo de partidos con ubicaciones
  const partidosConUbicacion = [
    {
      id: 1,
      nombre: "Partido amistoso - Fútbol 5",
      cancha: "MegaFutbol Llavallol",
      lat: -34.6922,
      lng: -58.4517,
      fecha: "Hoy, 27 de octubre",
      hora: "17:00 - 18:00",
      jugadores: "4/10",
      tipo: "Fútbol 5",
      genero: "Masculino",
    },
    {
      id: 2,
      nombre: "Torneo relámpago - Fútbol 7",
      cancha: "Deportivo San Lorenzo",
      lat: -34.6294,
      lng: -58.3686,
      fecha: "Mañana, 28 de octubre",
      hora: "19:00 - 21:00",
      jugadores: "Completo",
      tipo: "Fútbol 7",
      genero: "Mixto",
    },
    {
      id: 3,
      nombre: "Fútbol femenino competitivo",
      cancha: "Futsal Elite",
      lat: -34.5875,
      lng: -58.3974,
      fecha: "Viernes, 29 de octubre",
      hora: "20:30 - 22:00",
      jugadores: "6/10",
      tipo: "Fútbol Sala",
      genero: "Femenino",
    },
  ];

  // Agregar marcadores para cada partido
  partidosConUbicacion.forEach((partido) => {
    // Determinar color del marcador según disponibilidad
    let colorIcon = "green";
    if (partido.jugadores === "Completo") {
      colorIcon = "red";
    } else if (partido.jugadores.includes("/")) {
      const [actual, total] = partido.jugadores.split("/").map(Number);
      if (actual / total > 0.8) {
        colorIcon = "orange";
      }
    }

    // Crear icono personalizado
    const customIcon = L.divIcon({
      html: `<div style="background-color: ${colorIcon}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 1px 3px rgba(0,0,0,0.4);"></div>`,
      iconSize: [20, 20],
      className: "custom-div-icon",
    });

    // Crear popup con información del partido
    const popupContent = `
            <div class="popup-partido">
                <h6>${partido.nombre}</h6>
                <p class="mb-2"><strong>${partido.cancha}</strong></p>
                <p class="mb-1"><i class="bi bi-calendar-event"></i> ${
                  partido.fecha
                }</p>
                <p class="mb-1"><i class="bi bi-clock"></i> ${partido.hora}</p>
                <div class="mb-2">
                    <span class="badge text-bg-dark">${partido.tipo}</span>
                    <span class="badge text-bg-dark">${partido.genero}</span>
                </div>
                <p class="mb-1"><strong>${partido.precio}</strong></p>
                <p class="mb-2">Jugadores: ${partido.jugadores}</p>
                <button class="btn btn-sm btn-primary ver-detalle-btn" 
                        data-partido-id="${partido.id}" 
                        data-tipo-partido="${
                          partido.tipo_requiere_equipo ? "equipo" : "individual"
                        }">
                    Ver detalles
                </button>
            </div>
        `;

    // Crear marcador y agregarlo al mapa
    const marker = L.marker([partido.lat, partido.lng], { icon: customIcon })
      .bindPopup(popupContent)
      .addTo(markersGroup);
  });

  // Ajustar vista para mostrar todos los marcadores
  if (partidosConUbicacion.length > 0) {
    const group = new L.featureGroup(markersGroup.getLayers());
    map.fitBounds(group.getBounds().pad(0.1));
  }
}

/**
 * Inicializa tooltips de Bootstrap
 */
function inicializarTooltips() {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
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
  filtrarPartidos();

  // Si estamos en vista mapa, actualizar marcadores
  if (vistaActual === "mapa") {
    actualizarMarcadoresMapa();
  }

  // Mostrar notificación
  mostrarToast("Filtros aplicados correctamente", "success");
}

/**
 * Obtiene los filtros seleccionados del formulario
 */
function obtenerFiltrosSeleccionados() {
  const filtros = {
    ubicacion: document.getElementById("filtroUbicacion").value.trim(),
    fecha: document.getElementById("filtroFecha").value,
    tipos: [],
    generos: [],
  };

  // Tipos de partido
  const checkboxesTipos = document.querySelectorAll(
    '#modalFiltros input[type="checkbox"][id*="Partido"]'
  );
  checkboxesTipos.forEach((checkbox) => {
    if (checkbox.checked) {
      filtros.tipos.push(checkbox.value);
    }
  });

  // Géneros
  const checkboxesGeneros = document.querySelectorAll(
    '#modalFiltros input[type="checkbox"][id*="genero"]'
  );
  checkboxesGeneros.forEach((checkbox) => {
    if (checkbox.checked) {
      filtros.generos.push(checkbox.value);
    }
  });

  return filtros;
}

/**
 * Actualiza los badges de filtros activos
 */
function actualizarBadgesFiltros(filtros = null) {
  const contenedorBadges = document.getElementById("badgesFiltros");
  const contenedorFiltros = document.getElementById("filtrosActivos");

  if (!filtros) {
    filtros = obtenerFiltrosSeleccionados();
  }

  contenedorBadges.innerHTML = "";

  let hayFiltros = false;

  // Badge de ubicación
  if (filtros.ubicacion) {
    const badge = crearBadgeFiltro(
      "ubicacion",
      `📍 ${filtros.ubicacion}`,
      "primary"
    );
    contenedorBadges.appendChild(badge);
    hayFiltros = true;
  }

  // Badge de fecha
  if (filtros.fecha) {
    const fechaFormateada = new Date(filtros.fecha).toLocaleDateString("es-ES");
    const badge = crearBadgeFiltro("fecha", `📅 ${fechaFormateada}`, "info");
    contenedorBadges.appendChild(badge);
    hayFiltros = true;
  }

  // Badges de tipos
  filtros.tipos.forEach((tipo) => {
    const nombreTipo = obtenerNombreTipo(tipo);
    const badge = crearBadgeFiltro("tipo", `⚽ ${nombreTipo}`, "success");
    contenedorBadges.appendChild(badge);
    hayFiltros = true;
  });

  // Badges de géneros
  filtros.generos.forEach((genero) => {
    const nombreGenero = obtenerNombreGenero(genero);
    const badge = crearBadgeFiltro("genero", `👥 ${nombreGenero}`, "secondary");
    contenedorBadges.appendChild(badge);
    hayFiltros = true;
  });

  // Mostrar/ocultar contenedor de filtros
  if (hayFiltros) {
    contenedorFiltros.classList.remove("d-none");
  } else {
    contenedorFiltros.classList.add("d-none");
  }
}

/**
 * Crea un badge de filtro con botón de eliminar
 */
function crearBadgeFiltro(tipo, texto, color) {
  const badge = document.createElement("span");
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
  elemento.closest(".badge").remove();

  // Limpiar el filtro correspondiente en el formulario
  if (tipo === "ubicacion") {
    document.getElementById("filtroUbicacion").value = "";
  } else if (tipo === "fecha") {
    document.getElementById("filtroFecha").value = "";
  }

  // Verificar si quedan filtros
  const badges = document.querySelectorAll("#badgesFiltros .badge");
  if (badges.length === 0) {
    document.getElementById("filtrosActivos").classList.add("d-none");
  }

  filtrarPartidos();

  // Actualizar mapa si está visible
  if (vistaActual === "mapa") {
    actualizarMarcadoresMapa();
  }
}

/**
 * Obtiene el nombre legible del tipo de partido
 */
function obtenerNombreTipo(tipo) {
  const tipos = {
    "futbol-5": "Fútbol 5",
    "futbol-7": "Fútbol 7",
    "futbol-11": "Fútbol 11",
    "futbol-sala": "Fútbol Sala",
  };
  return tipos[tipo] || tipo;
}

/**
 * Obtiene el nombre legible del género
 */
function obtenerNombreGenero(genero) {
  const generos = {
    masculino: "Masculino",
    femenino: "Femenino",
    mixto: "Mixto",
  };
  return generos[genero] || genero;
}

/**
 * Filtra los partidos según búsqueda y filtros activos
 */
function filtrarPartidos() {
  const termino = document
    .getElementById("busquedaPartidos")
    .value.toLowerCase()
    .trim();
  const filtros = obtenerFiltrosSeleccionados();
  const partidos = document.querySelectorAll(".partido-item");
  const estadoVacio = document.getElementById("estadoVacio");

  let partidosVisibles = 0;

  partidos.forEach((partido) => {
    const nombre = partido.dataset.nombre.toLowerCase();
    const ubicacion = partido.dataset.ubicacion.toLowerCase();
    const tipo = partido.dataset.tipo;
    const genero = partido.dataset.genero;

    let mostrar = true;

    // Filtro de búsqueda por texto
    if (termino && !nombre.includes(termino) && !ubicacion.includes(termino)) {
      mostrar = false;
    }

    // Filtro de ubicación
    if (
      filtros.ubicacion &&
      !ubicacion.includes(filtros.ubicacion.toLowerCase())
    ) {
      mostrar = false;
    }

    // Filtro de tipos
    if (filtros.tipos.length > 0 && !filtros.tipos.includes(tipo)) {
      mostrar = false;
    }

    // Filtro de géneros
    if (filtros.generos.length > 0 && !filtros.generos.includes(genero)) {
      mostrar = false;
    }

    // Mostrar/ocultar partido
    if (mostrar) {
      partido.style.display = "block";
      partido.classList.remove("filtrado");
      partido.classList.add("visible");
      partidosVisibles++;
    } else {
      partido.style.display = "none";
      partido.classList.add("filtrado");
      partido.classList.remove("visible");
    }
  });

  // Mostrar estado vacío si no hay resultados
  if (partidosVisibles === 0) {
    estadoVacio.classList.remove("d-none");
    actualizarMensajeVacio(termino, filtros);
  } else {
    estadoVacio.classList.add("d-none");
  }
}

/**
 * Actualiza el mensaje del estado vacío
 */
function actualizarMensajeVacio(termino, filtros) {
  const estadoVacio = document.getElementById("estadoVacio");
  let mensaje = "No se encontraron partidos";

  if (
    termino ||
    filtros.ubicacion ||
    filtros.fecha ||
    filtros.tipos.length > 0 ||
    filtros.generos.length > 0
  ) {
    mensaje = "No hay partidos que coincidan con los criterios de búsqueda";
  }

  const h5 = estadoVacio.querySelector("h5");
  if (h5) {
    h5.textContent = mensaje;
  }
}

/**
 * Limpia todos los filtros
 */
function limpiarTodosFiltros() {
  document.getElementById("busquedaPartidos").value = "";
  limpiarFormularioFiltros();
  document.getElementById("filtrosActivos").classList.add("d-none");
  document.getElementById("badgesFiltros").innerHTML = "";
}

/**
 * Limpia el formulario de filtros
 */
function limpiarFormularioFiltros() {
  document.getElementById("filtroUbicacion").value = "";
  document.getElementById("filtroFecha").value = "";

  // Limpiar checkboxes
  const checkboxes = document.querySelectorAll(
    '#modalFiltros input[type="checkbox"]'
  );
  checkboxes.forEach((checkbox) => {
    checkbox.checked = false;
  });
}

/**
 * Muestra un toast de notificación
 */
function mostrarToast(mensaje, tipo = "info") {
  // Crear el toast si no existe
  let toastContainer = document.getElementById("toastContainer");
  if (!toastContainer) {
    toastContainer = document.createElement("div");
    toastContainer.id = "toastContainer";
    toastContainer.className = "toast-container position-fixed top-0 end-0 p-3";
    toastContainer.style.zIndex = "1200";
    document.body.appendChild(toastContainer);
  }

  const toast = document.createElement("div");
  toast.className =
    "toast align-items-center text-bg-" +
    (tipo === "success" ? "success" : "primary");
  toast.setAttribute("role", "alert");
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
  toast.addEventListener("hidden.bs.toast", function () {
    toast.remove();
  });
}

/**
 * Funciones específicas para interacción con partidos
 */

/**
 * Ver detalles de un partido
 */
function abrirModalSolicitarUnirse(idPartido) {
  console.log("Abriendo modal para solicitar unirse:", idPartido);

  // Cargar información del partido
  const infoPartido = obtenerInfoPartido(idPartido);

  // Cargar información en el modal con mejor estructura
  const contenedorInfo = document.getElementById("infoPartidoSolicitar");
  contenedorInfo.innerHTML = `
        <div class="card border-light">
            <div class="card-body">
                <h6 class="card-title mb-4">${infoPartido.titulo}</h6>
                
                <div class="row mb-3 align-items-center">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-geo-alt me-2"></i>Cancha:
                    </div>
                    <div class="col-5">${infoPartido.cancha}</div>
                    <div class="col-3 text-end">
                        <a href="/Proyecto_Integrador_PW2025/FutMatch/public/HTML/jugador/perfilCancha_Jugador.php?id=${infoPartido.canchaId}" 
                           class="btn btn-sm btn-dark">
                            <i class="bi bi-eye me-1"></i>Ver cancha
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-calendar-event me-2"></i>Fecha:
                    </div>
                    <div class="col-8">${infoPartido.fecha}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-clock me-2"></i>Hora:
                    </div>
                    <div class="col-8">${infoPartido.hora}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-people me-2"></i>Jugadores:
                    </div>
                    <div class="col-8">${infoPartido.jugadores}</div>
                </div>
                
                <div class="row mb-3 align-items-center">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-person me-2"></i>Organizador:
                    </div>
                    <div class="col-5">${infoPartido.organizador}</div>
                    <div class="col-3 text-end">
                        <a href="/Proyecto_Integrador_PW2025/FutMatch/public/HTML/jugador/perfilJugador.php?id=${infoPartido.organizadorId}" 
                           class="btn btn-sm btn-dark">
                            <i class="bi bi-eye me-1"></i>Ver perfil
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">Modalidad:</div>
                    <div class="col-8">${infoPartido.badges}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">Descripción:</div>
                    <div class="col-8 text-muted">${infoPartido.descripcion}</div>
                </div>
            </div>
        </div>
    `;

  // Configurar botón de solicitar unirse
  const btnSolicitarUnirse = document.getElementById("btnSolicitarUnirse");
  btnSolicitarUnirse.onclick = () => {
    // Simular envío de solicitud
    btnSolicitarUnirse.innerHTML =
      '<i class="bi spinner-border spinner-border-sm me-2"></i>Enviando...';
    btnSolicitarUnirse.disabled = true;

    setTimeout(() => {
      document
        .getElementById("modalSolicitarUnirse")
        .querySelector(".btn-close")
        .click();
      mostrarToast("¡Solicitud enviada con éxito!", "success");

      // Resetear botón
      btnSolicitarUnirse.innerHTML =
        '<i class="bi bi-send me-2"></i>Solicitar participación';
      btnSolicitarUnirse.disabled = false;
    }, 2000);
  };

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalSolicitarUnirse")
  );
  modal.show();
}

/**
 * Abre el modal para unirse como equipo
 */
function abrirModalUnirseEquipo(idPartido) {
  console.log("Abriendo modal para unirse como equipo:", idPartido);

  // Cargar información del partido
  const infoPartido = obtenerInfoPartido(idPartido);

  // Cargar información en el modal con la misma estructura que el modal individual
  const contenedorInfo = document.getElementById("infoPartidoEquipo");
  contenedorInfo.innerHTML = `
        <div class="card border-light">
            <div class="card-body">
                <h6 class="card-title mb-4">${infoPartido.titulo}</h6>
                
                <div class="row mb-3 align-items-center">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-geo-alt me-2"></i>Cancha:
                    </div>
                    <div class="col-5">${infoPartido.cancha}</div>
                    <div class="col-3 text-end">
                        <a href="/Proyecto_Integrador_PW2025/FutMatch/public/HTML/jugador/perfilCancha_Jugador.php?id=${
                          infoPartido.canchaId
                        }" 
                           class="btn btn-sm btn-dark">
                            <i class="bi bi-eye me-1"></i>Ver cancha
                        </a>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-calendar-event me-2"></i>Fecha:
                    </div>
                    <div class="col-8">${infoPartido.fecha}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-clock me-2"></i>Hora:
                    </div>
                    <div class="col-8">${infoPartido.hora}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">
                        <i class="bi bi-trophy me-2"></i>Estado:
                    </div>
                    <div class="col-8">${
                      infoPartido.equipos || "Buscando equipos"
                    }</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-4 text-primary fw-bold">Modalidad:</div>
                    <div class="col-8">${infoPartido.badges}</div>
                </div>
            </div>
        </div>
    `;

  // Cargar información del equipo anfitrión
  const equipoAnfitrionInfo = document.getElementById("equipoAnfitrionInfo");
  const equipoAnfitrion = {
    nombre: infoPartido.equipoAnfitrion || "Los Leones FC",
    partidosGanados: 15,
    partidosJugados: 23,
    integrantes: 12,
    id: 1,
  };

  equipoAnfitrionInfo.innerHTML = `
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="card-title mb-2">${equipoAnfitrion.nombre}</h6>
                        <div class="row text-sm">
                            <div class="col-6">
                                <span class="text-muted">Partidos ganados:</span><br>
                                <strong class="text-success">${equipoAnfitrion.partidosGanados}/${equipoAnfitrion.partidosJugados}</strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted">Integrantes:</span><br>
                                <strong class="text-info">${equipoAnfitrion.integrantes} jugadores</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <a href="/Proyecto_Integrador_PW2025/FutMatch/public/HTML/jugador/perfilEquipo_Jugador.php?id=${equipoAnfitrion.id}" 
                           class="btn btn-sm btn-dark">
                            <i class="bi bi-eye me-1"></i>Ver equipo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;

  // Configurar botón solicitar
  const btnSolicitarEquipo = document.getElementById("btnSolicitarEquipo");
  btnSolicitarEquipo.onclick = () => {
    const equipoSeleccionado = document.getElementById("selectorEquipo").value;
    const equipoNombre =
      document.getElementById("selectorEquipo").selectedOptions[0]?.text;

    if (!equipoSeleccionado) {
      alert("Por favor selecciona un equipo para participar");
      return;
    }

    // Simular envío de solicitud
    btnSolicitarEquipo.innerHTML =
      '<i class="bi spinner-border spinner-border-sm me-2"></i>Enviando...';
    btnSolicitarEquipo.disabled = true;

    setTimeout(() => {
      document
        .getElementById("modalUnirseEquipo")
        .querySelector(".btn-close")
        .click();
      mostrarToast(
        `¡Solicitud enviada con éxito! ${equipoNombre} ha sido inscrito al partido.`,
        "success"
      );

      // Resetear formulario y botón
      document.getElementById("selectorEquipo").value = "";
      btnSolicitarEquipo.innerHTML =
        '<i class="bi bi-send me-2"></i>Solicitar participación';
      btnSolicitarEquipo.disabled = false;
    }, 2000);
  };

  // Mostrar modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalUnirseEquipo")
  );
  modal.show();
}

/**
 * Obtiene información simulada de un partido
 */
function obtenerInfoPartido(idPartido) {
  const partidos = {
    1: {
      titulo: "Partido amistoso - Fútbol 5",
      cancha: "MegaFutbol Llavallol",
      canchaId: 1,
      fecha: "Hoy, 27 de octubre",
      hora: "17:00 - 18:00 hs",
      jugadores: "4/10 jugadores",
      organizador: "Juan Pérez",
      organizadorId: 1,
      descripcion:
        "Partido amistoso para pasar un buen rato. Todos los niveles son bienvenidos. Se juega con reglas estándar de fútbol 5.",
      badges:
        '<span class="badge text-bg-dark me-1">Fútbol 5</span><span class="badge text-bg-dark me-1">Masculino</span><span class="badge text-bg-dark">4/10 jugadores</span>',
    },
    2: {
      titulo: "Torneo relámpago - Fútbol 7",
      cancha: "Deportivo San Lorenzo",
      canchaId: 2,
      fecha: "Mañana, 28 de octubre",
      hora: "19:00 - 21:00 hs",
      equipos: "Buscando equipos",
      organizador: "Carlos Martínez",
      organizadorId: 2,
      equipoAnfitrion: "Los Leones FC",
      descripcion:
        "Torneo relámpago por equipos. Se buscan equipos completos para participar en un emocionante torneo de fútbol 7.",
      badges:
        '<span class="badge text-bg-dark me-1">Fútbol 7</span><span class="badge text-bg-dark me-1">Mixto</span><span class="badge text-bg-dark">Buscando equipos</span>',
    },
    3: {
      titulo: "Fútbol femenino competitivo",
      cancha: "Futsal Elite",
      canchaId: 3,
      fecha: "Viernes, 29 de octubre",
      hora: "20:30 - 22:00 hs",
      jugadores: "6/10 jugadoras",
      organizador: "María González",
      organizadorId: 3,
      descripcion:
        "Partido competitivo para jugadoras con experiencia. Buen nivel técnico requerido.",
      badges:
        '<span class="badge text-bg-dark me-1">Fútbol Sala</span><span class="badge text-bg-dark">Femenino</span><span class="badge text-bg-dark">6/10 jugadoras</span>',
    },
  };

  return partidos[idPartido] || partidos[1];
}
