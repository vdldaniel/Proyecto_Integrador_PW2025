

document.addEventListener("DOMContentLoaded", function () {
    console.log("JS del selector cargado correctamente");

    const cargarCanchas = () => {
        fetch(BASE_URL + "src/controllers/admin-cancha/get_lista_canchas.php")
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                if (data.status !== "success" || !Array.isArray(data.data)) {
                    console.error("Respuesta de la API no exitosa o datos no válidos:", data);
                    return;
                }

                const lista = document.getElementById("listaCanchas");
                lista.innerHTML = "";
                let primeraCancha = null;

                data.data.forEach(cancha => {
                    if (!primeraCancha) primeraCancha = cancha;

                    let badge = "";
                    const idEstado = parseInt(cancha.id_estado);
                    switch (idEstado) {
                        case 3: badge = `<span class="badge bg-success">Habilitada</span>`; break;
                        case 4: badge = `<span class="badge bg-secondary">Deshabilitada</span>`; break;
                        case 5: badge = `<span class="badge bg-danger">Suspendida</span>`; break;
                        case 2: badge = `<span class="badge bg-warning text-dark">En revisión</span>`; break;
                        default: badge = `<span class="badge bg-info text-dark">Pendiente</span>`;
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
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        actualizarBanner(link.getAttribute('data-cancha-id'));
                    });

                    lista.appendChild(li);
                });

                if (primeraCancha) {
                    console.log("Cargando primera cancha:", primeraCancha.id_cancha);
                    actualizarBanner(primeraCancha.id_cancha);
                } else {
                    console.log("No se encontraron canchas para cargar.");
                }
            })
            .catch(err => console.error("Error cargando canchas:", err));
    };
    
    cargarCanchas();
});


// ==========================================================
// FUNCIÓN PARA ACTUALIZAR LA INFORMACIÓN DE CANCHA (BANNER Y PANEL)
// ==========================================================
function actualizarBanner(id) {
    if (!id) return;

    const btn = document.getElementById("btnSelectorCanchas");
    if (btn) btn.innerHTML = `<i class="bi bi-arrow-clockwise spin"></i> Cargando...`;
    
    document.querySelectorAll("#listaCanchas .dropdown-item").forEach(item => item.classList.remove("active"));
        
    fetch(BASE_URL + "src/controllers/admin-cancha/get_perfil_cancha.php?id=" + id)
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
            return res.json();
        })
        .then(json => {
            if (json.status !== "success" || !json.data || !json.data.cancha) {
                console.error("Respuesta de la API de perfil no exitosa o datos no válidos:", json);
                if (btn) btn.innerHTML = `<i class="bi bi-building"></i> Error al cargar`;
                return;
            }

            const cancha = json.data.cancha;
            const tipos = Array.isArray(json.data.tipos_partido) ? json.data.tipos_partido : [];
            const idEstado = parseInt(cancha.id_estado);

            // -------------------------------------------------
            // 1. ACTUALIZACIÓN DE SELECTOR Y BANNER SUPERIOR
            // -------------------------------------------------
            const selectedItem = document.querySelector(`#listaCanchas .dropdown-item[data-cancha-id="${cancha.id_cancha}"]`);
            if (selectedItem) selectedItem.classList.add("active");

            let badge = "";
            switch (idEstado) {
                case 3: badge = `<span class="badge bg-success">Habilitada</span>`; break;
                case 4: badge = `<span class="badge bg-secondary">Deshabilitada</span>`; break;
                case 5: badge = `<span class="badge bg-danger">Suspendida</span>`; break;
                case 2: badge = `<span class="badge bg-warning text-dark">En revisión</span>`; break; 
                default: badge = `<span class="badge bg-info text-dark">Pendiente</span>`;
            }

            if (btn) {
                btn.innerHTML = `<i class="bi bi-building"></i> ${cancha.nombre} <span class="ms-2">${badge}</span>`;
            }

            document.getElementById("nombreCancha").innerText = cancha.nombre || "Nombre Desconocido";

            const descripcion = cancha.descripcion_banner || cancha.descripcion_cancha || cancha.descripcion || ""; 
            document.getElementById("descripcionCancha").innerText = descripcion;

            const banner = document.getElementById("bannerCancha");
            let bannerUrl = cancha.banner || "<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>";

            if (!bannerUrl || bannerUrl.trim() === "" || bannerUrl.includes("<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>")) {
                const defaultBanner = banner.style.backgroundImage.match(/url\(['"]?(.*?)['"]?\)/);
                if (defaultBanner && defaultBanner[1] !== "") {
                    bannerUrl = defaultBanner[1];
                } else {
                    bannerUrl = "<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>"; 
                }
            }
            banner.style.backgroundImage = `url('${bannerUrl}')`;

            let estadoTexto = "";
            switch (idEstado) {
                case 3: estadoTexto = "Habilitada"; break;
                case 4: estadoTexto = "Deshabilitada"; break;
                case 5: estadoTexto = "Suspendida"; break;
                case 2: estadoTexto = "En revisión"; break;
                default: estadoTexto = "Pendiente";
            }
            
            let total = 0;
            tipos.forEach(t => {
                total += parseInt(t.max_participantes) || 0; 
            });

            const perfilJugadoresElement = document.getElementById("perfilJugadores");
            if (cancha.perfil_cancha_admin_mode) {
                perfilJugadoresElement.innerText = 'Admin View';
            } else if (tipos.length > 0) {
                 perfilJugadoresElement.innerText = total;
            }


            // -------------------------------------------------
            // 2. ACTUALIZACIÓN DEL PANEL DE INFORMACIÓN BÁSICA
            // -------------------------------------------------

            // Dirección
            document.getElementById("direccionCancha").innerText = cancha.direccion_completa || "Dirección no especificada";

            // Superficie
            document.getElementById("superficieCancha").innerText = cancha.superficie_nombre || "Desconocida";

            // Tipo de Cancha (Generar lista de tipos/modalidades)
            const tipoCanchaElement = document.getElementById("tipoCancha");
            if (tipos.length > 0) {
                const tiposTexto = tipos.map(t => `${t.nombre} (${t.min_participantes}-${t.max_participantes})`).join(', ');
                tipoCanchaElement.innerText = tiposTexto;
            } else {
                tipoCanchaElement.innerText = "No hay tipos de partido configurados.";
            }

            // Capacidad (Capacidad máxima total admitida)
            // Se mantiene la misma lógica de suma de max_participantes.
            document.getElementById("capacidadCancha").innerText = `${total} jugadores (Máx. total)`;

            // Estado (Para el badge en modo admin)
            const estadoBadgeElement = document.getElementById("estadoCancha");
            if (estadoBadgeElement) {
                let estadoClase = "text-bg-dark";
                
                switch (idEstado) {
                    case 3: estadoClase = "text-bg-success"; break;
                    case 4: estadoClase = "text-bg-secondary"; break;
                    case 5: estadoClase = "text-bg-danger"; break;
                    case 2: estadoClase = "text-bg-warning text-dark"; break;
                    default: estadoClase = "text-bg-info text-dark";
                }

                estadoBadgeElement.className = `badge ${estadoClase}`;
                estadoBadgeElement.innerText = estadoTexto; // Usa el texto calculado previamente
            }

            // Botón "Ver en mapa"
            const btnMapa = document.getElementById("btnVerEnMapa");
            if (btnMapa && cancha.latitud && cancha.longitud) {
                const mapUrl = `https://www.google.com/maps/search/?api=1&query=${cancha.latitud},${cancha.longitud}`;
                // NOTA: Corregí el URL de Google Maps para que sea válido
                btnMapa.onclick = () => window.open(mapUrl, '_blank');
                // Opcional: Si el botón estaba oculto o deshabilitado, lo muestras.
                // btnMapa.style.display = 'block'; 
            }
        })
        .catch(err => {
            console.error("Error al cargar el perfil de cancha:", err);
            if (btn) btn.innerHTML = `<i class="bi bi-building"></i> Error al cargar`;
        });
}