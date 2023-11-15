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
});
// Ajoutez cette fonction à votre fichier JavaScript
function deletePlante(link) {
  // Obtenez l'ID de l'article à supprimer à partir de l'attribut data-article-id
  var articleId = link.getAttribute("data-article-id");

  // Effectuez une requête AJAX pour supprimer l'article côté serveur
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "/supprimer/" + articleId, true);

  // Définissez le gestionnaire d'événements pour la réponse de la requête
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // La requête s'est bien déroulée, supprimez la ligne du tableau correspondante
        var row = link.closest(".delete_article");
        row.remove();

        // Mettez à jour le total général
        updateTotal();
      } else {
        // La requête a échoué, affichez une alerte ou gérez l'erreur de la manière appropriée
        console.error("Erreur lors de la suppression de l'article.");
      }
    }
  };

  // Envoyez la requête AJAX
  xhr.send();
}

function updateQuantityAndTotalInSession(planteId, newQuantity) {
  // ... votre logique pour mettre à jour la quantité dans le panier côté client ...

  // Mettez à jour le total côté client
  let totalParPlante = newQuantity * prixUnitaire; // Assurez-vous d'avoir le prix unitaire
  totalGeneral += totalParPlante;

  // Mettez à jour le total dans la session côté client (par exemple, en utilisant localStorage ou sessionStorage)
  sessionStorage.setItem("total_general", totalGeneral);
}
