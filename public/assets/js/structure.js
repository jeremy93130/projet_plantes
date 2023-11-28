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
    const searchInput = document.getElementById("plante_search");
    const plantes = document.querySelectorAll(".plantesResults");
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
  });
});
