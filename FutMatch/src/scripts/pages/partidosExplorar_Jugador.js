// Variables globales para paginación
let partidosPaginados = [];
let paginaActual = 0;
const PARTIDOS_POR_PAGINA = 6;

// Variables globales para mapa
let mapa = null;
let marcadores = [];
let todosLosPartidos = []; // Guardar todos los partidos sin paginar

window.onload = function () {
  cargarPartidosDisponibles();
  configurarEventos();
};

function configurarEventos() {
  // Evento para cambiar vista (Listado <-> Mapa)
  const btnCambiarVista = document.getElementById("btnCambiarVista");
  if (btnCambiarVista) {
    btnCambiarVista.addEventListener("click", toggleVista);
  }
}

async function cargarPartidosDisponibles() {
  try {
    const response = await fetch(GET_PARTIDOS_DISPONIBLES_JUGADOR);

    if (!response.ok) {
      throw new Error(
        `Error ${response.status}: No se pudieron cargar los partidos`
      );
    }

    const partidos = await response.json();

    // Guardar todos los partidos para el mapa
    todosLosPartidos = partidos;

    // Dividir partidos en páginas
    let paginado = [[]];
    let pagina = 0;

    partidos.forEach((partido) => {
      if (paginado[pagina].length === PARTIDOS_POR_PAGINA) {
        paginado.push([]);
        pagina++;
      }
      paginado[pagina].push(partido);
    });

    // Guardar en variable global
    partidosPaginados = paginado;
    paginaActual = 0;

    // Renderizar la primera página
    if (partidosPaginados.length > 0) {
      renderizarPaginaPartidos(partidosPaginados[0], 0);
      actualizarControlesPaginacion();
    }
  } catch (error) {
    console.error("Error al cargar los partidos disponibles:", error);
  }
}

function renderizarPaginaPartidos(partidos, numeroPagina) {
  const contenedor = document.getElementById("listaPartidos");
  contenedor.innerHTML = ""; // Limpiar el contenedor

  partidos.forEach((partido) => {
    // Determinar la imagen del partido (usar foto de cancha)
    const imagenPartido = partido.foto_cancha
      ? partido.foto_cancha
      : IMG_PARTIDO_DEFAULT;

    // Calcular cupos disponibles
    const cuposDisponibles =
      partido.max_participantes - partido.participantes_actuales;

    const partidoCard = document.createElement("div");
    partidoCard.className = "col-12 col-md-6 col-lg-4 mb-4 tarjeta-item";
    partidoCard.innerHTML = `
      <div class="card h-100 shadow">
        <img src="${imagenPartido}" class="card-img-top tarjeta-imagen" alt="${
      partido.tipo_partido
    }">
        <div class="card-body d-flex flex-column">
          <h5 class="tarjeta-titulo">${
            partido.motivo || partido.tipo_partido
          } - ${partido.nombre_cancha}</h5>
          <p class="tarjeta-ubicacion">
            <i class="bi bi-geo-alt"></i> ${partido.direccion_completa}
          </p>

          <div class="tarjeta-badges mb-2">
            <span class="badge text-bg-dark">${partido.tipo_partido}</span>
            <span class="badge text-bg-dark">${partido.tipo_superficie}</span>
          </div>
          
          <!-- Fecha y hora -->
          <div class="partido-datetime mb-2 d-flex justify-content-between align-items-center">
            <div class="fw-bold text-start">${partido.dia_semana}</div>
            <div class="fw-bold text-center">${
              partido.fecha_partido_formato
            }</div>
            <div class="fw-bold text-end">${partido.hora_inicio}</div>
          </div>

          <div class="tarjeta-footer">
            <div class="text-warning fw-bold mb-2">
              <i class="bi bi-people-fill"></i> 
              ${partido.participantes_actuales}/${
      partido.max_participantes
    } participantes
            </div>
            <div class="tarjeta-acciones">
              <div class="d-grid gap-2">
                <button class="btn btn-primary" onclick="renderizarModalUnirsePartido(${
                  partido.id_partido
                })" ${cuposDisponibles === 0 ? "disabled" : ""}>
                  <i class="bi bi-person-plus"></i> Solicitar unirse
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    contenedor.appendChild(partidoCard);
  });
}

function renderizarModalUnirsePartido(idPartido) {
  // Buscar el partido en la lista global
  const partido = todosLosPartidos.find((p) => p.id_partido == idPartido);

  if (!partido) {
    showToast("Error: No se encontró información del partido", "Error");
    return;
  }

  // Calcular cupos disponibles
  const cuposDisponibles =
    partido.max_participantes - partido.participantes_actuales;

  // Renderizar información del partido en el modal
  const infoPartidoHTML = `
    <div class="card">
      <div class="card-body">
        <h6 class="card-title fw-bold">${partido.tipo_partido} - ${
    partido.nombre_cancha
  }</h6>
        <p class="card-text text-muted mb-2">
          <i class="bi bi-geo-alt"></i> ${partido.direccion_completa}
        </p>
        <div class="d-flex gap-2 mb-2">
          <span class="badge text-bg-primary">
            ${partido.dia_semana} ${partido.fecha_partido_formato}
          </span>
          <span class="badge text-bg-dark">${partido.hora_inicio}</span>
          <span class="badge text-bg-dark">${partido.tipo_superficie}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted">
            <i class="bi bi-people-fill"></i> ${
              partido.participantes_actuales
            }/${partido.max_participantes} participantes
          </span>
          <span class="badge ${
            cuposDisponibles > 0 ? "text-bg-success" : "text-bg-danger"
          }">
            ${cuposDisponibles} cupos disponibles
          </span>
        </div>
        ${
          partido.descripcion
            ? `<p class="card-text mt-2 small">${partido.descripcion}</p>`
            : ""
        }
      </div>
    </div>
  `;

  document.getElementById("infoPartidoSolicitar").innerHTML = infoPartidoHTML;

  // Configurar el botón de confirmar
  const btnConfirmar = document.getElementById("btnConfirmarSolicitud");
  btnConfirmar.onclick = function () {
    enviarSolicitudUnirse(idPartido);
  };

  // Mostrar el modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalSolicitarUnirse")
  );
  modal.show();
}

async function enviarSolicitudUnirse(idPartido) {
  const btnConfirmar = document.getElementById("btnConfirmarSolicitud");

  try {
    // Deshabilitar botón mientras se envía
    btnConfirmar.disabled = true;
    btnConfirmar.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

    const response = await fetch(POST_SOLICITANTE_PARTIDO_JUGADOR, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id_partido: idPartido,
      }),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Error al enviar solicitud");
    }

    // Mostrar mensaje de éxito
    showToast(
      "¡Solicitud enviada correctamente! Podés ver el estado de la misma en 'Mis Partidos'",
      "success"
    );

    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(
      document.getElementById("modalSolicitarUnirse")
    );
    modal.hide();

    // Refrescar la página para mostrar el estado actualizado
    window.location.reload();
  } catch (error) {
    console.error("Error al enviar solicitud:", error);
  } finally {
    // Rehabilitar botón
    btnConfirmar.disabled = false;
    btnConfirmar.innerHTML = '<i class="bi bi-send me-2"></i>Enviar solicitud';
  }
}

// ===================================
// FUNCIONES DE PAGINACIÓN
// ===================================

function irAPagina(numeroPagina) {
  if (numeroPagina >= 0 && numeroPagina < partidosPaginados.length) {
    paginaActual = numeroPagina;
    renderizarPaginaPartidos(partidosPaginados[numeroPagina], numeroPagina);
    actualizarControlesPaginacion();

    // Scroll suave hacia arriba
    document.getElementById("listaPartidos").scrollIntoView({
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

  const totalPaginas = partidosPaginados.length;

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
    'nav[aria-label="Paginación de partidos"]'
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

    // Agregar marcadores de partidos
    agregarMarcadoresPartidos();
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

function agregarMarcadoresPartidos() {
  // Limpiar marcadores previos
  marcadores.forEach((marcador) => mapa.removeLayer(marcador));
  marcadores = [];

  // Si no hay partidos con coordenadas, mostrar mensaje
  if (!todosLosPartidos || todosLosPartidos.length === 0) {
    return;
  }

  const bounds = [];

  // Agregar marcador por cada partido
  todosLosPartidos.forEach((partido) => {
    // Verificar si tiene coordenadas
    if (partido.latitud && partido.longitud) {
      const lat = parseFloat(partido.latitud);
      const lng = parseFloat(partido.longitud);

      // Crear icono personalizado para partidos
      const iconoPartido = L.divIcon({
        className: "custom-marker",
        html: '<i class="bi bi-dribbble text-success" style="font-size: 2rem;"></i>',
        iconSize: [30, 40],
        iconAnchor: [15, 40],
        popupAnchor: [0, -40],
      });

      // Crear marcador
      const marcador = L.marker([lat, lng], { icon: iconoPartido })
        .addTo(mapa)
        .bindPopup(crearPopupPartido(partido));

      marcadores.push(marcador);
      bounds.push([lat, lng]);
    }
  });

  // Ajustar vista del mapa para mostrar todos los marcadores
  if (bounds.length > 0) {
    mapa.fitBounds(bounds, { padding: [50, 50] });
  }
}

function crearPopupPartido(partido) {
  const cuposDisponibles =
    partido.max_participantes - partido.participantes_actuales;

  return `
    <div class="popup-cancha">
      <h6 class="fw-bold">${partido.tipo_partido}</h6>
      <p class="small mb-1">
        <i class="bi bi-geo-alt"></i> ${partido.nombre_cancha}
      </p>
      <p class="small mb-2">${partido.direccion_completa}</p>
      <div class="mb-2">
        <span class="badge text-bg-primary small">
          ${partido.dia_semana} ${partido.fecha_partido_formato}
        </span>
        <span class="badge text-bg-dark small">${partido.hora_inicio}</span>
      </div>
      <div class="mb-2">
        <span class="badge text-bg-dark small">${partido.tipo_superficie}</span>
        <span class="badge ${
          cuposDisponibles > 0 ? "text-bg-success" : "text-bg-danger"
        } small">
          ${cuposDisponibles} cupos
        </span>
      </div>
      <p class="small mb-2">
        <i class="bi bi-people"></i> ${partido.participantes_actuales}/${
    partido.max_participantes
  } participantes
      </p>
      <button onclick="renderizarModalUnirsePartido(${partido.id_partido})" 
              class="btn btn-primary btn-sm w-100" 
              ${cuposDisponibles === 0 ? "disabled" : ""}>
        <i class="bi bi-person-plus"></i> Solicitar unirse
      </button>
    </div>
  `;
}
