/**
 * Torneos Explorar JavaScript
 * Funcionalidad para la página de exploración de torneos
 */

document.addEventListener("DOMContentLoaded", function () {
  // Inicializar funcionalidades
  inicializarBusqueda();
  inicializarFiltros();
  inicializarModales();
  inicializarCambioVista();
});

// Funcionalidad de búsqueda
function inicializarBusqueda() {
  const searchInput = document.getElementById("busquedaTorneos");
  if (!searchInput) return;

  searchInput.addEventListener("input", function (e) {
    filtrarTorneos(e.target.value);
  });

  // Botón limpiar búsqueda
  const limpiarBusqueda = document.getElementById("limpiarBusqueda");
  if (limpiarBusqueda) {
    limpiarBusqueda.addEventListener("click", function () {
      searchInput.value = "";
      filtrarTorneos("");
    });
  }
}

function filtrarTorneos(termino) {
  const listaTorneos = document.getElementById("listaTorneos");
  const estadoVacio = document.getElementById("estadoVacio");
  if (!listaTorneos) return;

  const torneos = listaTorneos.querySelectorAll(".torneo-item");
  const terminoLower = termino.toLowerCase();
  let torneosVisibles = 0;

  torneos.forEach((torneo) => {
    const nombre = torneo.getAttribute("data-nombre").toLowerCase();
    const ubicacion = torneo.getAttribute("data-ubicacion").toLowerCase();
    const tipo = torneo.getAttribute("data-tipo").toLowerCase();

    const esVisible =
      nombre.includes(terminoLower) ||
      ubicacion.includes(terminoLower) ||
      tipo.includes(terminoLower);

    if (esVisible) {
      torneo.style.display = "";
      torneosVisibles++;
    } else {
      torneo.style.display = "none";
    }
  });

  // Mostrar/ocultar estado vacío
  if (torneosVisibles === 0) {
    estadoVacio.classList.remove("d-none");
  } else {
    estadoVacio.classList.add("d-none");
  }
}

// Funcionalidad de filtros
function inicializarFiltros() {
  const btnAplicarFiltros = document.getElementById("btnAplicarFiltros");
  const btnLimpiarFiltros = document.getElementById("btnLimpiarFiltros");
  const limpiarFiltros = document.getElementById("limpiarFiltros");

  if (btnAplicarFiltros) {
    btnAplicarFiltros.addEventListener("click", aplicarFiltros);
  }

  if (btnLimpiarFiltros) {
    btnLimpiarFiltros.addEventListener("click", limpiarTodosFiltros);
  }

  if (limpiarFiltros) {
    limpiarFiltros.addEventListener("click", limpiarTodosFiltros);
  }
}

function aplicarFiltros() {
  const filtrosActivos = document.getElementById("filtrosActivos");
  const badgesFiltros = document.getElementById("badgesFiltros");

  // Obtener filtros seleccionados
  const estadosSeleccionados = Array.from(
    document.querySelectorAll('input[type="checkbox"]:checked')
  ).map((checkbox) => checkbox.value);

  const zonaSeleccionada = document.getElementById("filtroZona").value;
  const premioMinimo = document.getElementById("premioMinimo").value;
  const premioMaximo = document.getElementById("premioMaximo").value;

  // Limpiar badges anteriores
  badgesFiltros.innerHTML = "";

  // Crear badges para filtros activos
  let hayFiltrosActivos = false;

  if (estadosSeleccionados.length > 0) {
    estadosSeleccionados.forEach((estado) => {
      crearBadgeFiltro(estado, badgesFiltros);
      hayFiltrosActivos = true;
    });
  }

  if (zonaSeleccionada) {
    crearBadgeFiltro(`Zona: ${zonaSeleccionada}`, badgesFiltros);
    hayFiltrosActivos = true;
  }

  if (premioMinimo || premioMaximo) {
    const rangoPremio = `Premio: ${premioMinimo || "0"} - ${
      premioMaximo || "∞"
    }`;
    crearBadgeFiltro(rangoPremio, badgesFiltros);
    hayFiltrosActivos = true;
  }

  // Mostrar/ocultar sección de filtros activos
  if (hayFiltrosActivos) {
    filtrosActivos.classList.remove("d-none");
  } else {
    filtrosActivos.classList.add("d-none");
  }

  // Aplicar filtros a la lista
  filtrarPorFiltros(
    estadosSeleccionados,
    zonaSeleccionada,
    premioMinimo,
    premioMaximo
  );

  // Cerrar modal
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalFiltros")
  );
  if (modal) {
    modal.hide();
  }
}

function crearBadgeFiltro(texto, contenedor) {
  const badge = document.createElement("span");
  badge.className = "badge text-bg-dark me-1";
  badge.innerHTML = `${texto} <i class="bi bi-x-circle ms-1" style="cursor: pointer;" onclick="this.parentElement.remove()"></i>`;
  contenedor.appendChild(badge);
}

function filtrarPorFiltros(estados, zona, premioMin, premioMax) {
  const listaTorneos = document.getElementById("listaTorneos");
  const estadoVacio = document.getElementById("estadoVacio");
  if (!listaTorneos) return;

  const torneos = listaTorneos.querySelectorAll(".torneo-item");
  let torneosVisibles = 0;

  torneos.forEach((torneo) => {
    let cumpleFiltros = true;

    // Filtro por estado
    if (estados.length > 0) {
      const estadoTorneo = torneo.getAttribute("data-estado");
      if (!estados.includes(estadoTorneo)) {
        cumpleFiltros = false;
      }
    }

    // Filtro por zona
    if (zona) {
      const ubicacionTorneo = torneo
        .getAttribute("data-ubicacion")
        .toLowerCase();
      if (!ubicacionTorneo.includes(zona.toLowerCase())) {
        cumpleFiltros = false;
      }
    }

    // Mostrar/ocultar torneo
    if (cumpleFiltros) {
      torneo.style.display = "";
      torneosVisibles++;
    } else {
      torneo.style.display = "none";
    }
  });

  // Mostrar/ocultar estado vacío
  if (torneosVisibles === 0) {
    estadoVacio.classList.remove("d-none");
  } else {
    estadoVacio.classList.add("d-none");
  }
}

function limpiarTodosFiltros() {
  // Limpiar checkboxes
  document
    .querySelectorAll('#modalFiltros input[type="checkbox"]')
    .forEach((checkbox) => {
      checkbox.checked = false;
    });

  // Limpiar selects
  document.getElementById("filtroZona").value = "";

  // Limpiar inputs numéricos
  document.getElementById("premioMinimo").value = "";
  document.getElementById("premioMaximo").value = "";

  // Ocultar filtros activos
  document.getElementById("filtrosActivos").classList.add("d-none");

  // Mostrar todos los torneos
  document.querySelectorAll(".torneo-item").forEach((torneo) => {
    torneo.style.display = "";
  });

  // Ocultar estado vacío
  document.getElementById("estadoVacio").classList.add("d-none");
}

// Inicializar modales
function inicializarModales() {
  // Los modales se manejan automáticamente por Bootstrap
  console.log("Modales de torneos explorar inicializados");
}

// Funciones de navegación
function verDetalleTorneo(id) {
  // Redirigir al detalle del torneo
  window.location.href = `misTorneosDetalle_AdminCancha.php?id=${id}`;
}

function inscribirseTorneo(id) {
  // Mostrar modal de inscripción o redirigir
  alert(`Inscribiéndose al torneo ${id}...`);
  // Aquí iría la lógica de inscripción
}

// Funcionalidad de cambio de vista
function inicializarCambioVista() {
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  const vistaListado = document.getElementById("vistaListado");
  const vistaMapa = document.getElementById("vistaMapa");
  const iconoVista = document.getElementById("iconoVista");
  const textoVista = document.getElementById("textoVista");

  let vistaActual = "listado"; // Por defecto mostrar listado

  if (btnCambiarVista) {
    btnCambiarVista.addEventListener("click", function () {
      if (vistaActual === "listado") {
        // Cambiar a vista de mapa
        vistaListado.classList.add("d-none");
        vistaMapa.classList.remove("d-none");
        iconoVista.className = "bi bi-list-ul";
        textoVista.textContent = "Listado";
        vistaActual = "mapa";

        // Inicializar mapa si no está cargado
        if (!window.mapaInicializado) {
          inicializarMapa();
        }
      } else {
        // Cambiar a vista de listado
        vistaMapa.classList.add("d-none");
        vistaListado.classList.remove("d-none");
        iconoVista.className = "bi bi-map";
        textoVista.textContent = "Mapa";
        vistaActual = "listado";
      }
    });
  }
}

// Inicializar mapa
function inicializarMapa() {
  if (typeof L === "undefined") {
    console.error(
      "Leaflet no está cargado. Necesitas incluir la librería de Leaflet."
    );
    return;
  }

  const map = L.map("map").setView([-34.6118, -58.396], 12); // Buenos Aires

  // Capa de mapa
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
  }).addTo(map);

  // Marcadores de torneos
  const torneos = [
    {
      id: 1,
      nombre: "Copa FutMatch Verano 2025",
      ubicacion: "Mega Fútbol Central, Palermo",
      lat: -34.5731,
      lng: -58.4254,
      estado: "inscripciones-abiertas",
    },
    {
      id: 2,
      nombre: "Liga Amateur Primavera",
      ubicacion: "Deportivo San Lorenzo",
      lat: -34.622,
      lng: -58.3694,
      estado: "proximamente",
    },
    {
      id: 3,
      nombre: "Torneo Express Navideño",
      ubicacion: "Futsal Elite, Recoleta",
      lat: -34.5875,
      lng: -58.3974,
      estado: "inscripciones-cerradas",
    },
    {
      id: 4,
      nombre: "Copa Clausura 2025",
      ubicacion: "Centro Deportivo Norte",
      lat: -34.55,
      lng: -58.45,
      estado: "en-curso",
    },
    {
      id: 5,
      nombre: "Liga Femenina Metropolitana",
      ubicacion: "Cancha Municipal Villa Crespo",
      lat: -34.5989,
      lng: -58.4372,
      estado: "inscripciones-abiertas",
    },
    {
      id: 6,
      nombre: "Copa Juvenil Sub-21",
      ubicacion: "Cancha La Bombonera",
      lat: -34.6365,
      lng: -58.3647,
      estado: "inscripciones-abiertas",
    },
  ];

  torneos.forEach((torneo) => {
    let colorMarcador;
    switch (torneo.estado) {
      case "inscripciones-abiertas":
        colorMarcador = "green";
        break;
      case "inscripciones-cerradas":
        colorMarcador = "orange";
        break;
      case "en-curso":
        colorMarcador = "red";
        break;
      case "proximamente":
        colorMarcador = "blue";
        break;
      default:
        colorMarcador = "gray";
    }

    const marker = L.marker([torneo.lat, torneo.lng]).addTo(map);

    const popupContent = `
            <div>
                <h6>${torneo.nombre}</h6>
                <p><i class="bi bi-geo-alt"></i> ${torneo.ubicacion}</p>
                <button class="btn btn-primary btn-sm" onclick="verDetalleTorneo(${torneo.id})">
                    Ver detalles
                </button>
            </div>
        `;

    marker.bindPopup(popupContent);
  });

  window.mapaInicializado = true;
}
