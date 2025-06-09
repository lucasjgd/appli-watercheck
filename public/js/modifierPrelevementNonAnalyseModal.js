document.addEventListener('DOMContentLoaded', () => {
    const modifModal = document.getElementById('modifierPrelevementNonAnalyseModal');
    modifModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-idNonAnalyse');
        const datePrelevementNonAnalyse = button.getAttribute('data-dateNonAnalyse');
        const emplacementIdPrelevementNonAnalyse = button.getAttribute('data-emplacementIdNonAnalyse');


        document.getElementById('modifDatePrelevementNonAnalyse').value = datePrelevementNonAnalyse;

        const emplacementSelect = document.getElementById('modifEmplacementPrelevementNonAnalyse');
        const options = emplacementSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value == emplacementIdPrelevementNonAnalyse) {
                options[i].selected = true;
                break;
            }
        }

        const form = document.getElementById('modifNonAnalyseForm');
        form.action = `/gestion_prelevement/modif-nonAnalyse/${id}`;

    });
});
