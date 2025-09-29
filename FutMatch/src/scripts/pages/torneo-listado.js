// Datos de ejemplo
const torneos = [
  {
    id: 1,
    nombre: "Copa FutMatch",
    inicio: "2025-11-15",
    fin: "2025-12-01",
    ubicacion: "Buenos Aires",
    categoria: "Amateur",
    estado: "inscripciones abiertas"
  },
  {
    id: 2,
    nombre: "Liga Metropolitana",
    inicio: "2025-09-01",
    fin: "2025-10-15",
    ubicacion: "CABA",
    categoria: "Profesional",
    estado: "en curso"
  },
  {
    id: 3,
    nombre: "Torneo de Verano",
    inicio: "2025-06-01",
    fin: "2025-07-01",
    ubicacion: "Mar del Plata",
    categoria: "Amateur",
    estado: "finalizado"
  }
];
// muestra detalles de torneo
const tablaTorneos = document.getElementById("tablaTorneos");
const btnFiltrar = document.getElementById("btnFiltrar");
const btnLimpiar = document.getElementById("btnLimpiar");

function renderTorneos(lista) {
  tablaTorneos.innerHTML = "";
  lista.forEach((t, i) => {
    tablaTorneos.innerHTML += `
      <tr>
        <td>${i + 1}</td>
        <td>${t.nombre}</td>
        <td>${t.inicio} al ${t.fin}</td>
        <td>${t.ubicacion}</td>
        <td>${t.categoria}</td>
        <td>${t.estado}</td>
        <td>
          <a href="torneo-detalle.html" class="btn btn-sm btn-warning">
            <i class="bi "></i> Ver detalle
          </a>
        </td>
      </tr>
    `;
  });
}
//filtra torneo segun selec de Usuario
function filtrar() {
  const estado = document.getElementById("filtroEstado").value;
  const categoria = document.getElementById("filtroCategoria").value;
  const ubicacion = document.getElementById("filtroUbicacion").value.toLowerCase();

  let filtrados = torneos;

  if (estado) {
    if (estado === "proximos") {
      filtrados = filtrados.filter(t => new Date(t.inicio) > new Date());
    } else if (estado === "curso") {
      filtrados = filtrados.filter(t => new Date(t.inicio) <= new Date() && new Date(t.fin) >= new Date());
    } else if (estado === "finalizados") {
      filtrados = filtrados.filter(t => new Date(t.fin) < new Date());
    }
  }

  if (categoria) {
    filtrados = filtrados.filter(t => t.categoria === categoria);
  }

  if (ubicacion) {
    filtrados = filtrados.filter(t => t.ubicacion.toLowerCase().includes(ubicacion));
  }

  renderTorneos(filtrados);
}

// Inicialización
renderTorneos(torneos);

btnFiltrar.addEventListener("click", filtrar);
btnLimpiar.addEventListener("click", () => renderTorneos(torneos));
