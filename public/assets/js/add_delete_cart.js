// Fonction ajout panier
var nbArticles = $("#nb_articles");
function ajouterAuPanier(url, id, nom, prix, image, clickedIcon) {
  var ajoutPanier = $("#ajout-panier");
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
      $("#ajout-panier").empty();
      if (response.message) {
        ajoutPanier.addClass("alert alert-success");
        ajoutPanier.append("<p>" + response.message + "</p>");
        nbArticles.text(response.totalQuantite);
        $(clickedIcon).find("i").addClass("selected_cart");
      } else if (response.doublon) {
        ajoutPanier.addClass("alert alert-warning");
        ajoutPanier.append("<p>" + response.doublon + "</p>");
      } else {
        ajoutPanier.addClass("alert alert-danger");
        ajoutPanier.append(
          "<p> Il y a eu un problème lors de l' ajout de votre produit dans le panier</p>"
        );
      }
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

$("#ajouter_panier_link").click(function(e) {
  e.preventDefault(); // Empêche l'action par défaut du lien
  var url = $(this).attr("href"); // Obtient l'URL de l'attribut href
  var id = $(this).data("id"); // Suppose que vous avez un attribut data-id pour l'ID
  var nom = $(this).data("nom"); // Suppose que vous avez un attribut data-nom pour le nom
  var prix = $(this).data("prix"); // Suppose que vous avez un attribut data-prix pour le prix
  var image = $(this).data("image"); // Suppose que vous avez un attribut data-image pour l'image
  ajouterAuPanier(url, id, nom, prix, image, null); // Passez null pour clickedIcon
});

// Click event for the icon button
$("#ajouter_panier_icon").click(function(e) {
  e.preventDefault(); // Empêche l'action par défaut du lien
  var url = $(this).data("url"); // Suppose que vous avez un attribut data-url pour l'URL
  var id = $(this).data("id"); // Suppose que vous avez un attribut data-id pour l'ID
  var nom = $(this).data("nom"); // Suppose que vous avez un attribut data-nom pour le nom
  var prix = $(this).data("prix"); // Suppose que vous avez un attribut data-prix pour le prix
  var image = $(this).data("image"); // Suppose que vous avez un attribut data-image pour l'image
  var clickedIcon = $(this).find("i")[0]; // Trouve l'élément <i> à l'intérieur de l'élément cliqué
  ajouterAuPanier(url, id, nom, prix, image, clickedIcon); // Passez la référence de l'icône cliquée
});