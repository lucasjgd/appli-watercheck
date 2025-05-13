document.addEventListener('DOMContentLoaded', () => {
    const modifModal = document.getElementById('modifierPrelevementModal');
    modifModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const datePrelevement = button.getAttribute('data-date');
        const emplacementId = button.getAttribute('data-emplacementId');
        const conductivite = button.getAttribute('data-conductivite');
        const turbidite = button.getAttribute('data-turbidite');
        const alcalinite = button.getAttribute('data-alcalinite');
        const durete = button.getAttribute('data-durete');
        const typePh = button.getAttribute('data-typeph');


        document.getElementById('modifDatePrelevement').value = datePrelevement;

        const emplacementSelect = document.getElementById('modifEmplacement');
        const options = emplacementSelect.options;
        for (let i = 0; i < options.length; i++) {
            if (options[i].value == emplacementId) {
                options[i].selected = true;
                break;
            }
        }

        document.getElementById('modifConductivite').value = conductivite;
        document.getElementById('modifTurbidite').value = turbidite;
        document.getElementById('modifAlcalinite').value = alcalinite;
        document.getElementById('modifDurete').value = durete;
        document.getElementById('modifTypePh').value = typePh;

        const form = document.getElementById('modifForm');
        form.action = `/gestion_prelevement/modif/${id}`;

    });
});