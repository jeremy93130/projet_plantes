$(document).ready(function () {
  $(".modifInfos").on("click", modifInfosPerso);
});

// Variables globales
var isFieldBeingModified = false;
var erreurConfirm = $("#erreurConfirm") ?? null;
var validatePasswordButton;
var validateButton;
var mdpActuel = $("#motDePasse");

function modifInfosPerso(event) {
  var fieldName = $(event.target).data("field");
  if (isFieldBeingModified) {
    // Si un champ est déjà en cours de modification, ne faites rien
    return;
  }

  if ($("#erreurConfirm").length) {
    $("#erreurConfirm").remove();
  }

  isFieldBeingModified = true;

  var changeElement = $("#" + fieldName);

  if (fieldName !== "motDePasse") {
    // Créez un champ d'entrée sans le bouton de validation
    var inputElement = $("<input type='text' id=" + fieldName + "_input>").val(
      changeElement.text()
    );

    // Ajoutez le champ d'entrée à l'élément
    changeElement.empty().append(inputElement);
    // Créez le bouton de validation en dehors de l'input
    validateButton = $(
      "<button type='button' class='validate-btn'>Valider</button>"
    );
  }

  if (fieldName === "motDePasse") {
    var oldPassword = $("#ancien-mdp");
    var newPassword = $("#nouveau-mdp");
    var confirmMdp = $("#confirm-nouveau-mdp");

    changeElement.hide();

    oldPassword
      .show()
      .html(
        "<input type='password' id='input-ancienMdp' placeholder='Entrez votre ancien mot de passe'/>"
      );
    newPassword
      .show()
      .html(
        "<input type='password' id='input-newMdp' placeholder='Entrez votre nouveau mot de passe'/>"
      );

    confirmMdp
      .show()
      .html(
        "<input type='password' id='input-newMdp-confirm' placeholder='confirmez votre nouveau mot de passe'/>"
      );
    validatePasswordButton = $(
      "<button type='button' class='validate-btn'>Valider</button>"
    );

    var divInputs = $("#inputs-mdp");
    divInputs.append(validatePasswordButton);

    validatePasswordButton.click(function () {
      var newValue = $("#" + fieldName + " input").val();
      changeElement.show();
      // On vérifie le nouveau mdp et la confirmation
      checkMDP();
      $("#" + fieldName).text(newValue);

      // Réattachez l'événement de modification au nouveau paragraphe
      mdpActuel.next("a.modifInfos").on("click", modifInfosPerso);

      isFieldBeingModified = false; // Réinitialisez l'état de modification
    });
  }

  // Ajoutez un événement pour sauvegarder les modifications lorsque le champ d'entrée perd le focus
  validateButton.click(function () {
    var newValue = $("#" + fieldName + " input").val();
    var confirmDiv = $("#confirm-infos");
    var nom = $("#nom_input").val();
    var prenom = $("#prenom_input").val();
    var email = $("#email_input").val();
    var telephone = $("#telephone_input").val();

    newValue == "" ? changeElement.text() : newValue;

    $("#" + fieldName).text(newValue);

    // Réattachez l'événement de modification au nouveau paragraphe
    $("#" + fieldName)
      .next("a.modifInfos")
      .on("click", modifInfosPerso);
    isFieldBeingModified = false; // Réinitialisez l'état de modification

    $.ajax({
      url: "/update",
      method: "post",
      data: {
        nom: nom,
        prenom: prenom,
        email: email,
        telephone: telephone,
        champModifie: fieldName,
      },
      success: function (response) {
        confirmDiv.empty();
        confirmDiv.append(
          '<p class="alert alert-success text-center">' +
            response.message +
            "</p>"
        );
      },
      error: function (response) {
        confirmDiv.empty();
        confirmDiv.append(
          '<p class="alert alert-danger text-center"> Il y eu une erreur lors de votre changement de ' +
            response.message +
            "</p>"
        );
      },
    });
  });

  // Ajoutez le bouton de validation à l'élément
  changeElement.append(validateButton);

  // Désactivez le lien "Modifier" pour éviter la création de champs d'entrée multiples
  changeElement.next("a.modifInfos").off("click");
}

function checkMDP() {
  var oldPassword = $("#ancien-mdp");
  var newPassword = $("#nouveau-mdp");
  var confirmMdp = $("#confirm-nouveau-mdp");
  var nouveauMdp = $("#input-newMdp").val();
  var confirmMDP = $("#input-newMdp-confirm").val();
  var ancienMdp = $("#input-ancienMdp").val();

  if (confirmMDP !== nouveauMdp) {
    parentInput = $("#inputs-mdp");
    parentInput.append(
      "<p id='erreurConfirm' class='alert alert-danger'>les mots de passe ne correspondent pas ! </p>"
    );
  } else if (ancienMdp == "" && nouveauMdp == "" && confirmMDP == "") {
    oldPassword.hide();
    newPassword.hide();
    confirmMdp.hide();
    mdpActuel.html("*******");
    validatePasswordButton.remove();
  } else {
    $.ajax({
      url: "/update",
      method: "post",
      data: {
        ancienMdp,
        nouveauMdp,
      },
      success: function (response) {
        if (response.erreur_mdp) {
          parentInput = $("#inputs-mdp");
          parentInput.append(
            "<p id='erreurConfirm' class='alert alert-danger'>" +
              response.erreur_mdp +
              "</p>"
          );
          oldPassword.hide();
          newPassword.hide();
          confirmMdp.hide();
          validatePasswordButton.remove();
        } else {
          parentInput = $("#inputs-mdp");
          parentInput.append(
            "<p id='erreurConfirm' class='alert alert-success'>" +
              response.success_message +
              "</p>"
          );
          oldPassword.hide();
          newPassword.hide();
          confirmMdp.hide();
          validatePasswordButton.remove();
        }
        mdpActuel.html("*******");
        isFieldBeingModified = false;
      },
      error: function (response) {
        console.log(response);
      },
    });
    if (parentInput.length) {
      parentInput.remove();
    }
  }
}
