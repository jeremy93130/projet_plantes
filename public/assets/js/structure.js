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
  var passerCommandeLink = document.getElementById("passer-commande-link");

  if (passerCommandeLink) {
    passerCommandeLink.addEventListener("click", function (event) {
      event.preventDefault();

      var totalGeneral = document
        .getElementById("total-general")
        .textContent.trim();

      // Utilisation de fetch pour envoyer la requête AJAX
      fetch("{{ path('app_commandes') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "total=" + encodeURIComponent(totalGeneral),
      })
        .then((response) => response.json())
        .then((data) => {
          // Gérer la réponse JSON du serveur ici
          if (data.url) {
            // Rediriger vers l'URL renvoyée par le serveur
            window.location.href = data.url;
          } else {
            console.error(
              "Aucune URL de redirection trouvée dans la réponse JSON."
            );
          }
        })
        .catch((error) => {
          // Gérer les erreurs ici
          console.error("Erreur lors de la requête AJAX:", error);
        });
    });
  }
});
