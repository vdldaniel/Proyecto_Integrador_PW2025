/**
 * Calendario Cancha JavaScript
 * Funcionalidad para la página de calendario de disponibilidad de cancha para jugadores
 */

// Variables globales
let fechaActual = new Date();
let vistaActual = "mes";
let horarioSeleccionado = null;
let fechaSeleccionada = null;

// Datos simulados de disponibilidad
const reservasSimuladas = [
  // Noviembre 2025
  {
    fecha: "2025-11-04",
    hora: "18:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Los Cracks FC",
  },
  {
    fecha: "2025-11-04",
    hora: "20:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Equipo Delta",
  },
  {
    fecha: "2025-11-05",
    hora: "19:00",
    duracion: 1,
    estado: "reservado",
    cliente: "Racing Amateur",
  },
  {
    fecha: "2025-11-05",
    hora: "21:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Villa FC",
  },
  {
    fecha: "2025-11-06",
    hora: "17:00",
    duracion: 3,
    estado: "reservado",
    cliente: "Deportivo Sur",
  },
  {
    fecha: "2025-11-07",
    hora: "18:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Club Atlético",
  },
  {
    fecha: "2025-11-08",
    hora: "20:00",
    duracion: 1,
    estado: "reservado",
    cliente: "Los Tigres",
  },
  {
    fecha: "2025-11-09",
    hora: "16:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Torneo Copa Verano",
  },
  {
    fecha: "2025-11-09",
    hora: "18:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Torneo Copa Verano",
  },
  {
    fecha: "2025-11-09",
    hora: "20:00",
    duracion: 2,
    estado: "reservado",
    cliente: "Torneo Copa Verano",
  },
  {
    fecha: "2025-11-10",
    hora: "15:00",
    duracion: 4,
    estado: "no-disponible",
    motivo: "Mantenimiento",
  },
];

document.addEventListener("DOMContentLoaded", function () {
  inicializarCalendario();
  configurarEventListeners();
  actualizarVistaActual();
});

function inicializarCalendario() {
  // Configurar fecha actual en el selector
  const selectorFecha = document.getElementById("selectorFecha");
  if (selectorFecha) {
    selectorFecha.value = fechaActual.toISOString().split("T")[0];
    selectorFecha.addEventListener("change", function () {
      fechaActual = new Date(this.value);
      actualizarVistaActual();
    });
  }

  // Actualizar título
  actualizarTituloFecha();

  // Renderizar vista inicial
  renderizarVistaMensual();
}

function configurarEventListeners() {
  // Botones de navegación
  document
    .getElementById("botonAnterior")
    ?.addEventListener("click", navegarAnterior);
  document
    .getElementById("botonSiguiente")
    ?.addEventListener("click", navegarSiguiente);
  document.getElementById("botonHoy")?.addEventListener("click", irAHoy);

  // Selectores de vista
  document.querySelectorAll(".selector-vista").forEach((selector) => {
    selector.addEventListener("click", function (e) {
      e.preventDefault();
      cambiarVista(this.dataset.vista);
    });
  });

  // Botón de reservar horario
  document
    .getElementById("botonReservarHorario")
    ?.addEventListener("click", function () {
      if (horarioSeleccionado && fechaSeleccionada) {
        mostrarModalReservaRapida();
      }
    });

  // Duración en modal de reserva rápida
  document
    .getElementById("duracionReservaRapida")
    ?.addEventListener("change", calcularTotalReservaRapida);
}

function navegarAnterior() {
  if (vistaActual === "mes") {
    fechaActual.setMonth(fechaActual.getMonth() - 1);
  } else if (vistaActual === "semana") {
    fechaActual.setDate(fechaActual.getDate() - 7);
  } else if (vistaActual === "dia") {
    fechaActual.setDate(fechaActual.getDate() - 1);
  }
  actualizarVistaActual();
}

function navegarSiguiente() {
  if (vistaActual === "mes") {
    fechaActual.setMonth(fechaActual.getMonth() + 1);
  } else if (vistaActual === "semana") {
    fechaActual.setDate(fechaActual.getDate() + 7);
  } else if (vistaActual === "dia") {
    fechaActual.setDate(fechaActual.getDate() + 1);
  }
  actualizarVistaActual();
}

function irAHoy() {
  fechaActual = new Date();
  document.getElementById("selectorFecha").value = fechaActual
    .toISOString()
    .split("T")[0];
  actualizarVistaActual();
}

function cambiarVista(nuevaVista) {
  vistaActual = nuevaVista;
  document.getElementById("vistaActual").textContent =
    nuevaVista.charAt(0).toUpperCase() + nuevaVista.slice(1);

  // Ocultar todas las vistas
  document.querySelectorAll(".vista-calendario").forEach((vista) => {
    vista.classList.add("d-none");
  });

  // Mostrar vista seleccionada
  const vistaElemento = document.getElementById(
    `vista${nuevaVista.charAt(0).toUpperCase() + nuevaVista.slice(1)}`
  );
  if (vistaElemento) {
    vistaElemento.classList.remove("d-none");
  }

  actualizarVistaActual();
}

function actualizarVistaActual() {
  actualizarTituloFecha();

  if (vistaActual === "mes") {
    renderizarVistaMensual();
  } else if (vistaActual === "semana") {
    renderizarVistaSemanal();
  } else if (vistaActual === "dia") {
    renderizarVistaDiaria();
  }
}

function actualizarTituloFecha() {
  const titulo = document.getElementById("displayFechaActual");
  if (!titulo) return;

  const opciones = { year: "numeric", month: "long" };
  let textoFecha = "";

  if (vistaActual === "mes") {
    textoFecha = `Calendario - ${fechaActual.toLocaleDateString(
      "es-ES",
      opciones
    )}`;
  } else if (vistaActual === "semana") {
    const inicioSemana = new Date(fechaActual);
    inicioSemana.setDate(fechaActual.getDate() - fechaActual.getDay());
    const finSemana = new Date(inicioSemana);
    finSemana.setDate(inicioSemana.getDate() + 6);

    textoFecha = `Calendario - Semana del ${inicioSemana.getDate()} al ${finSemana.getDate()} de ${fechaActual.toLocaleDateString(
      "es-ES",
      { month: "long", year: "numeric" }
    )}`;
  } else if (vistaActual === "dia") {
    textoFecha = `Calendario - ${fechaActual.toLocaleDateString("es-ES", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    })}`;
  }

  titulo.textContent = textoFecha;
}

function renderizarVistaMensual() {
  const tbody = document.querySelector("#vistaMensual tbody");
  if (!tbody) return;

  tbody.innerHTML = "";

  const primerDia = new Date(
    fechaActual.getFullYear(),
    fechaActual.getMonth(),
    1
  );
  const ultimoDia = new Date(
    fechaActual.getFullYear(),
    fechaActual.getMonth() + 1,
    0
  );
  const diasAntes = primerDia.getDay();

  let fecha = new Date(primerDia);
  fecha.setDate(fecha.getDate() - diasAntes);

  for (let semana = 0; semana < 6; semana++) {
    const fila = document.createElement("tr");

    for (let dia = 0; dia < 7; dia++) {
      const celda = document.createElement("td");
      celda.classList.add("calendario-dia");
      celda.style.height = "120px";
      celda.style.verticalAlign = "top";
      celda.style.position = "relative";
      celda.style.cursor = "pointer";

      const esMesActual = fecha.getMonth() === fechaActual.getMonth();
      const esHoy = esHoyFecha(fecha);

      if (!esMesActual) {
        celda.classList.add("text-muted");
      }

      if (esHoy) {
        celda.classList.add("bg-primary", "bg-opacity-25");
      }

      const numeroDia = document.createElement("div");
      numeroDia.classList.add("fw-bold", "mb-1");
      numeroDia.textContent = fecha.getDate();
      celda.appendChild(numeroDia);

      // Agregar indicadores de disponibilidad
      const disponibilidad = obtenerDisponibilidadDia(fecha);
      const indicador = document.createElement("div");
      indicador.classList.add("small");

      if (disponibilidad.total === 0) {
        indicador.innerHTML = '<span class="badge text-bg-dark">Cerrado</span>';
      } else if (disponibilidad.disponibles === 0) {
        indicador.innerHTML =
          '<span class="badge text-bg-dark">Sin horarios</span>';
      } else if (disponibilidad.disponibles <= 3) {
        indicador.innerHTML = `<span class="badge text-bg-dark">${disponibilidad.disponibles} horarios</span>`;
      } else {
        indicador.innerHTML = `<span class="badge text-bg-dark">${disponibilidad.disponibles} horarios</span>`;
      }

      celda.appendChild(indicador);

      // Agregar event listener para navegación
      const fechaString = formatearFecha(fecha);
      celda.addEventListener("click", function () {
        fechaActual = new Date(fechaString);
        cambiarVista("dia");
      });

      fila.appendChild(celda);
      fecha.setDate(fecha.getDate() + 1);
    }

    tbody.appendChild(fila);
  }
}

function renderizarVistaSemanal() {
  const tbody = document.querySelector("#vistaSemanal tbody");
  if (!tbody) return;

  tbody.innerHTML = "";

  // Calcular inicio de semana (domingo)
  const inicioSemana = new Date(fechaActual);
  inicioSemana.setDate(fechaActual.getDate() - fechaActual.getDay());

  // Generar horarios (7:00 AM - 11:00 PM)
  for (let hora = 7; hora <= 23; hora++) {
    const fila = document.createElement("tr");

    // Celda de hora
    const celdaHora = document.createElement("td");
    celdaHora.classList.add("text-center", "fw-bold", "bg-light", "text-dark");
    celdaHora.textContent = `${hora.toString().padStart(2, "0")}:00`;
    fila.appendChild(celdaHora);

    // Celdas para cada día de la semana
    for (let dia = 0; dia < 7; dia++) {
      const fechaDia = new Date(inicioSemana);
      fechaDia.setDate(inicioSemana.getDate() + dia);

      const celda = document.createElement("td");
      celda.style.height = "40px";
      celda.style.cursor = "pointer";

      const horaString = `${hora.toString().padStart(2, "0")}:00`;
      const disponible = esHorarioDisponible(fechaDia, horaString);

      if (disponible) {
        celda.classList.add("bg-success", "bg-opacity-25", "border-success");
        celda.innerHTML =
          '<div class="text-center small text-success">Disponible</div>';

        celda.addEventListener("click", function () {
          seleccionarHorario(fechaDia, horaString);
        });
      } else {
        const reserva = obtenerReserva(fechaDia, horaString);
        if (reserva) {
          celda.classList.add("bg-danger", "bg-opacity-25", "border-danger");
          celda.innerHTML = `<div class="text-center small text-danger">${
            reserva.cliente || "Reservado"
          }</div>`;
        } else {
          celda.classList.add("bg-secondary", "bg-opacity-25");
          celda.innerHTML =
            '<div class="text-center small text-muted">No disponible</div>';
        }
      }

      fila.appendChild(celda);
    }

    tbody.appendChild(fila);
  }
}

function renderizarVistaDiaria() {
  const tbody = document.querySelector("#vistaDiaria tbody");
  const encabezado = document.getElementById("encabezadoVistaDiaria");

  if (!tbody || !encabezado) return;

  tbody.innerHTML = "";

  // Actualizar encabezado
  encabezado.textContent = fechaActual.toLocaleDateString("es-ES", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  });

  // Generar horarios (7:00 AM - 11:00 PM)
  for (let hora = 7; hora <= 23; hora++) {
    const fila = document.createElement("tr");

    // Celda de hora
    const celdaHora = document.createElement("td");
    celdaHora.classList.add("text-center", "fw-bold", "bg-light", "text-dark");
    celdaHora.textContent = `${hora.toString().padStart(2, "0")}:00 - ${(
      hora + 1
    )
      .toString()
      .padStart(2, "0")}:00`;
    fila.appendChild(celdaHora);

    // Celda de disponibilidad
    const celda = document.createElement("td");
    celda.style.height = "60px";
    celda.style.cursor = "pointer";

    const horaString = `${hora.toString().padStart(2, "0")}:00`;
    const disponible = esHorarioDisponible(fechaActual, horaString);

    if (disponible) {
      celda.classList.add("bg-success", "bg-opacity-25", "border-success");
      celda.innerHTML = `
                <div class="d-flex justify-content-between align-items-center p-2">
                    <div>
                        <div class="text-success fw-bold">Disponible</div>
                        <div class="small text-muted">$2,800/hora</div>
                    </div>
                    <button class="btn btn-sm btn-success" onclick="seleccionarHorario('${formatearFecha(
                      fechaActual
                    )}', '${horaString}')">
                        <i class="bi bi-calendar-plus"></i>
                    </button>
                </div>
            `;
    } else {
      const reserva = obtenerReserva(fechaActual, horaString);
      if (reserva) {
        celda.classList.add("bg-danger", "bg-opacity-25", "border-danger");
        celda.innerHTML = `
                    <div class="p-2">
                        <div class="text-danger fw-bold">Reservado</div>
                        <div class="small text-muted">${reserva.cliente}</div>
                        <div class="small text-muted">${reserva.duracion}h de duración</div>
                    </div>
                `;
      } else {
        celda.classList.add("bg-secondary", "bg-opacity-25");
        celda.innerHTML = `
                    <div class="p-2">
                        <div class="text-muted fw-bold">No disponible</div>
                        <div class="small text-muted">Fuera de horario</div>
                    </div>
                `;
      }
    }

    fila.appendChild(celda);
    tbody.appendChild(fila);
  }

  // Actualizar resumen del día
  actualizarResumenDia();
}

function actualizarResumenDia() {
  const resumen = document.getElementById("resumenDia");
  if (!resumen) return;

  const disponibilidad = obtenerDisponibilidadDia(fechaActual);

  resumen.innerHTML = `
        <div class="text-center mb-3">
            <h5>${fechaActual.toLocaleDateString("es-ES", {
              weekday: "long",
              day: "numeric",
              month: "long",
            })}</h5>
        </div>
        
        <div class="row text-center mb-3">
            <div class="col-4">
                <div class="text-success">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                    <div class="fw-bold">${disponibilidad.disponibles}</div>
                    <small>Disponibles</small>
                </div>
            </div>
            <div class="col-4">
                <div class="text-danger">
                    <i class="bi bi-x-circle-fill fs-4"></i>
                    <div class="fw-bold">${disponibilidad.reservados}</div>
                    <small>Reservados</small>
                </div>
            </div>
            <div class="col-4">
                <div class="text-muted">
                    <i class="bi bi-clock-fill fs-4"></i>
                    <div class="fw-bold">${disponibilidad.total}</div>
                    <small>Total</small>
                </div>
            </div>
        </div>
        
        ${
          disponibilidad.disponibles > 0
            ? `
            <div class="alert alert-success py-2">
                <small><i class="bi bi-info-circle"></i> Horarios disponibles para reservar</small>
            </div>
        `
            : `
            <div class="alert alert-warning py-2">
                <small><i class="bi bi-exclamation-triangle"></i> No hay horarios disponibles</small>
            </div>
        `
        }
    `;
}

// Funciones de utilidad
function esHoyFecha(fecha) {
  const hoy = new Date();
  return fecha.toDateString() === hoy.toDateString();
}

function formatearFecha(fecha) {
  return fecha.toISOString().split("T")[0];
}

function obtenerDisponibilidadDia(fecha) {
  const fechaString = formatearFecha(fecha);
  let disponibles = 0;
  let reservados = 0;
  let total = 17; // 7:00 AM - 11:00 PM = 17 horas

  // Verificar si es un día en el pasado
  const hoy = new Date();
  hoy.setHours(0, 0, 0, 0);
  if (fecha < hoy) {
    return { disponibles: 0, reservados: 0, total: 0 };
  }

  for (let hora = 7; hora <= 23; hora++) {
    const horaString = `${hora.toString().padStart(2, "0")}:00`;
    if (esHorarioDisponible(fecha, horaString)) {
      disponibles++;
    } else if (obtenerReserva(fecha, horaString)) {
      reservados++;
    }
  }

  return { disponibles, reservados, total };
}

function esHorarioDisponible(fecha, hora) {
  const fechaString = formatearFecha(fecha);
  const hoy = new Date();

  // No permitir reservas en el pasado
  if (
    fecha < hoy.toDateString() ||
    (fecha.toDateString() === hoy.toDateString() &&
      parseInt(hora) <= hoy.getHours())
  ) {
    return false;
  }

  // Verificar si ya está reservado
  return !reservasSimuladas.some(
    (reserva) => reserva.fecha === fechaString && reserva.hora === hora
  );
}

function obtenerReserva(fecha, hora) {
  const fechaString = formatearFecha(fecha);
  return reservasSimuladas.find(
    (reserva) => reserva.fecha === fechaString && reserva.hora === hora
  );
}

function seleccionarHorario(fecha, hora) {
  if (typeof fecha === "string") {
    fechaSeleccionada = fecha;
  } else {
    fechaSeleccionada = formatearFecha(fecha);
  }

  horarioSeleccionado = hora;

  // Habilitar botón de reservar
  const botonReservar = document.getElementById("botonReservarHorario");
  if (botonReservar) {
    botonReservar.disabled = false;
    botonReservar.innerHTML = `<i class="bi bi-calendar-plus"></i> Reservar ${hora}`;
  }

  // Mostrar modal directamente
  mostrarModalReservaRapida();
}

function mostrarModalReservaRapida() {
  if (!horarioSeleccionado || !fechaSeleccionada) return;

  // Actualizar información del modal
  document.getElementById(
    "horarioSeleccionado"
  ).textContent = `${horarioSeleccionado} - ${
    parseInt(horarioSeleccionado.split(":")[0]) + 1
  }:00`;

  const fechaObj = new Date(fechaSeleccionada);
  document.getElementById("fechaSeleccionada").textContent =
    fechaObj.toLocaleDateString("es-ES", {
      weekday: "long",
      year: "numeric",
      month: "long",
      day: "numeric",
    });

  calcularTotalReservaRapida();

  const modal = new bootstrap.Modal(
    document.getElementById("modalReservaRapida")
  );
  modal.show();
}

function calcularTotalReservaRapida() {
  const duracion =
    parseInt(document.getElementById("duracionReservaRapida")?.value) || 2;
  const precioPorHora = 2800;
  const subtotal = duracion * precioPorHora;
  const descuento = Math.floor(subtotal * 0.05); // 5% descuento
  const total = subtotal - descuento;

  // Actualizar valores en el modal
  document.getElementById(
    "subtotalReservaRapida"
  ).textContent = `$${subtotal.toLocaleString()}`;
  document.getElementById(
    "descuentoReservaRapida"
  ).textContent = `-$${descuento.toLocaleString()}`;
  document.getElementById(
    "totalReservaRapida"
  ).textContent = `$${total.toLocaleString()}`;
}

function confirmarReservaRapida() {
  const form = document.getElementById("formReservaRapida");
  if (!form) return;

  // Simular confirmación
  const modal = bootstrap.Modal.getInstance(
    document.getElementById("modalReservaRapida")
  );
  modal.hide();

  setTimeout(() => {
    alert("¡Reserva confirmada! Serás redirigido al sistema de pago.");
    // Aquí iría la redirección al pago
    // window.location.href = 'pago-reserva.php';
  }, 500);
}

// Funciones adicionales
function volverCancha() {
  window.history.back();
}

function verPoliticasReserva() {
  const modal = new bootstrap.Modal(document.getElementById("modalPoliticas"));
  modal.show();
}
