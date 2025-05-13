function filtrerPrelevements(type) {
    const lignes = document.querySelectorAll('.prelevement');

    lignes.forEach(ligne => {
        ligne.style.display = 'table-row';
        if (type === 'analyses' && !ligne.classList.contains('analyse')) {
            ligne.style.display = 'none';
        }
        if (type === 'nonAnalyses' && !ligne.classList.contains('non-analyse')) {
            ligne.style.display = 'none';
        }
    });
}