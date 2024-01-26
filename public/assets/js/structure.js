document.addEventListener("DOMContentLoaded", function () {
  // Initialiser tous les dropdowns
  $(document).ready(function () {
    $(".dropdown-toggle").dropdown();
  });

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

  var stripe = Stripe(
    "pk_test_51OICEgC3GA5BR02AuVfYushtuoMQHtv99wK9FATC9PnIHCwDhOR2jlvTOAcZIoGmnxNOSeU9JDvP7OHMAeg0AX0B00E7MKlVNK"
  );
});

var searchInput = document.getElementById("plante_search");
var plantes = document.querySelectorAll(".plantesResults");
if (searchInput) {
  searchInput.addEventListener("input", function () {
    var searchTerm = searchInput.value.toLowerCase();
    plantes.forEach(function (plante) {
      var planteName = plante.getAttribute("data-nom").toLowerCase();
      if (planteName.startsWith(searchTerm)) {
        plante.style.display = "block";
      } else {
        plante.style.display = "none";
      }
    });
  });
}
