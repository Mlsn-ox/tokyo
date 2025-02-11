// Tooltip Bootstrap
document.addEventListener("DOMContentLoaded", function () {
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]') // Sélectionne attribut data-bs-toggle="tooltip"
  );
  tooltipTriggerList.forEach(function (input) {
    let tooltip = new bootstrap.Tooltip(input, { trigger: "manual" }); // Permet l'affichage du tooltip quand l'utilisateur saisit des données
    input.addEventListener("focus", function () {
      tooltip.show();
    });
    input.addEventListener("blur", function () {
      tooltip.hide();
    });
  });
});
