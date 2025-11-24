/**
 * REGISTRO ADMIN CANCHA - Validaciones del formulario + Mapa Leaflet
 * ====================================================================
 * Validaciones en tiempo real y al submit
 * Implementación de mapa interactivo para seleccionar ubicación
 */

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formRegistroAdmin");
  const inputNombre = document.getElementById("inputNombre");
  const inputApellido = document.getElementById("inputApellido");
  const inputNombreCancha = document.getElementById("inputNombreCancha");
  const inputTelefono = document.getElementById("inputTelefono");
  const inputEmail = document.getElementById("inputEmail");
  const checkTerminos = document.getElementById("checkTerminos");
  const inputContacto = document.getElementById("inputContacto");
  const inputHorario = document.getElementById("inputHorario");

  // Campos del mapa
  const inputBuscadorDireccion = document.getElementById(
    "inputBuscadorDireccion"
  );
  const btnBuscarDireccion = document.getElementById("btnBuscarDireccion");
  const inputDireccion = document.getElementById("inputDireccion");
  const inputLatitud = document.getElementById("inputLatitud");
  const inputLongitud = document.getElementById("inputLongitud");
  const inputPais = document.getElementById("inputPais");
  const inputProvincia = document.getElementById("inputProvincia");
  const inputLocalidad = document.getElementById("inputLocalidad");
  const direccionSeleccionada = document.getElementById(
    "direccionSeleccionada"
  );
  const textoDireccion = document.getElementById("textoDireccion");

  // ===================================
  // INICIALIZACIÓN DEL MAPA LEAFLET
  // ===================================
  // Centrado en La Plata, Argentina por defecto
  const map = L.map("map").setView([-34.9214, -57.9544], 13);

  // Añadir capa de OpenStreetMap
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: "© OpenStreetMap contributors",
    maxZoom: 19,
  }).addTo(map);

  // Marcador arrastrable
  let marker = L.marker([-34.9214, -57.9544], {
    draggable: true,
  }).addTo(map);

  // Actualizar coordenadas cuando se arrastra el marcador
  marker.on("dragend", function (e) {
    const position = marker.getLatLng();
    obtenerDireccionPorCoordenadas(position.lat, position.lng);
  });

  // ===================================
  // GEOCODIFICACIÓN: Búsqueda de dirección
  // ===================================
  btnBuscarDireccion.addEventListener("click", buscarDireccion);
  inputBuscadorDireccion.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      buscarDireccion();
    }
  });

  function buscarDireccion() {
    const query = inputBuscadorDireccion.value.trim();
    if (!query) {
      alert("Por favor, ingresá una dirección para buscar.");
      return;
    }

    // Usar proxy para evitar problemas de CORS
    const url = `${GEOCODING_PROXY}?tipo=search&q=${encodeURIComponent(query)}`;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data.length > 0) {
          const result = data[0];
          const lat = parseFloat(result.lat);
          const lon = parseFloat(result.lon);

          // Mover el mapa y el marcador
          map.setView([lat, lon], 16);
          marker.setLatLng([lat, lon]);

          // Obtener dirección detallada
          obtenerDireccionPorCoordenadas(lat, lon);
        } else {
          alert(
            "No se encontró la dirección. Intentá con otra búsqueda o arrastrá el marcador en el mapa."
          );
        }
      })
      .catch((error) => {
        console.error("Error en la búsqueda:", error);
        alert("Error al buscar la dirección. Intentá nuevamente.");
      });
  }

  // ===================================
  // GEOCODIFICACIÓN INVERSA: Coordenadas -> Dirección
  // ===================================
  function obtenerDireccionPorCoordenadas(lat, lon) {
    const url = `${GEOCODING_PROXY}?tipo=reverse&lat=${lat}&lon=${lon}`;

    fetch(url)
      .then((response) => response.json())
      .then((data) => {
        if (data && data.address) {
          const address = data.address;
          const displayName = data.display_name;

          // Guardar dirección completa
          inputDireccion.value = displayName;
          inputLatitud.value = lat;
          inputLongitud.value = lon;

          // Extraer componentes de la dirección
          inputPais.value = address.country || "";
          inputProvincia.value = address.state || "";
          inputLocalidad.value =
            address.city || address.town || address.village || "";

          // Mostrar dirección seleccionada
          textoDireccion.textContent = displayName;
          direccionSeleccionada.classList.remove("d-none");

          // Marcar como válido
          inputDireccion.setCustomValidity("");
        }
      })
      .catch((error) => {
        console.error("Error en geocodificación inversa:", error);
      });
  }

  // ===================================
  // VALIDACIÓN: Nombre y Apellido - Solo letras y espacios
  // ===================================
  function validarSoloLetras(input) {
    input.addEventListener("input", function (e) {
      // Remover cualquier carácter que no sea letra o espacio
      this.value = this.value.replace(/[^a-záéíóúñA-ZÁÉÍÓÚÑ\s]/g, "");
    });

    input.addEventListener("keypress", function (e) {
      // Prevenir entrada de números y caracteres especiales
      const char = String.fromCharCode(e.which);
      if (!/[a-záéíóúñA-ZÁÉÍÓÚÑ\s]/.test(char)) {
        e.preventDefault();
      }
    });
  }

  validarSoloLetras(inputNombre);
  validarSoloLetras(inputApellido);

  // Validación de campos al escribir
  [inputNombre, inputApellido, inputNombreCancha].forEach((campo) => {
    campo.addEventListener("input", function () {
      if (this.value.trim()) {
        this.classList.remove("is-invalid");
        this.classList.add("is-valid");
      } else {
        this.classList.remove("is-valid");
      }
    });
  });

  // ===================================
  // VALIDACIÓN: Teléfono - Solo números y guiones
  // ===================================
  inputTelefono.addEventListener("input", function (e) {
    // Permitir solo números, guiones y espacios
    this.value = this.value.replace(/[^0-9\-\s]/g, "");
  });

  inputTelefono.addEventListener("keypress", function (e) {
    const char = String.fromCharCode(e.which);
    if (!/[0-9\-\s]/.test(char)) {
      e.preventDefault();
    }
  });

  // ===================================
  // VALIDACIÓN: Email
  // ===================================
  inputEmail.addEventListener("blur", function () {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (this.value && !emailRegex.test(this.value)) {
      this.setCustomValidity("Ingresá un email válido");
      this.classList.add("is-invalid");
      this.classList.remove("is-valid");
    } else if (this.value) {
      this.setCustomValidity("");
      this.classList.remove("is-invalid");
      this.classList.add("is-valid");
    }
  });

  // ===================================
  // VALIDACIÓN: Teléfono - Formato válido
  // ===================================
  inputTelefono.addEventListener("blur", function () {
    // Mínimo 8 dígitos (sin contar guiones y espacios)
    const digitos = this.value.replace(/[\-\s]/g, "");
    if (this.value && digitos.length < 8) {
      this.setCustomValidity("El teléfono debe tener al menos 8 dígitos");
      this.classList.add("is-invalid");
      this.classList.remove("is-valid");
    } else if (this.value) {
      this.setCustomValidity("");
      this.classList.remove("is-invalid");
      this.classList.add("is-valid");
    }
  });

  // ===================================
  // VALIDACIÓN: Selects (Contacto, Horario)
  // ===================================
  const selects = [inputContacto, inputHorario];

  selects.forEach((select) => {
    select.addEventListener("change", function () {
      if (this.value) {
        this.classList.remove("is-invalid");
        this.classList.add("is-valid");
      } else {
        this.classList.add("is-invalid");
        this.classList.remove("is-valid");
      }
    });
  });

  // ===================================
  // VALIDACIÓN GENERAL EN BLUR
  // ===================================
  const camposObligatorios = [
    inputNombre,
    inputApellido,
    inputNombreCancha,
    inputTelefono,
    inputEmail,
  ];

  camposObligatorios.forEach((campo) => {
    campo.addEventListener("blur", function () {
      if (!this.value.trim()) {
        this.classList.add("is-invalid");
        this.classList.remove("is-valid");
      }
    });
  });

  // ===================================
  // SUBMIT DEL FORMULARIO
  // ===================================
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();

    // Resetear estados
    let isValid = true;

    // Validar nombre
    if (!inputNombre.value.trim()) {
      inputNombre.classList.add("is-invalid");
      isValid = false;
    }

    // Validar apellido
    if (!inputApellido.value.trim()) {
      inputApellido.classList.add("is-invalid");
      isValid = false;
    }

    // Validar nombre de cancha
    if (!inputNombreCancha.value.trim()) {
      inputNombreCancha.classList.add("is-invalid");
      isValid = false;
    }

    // Validar dirección (coordenadas del mapa)
    if (!inputDireccion.value || !inputLatitud.value || !inputLongitud.value) {
      inputDireccion.setCustomValidity(
        "Debes seleccionar una ubicación en el mapa"
      );
      document.getElementById("errorDireccion").style.display = "block";
      isValid = false;
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!inputEmail.value || !emailRegex.test(inputEmail.value)) {
      inputEmail.classList.add("is-invalid");
      isValid = false;
    }

    // Validar teléfono
    const digitos = inputTelefono.value.replace(/[\-\s]/g, "");
    if (!inputTelefono.value || digitos.length < 8) {
      inputTelefono.classList.add("is-invalid");
      isValid = false;
    }

    // Validar términos y condiciones
    if (!checkTerminos.checked) {
      checkTerminos.classList.add("is-invalid");
      const feedbackDiv =
        checkTerminos.parentElement.querySelector(".invalid-feedback");
      if (feedbackDiv) {
        feedbackDiv.style.display = "block";
      }
      isValid = false;
    } else {
      checkTerminos.classList.remove("is-invalid");
      const feedbackDiv =
        checkTerminos.parentElement.querySelector(".invalid-feedback");
      if (feedbackDiv) {
        feedbackDiv.style.display = "none";
      }
    }

    // Validar método de contacto
    if (!inputContacto.value) {
      inputContacto.classList.add("is-invalid");
      isValid = false;
    }

    // Validar horario de preferencia
    if (!inputHorario.value) {
      inputHorario.classList.add("is-invalid");
      isValid = false;
    }

    // Marcar formulario como validado
    form.classList.add("was-validated");

    // Si todo es válido, enviar
    if (isValid) {
      console.log("Formulario válido. Enviando...");
      // Enviar el formulario al servidor
      form.submit();
    } else {
      // Scroll al primer campo inválido
      const primerInvalido = form.querySelector(".is-invalid");
      if (primerInvalido) {
        primerInvalido.scrollIntoView({ behavior: "smooth", block: "center" });
        primerInvalido.focus();
      }
    }
  });

  // ===================================
  // VALIDACIÓN VISUAL DEL CHECKBOX
  // ===================================
  checkTerminos.addEventListener("change", function () {
    if (this.checked) {
      this.classList.remove("is-invalid");
      const feedbackDiv = this.parentElement.querySelector(".invalid-feedback");
      if (feedbackDiv) {
        feedbackDiv.style.display = "none";
      }
    }
  });
});
