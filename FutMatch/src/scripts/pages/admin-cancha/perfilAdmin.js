// VARIABLES
let SUPERFICIES_CACHE = [];
let TIPOS_PARTIDO_CACHE = [];

let CANCHAS_CACHE = {};
let ID_CANCHA_ACTUAL = null; // Variable para almacenar el ID de la cancha visible actualmente

// TOASTS
// ==========================================================
function mostrarToast(message, isSuccess = true) {
  // Implementación de una función de notificación (e.g., usando Bootstrap Toast o alert simple)
  if (isSuccess) {
    alert(`Éxito: ${message}`);
  } else {
    alert(`Error: ${message}`);
  }
}

// FUNCIÓN SELECTORES (CORREGIDA: Ahora devuelve una Promise)
// ==========================================================
const cargarSelectores = () => {
  // Usamos Promise.all para asegurarnos de que ambas llamadas fetch terminen
  return Promise.all([
    // 1. Cargar Tipos de Superficie
    fetch(BASE_URL + "src/controllers/admin-cancha/get_superficies.php")
      .then((r) => r.json())
      .then((json) => {
        SUPERFICIES_CACHE = json.data || [];
        const select = document.getElementById("editTipoSuperficie");
        // Importante: Limpiar y rellenar el select
        select.innerHTML = '<option value="">Seleccione superficie</option>';
        SUPERFICIES_CACHE.forEach((s) => {
          select.innerHTML += `<option value="${s.id_superficie}">${s.nombre}</option>`;
        });
      })
      .catch((err) => console.error("Error cargando superficies:", err)),

    // 2. Cargar Tipos de Partido (Capacidad)
    fetch(BASE_URL + "src/controllers/admin-cancha/get_tipo_partido.php")
      .then((r) => r.json())
      .then((json) => {
        TIPOS_PARTIDO_CACHE = json.data || [];
        const select = document.getElementById("editCapacidadCancha");
        // Importante: Limpiar y rellenar el select
        select.innerHTML =
          '<option value="">Seleccione tipo de cancha</option>';
        TIPOS_PARTIDO_CACHE.forEach((t) => {
          select.innerHTML += `<option value="${t.id_tipo_partido}">${t.nombre} (${t.min_participantes}vs${t.max_participantes})</option>`;
        });
      })
      .catch((err) => console.error("Error cargando tipos de partido:", err)),
  ]);
};

// FUNCIÓN PARA ABRIR Y RELLENAR EL MODAL DE EDICIÓN
// ==========================================================
function abrirModalEditar(id) {
  const cancha = CANCHAS_CACHE[id];

  if (!cancha) {
    console.error(
      "No se encontró la cancha con ID",
      id,
      "en la caché. Cargue el perfil primero."
    );
    mostrarToast(
      "No se pudo cargar la información de la cancha para editar. Inténtelo de nuevo.",
      false
    );
    return;
  }

  // 1. Rellenar campos de texto e ID oculta
  document.getElementById("editCanchaId").value = cancha.id_cancha;
  document.getElementById("editNombreCancha").value = cancha.nombre || "";

  // **CORRECCIÓN CLAVE 1: Revisar la propiedad de Ubicación/Dirección.**
  // Asume que la dirección puede venir como 'direccion_completa' o quizás solo 'ubicacion' o 'direccion'
  document.getElementById("editUbicacionCancha").value =
    cancha.direccion_completa || cancha.ubicacion || "";

  // **CORRECCIÓN CLAVE 2: Revisar la propiedad de Descripción.**
  // Intenta con las diferentes propiedades que aparecen en el perfil:
  document.getElementById("editDescripcionCancha").value =
    cancha.descripcion || cancha.descripcion_cancha || "";

  // 2. Seleccionar el valor de Superficie
  document.getElementById("editTipoSuperficie").value =
    cancha.id_superficie || "";

  // 3. Seleccionar el valor de Capacidad/Tipo de Partido
  // Utilizamos el id_tipo_partido directamente si existe, si no, intentamos tomar el primero de la lista.
  const idTipoPartidoActual =
    cancha.id_tipo_partido ||
    (cancha.tipos_partido && cancha.tipos_partido.length > 0
      ? cancha.tipos_partido[0].id_tipo_partido
      : "");

  // Asegúrate de que este valor coincida con un <option> en el select 'editCapacidadCancha'
  document.getElementById("editCapacidadCancha").value = idTipoPartidoActual;

  const inputUbicacion = document.getElementById("editUbicacionCancha");
  const alertUbicacion = document.getElementById("alertUbicacionEditar");

  inputUbicacion.oninput = () => {
    alertUbicacion.classList.remove("d-none");
  };
  alertUbicacion.classList.add("d-none");

  // 4. Abrir modal
  const modal = new bootstrap.Modal(
    document.getElementById("modalEditarCancha")
  );
  modal.show();
}

// FUNCIÓN PARA MANEJAR LA ACTUALIZACIÓN (LISTENER)
// ==========================================================
document
  .getElementById("btnActualizarCancha")
  .addEventListener("click", function () {
    const data = new FormData();
    data.append("id_cancha", document.getElementById("editCanchaId").value);
    data.append("nombre", document.getElementById("editNombreCancha").value);
    data.append(
      "descripcion",
      document.getElementById("editDescripcionCancha").value
    );
    data.append(
      "ubicacion",
      document.getElementById("editUbicacionCancha").value
    );
    data.append(
      "superficie",
      document.getElementById("editTipoSuperficie").value
    );
    data.append(
      "id_tipo_partido",
      document.getElementById("editCapacidadCancha").value
    );

    fetch(BASE_URL + "src/controllers/admin-cancha/update_cancha.php", {
      method: "POST",
      body: data,
    })
      .then((r) => r.json())
      .then((res) => {
        if (res.status === "success") {
          const modal = bootstrap.Modal.getInstance(
            document.getElementById("modalEditarCancha")
          );
          if (modal) modal.hide();

          mostrarToast("Cancha actualizada exitosamente.", true); // Mensaje de éxito

          // Recargar lista de canchas y perfil de la cancha actual
          cargarCanchas().then(() => {
            if (ID_CANCHA_ACTUAL) {
              actualizarBanner(ID_CANCHA_ACTUAL);
            }
          });
        } else {
          console.error(
            "Error del servidor al actualizar:",
            res.message || res
          );
          mostrarToast(
            "Error al actualizar la cancha: " +
              (res.message || "Error desconocido"),
            false
          ); // Mensaje de error
        }
      })
      .catch((err) => {
        console.error("Error de red/fetch al actualizar:", err);
        mostrarToast("Error de conexión al actualizar la cancha.", false); // Mensaje de error de red
      });
  });

// DOCUMENT READY / CARGA INICIAL (CORREGIDA: Espera a cargarSelectores)
// ==========================================================

const cargarCanchas = () => {
  return fetch(BASE_URL + "src/controllers/admin-cancha/get_lista_canchas.php")
    .then((response) => {
      if (!response.ok)
        throw new Error(`HTTP error! status: ${response.status}`);
      return response.json();
    })
    .then((data) => {
      if (data.status !== "success" || !Array.isArray(data.data)) {
        console.error(
          "Respuesta de la API no exitosa o datos no válidos:",
          data
        );
        return;
      }

      // Almacenar caché para el modal de edición como un objeto para acceso rápido
      CANCHAS_CACHE = data.data.reduce((acc, cancha) => {
        // Solo almacenamos los datos básicos de la lista aquí
        acc[cancha.id_cancha] = cancha;
        return acc;
      }, {});

      const lista = document.getElementById("listaCanchas");
      lista.innerHTML = "";
      let primeraCancha = null;

      data.data.forEach((cancha) => {
        if (!primeraCancha) primeraCancha = cancha;

        let badge = "";
        const idEstado = parseInt(cancha.id_estado);
        switch (idEstado) {
          case 3:
            badge = `<span class="badge bg-success">Habilitada</span>`;
            break;
          case 4:
            badge = `<span class="badge bg-secondary">Deshabilitada</span>`;
            break;
          case 5:
            badge = `<span class="badge bg-danger">Suspendida</span>`;
            break;
          case 2:
            badge = `<span class="badge bg-warning text-dark">En revisión</span>`;
            break;
          default:
            badge = `<span class="badge bg-info text-dark">Pendiente</span>`;
        }

        const li = document.createElement("li");
        li.innerHTML = `
                    <a class="dropdown-item d-flex justify-content-between align-items-center"
                        href="#"
                        data-cancha-id="${cancha.id_cancha}">
                        <span><i class="bi bi-building"></i> ${cancha.nombre}</span>
                        ${badge}
                    </a>
                `;

        const link = li.querySelector(".dropdown-item");
        link.addEventListener("click", (e) => {
          e.preventDefault();
          actualizarBanner(link.getAttribute("data-cancha-id"));
        });

        lista.appendChild(li);
      });

      if (primeraCancha) {
        actualizarBanner(primeraCancha.id_cancha);
      }
    })
    .catch((err) => console.error("Error cargando canchas:", err));
};

async function actualizarBanner(id) {
  if (!id) return;

  ID_CANCHA_ACTUAL = id; // Almacenar ID actual

  const btn = document.getElementById("btnSelectorCanchas");
  if (btn)
    btn.innerHTML = `<i class="bi bi-arrow-clockwise spin"></i> Cargando...`;

  document
    .querySelectorAll("#listaCanchas .dropdown-item")
    .forEach((item) => item.classList.remove("active"));

  fetch(
    BASE_URL + "src/controllers/admin-cancha/get_perfil_cancha.php?id=" + id
  )
    .then((res) => {
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      return res.json();
    })
    .then(async (json) => {
      if (json.status !== "success" || !json.data || !json.data.cancha) {
        console.error(
          "Respuesta de la API de perfil no exitosa o datos no válidos:",
          json
        );
        if (btn)
          btn.innerHTML = `<i class="bi bi-building"></i> Error al cargar`;
        return;
      }

      const cancha = json.data.cancha;
      const tipos = Array.isArray(json.data.tipos_partido)
        ? json.data.tipos_partido
        : [];
      const servicios = Array.isArray(json.data.servicios)
        ? json.data.servicios
        : [];
      const idEstado = parseInt(cancha.id_estado);

      CANCHAS_CACHE[id] = {
        ...cancha,
        tipos_partido: tipos,
        servicios: servicios,
      };

      // ===== CARGAR HORARIOS DESDE EL MÉTODO CENTRALIZADO =====
      // Usar el método de la clase base PerfilCanchaBase para cargar horarios
      if (typeof PerfilCanchaBase !== "undefined") {
        const baseInstance = new PerfilCanchaBase();
        await baseInstance.cargarHorariosCancha(id);
        // Los horarios ya fueron renderizados por el método centralizado
        // pero si necesitamos la lógica específica del admin, podemos sobrescribirla después
      }

      // 1. ACTUALIZACIÓN DE SELECTOR Y BANNER SUPERIOR

      const selectedItem = document.querySelector(
        `#listaCanchas .dropdown-item[data-cancha-id="${cancha.id_cancha}"]`
      );
      if (selectedItem) selectedItem.classList.add("active");

      let badge = "";
      switch (idEstado) {
        case 3:
          badge = `<span class="badge bg-success">Habilitada</span>`;
          break;
        case 4:
          badge = `<span class="badge bg-secondary">Deshabilitada</span>`;
          break;
        case 5:
          badge = `<span class="badge bg-danger">Suspendida</span>`;
          break;
        case 2:
          badge = `<span class="badge bg-warning text-dark">En revisión</span>`;
          break;
        default:
          badge = `<span class="badge bg-info text-dark">Pendiente</span>`;
      }

      if (btn) {
        btn.innerHTML = `<i class="bi bi-building"></i> ${cancha.nombre} <span class="ms-2">${badge}</span>`;
      }

      document.getElementById("nombreCancha").innerText =
        cancha.nombre || "Nombre Desconocido";

      const descripcion =
        cancha.descripcion_banner ||
        cancha.descripcion_cancha ||
        cancha.descripcion ||
        "";
      document.getElementById("descripcionCancha").innerText = descripcion;

      const banner = document.getElementById("bannerCancha");
      let bannerUrl =
        cancha.banner || "<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>";

      if (
        !bannerUrl ||
        bannerUrl.trim() === "" ||
        bannerUrl.includes("<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>")
      ) {
        const defaultBanner = banner.style.backgroundImage.match(
          /url\(['"]?(.*?)['"]?\)/
        );
        if (defaultBanner && defaultBanner[1] !== "") {
          bannerUrl = defaultBanner[1];
        } else {
          bannerUrl = "<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>";
        }
      }
      banner.style.backgroundImage = `url('${bannerUrl}')`;

      let estadoTexto = "";
      switch (idEstado) {
        case 3:
          estadoTexto = "Habilitada";
          break;
        case 4:
          estadoTexto = "Deshabilitada";
          break;
        case 5:
          estadoTexto = "Suspendida";
          break;
        case 2:
          estadoTexto = "En revisión";
          break;
        default:
          estadoTexto = "Pendiente";
      }

      let total = 0;
      tipos.forEach((t) => {
        total += parseInt(t.max_participantes) || 0;
      });

      const perfilJugadoresElement = document.getElementById("perfilJugadores");
      if (cancha.perfil_cancha_admin_mode) {
        perfilJugadoresElement.innerText = "Admin View";
      } else if (tipos.length > 0) {
        perfilJugadoresElement.innerText = total;
      }

      // 2. ACTUALIZACIÓN DEL PANEL DE INFORMACIÓN BÁSICA
      document.getElementById("direccionCancha").innerText =
        cancha.direccion_completa || "Dirección no especificada";
      document.getElementById("superficieCancha").innerText =
        cancha.superficie_nombre || "Desconocida";

      const tipoCanchaElement = document.getElementById("tipoCancha");
      if (tipos.length > 0) {
        const tiposTexto = tipos
          .map(
            (t) => `${t.nombre} (${t.min_participantes}-${t.max_participantes})`
          )
          .join(", ");
        tipoCanchaElement.innerText = tiposTexto;
      } else {
        tipoCanchaElement.innerText = "No hay tipos de partido configurados.";
      }

      document.getElementById(
        "capacidadCancha"
      ).innerText = `${total} jugadores (Máx. total)`;

      const estadoBadgeElement = document.getElementById("estadoCancha");
      if (estadoBadgeElement) {
        let estadoClase = "text-bg-dark";
        switch (idEstado) {
          case 3:
            estadoClase = "text-bg-success";
            break;
          case 4:
            estadoClase = "text-bg-secondary";
            break;
          case 5:
            estadoClase = "text-bg-danger";
            break;
          case 2:
            estadoClase = "text-bg-warning text-dark";
            break;
          default:
            estadoClase = "text-bg-info text-dark";
        }
        estadoBadgeElement.className = `badge ${estadoClase}`;
        estadoBadgeElement.innerText = estadoTexto;
      }

      const btnMapa = document.getElementById("btnVerEnMapa");
      if (btnMapa && cancha.latitud && cancha.longitud) {
        // Se corrigió la URL del mapa para ser funcional
        const mapUrl = `https://www.google.com/maps/search/?api=1&query=${cancha.latitud},${cancha.longitud}`;
        btnMapa.onclick = () => window.open(mapUrl, "_blank");
      }

      // 3. HORARIOS - Manejados por el método centralizado cargarHorariosCancha() arriba
      // No duplicar código aquí

      // 4. ACTUALIZACIÓN DEL PANEL DE SERVICIOS
      const serviciosContainer = document.getElementById("serviciosContainer");
      if (serviciosContainer) {
        if (servicios.length > 0) {
          const iconos = {
            Vestuarios: { icon: "bi-droplet", color: "text-primary" },
            Duchas: { icon: "bi-shield-check", color: "text-success" },
            Estacionamiento: { icon: "bi-car-front", color: "text-info" },
            Bar: { icon: "bi-cup-hot", color: "text-danger" },
            "WIFI gratis": { icon: "bi-wifi", color: "text-primary" },
            "Iluminación LED": { icon: "bi-lightbulb", color: "text-warning" },
          };

          let htmlServicios = "";
          const serviciosNombres = servicios.map((s) => s.servicio_nombre);

          const serviciosFijos = [
            "Vestuarios",
            "Duchas",
            "Estacionamiento",
            "Bar",
            "WIFI gratis",
            "Iluminación LED",
          ];

          serviciosFijos.forEach((nombre) => {
            const servicioEncontrado = serviciosNombres.includes(nombre);
            const data = iconos[nombre] || {
              icon: "bi-question-circle",
              color: "text-muted",
            };

            if (servicioEncontrado) {
              htmlServicios += `
                                <div class="col-6 mb-2" data-servicio="${nombre}">
                                    <small><i class="bi ${data.icon} ${data.color}"></i> ${nombre}</small>
                                </div>
                            `;
            }
          });

          if (cancha.perfil_cancha_admin_mode) {
            htmlServicios += `
                            <div class="col-6 mb-2">
                                <small><i class="bi bi-shield-shaded text-secondary"></i> Seguridad 24hs</small>
                            </div>
                            <div class="col-6 mb-2">
                                <small><i class="bi bi-tools text-warning"></i> Mantenimiento</small>
                            </div>
                        `;
          }

          serviciosContainer.innerHTML = htmlServicios;
        } else {
          serviciosContainer.innerHTML = `<div class="col-12"><p class="text-muted">No hay servicios asociados a esta cancha.</p></div>`;
        }
      }
    })
    .catch((err) => {
      console.error("Error al cargar el perfil de cancha:", err);
      if (btn) btn.innerHTML = `<i class="bi bi-building"></i> Error al cargar`;
    });
}

document.addEventListener("DOMContentLoaded", function () {
  console.log("JS del selector cargado correctamente");

  // antes de proceder con la carga inicial de canchas.
  cargarSelectores()
    .then(() => {
      console.log(
        "Selectores de edición (Superficie, Capacidad) cargados y listos."
      );

      // Exponer cargarCanchas globalmente y ejecutar la carga inicial
      window.cargarCanchas = cargarCanchas;
      window.actualizarBanner = actualizarBanner;
      window.abrirModalEditar = abrirModalEditar; // Exponer para el botón HTML
      cargarCanchas();
    })
    .catch((error) => {
      console.error(
        "Error crítico al inicializar la aplicación. No se pudieron cargar los selectores de edición.",
        error
      );
      mostrarToast(
        "Error crítico de inicialización. Recargue la página.",
        false
      );
    });
});
