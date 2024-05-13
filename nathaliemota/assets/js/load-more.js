(function ($) {
  $(document).ready(function () {
    let currentPage = 1;
    let allPhotosLoaded = false; // Variable pour suivre l'état des photos chargées

    $("#load-more").on("click", function () {
      if (!allPhotosLoaded) {
        currentPage++; // Incrémente la page actuelle

        let formData = new FormData();
        formData.append("action", "load_more_photos");
        formData.append("paged", currentPage);
        formData.append("_wpnonce", loadMore_js.load_more_photos_nonce); // Utilisation du nonce

        // Effectue la requête AJAX avec fetch
        fetch(loadMore_js.ajax_url, {
          method: "POST",
          body: formData,
        })
          .then(function (response) {
            if (!response.ok) {
              throw new Error("La réponse n'a pas pu être récupérée.");
            }
            return response.json(); // Attend une réponse au format json
          })

          .then(function (res) {
            if (res.success && res.html) {
              // Ajoute la réponse (8 nouvelles photos) à la liste existante
              $(".nmota-home-photo-list").append(res.html);

              if (!res.hasMore) {
                allPhotosLoaded = true; // Met à jour l'état des photos chargées
                $("#load-more").hide(); // Cache le bouton "Charger plus"
              }
            } else {
              console.error("Aucune nouvelle publication trouvée.");
            }
          })
          .catch(function (error) {
            // Gestion des erreurs lors de la requête AJAX
            console.error(
              "Une erreur s'est produite lors de la requête AJAX : ",
              error
            );
          });
      }
    });
  });
})(jQuery);
