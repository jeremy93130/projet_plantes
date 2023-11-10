document.addEventListener("DOMContentLoaded", function () {
  const quantiteInputs = document.querySelectorAll(".quantite-input");

  let incrementState = 1; // Variable pour stocker l'état de l'incrément

  quantiteInputs.forEach(function (input) {
    input.addEventListener("input", function () {
      let quantite = parseInt(input.value);
      const prixUnitaire = parseFloat(input.dataset.prix);

      if (isNaN(quantite) || quantite <= 0) {
        quantite = 1;
        input.value = 1;
      }

      const totalLigne = quantite * prixUnitaire;
      input.closest("tr").querySelector(".total-column").textContent =
        totalLigne.toFixed(2);

      updateTotalGeneral();
    });
  });

  const plus = document.getElementById("plus");
  const moins = document.getElementById("moins");

  plus.addEventListener("click", function () {
    incrementState = 1;
    updateInputs();
  });

  moins.addEventListener("click", function () {
    incrementState = -1;
    updateInputs();
  });

  function updateInputs() {
    quantiteInputs.forEach(function (input) {
      let quantite = parseInt(input.value);
      quantite += incrementState;
      input.value = quantite;

      // Mettez à jour le total de la ligne uniquement si la quantité a changé
      if (incrementState !== 0) {
        ajusterQuantite(input);
      }
    });

    // Réinitialisez l'état d'incrément à 0 après la mise à jour
    incrementState = 0;

    // Mettez à jour le total général
    updateTotalGeneral();
  }

  function ajusterQuantite(input) {
    console.trace();
    let quantite = parseInt(input.value);
    console.log(quantite);
    if (isNaN(quantite) || quantite <= 0) {
      quantite = 1;
      input.value = 1;
    }

    const prixUnitaire = parseFloat(input.dataset.prix);
    const totalLigne = quantite * prixUnitaire;

    input.closest("tr").querySelector(".total-column").textContent =
      totalLigne.toFixed(2);
  }

  function updateTotalGeneral() {
    const totalGeneralElements = document.getElementById("total-general");
    let totalGeneral = 0;

    quantiteInputs.forEach(function (input) {
      const quantite = parseInt(input.value);
      const prixUnitaire = parseFloat(input.dataset.prix);
      totalGeneral += quantite * prixUnitaire;
    });

    totalGeneralElements.textContent = totalGeneral.toFixed(2);
  }
});
