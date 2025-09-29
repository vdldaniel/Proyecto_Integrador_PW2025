   
//    const Horizonte={
//     Cancha:"5",
//     Horario:["11"],
//     FechaLibre:["2025-09-28"]
//    }
//    const Easy={
//     Cancha:"7",
//     Horario:["12"],
//     FechaLibre:["2025-09-29"]
//    }
//    const Trocha={
//     Cancha:"5",
//     Horario:["13"],
//     FechaLibre:["2025-09-30"]
//    }
   
//    const FechaInput=document.getElementById("fecha");
//    const Hora=document.getElementById("hora");

//    FechaInput.addEventListener("change",(e)=>{
//     console.log(FechaInput.value);
//    })

//    Hora.addEventListener("change",(e)=>{
//     console.log(Hora.value);

//     const horarioSelect=e.target.value;

//     if((Horizonte.Horario.includes(horarioSelect))){

//         console.log("hola")
//         //const marker = l.marker([-34.85447547095988,-58.5020413706672]).addTo(map);
//         //return marker;
//     }
//     //console.log(Horizonte.Horario)
//     //console.log(horarioSelect)


//    })

//    document.querySelectorAll('.btn').forEach(buttonElement => {
//   const button = bootstrap.Button.getOrCreateInstance(buttonElement)
//   button.toggle()
// })
    const form=document.getElementById("form1");
    const btnBusqueda=document.getElementById("formBusqueda");
    //const btnJugador=document.getElementById("formBusqueda");
    // const btnEquipo=document.getElementById("equipo");
    // const btnCompleto=document.getElementById("completo");
    const tamanioCancha=document.getElementById('tCancha');
    const fecha=document.getElementById('fecha');
    const hora=document.getElementById('hora');
    const cantidadJug=document.getElementById('cantidadJugadores');
    const dificultad=document.getElementById('dificultad');
    

//    (() => {
//   'use strict'

//   // Fetch all the forms we want to apply custom Bootstrap validation styles to
//   const forms = document.querySelectorAll('.needs-validation')

//   // Loop over them and prevent submission
//   Array.from(forms).forEach(form => {
//     form.addEventListener('submit', event => {
//       if (!form.checkValidity()) {
//         event.preventDefault()
//         event.stopPropagation()
//       }

//       form.classList.add('was-validated')
//     }, false)
//   })
// })()

   


   
   const map = L.map('map').setView([-34.85412125259546, -58.52271505854349], 13);
   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);
  
  
    const locations = [
      { lat: -34.85878021262361, lng: -58.51908492751629 },
      { lat: -34.860332863852996, lng: -58.530284851425144 },
      { lat: -34.85447547095988, lng: -58.5020413706672 }
    ];

   locations.forEach(loc => L.marker([loc.lat, loc.lng]).addTo(map));
    


