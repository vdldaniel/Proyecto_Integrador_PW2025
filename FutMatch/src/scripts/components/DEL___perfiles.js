const url = `${GET_INFO_PERFIL}?id=${CURRENT_USER_ID}&tipo=${TIPO_PERFIL}`;

document.addEventListener("DOMContentLoaded", function () {
  getInfoPerfil();
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
