jQuery(document).ready(function () {
  function openModal(buttonId) {
    const contactButton = jQuery(buttonId);
    const contactOverlay = jQuery("#contact__overlay");
    const contactBackground = jQuery("#contact-background");

    contactButton.click(function (event) {
      event.stopPropagation(); // Arrêter la propagation pour éviter la fermeture immédiate
      contactOverlay.addClass("contact-opened");

      // Remplissage automatique du champ "Réf. photo"

      const referenceValue = referenceJS.referenceValue; // On accède à la valeur transmise grâce à "wp_localize_script" dans functions.php
      const refPhotoInput = jQuery("#contact-photo-ref");

      if (refPhotoInput.length) {
        refPhotoInput.val(referenceValue); // Remplir le champ avec la valeur de référence
      }
    });

    // On ferme la modale, au clic sur la div background
    contactBackground.click(function (event) {
      // Empêcher la propagation de l'événement pour éviter de déclencher le gestionnaire de fenêtre
      event.stopPropagation();
      contactOverlay.removeClass("contact-opened");
    });
  }

  openModal("#contact__button");
  openModal("#nmota-photo-post__cta--button");
});
