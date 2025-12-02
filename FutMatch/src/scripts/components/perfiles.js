/**
 * Función compartida para cargar información de perfil
 * Reutilizable por todos los tipos de perfil (jugador, cancha, admin)
 * @param {string} id - ID del perfil a cargar
 * @param {string} tipo - Tipo de perfil ('jugador', 'cancha', 'admin_cancha')
 * @returns {Promise<Object>} Datos del perfil
 */
async function cargarInfoPerfil(id, tipo) {
  try {
    const url = `${GET_INFO_PERFIL}?id=${id}&tipo=${tipo}`;
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }

    const data = await response.json();
    console.log("Información del perfil cargada:", data);
    return data;
  } catch (error) {
    console.error("Error al obtener la información del perfil:", error);
    throw error;
  }
}

// Compatibilidad con código existente
const url =
  typeof CURRENT_USER_ID !== "undefined" && typeof TIPO_PERFIL !== "undefined"
    ? `${GET_INFO_PERFIL}?id=${CURRENT_USER_ID}&tipo=${TIPO_PERFIL}`
    : null;

document.addEventListener("DOMContentLoaded", function () {
  if (url) {
    getInfoPerfil();
  }
});

async function getInfoPerfil() {
  try {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Error en la solicitud: " + response.status);
    }
    const data = await response.json();
    console.log("Información del perfil:", data);
  } catch (error) {
    console.error("Error al obtener la información del perfil:", error);
  }
}

// Exportar para uso global
window.cargarInfoPerfil = cargarInfoPerfil;
