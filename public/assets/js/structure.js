document.addEventListener("DOMContentLoaded", function () {
  // Sélectionnez tous les éléments avec la classe 'quantity'
  let quantityInputs = document.querySelectorAll(".quantity");

  quantityInputs.forEach((input) => {
    let plusButton = input.nextElementSibling;
    let moinsButton = input.previousElementSibling;
    let totalColumn = input.parentElement.nextElementSibling;

    plusButton.addEventListener("click", function () {
      if (!plusButton.disabled) {
        input.value = parseInt(input.value) + 1;
        plusButton.disabled = true;
        moinsButton.disabled = true;
        updateTotal(input, plusButton, moinsButton, totalColumn);
      }
    });

    moinsButton.addEventListener("click", function () {
      if (!moinsButton.disabled && parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        plusButton.disabled = true;
        moinsButton.disabled = true;
        updateTotal(input, plusButton, moinsButton, totalColumn);
      }
    });

    input.addEventListener("input", function () {
      if (parseInt(input.value) < 1) {
        input.value = 1;
      }
      updateTotal(input, plusButton, moinsButton, totalColumn);
    });
  });

  function updateTotal(input, plusButton, moinsButton, totalColumn) {
    let prixUnitaire = parseFloat(
      input.parentElement.previousElementSibling.textContent
    );
    let nouvelleQuantite = parseInt(input.value);
    let nouveauTotal = prixUnitaire * nouvelleQuantite;
    totalColumn.textContent = nouveauTotal.toFixed(2);
    updateTotalGeneral();
    setTimeout(() => {
      plusButton.disabled = false;
      moinsButton.disabled = false;
    }, 1000); // Réactivez les boutons après 1 seconde
  }

  function updateTotalGeneral() {
    let totalGeneral = 0;
    document.querySelectorAll(".quantity").forEach(function (input) {
      let prixUnitaire = parseFloat(
        input.parentElement.previousElementSibling.textContent
      );
      let quantite = parseInt(input.value);
      let total = prixUnitaire * quantite;
      totalGeneral += total;
    });
    document.getElementById("total-general").textContent =
      totalGeneral.toFixed(2) + "€";
  }
  // Supposons que vous ayez un bouton ou un événement déclencheur avec l'ID 'passer-commande'
  $("#passer-commande").on("click", function () {
    // Récupérer la valeur du total depuis le champ caché
    var total = $("#total-general").text().trim();
    ;
    console.log(total);

    // Effectuer la requête AJAX
    $.ajax({
      type: "POST",
      url: "/commandes",
      data: { total: total },
      success: function (response) {
        // La réponse du serveur (éventuellement une URL) est stockée dans response
        var url = response.url;
        console.log(url);

        // Rediriger l'utilisateur vers la nouvelle URL (s'il y en a une)
        if (url) {
          window.location.href = url;
        }
      },
      error: function (error) {
        console.error("Erreur lors de la requête AJAX:", error);
      },
    });
  });
});

