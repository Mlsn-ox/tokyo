const inputs = document.querySelectorAll("[required]");
const submit = document.querySelector(".submit");
const psw1 = document.querySelector('input[name="password1"]');
const psw2 = document.querySelector('input[name="password2"]');

// Tooltip Bootstrap
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

function checkPsw(a, b) {
  return a === b ? true : false;
}

psw2.addEventListener("input", function () {
  console.log(checkPsw(psw1.value, psw2.value));
});

/**
 * Vérification de la saisie des champs du formulaire
 * @returns {boolean} true si tous les champs sont remplis
 * @returns {boolean} false si un champ est vide
 * @returns {boolean} active submit si tous les champs sont remplis
 */
function checkForm() {
  // spread operator pour transformer la NodeList en tableau pour pouvoir utiliser la méthode every
  const allFilled = [...inputs].every((input) => {
    return input.value.trim() !== ""; // retourne true si tous les champs sont remplis
  });
  submit.disabled = !allFilled; // Désactive le bouton submit si un champ est vide
  if (allFilled && checkPsw(psw1.value, psw2.value)) {
    // Vérifie si les psw correspondent
    submit.innerHTML = "S'inscrire";
    submit.classList.add("notif-bounce", "btn-success");
    submit.classList.remove("btn-outline-success");
  } else {
    submit.innerHTML = "Remplissez tous les champs demandés";
    submit.classList.add("btn-outline-success");
    submit.classList.remove("notif-bounce", "btn-success");
  }
}

inputs.forEach((input) => {
  input.addEventListener("input", checkForm);
});
