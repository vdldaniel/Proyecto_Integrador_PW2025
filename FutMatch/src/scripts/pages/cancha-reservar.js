 

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
    


