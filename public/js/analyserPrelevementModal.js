document.addEventListener('DOMContentLoaded', () => {
    const analyseModal = document.getElementById('analyserPrelevementModal');
    analyseModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const form = analyseModal.querySelector('form');
        const id = button.getAttribute('data-id');
        form.action = `/gestion_prelevement/analyse/${id}`;

    });
});