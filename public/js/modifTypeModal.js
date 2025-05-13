document.addEventListener('DOMContentLoaded', function () {
    const modifModal = document.getElementById('modifTypeModal');
    modifModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const libelle = button.getAttribute('data-libelle');

        const inputLibelle = document.getElementById('modifNomType');
        inputLibelle.value = libelle;

        const form = document.getElementById('modifTypeForm');
        form.action = `/gestion_type_ph/modif/${id}`;
    });
});