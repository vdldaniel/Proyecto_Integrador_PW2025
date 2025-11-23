document.addEventListener("DOMContentLoaded", function () {
    console.log("JS del selector cargado correctamente");
    fetch(BASE_URL + "src/controllers/admin-cancha/get_lista_canchas.php")
        .then(response => response.json())
        .then(data => {
            if (data.status !== "success") return;

            const lista = document.getElementById("listaCanchas");
            const btn = document.getElementById("btnSelectorCanchas");

            lista.innerHTML = "";

            data.data.forEach(cancha => {

                // Badge según estado
                let badge = "";
                switch (parseInt(cancha.id_estado)) {
                    case 3: badge = `<span class="badge bg-success">Habilitada</span>`; break;
                    case 4: badge = `<span class="badge bg-secondary">Deshabilitada</span>`; break;
                    case 5: badge = `<span class="badge bg-danger">Suspendida</span>`; break;
                    case 2: badge = `<span class="badge bg-warning text-dark">En revisión</span>`; break;
                    default:
                        badge = `<span class="badge bg-info text-dark">Pendiente</span>`;
                }

                // Crear item del dropdown
                const li = document.createElement("li");
                li.innerHTML = `
                    <a class="dropdown-item d-flex justify-content-between align-items-center"
                       href="perfil_cancha.php?id=${cancha.id_cancha}">
                        <span><i class="bi bi-building"></i> ${cancha.nombre}</span>
                        ${badge}
                    </a>
                `;

                lista.appendChild(li);

                // Si es la cancha actual → actualizar botón con nombre + badge
                if (parseInt("<?= $id_cancha ?>") === parseInt(cancha.id_cancha)) {
                    btn.innerHTML = `
                        <i class="bi bi-building"></i> 
                        ${cancha.nombre}
                        <span class="ms-2">${badge}</span>
                    `;
                    li.querySelector("a").classList.add("active");
                }

            });

        })
        .catch(err => console.error("Error cargando canchas:", err));
});


function actualizarBanner(id) {

    fetch(BASE_URL + "src/controllers/admin-cancha/get_perfil_cancha.php?id=" + id)
        .then(res => res.json())
        .then(json => {

            if (json.status !== "success") return;

            const cancha = json.data.cancha;
            const tipos = json.data.tipos_partido;


            document.getElementById("nombreCancha").innerText = cancha.nombre;
            document.getElementById("descripcionCancha").innerText =
                cancha.descripcion_banner
                ?? cancha.descripcion_cancha
                ?? cancha.descripcion
                ?? "";



            const banner = document.querySelector(".profile-banner-image");

            banner.style.backgroundImage = `url('${cancha.banner || "<?= IMG_BANNER_PERFIL_CANCHA_DEFAULT ?>"}')`;

            let estadoBadge = "";
            switch (parseInt(cancha.id_estado)) {
                case 3: estadoBadge = "Habilitada"; break;
                case 4: estadoBadge = "Deshabilitada"; break;
                case 5: estadoBadge = "Suspendida"; break;
                default: estadoBadge = "Pendiente";
            }
            document.getElementById("estadoCancha").innerText = estadoBadge;

            // ------ Calcular total jugadores (sumando los tipos de partido) ------
            let total = 0;
            tipos.forEach(t => total += t.max_participantes);

            document.getElementById("perfilJugadores").innerText = total;

        })
        .catch(err => console.error("Error:", err));
}
