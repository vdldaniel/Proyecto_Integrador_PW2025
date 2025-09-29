document.addEventListener("DOMContentLoaded", () => {
    fetch("/Proyecto_Integrador_PW2025/FutMatch/public/HTML/navbar-admin.html")
    .then(response => response.text())
    .then(data => {
      document.getElementById("navbarFutmatchAdmin").innerHTML = data;
    });
});