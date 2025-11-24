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
                    actualizarBanner(primeraCancha.id_cancha);
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
            const horarios = Array.isArray(json.data.horarios) ? json.data.horarios : [];
            const servicios = Array.isArray(json.data.servicios) ? json.data.servicios : []; // NUEVO
            const idEstado = parseInt(cancha.id_estado);

            //ACTUALIZACIÓN DE SELECTOR Y BANNER SUPERIOR
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


            //ACTUALIZACIÓN DEL PANEL DE INFORMACIÓN BÁSICA
            document.getElementById("direccionCancha").innerText = cancha.direccion_completa || "Dirección no especificada";
            document.getElementById("superficieCancha").innerText = cancha.superficie_nombre || "Desconocida";
            
            const tipoCanchaElement = document.getElementById("tipoCancha");
            if (tipos.length > 0) {
                const tiposTexto = tipos.map(t => `${t.nombre} (${t.min_participantes}-${t.max_participantes})`).join(', ');
                tipoCanchaElement.innerText = tiposTexto;
            } else {
                tipoCanchaElement.innerText = "No hay tipos de partido configurados.";
            }

            document.getElementById("capacidadCancha").innerText = `${total} jugadores (Máx. total)`;

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
                estadoBadgeElement.innerText = estadoTexto;
            }

            const btnMapa = document.getElementById("btnVerEnMapa");
            if (btnMapa && cancha.latitud && cancha.longitud) {
                const mapUrl = `https://www.google.com/maps/search/?api=1&query=$${cancha.latitud},${cancha.longitud}`;
                btnMapa.onclick = () => window.open(mapUrl, '_blank');
            }


            //ACTUALIZACIÓN DEL PANEL DE HORARIOS
            const diasAtencionEl = document.getElementById('diasAtencion');
            const horarioPrincipalEl = document.getElementById('horarioPrincipal');
            const estadoActualEl = document.getElementById('estadoActual');
            const horaCierreEl = document.getElementById('horaCierre');

            if (horarios.length > 0) {
                const formatTime = (timeStr) => timeStr ? timeStr.substring(0, 5) : 'N/A';
                
                const horariosAgrupados = horarios.reduce((acc, current) => {
                    const key = `${current.hora_apertura}-${current.hora_cierre}`;
                    if (!acc[key]) {
                        acc[key] = {
                            dias: [],
                            apertura: formatTime(current.hora_apertura),
                            cierre: formatTime(current.hora_cierre)
                        };
                    }
                    acc[key].dias.push(current.dia_nombre);
                    return acc;
                }, {});
                
                let diasAtencion = '';
                let horarioPrincipal = '';
                
                Object.values(horariosAgrupados).forEach(group => {
                    diasAtencion += `${group.dias.join(', ')}, `;
                    if (horarioPrincipal === '') {
                        horarioPrincipal = `${group.apertura} - ${group.cierre}`;
                    }
                });

                diasAtencionEl.innerText = diasAtencion.slice(0, -2);
                horarioPrincipalEl.innerText = horarioPrincipal;
                
                const ahora = new Date();
                const diaActual = ahora.getDay() === 0 ? 7 : ahora.getDay(); 
                const horaActualMinutos = ahora.getHours() * 60 + ahora.getMinutes();
                
                const horarioHoy = horarios.find(h => parseInt(h.id_dia) === diaActual);
                
                let estadoActual = 'Cerrado';
                let horaCierre = 'N/A';
                let estadoClase = 'text-danger';

                if (horarioHoy) {
                    const [hApertura, mApertura] = horarioHoy.hora_apertura.split(':').map(Number);
                    const [hCierre, mCierre] = horarioHoy.hora_cierre.split(':').map(Number);
                    
                    const minApertura = hApertura * 60 + mApertura;
                    let minCierre = hCierre * 60 + mCierre;
                    
                    if (minCierre === 0) minCierre = 24 * 60;
                    
                    horaCierre = formatTime(horarioHoy.hora_cierre);

                    if (horaActualMinutos >= minApertura && horaActualMinutos < minCierre) {
                        estadoActual = 'Abierto';
                        estadoClase = 'text-success';
                    }
                }
                
                estadoActualEl.innerHTML = `<i class="bi bi-circle-fill"></i> ${estadoActual}`;
                estadoActualEl.className = `fw-bold ${estadoClase}`;
                horaCierreEl.innerText = `Cierra a las ${horaCierre}`;

            } else {
                diasAtencionEl.innerText = "Horarios no definidos";
                horarioPrincipalEl.innerText = "N/A";
                estadoActualEl.innerHTML = `<i class="bi bi-circle-fill"></i> Cerrado`;
                estadoActualEl.className = `fw-bold text-danger`;
                horaCierreEl.innerText = `Cierra a las N/A`;
            }

            //ACTUALIZACIÓN DEL PANEL DE SERVICIOS
            const serviciosContainer = document.getElementById('serviciosContainer');
            if (serviciosContainer) {
                if (servicios.length > 0) {
                    // Mapeo manual de nombres de servicio a íconos/colores
                    const iconos = {
                        "Vestuarios": { icon: "bi-droplet", color: "text-primary" },
                        "Duchas": { icon: "bi-shield-check", color: "text-success" },
                        "Estacionamiento": { icon: "bi-car-front", color: "text-info" },
                        "Bar": { icon: "bi-cup-hot", color: "text-danger" },
                        "WIFI gratis": { icon: "bi-wifi", color: "text-primary" },
                        "Iluminación LED": { icon: "bi-lightbulb", color: "text-warning" }, // Asume que este existe aunque no esté en la DB
                    };

                    let htmlServicios = '';
                    const serviciosNombres = servicios.map(s => s.servicio_nombre);

                    // Lista de servicios que quieres mostrar (incluyendo los que no están en la DB pero sí en el HTML)
                    const serviciosFijos = ["Vestuarios", "Duchas", "Estacionamiento", "Bar", "WIFI gratis", "Iluminación LED"];

                    serviciosFijos.forEach(nombre => {
                        const servicioEncontrado = serviciosNombres.includes(nombre);
                        const data = iconos[nombre] || { icon: "bi-question-circle", color: "text-muted" };
                        
                        // Si el servicio existe en la DB o es uno fijo que quieres mostrar si está disponible (como la iluminación LED)
                        if (servicioEncontrado) {
                             htmlServicios += `
                                <div class="col-6 mb-2" data-servicio="${nombre}">
                                    <small><i class="bi ${data.icon} ${data.color}"></i> ${nombre}</small>
                                </div>
                            `;
                        }
                    });

                    // Si estás en modo Admin, puedes querer añadir servicios adicionales
                    if (cancha.perfil_cancha_admin_mode) {
                        // Ejemplos de servicios solo visibles en modo admin
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
        .catch(err => {
            console.error("Error al cargar el perfil de cancha:", err);
            if (btn) btn.innerHTML = `<i class="bi bi-building"></i> Error al cargar`;
        });
}