document.addEventListener("DOMContentLoaded", function () {
  /** @type {HTMLElement} */

  const siteNavigation = document.getElementById("nmota__header__navigation");

  /**
   * @param {HTMLElement} el
   * @param {string}      attr
   * @param {any}         value
   */
  const setAttr = (el, attr, value) => el.setAttribute(attr, value);

  if (siteNavigation) {
    /** @type {HTMLElement} */
    const mobileButton = siteNavigation.querySelector(
      ".nmota__header__navigation--button"
    );

    /**
     * Au clic sur le bouton mobile, on affiche ou masque le menu :
     * - on ajouter/supprime la classe "toggled" sur la <nav> qui nous servira à masquer/afficher en css
     * - on passe l'attribut "aria-expanded" à true/false
     */

    if (mobileButton) {
      mobileButton.addEventListener("click", function () {
        siteNavigation.classList.toggle("toggled");

        if (siteNavigation.classList.contains("toggled")) {
          siteNavigation.classList.remove("overflow-hidden"); // Désactive overflow-hidden
        } else {
          // Fermer le menu après une courte pause pour permettre à la transition de se terminer
          setTimeout(() => {
            siteNavigation.classList.add("overflow-hidden");
          }, 400); // Correspond à la durée de la transition CSS
        }

        if (mobileButton.getAttribute("aria-expanded") === "true") {
          setAttr(mobileButton, "aria-expanded", "false");
        } else {
          setAttr(mobileButton, "aria-expanded", "true");
        }
      });
    }
  }
});
