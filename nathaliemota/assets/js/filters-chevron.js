document.addEventListener("DOMContentLoaded", function () {
  // On sélectionne tous les éléments <select> à l'intérieur des éléments ayant la classe "filter-wrapper"
  const selects = document.querySelectorAll(".filter-wrapper select");

  // Pour chaque élément <select> trouvé
  selects.forEach((select) => {
    // On sélectionne l'élément qui précède directement le <select> (le chevron)
    const filterChevron = select.previousElementSibling;

    // On écoute le clic à chaque <select>
    select.addEventListener("click", function () {
      filterChevron.classList.toggle("opened");
    });

    // On ajoute un écouteur pour l'événement "blur" sur les select
    select.addEventListener("blur", function () {
      // Retire la classe "opened" du chevron lorsque le select perd le focus
      filterChevron.classList.remove("opened");
    });
  });
});
