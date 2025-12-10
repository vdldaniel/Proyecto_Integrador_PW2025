// Variables globales para paginación
let canchasPaginadas = [];
let paginaActual = 0;
const CANCHAS_POR_PAGINA = 6;

// Variables globales para mapa
let mapa = null;
let marcadores = [];
let todasLasCanchas = []; // Guardar todas las canchas sin paginar

window.onload = function () {
  cargarCanchasDisponibles();
  configurarEventos();
};

function configurarEventos() {
  // Evento para cambiar vista (Listado <-> Mapa)
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  if (btnCambiarVista) {
    btnCambiarVista.addEventListener("click", toggleVista);
  }

  // Evento para búsqueda
  const busquedaCanchas = document.getElementById("busquedaCanchas");
  if (busquedaCanchas) {
    busquedaCanchas.addEventListener("input", (e) => {
      filtrarCanchas(e.target.value);
    });
  }
}

async function cargarCanchasDisponibles() {
  try {
    const response = await fetch(GET_CANCHAS_DISPONIBLES_JUGADOR, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    let canchas = await response.json();

    //console.log("Canchas disponibles:", canchas);

    // Guardar todas las canchas para el mapa
    todasLasCanchas = canchas;

    // Dividir canchas en páginas
    let paginado = [[]];
    let pagina = 0;

    canchas.forEach((cancha) => {
      const paginaActualArray = paginado[pagina];

      // Si la página actual está llena, se crea recién ahí una nueva
      if (paginaActualArray.length === CANCHAS_POR_PAGINA) {
        paginado.push([]); // creás una nueva sola vez
        pagina++;
      }

      paginado[pagina].push(cancha);
    });

    // Guardar en variable global
    canchasPaginadas = paginado;
    paginaActual = 0;

    //console.log("Canchas paginadas:", paginado);

    // Renderizar la primera página
    if (canchasPaginadas.length > 0) {
      renderizarPaginaCanchas(canchasPaginadas[0], 0);
      actualizarControlesPaginacion();
    }
  } catch (error) {
    console.error("Error al cargar las canchas disponibles:", error);
  }
}

function renderizarPaginaCanchas(canchas, numeroPagina) {
  const contenedor = document.getElementById("listaCanchas");
  contenedor.innerHTML = ""; // Limpiar el contenedor

  canchas.forEach((cancha) => {
    // Determinar la imagen de la cancha
    const imagenCancha = cancha.foto_cancha
      ? cancha.foto_cancha
      : IMG_CANCHA_DEFAULT;

    // Generar estrellas de calificación
    const calificacion = parseFloat(cancha.calificacion_promedio) || 0;
    const estrellasHTML = generarEstrellas(calificacion);

    const canchaCard = document.createElement("div");
    canchaCard.className = "col-12 col-md-6 col-lg-4 mb-4 tarjeta-item";
    canchaCard.innerHTML = `
      <div class="card h-100 shadow">
        <img src="${imagenCancha}" class="card-img-top tarjeta-imagen" alt="${
      cancha.nombre_cancha
    }">
        <div class="card-body d-flex flex-column">
          <h5 class="tarjeta-titulo">${cancha.nombre_cancha}</h5>
          <p class="tarjeta-ubicacion">
            <i class="bi bi-geo-alt"></i> ${cancha.direccion_completa}
          </p>
          <div class="tarjeta-badges">
            <span class="badge text-bg-dark">${
              cancha.tipo_partido_max || "Sin tipo"
            }</span>
            <span class="badge text-bg-dark">${cancha.tipo_superficie}</span>
          </div>
          <p class="tarjeta-descripcion">
            ${cancha.descripcion_cancha || "Sin descripción disponible"}
          </p>
          <div class="tarjeta-footer">
            <div class="tarjeta-calificacion text-warning mb-2">
              ${estrellasHTML}
              <small class="text-muted">(${calificacion.toFixed(1)})</small>
            </div>
            <div class="tarjeta-acciones">
              <div class="d-grid gap-2">
                <a href="${PAGE_PERFIL_CANCHA_JUGADOR}?id=${cancha.id_cancha}" 
                   class="btn btn-primary">
                  <i class="bi bi-eye"></i> Ver detalles
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    contenedor.appendChild(canchaCard);
  });
}

function generarEstrellas(calificacion) {
  let estrellas = "";
  const calificacionRedondeada = Math.round(calificacion * 2) / 2; // Redondear a 0.5

  for (let i = 1; i <= 5; i++) {
    if (i <= calificacionRedondeada) {
      estrellas += '<i class="bi bi-star-fill"></i>';
    } else if (i - 0.5 === calificacionRedondeada) {
      estrellas += '<i class="bi bi-star-half"></i>';
    } else {
      estrellas += '<i class="bi bi-star"></i>';
    }
  }
  return estrellas;
}

// ===================================
// FUNCIONES DE PAGINACIÓN
// ===================================

function irAPagina(numeroPagina) {
  if (numeroPagina >= 0 && numeroPagina < canchasPaginadas.length) {
    paginaActual = numeroPagina;
    renderizarPaginaCanchas(canchasPaginadas[numeroPagina], numeroPagina);
    actualizarControlesPaginacion();

    // Scroll suave hacia arriba
    document.getElementById("listaCanchas").scrollIntoView({
      behavior: "smooth",
      block: "start",
    });
  }
}

function paginaSiguiente() {
  irAPagina(paginaActual + 1);
}

function paginaAnterior() {
  irAPagina(paginaActual - 1);
}

function actualizarControlesPaginacion() {
  const paginacion = document.querySelector(".pagination");
  if (!paginacion) return;

  const totalPaginas = canchasPaginadas.length;

  // Construir HTML de paginación
  let html = "";

  // Botón Anterior
  html += `
    <li class="page-item ${paginaActual === 0 ? "disabled" : ""}">
      <a class="page-link" href="#" onclick="paginaAnterior(); return false;" tabindex="${
        paginaActual === 0 ? "-1" : "0"
      }">
        <i class="bi bi-chevron-left"></i> Anterior
      </a>
    </li>
  `;

  // Números de página
  for (let i = 0; i < totalPaginas; i++) {
    html += `
      <li class="page-item ${i === paginaActual ? "active" : ""}">
        <a class="page-link" href="#" onclick="irAPagina(${i}); return false;">
          ${i + 1}
        </a>
      </li>
    `;
  }

  // Botón Siguiente
  html += `
    <li class="page-item ${
      paginaActual === totalPaginas - 1 ? "disabled" : ""
    }">
      <a class="page-link" href="#" onclick="paginaSiguiente(); return false;" tabindex="${
        paginaActual === totalPaginas - 1 ? "-1" : "0"
      }">
        Siguiente <i class="bi bi-chevron-right"></i>
      </a>
    </li>
  `;

  paginacion.innerHTML = html;
}

// ===================================
// FUNCIONES DE VISTA MAPA
// ===================================

function toggleVista() {
  const vistaListado = document.getElementById("vistaListado");
  const vistaMapa = document.getElementById("vistaMapa");
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  const iconoVista = document.getElementById("iconoVista");
  const textoVista = document.getElementById("textoVista");
  const paginacionNav = document.querySelector(
    'nav[aria-label="Paginación de canchas"]'
  );

  if (vistaListado.classList.contains("d-none")) {
    // Cambiar a vista listado
    vistaListado.classList.remove("d-none");
    vistaMapa.classList.add("d-none");
    paginacionNav.classList.remove("d-none");
    iconoVista.className = "bi bi-map";
    textoVista.textContent = "Mapa";
  } else {
    // Cambiar a vista mapa
    vistaListado.classList.add("d-none");
    vistaMapa.classList.remove("d-none");
    paginacionNav.classList.add("d-none");
    iconoVista.className = "bi bi-list-ul";
    textoVista.textContent = "Listado";

    // Inicializar mapa si no existe
    if (!mapa) {
      inicializarMapa();
    }

    // Agregar marcadores de canchas
    agregarMarcadoresCanchas();
  }
}

function inicializarMapa() {
  // Crear mapa centrado en Buenos Aires
  mapa = L.map("map").setView([-34.6037, -58.3816], 12);

  // Agregar capa de tiles (OpenStreetMap)
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution:
      '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19,
  }).addTo(mapa);

  // Ajustar tamaño del mapa después de un breve delay
  setTimeout(() => {
    mapa.invalidateSize();
  }, 100);
}

function agregarMarcadoresCanchas() {
  // Limpiar marcadores previos
  marcadores.forEach((marcador) => mapa.removeLayer(marcador));
  marcadores = [];

  // Si no hay canchas con coordenadas, mostrar mensaje
  if (!todasLasCanchas || todasLasCanchas.length === 0) {
    return;
  }

  const bounds = [];

  // Agregar marcador por cada cancha
  todasLasCanchas.forEach((cancha) => {
    // Verificar si tiene coordenadas (deberías agregar lat/lng a tu vista SQL)
    if (cancha.latitud && cancha.longitud) {
      const lat = parseFloat(cancha.latitud);
      const lng = parseFloat(cancha.longitud);

      // Crear icono personalizado
      const iconoCancha = L.divIcon({
        className: "custom-marker",
        html: '<i class="bi bi-geo-alt-fill text-primary" style="font-size: 2rem;"></i>',
        iconSize: [30, 40],
        iconAnchor: [15, 40],
        popupAnchor: [0, -40],
      });

      // Crear marcador
      const marcador = L.marker([lat, lng], { icon: iconoCancha })
        .addTo(mapa)
        .bindPopup(crearPopupCancha(cancha));

      marcadores.push(marcador);
      bounds.push([lat, lng]);
    }
  });

  // Ajustar vista del mapa para mostrar todos los marcadores
  if (bounds.length > 0) {
    mapa.fitBounds(bounds, { padding: [50, 50] });
  }
}

function filtrarCanchas(busqueda) {
  const terminoBusqueda = busqueda.toLowerCase().trim();

  if (terminoBusqueda === "") {
    paginarCanchas(todasLasCanchas);
    return;
  }

  const canchasFiltradas = todasLasCanchas.filter((cancha) => {
    // Buscar en nombre de cancha
    if (cancha.nombre_cancha.toLowerCase().includes(terminoBusqueda))
      return true;

    // Buscar en dirección
    if (cancha.direccion_completa.toLowerCase().includes(terminoBusqueda))
      return true;

    // Buscar en tipo de partido
    if (
      cancha.tipo_partido_max &&
      cancha.tipo_partido_max.toLowerCase().includes(terminoBusqueda)
    )
      return true;

    // Buscar en superficie
    if (
      cancha.tipo_superficie &&
      cancha.tipo_superficie.toLowerCase().includes(terminoBusqueda)
    )
      return true;

    return false;
  });

  paginarCanchas(canchasFiltradas);
}

function paginarCanchas(canchas) {
  let paginado = [[]];
  let pagina = 0;

  canchas.forEach((cancha) => {
    if (paginado[pagina].length === CANCHAS_POR_PAGINA) {
      paginado.push([]);
      pagina++;
    }
    paginado[pagina].push(cancha);
  });

  canchasPaginadas = paginado;
  paginaActual = 0;

  if (canchasPaginadas.length > 0) {
    renderizarPaginaCanchas(canchasPaginadas[0], 0);
    actualizarControlesPaginacion();
  }
}

function crearPopupCancha(cancha) {
  const calificacion = parseFloat(cancha.calificacion_promedio) || 0;
  const estrellasHTML = generarEstrellas(calificacion);

  return `
    <div class="popup-cancha">
      <h6 class="fw-bold">${cancha.nombre_cancha}</h6>
      <p class="small">
        <i class="bi bi-geo-alt"></i> ${cancha.direccion_completa}
      </p>
      <div class="mb-2">
        <span class="badge text-bg-dark"">${
          cancha.tipo_partido_max || "Sin tipo"
        }</span>
        <span class="badge text-bg-dark">${cancha.tipo_superficie}</span>
      </div>
      <div class="text-warning small mb-2">
        ${estrellasHTML}
        <small class="text-muted">(${calificacion.toFixed(1)})</small>
      </div>
      <a href="${PAGE_PERFIL_CANCHA_JUGADOR}?id=${cancha.id_cancha}" 
         class="btn btn-primary btn-sm w-100 text-light">
        <i class="bi bi-eye"></i> Ver detalles
      </a>
    </div>
  `;
}
