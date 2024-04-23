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

        if (mobileButton.getAttribute("aria-expanded") === "true") {
          setAttr(mobileButton, "aria-expanded", "false");
        } else {
          setAttr(mobileButton, "aria-expanded", "true");
        }
      });
    }
  }
});
