(function ($) {
  $(document).ready(function () {
    // Initialisation des variables pour suivre l'état de la pagination
    let currentPage = 1; // Page actuelle
    let allPhotosLoaded = false; // Indique si toutes les photos ont été chargées
    let photoIndex = 0; // Index de la première photo

    // Fonction pour charger plus de photos et appliquer les filtres
    function loadMore_and_filters(resetPage = false) {
      // Si resetPage est vrai, réinitialise currentPage et allPhotosLoaded
      if (resetPage) {
        currentPage = 1;
        allPhotosLoaded = false;
        $("#load-more").show(); // On affiche le bouton "Charger plus"
      } else {
        currentPage++; // Sinon, incrémente la page actuelle
      }

      // Cacher le bouton "charger plus"
      $("#load-more").hide();

      // Récupération des valeurs des filtres
      const category = $("#filter-category").val(); // Catégorie sélectionnée
      const format = $("#filter-format").val(); // Format sélectionné
      const sort = $("#filter-sort").val(); // Tri sélectionné

      // Création d'un formulaire pour envoyer les données via AJAX
      let formData = new FormData();
      formData.append("action", "loadMore_and_filters"); // Action AJAX déclarée dans functions.php
      formData.append("paged", currentPage); // Numéro de page
      formData.append(
        "_wpnonce",
        loadMore_and_filters_JS.loadMore_and_filters_nonce
      ); // Jeton de sécurité
      formData.append("categorie", category); // Valeur du filtre "catégorie"
      formData.append("format", format); // Valeur du filtre "format"
      formData.append("sort", sort); // Valeur du tri

      // Requête AJAX pour charger plus de photos et appliquer les filtres
      fetch(loadMore_and_filters_JS.ajax_url, {
        method: "POST",
        body: formData, // Corps de la requête avec les données FormData
      })
        .then(function (response) {
          if (!response.ok) {
            throw new Error("La réponse n'a pas pu être récupérée."); // Gestion des erreurs de réponse HTTP
          }
          return response.json(); // Conversion de la réponse en JSON
        })
        .then(function (res) {
          // Traitement de la réponse JSON
          if (res.success && res.html && res.html.trim() !== "") {
            if (resetPage) {
              photoIndex = 0; // Réinitialiser l'index des photos si resetPage est vrai
              $(".nmota-home-photo-list").html(res.html); // Remplace le contenu existant par le HTML
            } else {
              $(".nmota-home-photo-list").append(res.html); // Ajoute le nouveau HTML à la fin du contenu existant
            }

            // Ré-attacher les écouteurs d'événements aux nouveaux éléments (fonction globale créée dans "lightbox.js")
            attachEventListeners();

            // Réinitialiser le slider après chargement AJAX
            initializeSlider();

            if (!res.hasMore) {
              // Si aucune photo supplémentaire n'est disponible
              allPhotosLoaded = true; // On déclare que toutes les photos correspondant aux filtres sont chargées
            } else {
              // On réaffiche le bouton "charger plus" si des photos supplémentaires sont disponibles
              $("#load-more").show();
            }

            // Réattribuer les index à toutes les photos visibles
            photoIndex = 0;
            $(".photo-block").each(function () {
              $(this).attr("data-index", photoIndex++);
            });
          } else {
            $(".nmota-home-photo-list").html(
              "<p>Aucune publication trouvée.</p>"
            ); // Remplace le contenu par le message d'erreur
            allPhotosLoaded = true; // On déclare que toutes les photos correspondant aux filtres sont chargées
            $("#load-more").hide(); // On cache le bouton "Charger plus"
          }
        })
        .catch(function (error) {
          // Gestion des erreurs de la requête AJAX
          console.error(
            "Une erreur s'est produite lors de la requête AJAX : ",
            error
          );
        });
    }

    // On écoute le clic sur le bouton "Charger plus"
    $("#load-more").on("click", function () {
      if (!allPhotosLoaded) {
        loadMore_and_filters(); // Charge plus de photos
      }
    });

    // On écoute les changements dans les filtres "select"
    $(".nmota-filters-container select").on("change", function () {
      loadMore_and_filters(true); // Applique les filtres et réinitialise la pagination
    });

    // Appel initial lors du chargement de la page (fonction globale créée dans "lightbox.js")
    attachEventListeners();
  });
})(jQuery);
