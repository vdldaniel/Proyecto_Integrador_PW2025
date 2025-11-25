function configurarCancha(idCancha) {

    fetch("backend/get_config_cancha.php?id_cancha=" + idCancha)
        .then(res => res.json())
        .then(data => {

            // --- Llenar info del complejo ---
            document.getElementById("nombreComplejo").innerHTML = data.cancha.nombre;
            document.getElementById("direccionComplejo").innerHTML = data.cancha.direccion_completa;
            document.getElementById("telefonoComplejo").innerHTML = data.cancha.telefono;

            // --- Llenar días y horarios ---
            cargarHorariosEnModal(data.dias, data.horarios);

            // Guardamos el id para el submit
            document.getElementById("modalConfigurarHorarios").dataset.idCancha = idCancha;

            // Abrir modal
            let modal = new bootstrap.Modal(document.getElementById("modalConfigurarHorarios"));
            modal.show();
        });
}
function cargarHorariosEnModal(dias, horarios) {

    // 1. Reseteamos todo
    document.querySelectorAll("input[type='checkbox']").forEach(chk => chk.checked = false);

    // 2. Buscar horario general (si todos los días comparten)
    let horaApertura = null;
    let horaCierre = null;

    horarios.forEach(h => {
        // Marcamos día
        marcarDia(h.id_dia);

        // Tomamos cualquier horario para cargar apertura/cierre general
        if (!horaApertura) horaApertura = h.hora_apertura;
        if (!horaCierre) horaCierre = h.hora_cierre;
    });

    // 3. Cargar horas si existen
    if (horaApertura) document.getElementById("horaApertura").value = horaApertura;
    if (horaCierre) document.getElementById("horaCierre").value = horaCierre;
}

function marcarDia(idDia) {
    const map = {
        1: "lunes",
        2: "martes",
        3: "miercoles",
        4: "jueves",
        5: "viernes",
        6: "sabado",
        7: "domingo"
    };

    if (map[idDia]) {
        document.getElementById(map[idDia]).checked = true;
    }
}

document.getElementById("botonGuardarConfiguracion").onclick = function() {

    const idCancha = document.getElementById("modalConfigurarHorarios").dataset.idCancha;

    // Recolectar días marcados
    const dias = [];
    const map = {
        lunes: 1,
        martes: 2,
        miercoles: 3,
        jueves: 4,
        viernes: 5,
        sabado: 6,
        domingo: 7
    };

    for (let dia in map) {
        if (document.getElementById(dia).checked) {
            dias.push(map[dia]);
        }
    }

    let datos = new FormData();
    datos.append("id_cancha", idCancha);
    datos.append("horaApertura", document.getElementById("horaApertura").value);
    datos.append("horaCierre", document.getElementById("horaCierre").value);
    datos.append("dias", JSON.stringify(dias));

    fetch("backend/update_config_cancha.php", {
        method: "POST",
        body: datos
    })
    .then(r => r.json())
    .then(resp => {
        if (resp.success) {
            alert("Configuración guardada correctamente.");
        }
    });
};
