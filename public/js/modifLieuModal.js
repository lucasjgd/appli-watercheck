document.addEventListener('DOMContentLoaded', () => {
    const modifModal = document.getElementById('modifLieuModal');
    modifModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const libelle = button.getAttribute('data-libelle');
        const latitude = button.getAttribute('data-latitude');
        const longitude = button.getAttribute('data-longitude');
        const ville = button.getAttribute('data-ville');

        const form = modifModal.querySelector('form');
        form.action = `/gestion_emplacement/modif/${id}`;

        document.getElementById('modifNomLieu').value = libelle;
        document.getElementById('modifLatitude').value = latitude;
        document.getElementById('modifLongitude').value = longitude;
        document.getElementById('modifVilleLieu').value = ville;
    });
});