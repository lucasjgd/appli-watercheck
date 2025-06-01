document.addEventListener("DOMContentLoaded", function () {
    const map = L.map('map').setView([46.8, 2.44], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
        attribution: '©Watercheck MAP '
    }).addTo(map);

    const prelevements = JSON.parse(document.getElementById('map').dataset.prelevements);

    function couleur(etat) {
        switch (etat.toLowerCase()) {
            case "potable": return "green";
            case "moyennement-potable": return "orange";
            case "non-potable": return "red";
            default: return "blue";
        }
    }

    prelevements.forEach(p => {
        const rond = L.divIcon({
            className: 'custom-div-icon',
            html: `<div style="background-color:${couleur(p.etat)}; width:15px; height:15px; border-radius:50%; border:2px solid black;"></div>`
        });

        L.marker(p.coord, { icon: rond }).addTo(map)
            .bindPopup(`<b>${p.lieu}</b><br>${p.ville}<br>${p.date}<br><strong>${p.etat}</strong>`);
    });
});
