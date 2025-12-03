
document.addEventListener('DOMContentLoaded', function() {
    
    // Ruta al endpoint PHP creado
    const ENDPOINT_URL = BASE_URL + "src/controllers/torneo/torneo_crear.php"; 

    // Elementos del Modal
    const abrirInscripcionesCheckbox = document.getElementById('abrirInscripciones');
    const fechaCierreContainer = document.getElementById('fechaCierreContainer');
    const fechaCierreInput = document.getElementById('fechaCierreInscripciones');
    const btnCrearTorneo = document.getElementById('btnCrearTorneo');
    const formCrearTorneo = document.getElementById('formCrearTorneo');
    const modalCrearTorneo = new bootstrap.Modal(document.getElementById('modalCrearTorneo'));


    // Lógica para mostrar/ocultar Fecha de Cierre de Inscripciones
    abrirInscripcionesCheckbox.addEventListener('change', function() {
        if (this.checked) {
            fechaCierreContainer.classList.remove('d-none');
            fechaCierreInput.setAttribute('required', 'required');
        } else {
            fechaCierreContainer.classList.add('d-none');
            fechaCierreInput.removeAttribute('required');
            fechaCierreInput.value = '';
        }
    });

    // Manejar el envío del formulario de Creación vía AJAX
    btnCrearTorneo.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Validación de Bootstrap
        if (!formCrearTorneo.checkValidity()) {
            formCrearTorneo.classList.add('was-validated');
            return;
        }

        // 1. Recolección de datos (Usando los nombres de variables esperados por el PHP Controller)
        const data = new FormData(); 
        
        data.append('nombre', document.getElementById('nombreTorneo').value);
        data.append('fechaInicio', document.getElementById('fechaInicio').value);
        data.append('fechaFin', document.getElementById('fechaFin').value);
        data.append('fechaEstimativa', document.getElementById('fechaEstimativa').checked ? 'true' : 'false');
        data.append('cantidadEquipos', document.getElementById('cantidadEquipos').value || ''); 
        data.append('descripcion', document.getElementById('descripcionTorneo').value || ''); 
        data.append('abrirInscripciones', abrirInscripcionesCheckbox.checked ? 'true' : 'false');

        if (abrirInscripcionesCheckbox.checked) {
             data.append('fechaCierreInscripciones', document.getElementById('fechaCierreInscripciones').value);
        }

        // 2. Estado de carga del botón
        btnCrearTorneo.disabled = true;
        btnCrearTorneo.textContent = 'Creando...';
        
        // 3. Petición AJAX
        fetch(ENDPOINT_URL, { 
            method: 'POST',
            body: data
        })
        .then(response => {
            if (!response.ok) {
                // Leer el mensaje de error del PHP (puede ser un error 400 o 500)
                return response.json().then(err => { throw new Error(err.message || 'Error en la petición.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                alert('✅ ' + data.message);
                formCrearTorneo.reset();
                formCrearTorneo.classList.remove('was-validated');
                modalCrearTorneo.hide();
                // Aquí deberías llamar a la función para recargar la lista de torneos si la tienes
                // loadTorneos(); 
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error AJAX:', error);
            alert('❌ Error al crear torneo: ' + error.message);
        })
        .finally(() => {
            btnCrearTorneo.disabled = false;
            btnCrearTorneo.textContent = 'Crear Torneo';
        });
    });

    // Aquí iría el resto de la lógica JS (loadTorneos, filtros, etc.)
});