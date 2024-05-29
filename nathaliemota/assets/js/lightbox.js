document.addEventListener("DOMContentLoaded", function () {
  let currentIndex = 0; // Initialisation de l'index actuel à 0
  let photos = [];
  const lightboxImage = document.querySelector(
    ".nmota__lightbox__wrapper--content img"
  );
  const lightboxPrev = document.getElementById("lightbox-prev");
  const lightboxNext = document.getElementById("lightbox-next");
  const lightbox = document.getElementById("lightbox");
  const close = document.querySelector(".close");
  const lightboxReference = document.getElementById("lightbox-reference");
  const lightboxCategorie = document.getElementById("lightbox-categorie");

  // Fonction pour mettre à jour la lightbox avec l'image actuelle
  function updateLightbox(photoIndex) {
    if (photoIndex >= 0 && photoIndex < photos.length) {
      const photo = photos[photoIndex];
      lightboxImage.setAttribute("src", photo.src);
      lightboxReference.textContent = photo.reference;
      lightboxCategorie.textContent = photo.category;
    }
  }

  // Fonction pour initialiser le diaporama
  window.initializeSlider = function () {
    const blocks = document.querySelectorAll(".photo-block");
    photos = Array.from(blocks)
      .map((block) => {
        const imgBlock = block.querySelector(".main-photo");
        return imgBlock
          ? {
              src: imgBlock.getAttribute("src"),
              reference: imgBlock.getAttribute("data-reference"),
              category: imgBlock.getAttribute("data-category"),
              index: parseInt(block.getAttribute("data-index"), 10),
            }
          : null;
      })
      .filter((photo) => photo);
  };

  // Fonction pour récupérer l'index de la photo depuis l'attribut data-index du bloc
  function getPhotoIndexFromBlock(block) {
    return block ? parseInt(block.getAttribute("data-index"), 10) : -1;
  }

  // Gestion des clics sur les éléments .photo-block
  document.addEventListener("click", function (e) {
    const block = e.target.closest(".photo-block");

    if (block) {
      // Vérifier si l'icône de l'œil a été cliquée
      const eyeIconClicked = e.target.closest(".icon-eye");
      if (eyeIconClicked) {
        // Si l'icône de l'œil a été cliquée, rediriger directement vers la page single de la photo
        e.preventDefault();
        lightbox.classList.remove("show"); // Empêcher la lightbox d'apparaître
        const postUrl = block.querySelector("a").getAttribute("href");
        window.location.href = postUrl;
        return;
      }

      // Vérifier si l'icône fullscreen a été cliquée
      const fullscreenIconClicked = e.target.closest(".icon-fullscreen");
      if (fullscreenIconClicked) {
        // Si l'icône fullscreen a été cliquée, afficher la lightbox
        e.preventDefault();
        currentIndex = getPhotoIndexFromBlock(block);
        updateLightbox(currentIndex);
        lightbox.classList.add("show");
        return;
      }
    }
  });

  // Gestion des événements de clic sur les flèches

  // Suivante
  lightboxNext.addEventListener("click", function () {
    if (photos.length > 0) {
      currentIndex = (currentIndex + 1) % photos.length;
      updateLightbox(currentIndex);
    }
  });

  // Précédente
  lightboxPrev.addEventListener("click", function () {
    if (photos.length > 0) {
      currentIndex = (currentIndex - 1 + photos.length) % photos.length;
      updateLightbox(currentIndex);
    }
  });

  // Ajout des écouteurs d'événements click sur le bouton de fermeture
  close.addEventListener("click", function () {
    lightbox.classList.remove("show");
  });

  // Fonction globale pour attacher les écouteurs d'événements

  // Cette fonction est rattachée globalement pour être appelée dans d'autres fichiers JS (ici "load-more_and_filters_ajax.js")
  // pour permettre de mettre à jour les écouteurs d'événements dynamiquement avec la requête AJAX...
  window.attachEventListeners = function () {
    const links = document.querySelectorAll(".photo-block a");
    links.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        const img = this.querySelector("img");
        lightboxImage.src = img.src;

        const reference = img.getAttribute("data-reference");
        const category = img.getAttribute("data-category");

        lightboxReference.textContent = reference;
        lightboxCategorie.textContent = category;

        lightbox.classList.add("show");
      });
    });
  };

  // Initialisation au chargement de la page
  initializeSlider();
  attachEventListeners();
});
