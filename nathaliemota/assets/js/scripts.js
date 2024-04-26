document.addEventListener("DOMContentLoaded", function () {
  const contactButton = document.getElementById("contact__button");
  const contactOverlay = document.getElementById("contact__overlay");
  const contactBackground = document.getElementById("contact-background");

  contactButton.addEventListener("click", function (event) {
    event.stopPropagation(); // Arrêter la propagation pour éviter la fermeture immédiate
    contactOverlay.classList.add("contact-opened");
  });

  // On ferme la modale, au clic sur la div background
  contactBackground.addEventListener("click", function (event) {
    // Empêcher la propagation de l'événement pour éviter de déclencher le gestionnaire de fenêtre
    event.stopPropagation();
    contactOverlay.classList.remove("contact-opened");
  });
});
