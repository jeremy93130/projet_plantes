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
    totalColumn.textContent = nouveauTotal.toFixed(2) + " €";
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
    var totalGeneral = $("#total-general input[type=hidden]").val();
    var quantity = $(".quantity");
    $("input.quantity").each(function () {
      var articleId = $(this).data("article");
      var articleQuantity = quantity.val();
      quantity[articleId] = articleQuantity;
    });
    console.log(totalGeneral);
    console.log(quantity);
    // Effectuer la requête AJAX
    console.log(JSON.stringify(quantity));
    $.ajax({
      type: "POST",
      url: "/commandes",
      contentType: "application/json",
      data: {
        totalGeneral: totalGeneral,
        quantite: JSON.stringify(quantity),
      },
      success: function (response) {
        // Le contenu du template est maintenant dans la réponse
        // console.log(response);
        window.location.href = "/commandes";
        $("#recap").text(response);
        // Vous pouvez utiliser le contenu de la réponse comme vous le souhaitez
        // Par exemple, injecter le contenu dans une div existante sur votre page
      },
      error: function (error) {
        console.error("Erreur lors de la requête AJAX:", error);
      },
    });
  });
  const searchInput = document.getElementById("plante_search");
  const plantes = document.querySelectorAll(".plantesResults");
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = searchInput.value.toLowerCase();
      plantes.forEach(function (plante) {
        const planteName = plante.getAttribute("data-nom").toLowerCase();
        if (planteName.startsWith(searchTerm)) {
          plante.style.display = "block";
        } else {
          plante.style.display = "none";
        }
      });
    });
  }
});
// Fonction ajout panier
var nb_counts = parseInt(localStorage.getItem("nb_counts")) || 0;
function ajouterAuPanier(url, nom, prix, image) {
  var nb_articles = document.getElementById("nb_articles");
  var produitData = {
    id: "{{ produit.id }}",
    nom: nom,
    prix: prix,
    image: image,
    nbArticles: nb_counts + 1,
    // Ajoutez d'autres informations si nécessaire
  };
  console.log(produitData);
  // Utilisez AJAX pour appeler l'action du contrôleur
  $.ajax({
    url: url,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(produitData),
    success: function (response) {
      if (response) {
        nb_counts++;
        nb_articles.textContent = nb_counts;
        // Stockez la nouvelle valeur de nb_counts dans localStorage
        localStorage.setItem("nb_counts", nb_counts);
        // localStorage.clear('nb_counts');
        // window.history.back;
      } else {
        alert("ok");
      }
    },
    error: function () {
      // alert("Une erreur s'est produite lors de l'ajout au panier");
    },
  });
}

function supprimerArticleDuPanier(url, id) {
  // Faites une requête AJAX pour supprimer l'article du panier
  $.ajax({
    url: url,
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ id: id }),
    success: function (response) {
      if (response && response.success) {
        // Si la suppression a réussi du côté serveur

        // Mise à jour du nombre d'articles
        nb_counts--;

        if (nb_counts == 0) {
          nb_articles.textContent = "";
        } else {
          // Mettez à jour l'affichage dans votre interface utilisateur
          nb_articles.textContent = nb_counts;
        }

        // Mettez à jour le localStorage avec la nouvelle valeur de nb_counts
        localStorage.setItem("nb_counts", nb_counts);

        // Rechargez la page pour refléter les changements (vous pouvez ajuster ceci selon vos besoins)
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

// Utilisez cette fonction en appelant supprimerArticleDuPanier(url, id)
// Assurez-vous d'avoir les variables url et id correctes

window.onload = function () {
  nb_counts = parseInt(localStorage.getItem("nb_counts")) || 0;
  if (nb_counts > 0) {
    // localStorage.clear('nb_counts');
    document.getElementById("nb_articles").textContent = nb_counts;
  } else if (nb_counts == 0) {
    document.getElementById("nb_articles").textContent = "";
  } else {
    document.getElementById("nb_articles").textContent = "";
  }
  var stripe = Stripe(
    "pk_test_51OICEgC3GA5BR02AuVfYushtuoMQHtv99wK9FATC9PnIHCwDhOR2jlvTOAcZIoGmnxNOSeU9JDvP7OHMAeg0AX0B00E7MKlVNK"
  );
};

function commander(url) {
  // Récupérer tous les éléments de quantité
  var quantities = document.querySelectorAll(".quantity");

  // Parcourir chaque élément de quantité et créer un objet de données de commande
  var commandeData = Array.from(quantities).map(function (quantityElement) {
    var altText = quantityElement
      .closest(".delete_article")
      .querySelector("img")
      .getAttribute("alt");

    var categorie = quantityElement
      .closest(".delete_article")
      .querySelector("img")
      .getAttribute("data-categorie");

    // Define prix before using it in calculations
    var prix = parseFloat(
      quantityElement.parentNode.previousElementSibling.textContent
        .replace("€", "")
        .trim()
    );

    return {
      id: quantityElement.getAttribute("data-article"),
      quantite: parseInt(quantityElement.value),
      alt: altText,
      categorie: categorie,
      prix: prix,
    };
  });

  // Récupérer le prix total
  var totalGeneral = parseFloat(
    document.getElementById("total-general").textContent.replace("€", "").trim()
  );

  // Utiliser AJAX pour envoyer les données au serveur
  $.ajax({
    url: "/commandes",
    method: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      commandeData: commandeData,
      totalGeneral: totalGeneral,
    }),
    success: function (response) {
      console.log(response);
      if (response.redirect) {
        // Redirigez l'utilisateur vers la page indiquée dans la réponse
        window.location.href = response.redirect;
      } else {
        // Traitez la réponse normalement si aucune redirection n'est spécifiée
        // ...
      }
    },
    error: function (error) {
      console.log(error.responseText);
      alert(
        "Erreur lors de la requête AJAX. Veuillez vérifier la console pour plus d'informations."
      );
    },
  });
}
