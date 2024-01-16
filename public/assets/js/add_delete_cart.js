// Fonction ajout panier
var nbArticles = $("#nb_articles");
function ajouterAuPanier(url, id, nom, prix, image) {
  var confirm_success_error = $(".achat-accueil");
  var produitData = {
    id: id,
    nom: nom,
    image: image,
    prix: prix,
    nbArticles: 1,
  };
  $.ajax({
    url: url,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(produitData),
    success: function (response) {
      var ajoutPanier = $('<div class="alert" id="ajout-panier"></div>');
      if (response.message) {
        ajoutPanier.addClass("alert-success");
        ajoutPanier.append("<p>" + response.message + "</p>");
        nbArticles.text(response.totalQuantite);
      } else if (response.doublon) {
        ajoutPanier.addClass("alert-warning");
        ajoutPanier.append("<p>" + response.doublon + "</p>");
      } else {
        ajoutPanier.addClass("alert-danger");
        ajoutPanier.append(
          "<p> Il y a eu un problème lors de l' ajout de votre produit dans le panier</p>"
        );
      }

      ajoutPanier.remove();
      confirm_success_error.append(ajoutPanier);
    },
    error: function (error) {
      console.log("Erreur lors de la requête AJAX :", error.responseText);
    },
  });
}

function supprimerArticleDuPanier(url, id) {
  $.ajax({
    url: url,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ id: id }),
    success: function (response) {
      if (response && response.success) {
        location.reload();
      } else {
        console.log("Erreur lors de la suppression de l'article du panier");
        console.log(response);
      }
    },
    error: function () {
      console.log("Une erreur s'est produite lors de la requête AJAX");
    },
  });
}
