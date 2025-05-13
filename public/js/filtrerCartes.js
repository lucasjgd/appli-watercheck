document.addEventListener("DOMContentLoaded", function () {
    const champVille = document.getElementById("filtre-ville");
    const champRegion = document.getElementById("filtre-region");
    const champQualite = document.getElementById("filtre-qualite");
    const boutonFiltrer = document.getElementById("bouton-filtrer");
    const cartes = document.querySelectorAll(".prelevement-card");
    const messageAucunResultat = document.getElementById("message-aucun-resultat");

    function filtrerCartes() {
        const ville = champVille.value.trim().toLowerCase();
        const region = champRegion.value.trim().toLowerCase();
        const qualite = champQualite.value.trim().toLowerCase();

        let cartesVisibles = 0;

        cartes.forEach(carte => {
            const villeCarte = carte.dataset.ville.trim().toLowerCase();
            const regionCarte = carte.dataset.region.trim().toLowerCase();
            const qualiteCarte = carte.dataset.qualite.trim().toLowerCase();

            const villeOK = !ville || villeCarte === ville;
            const regionOK = !region || regionCarte === region;
            const qualiteOK = !qualite || qualiteCarte === qualite;

            if (villeOK && regionOK && qualiteOK) {
                carte.style.display = "block";
                cartesVisibles++;
            } else {
                carte.style.display = "none";
            }
        });

        if (cartesVisibles === 0) {
            messageAucunResultat.style.display = "block";
        } else {
            messageAucunResultat.style.display = "none";
        }
    }

    boutonFiltrer.addEventListener("click", filtrerCartes);
});