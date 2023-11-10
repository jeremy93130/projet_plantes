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
      totalGeneral.toFixed(2) + '€';
  }

  function deleteArticle() {
    // Sélectionnez tous les éléments avec la classe 'supprimer_article'
    let supprimerArticles = document.querySelectorAll(".supprimer_article");

    // Ajoutez un gestionnaire d'événements à chaque bouton 'supprimer_article'
    supprimerArticles.forEach((supprimerButton) => {
      supprimerButton.addEventListener("click", function () {
        // Obtenez l'ID de l'article à supprimer, par exemple, à partir d'un attribut data
        let articleId = this.getAttribute("data-article-id");

        // Appeler la fonction pour supprimer l'article côté serveur (vous devez l'implémenter côté serveur)
        deleteArticleFromCart(articleId);

        // Facultatif : Mettez à jour l'interface utilisateur côté client si nécessaire
        // Par exemple, supprimez la ligne correspondante à l'article du DOM
        let articleRow = this.closest("tr");
        articleRow.remove();
      });
    });
  }

  // Appelez la fonction deleteArticle pour l'initialiser
  deleteArticle();
});
