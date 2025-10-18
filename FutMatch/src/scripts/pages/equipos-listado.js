// document.addEventListener("DOMContentLoaded", () => {
//   fetch("../HTML/navbar.html")
//        .then(response => response.text())
//        .then(data => {
//            // Crear un DOM temporal para extraer solo el <nav>
//            const tempDiv = document.createElement('div');
//            tempDiv.innerHTML = data;
//            const nav = tempDiv.querySelector('nav#navbarFutmatch');
//            if (nav) {
//                document.getElementById("navbarFutmatch").innerHTML = nav.outerHTML;
//            }
//        });
//});