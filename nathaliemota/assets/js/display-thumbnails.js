jQuery(document).ready(function ($) {
  // Fonction pour gérer le survol des flèches
  function thumbnailHover(arrowClass, thumbnailId) {
    $(arrowClass).hover(
      function () {
        // Au survol, ajoute la classe display-thumbnail
        $(thumbnailId).addClass("display-thumbnail");
      },
      function () {
        // Lorsqu'on ne survole plus, retire la classe display-thumbnail
        $(thumbnailId).removeClass("display-thumbnail");
      }
    );
  }

  // Appel de la fonction pour gérer le survol de la flèche next
  thumbnailHover(
    ".nmota-photo-post__cta__nav__arrows--next",
    "#next-thumbnail"
  );

  // Appel de la fonction pour gérer le survol de la flèche prev
  thumbnailHover(
    ".nmota-photo-post__cta__nav__arrows--prev",
    "#prev-thumbnail"
  );
});
