// Supposons que vous ayez un bouton ou un événement déclencheur avec l'ID 'passer-commande'
$("#commander").on("click", function () {
  // Récupérer la valeur du total depuis le champ caché
  var quantite = $(".quantity");
  var totalGeneral = parseFloat(
    document.getElementById("total-general").textContent.replace("€", "").trim()
  );

  var commandeDatas = [];

  quantite.each(function () {
    // On récupere l'id
    var articleId = $(this).data("article");
    // on récupere la valeur de l'input pour récuperer la quantité
    var articleQuantity = $(this).val();
    var deleteArticle = $(this).closest(".delete_article");

    var altText = deleteArticle.find("img").attr("alt");
    var categorie = deleteArticle.find("img").data("categorie");
    // On récupere le prix via quantite (classe de l'input quantity).parent(le noeud parent = td class="quantity-input").prev(element frere precedent = le td qui contient {{produit.prix}}).text(le text du td).remplace(on remplace € par une chaine vide).trim(on supprime les espaces avant et arriere)
    var prix = parseFloat(
      $(this).parent().prev().text().replace("€", "").trim()
    );

    var lot = parseInt($(this).data("lot"));

    var commandeData = {
      id: articleId,
      quantite: articleQuantity,
      alt: altText,
      categorie: categorie,
      prix: prix,
      lot: lot,
    };
    commandeDatas.push(commandeData);
  });

  var dataToSend = {
    commandeData: commandeDatas,
    totalGeneral: totalGeneral,
  };

  $.ajax({
    type: "POST",
    url: "/commandes",
    contentType: "application/json",
    data: JSON.stringify(dataToSend),
    success: function (response) {
      if (response.redirect) {
        window.location.href = response.redirect;
      } else if (response.erreur_stock) {
        let erreur = $("<p>" + response.erreur_stock + "</p>");
        $(".quantite-input").append(erreur);
      } else {
        deleteArticle.html(
          "<div><h2>Une Erreur s'est produite, Merci de raffraichir la page et réessayer</h2></div>"
        );
      }
    },
    error: function (error) {
      console.error("Erreur lors de la requête AJAX:", error);
    },
  });
});
