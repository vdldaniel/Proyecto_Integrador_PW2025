/**
 * Funcionalidades base del perfil de cancha
 * SOLO funciones comunes de UI compartidas entre jugador y admin
 * La carga de datos específica se maneja en cada implementación (perfilAdmin.js, cancha-perfil-jugador.js)
 */

class PerfilCanchaBase {
  constructor() {
    this.datosCancha = null;
    this.inicializarEventosComunes();
  }

  /**
   * Inicializa eventos comunes a todas las vistas de perfil de cancha
   */
  inicializarEventosComunes() {
    // Botón compartir cancha (solo jugadores)
    const btnCompartirCancha = document.getElementById("btnCompartirCancha");
    if (btnCompartirCancha) {
      btnCompartirCancha.addEventListener("click", () =>
        this.compartirCancha()
      );
    }

    // Botón ver disponibilidad (solo jugadores)
    const btnVerDisponibilidad = document.getElementById(
      "btnVerDisponibilidad"
    );
    if (btnVerDisponibilidad) {
      btnVerDisponibilidad.addEventListener("click", () =>
        this.verDisponibilidad()
      );
    }

    // Botón ver en mapa (solo jugadores)
    const btnVerEnMapa = document.getElementById("btnVerEnMapa");
    if (btnVerEnMapa) {
      btnVerEnMapa.addEventListener("click", () => this.verEnMapa());
    }

    // Botón ver reseñas (solo jugadores)
    const btnVerResenas = document.getElementById("btnVerResenas");
    if (btnVerResenas) {
      btnVerResenas.addEventListener("click", () => this.verResenas());
    }

    // Botones ver detalles de torneos
    const botonesVerDetalles = document.querySelectorAll(".btnVerDetalles");
    botonesVerDetalles.forEach((boton) => {
      boton.addEventListener("click", (e) => {
        const torneoId = e.target.getAttribute("data-torneo-id");
        this.verDetallesTorneo(torneoId);
      });
    });

    // Selector de cancha (solo admin)
    const selectorCancha = document.querySelector(".dropdown-menu");
    if (selectorCancha) {
      selectorCancha.addEventListener("click", (e) => {
        if (e.target.classList.contains("dropdown-item")) {
          e.preventDefault();
          this.cambiarCancha(e.target);
        }
      });
    }

    // Botón de navegación hacia disponibilidad/agenda
    const btnNavegacion = document.querySelector("[data-url]");
    if (btnNavegacion) {
      btnNavegacion.addEventListener("click", (e) => {
        const url = e.target.getAttribute("data-url");
        if (url) {
          window.location.href = url;
        }
      });
    }
  }

  /**
   * Compartir información de la cancha
   */
  compartirCancha() {
    const nombreCancha =
      document.getElementById("nombreCancha")?.textContent || "Cancha";
    const direccion =
      document.getElementById("direccionCancha")?.textContent || "";

    if (navigator.share) {
      navigator
        .share({
          title: `${nombreCancha} - FutMatch`,
          text: `¡Mirá esta cancha! ${nombreCancha} - ${direccion}`,
          url: window.location.href,
        })
        .catch((err) => console.log("Error al compartir:", err));
    } else {
      // Fallback: copiar URL al clipboard
      navigator.clipboard
        .writeText(window.location.href)
        .then(() => {
          this.mostrarNotificacion("¡URL copiada al portapapeles!", "success");
        })
        .catch(() => {
          this.mostrarNotificacion("No se pudo copiar la URL", "error");
        });
    }
  }

  /**
   * Abrir ubicación en Google Maps
   */
  verEnMapa() {
    const direccion = document.getElementById("direccionCancha")?.textContent;
    if (direccion) {
      const urlMaps = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(
        direccion
      )}`;
      window.open(urlMaps, "_blank");
    } else {
      this.mostrarNotificacion("No se encontró la dirección", "error");
    }
  }

  /**
   * Ver disponibilidad de la cancha (redirige al calendario)
   */
  verDisponibilidad() {
    // Obtener ID de la cancha desde la URL o desde los datos cargados
    const urlParams = new URLSearchParams(window.location.search);
    const idCancha =
      urlParams.get("id") ||
      urlParams.get("id_cancha") ||
      (this.datosCancha ? this.datosCancha.id_cancha : null) ||
      (typeof ID_CANCHA !== "undefined" ? ID_CANCHA : null);

    if (idCancha) {
      // Verificar si PAGE_CALENDARIO_CANCHA_JUGADOR está definida
      if (typeof PAGE_CALENDARIO_CANCHA_JUGADOR !== "undefined") {
        window.location.href = `${PAGE_CALENDARIO_CANCHA_JUGADOR}?id=${idCancha}`;
      } else {
        console.error("PAGE_CALENDARIO_CANCHA_JUGADOR no está definida");
        this.mostrarNotificacion(
          "Error: No se pudo cargar el calendario",
          "error"
        );
      }
    } else {
      console.error("No se encontró ID de cancha para redirigir al calendario");
      this.mostrarNotificacion(
        "Error: No se pudo identificar la cancha",
        "error"
      );
    }
  }

  /**
   * Ver todas las reseñas de la cancha
   */
  verResenas() {
    // TODO: Implementar modal o página de reseñas
    console.log("Mostrar reseñas de la cancha");
    this.mostrarNotificacion("Función de reseñas en desarrollo", "info");
  }

  /**
   * Ver detalles de un torneo específico
   * @param {string} torneoId - ID del torneo
   */
  verDetallesTorneo(torneoId) {
    console.log(`Ver detalles del torneo ${torneoId}`);
    // TODO: Redirigir a página de detalles del torneo o abrir modal
    this.mostrarNotificacion("Redirigiendo a detalles del torneo...", "info");
  }

  /**
   * Cambiar cancha seleccionada (solo admin)
   * @param {HTMLElement} item - Elemento clickeado del dropdown
   */
  cambiarCancha(item) {
    // Remover clase active de todos los items
    document.querySelectorAll(".dropdown-item").forEach((i) => {
      i.classList.remove("active");
      i.innerHTML = i.innerHTML.replace(
        '<i class="bi bi-check-circle"></i>',
        '<i class="bi bi-building"></i>'
      );
    });

    // Marcar como activo el item seleccionado
    item.classList.add("active");
    item.innerHTML = item.innerHTML.replace(
      '<i class="bi bi-building"></i>',
      '<i class="bi bi-check-circle"></i>'
    );

    // Actualizar el botón del dropdown
    const dropdownButton = document.querySelector(".dropdown-toggle");
    const textoCancha = item.textContent.trim();
    dropdownButton.innerHTML = `<i class="bi bi-building"></i> ${textoCancha}`;

    // Cargar datos de la nueva cancha
    this.cargarDatosCancha(textoCancha);
  }

  /**
   * Cargar datos de una cancha específica
   * @param {string} nombreCancha - Nombre de la cancha a cargar
   */
  cargarDatosCancha(nombreCancha) {
    console.log(`Cargando datos de: ${nombreCancha}`);

    // Actualizar información en la página
    document.getElementById("nombreCancha").textContent = nombreCancha;

    // TODO: Hacer petición AJAX para cargar datos reales de la cancha
    this.mostrarNotificacion(`Cancha cambiada a: ${nombreCancha}`, "success");
  }

  /**
   * Mostrar notificación temporal
   * @param {string} mensaje - Mensaje a mostrar
   * @param {string} tipo - Tipo de notificación (success, error, info, warning)
   */
  mostrarNotificacion(mensaje, tipo = "info") {
    // Crear elemento de notificación
    const notificacion = document.createElement("div");
    notificacion.className = `alert alert-${
      tipo === "error" ? "danger" : tipo
    } alert-dismissible fade show position-fixed`;
    notificacion.style.cssText =
      "top: 20px; right: 20px; z-index: 9999; min-width: 300px;";

    notificacion.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

    // Agregar al body
    document.body.appendChild(notificacion);

    // Auto-remover después de 3 segundos
    setTimeout(() => {
      if (notificacion.parentNode) {
        notificacion.remove();
      }
    }, 3000);
  }

  /**
   * Actualizar información de la cancha en la interfaz
   * @param {Object} datosCancha - Objeto con datos de la cancha
   */
  actualizarInterfazCancha(datosCancha) {
    // Actualizar elementos si existen
    const elementos = {
      nombreCancha: datosCancha.nombre,
      descripcionCancha: datosCancha.descripcion,
      direccionCancha: datosCancha.direccion,
      tipoCancha: datosCancha.tipo,
      superficieCancha: datosCancha.superficie,
      capacidadCancha: datosCancha.capacidad + " jugadores",
    };

    Object.keys(elementos).forEach((id) => {
      const elemento = document.getElementById(id);
      if (elemento && elementos[id]) {
        elemento.textContent = elementos[id];
      }
    });
  }

  /**
   * Formatear fecha para mostrar en la interfaz
   * @param {string|Date} fecha - Fecha a formatear
   * @returns {string} Fecha formateada
   */
  formatearFecha(fecha) {
    const fechaObj = typeof fecha === "string" ? new Date(fecha) : fecha;
    return fechaObj.toLocaleDateString("es-AR", {
      weekday: "short",
      day: "numeric",
      month: "numeric",
    });
  }

  /**
   * Formatear hora para mostrar en la interfaz
   * @param {string} hora - Hora en formato HH:mm
   * @returns {string} Hora formateada
   */
  formatearHora(hora) {
    return hora + ":00";
  }

  /**
   * Cargar horarios de la cancha desde el backend
   * @param {number} idCancha - ID de la cancha
   */
  async cargarHorariosCancha(idCancha) {
    try {
      // Verificar que GET_HORARIOS_CANCHAS esté definido
      if (typeof GET_HORARIOS_CANCHAS === "undefined") {
        console.error("GET_HORARIOS_CANCHAS no está definido");
        return;
      }

      const response = await fetch(
        `${GET_HORARIOS_CANCHAS}?id_cancha=${idCancha}`
      );
      const result = await response.json();

      if (result.status === "success" && result.data) {
        this.renderizarHorarios(result.data);
      } else {
        console.error("Error al cargar horarios:", result);
        this.renderizarHorariosDefault();
      }
    } catch (error) {
      console.error("Error al cargar horarios:", error);
      this.renderizarHorariosDefault();
    }
  }

  /**
   * Renderizar horarios en la interfaz (formato Google Maps)
   * @param {Array} horarios - Array de objetos con horarios
   */
  renderizarHorarios(horarios) {
    // Renderizar horarios detallados por día
    const horariosDetallados = document.getElementById("horariosDetallados");
    if (horariosDetallados) {
      let html = '<div class="horarios-lista">';

      // Asegurar que están ordenados por id_dia (1=Lunes, 7=Domingo)
      const horariosOrdenados = [...horarios].sort(
        (a, b) => a.id_dia - b.id_dia
      );

      horariosOrdenados.forEach((horario) => {
        const diaNombre = horario.dia_nombre || "Sin nombre";
        let horarioTexto = "";

        // Si tiene horarios, mostrarlos; si no, "Cerrado"
        if (horario.hora_apertura && horario.hora_cierre) {
          // Formatear horas (quitar segundos si existen)
          const apertura = horario.hora_apertura.substring(0, 5);
          const cierre = horario.hora_cierre.substring(0, 5);
          horarioTexto = `${apertura} - ${cierre}`;
        } else {
          horarioTexto = '<span class="text-danger">Cerrado</span>';
        }

        // Crear fila con puntos (dots) para separación visual
        html += `
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-medium" style="min-width: 100px;">${diaNombre}</span>
            <span class="flex-grow-1 border-bottom border-1 mx-2" style="border-style: dotted !important;"></span>
            <span class="text-end" style="min-width: 120px;">${horarioTexto}</span>
          </div>
        `;
      });

      html += "</div>";
      horariosDetallados.innerHTML = html;
    }

    // Actualizar estado actual de la cancha
    this.actualizarEstadoActual(horarios);
  }

  /**
   * Renderizar horarios por defecto cuando no hay datos
   */
  renderizarHorariosDefault() {
    const horariosDetallados = document.getElementById("horariosDetallados");
    if (horariosDetallados) {
      horariosDetallados.innerHTML =
        '<p class="text-muted">No hay horarios disponibles</p>';
    }

    const estadoActual = document.getElementById("estadoActual");
    if (estadoActual) {
      estadoActual.innerHTML =
        '<span class="text-muted"><i class="bi bi-circle-fill"></i> Estado no disponible</span>';
    }

    const horaCierre = document.getElementById("horaCierre");
    if (horaCierre) horaCierre.textContent = "";
  }

  /**
   * Actualizar estado actual de la cancha (Abierto/Cerrado)
   * @param {Array} horarios - Array de objetos con horarios
   */
  actualizarEstadoActual(horarios) {
    const ahora = new Date();
    const diaActual = ahora.getDay() === 0 ? 7 : ahora.getDay(); // Convertir domingo (0) a 7
    const horaActual = ahora.getHours() * 60 + ahora.getMinutes(); // Minutos desde medianoche

    // Buscar horario del día actual
    const horarioHoy = horarios.find((h) => h.id_dia === diaActual);
    const estadoActual = document.getElementById("estadoActual");
    const horaCierre = document.getElementById("horaCierre");

    if (!estadoActual) return;

    // Si no hay horario para hoy o está cerrado
    if (!horarioHoy || !horarioHoy.hora_apertura) {
      estadoActual.innerHTML = '<span class="badge bg-danger">Cerrado</span>';
      if (horaCierre) horaCierre.textContent = "";
      return;
    }

    // Convertir horas de apertura y cierre a minutos
    const [aH, aM] = horarioHoy.hora_apertura.split(":").map(Number);
    const [cH, cM] = horarioHoy.hora_cierre.split(":").map(Number);
    const minApertura = aH * 60 + aM;
    const minCierre = cH * 60 + cM;

    // Determinar si está abierto
    if (horaActual >= minApertura && horaActual < minCierre) {
      estadoActual.innerHTML =
        'Actualmente <span class="badge bg-success">Abierto</span>';
      if (horaCierre)
        horaCierre.textContent = `Cierra a las ${horarioHoy.hora_cierre}`;
    } else {
      estadoActual.innerHTML =
        'Actualmente <span class="badge bg-danger">Cerrado</span>';
      if (horaCierre) horaCierre.textContent = "";
    }
  }
}

// Exportar la clase para uso en otros archivos
window.PerfilCanchaBase = PerfilCanchaBase;
