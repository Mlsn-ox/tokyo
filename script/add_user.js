const submit = document.querySelector(".submit");
const inputName = document.querySelector("#name");
const nameInfo = document.querySelector("#nameHelp");
const passwords = document.querySelectorAll(".toggle-password");
const pswInfo1 = document.querySelector("#passwordHelp");
const pswInfo2 = document.querySelector("#pswHelp");
const psw1 = document.querySelector('input[name="password1"]');
const psw2 = document.querySelector('input[name="password2"]');

// Affiche le mot de passe en clair
passwords.forEach((toggle) => {
  toggle.addEventListener("click", () => {
    const inputId = toggle.getAttribute("data-target");
    const input = document.getElementById(inputId);
    const isPassword = input.getAttribute("type") === "password";
    input.setAttribute("type", isPassword ? "text" : "password");
    toggle.textContent = isPassword ? "🙈" : "👁️";
  });
});

function checkPsw(a, b) {
  return a === b ? true : false;
}
function patternPsw(c) {
  return /^(?=.*[A-Z])(?=.*\d).{7,}$/.test(c);
}

psw1.addEventListener("input", function () {
  if (patternPsw(psw1.value)) {
    psw1.style.border = "2px solid var(--green)";
    pswInfo1.classList.remove("text-danger");
    pswInfo1.classList.add("text-success");
  } else if (psw1.value === "") {
    psw1.style.border = "none";
    pswInfo1.classList.remove("text-success");
    pswInfo1.classList.remove("text-danger");
  } else {
    psw1.style.border = "2px solid var(--red)";
    pswInfo1.classList.remove("text-success");
    pswInfo1.classList.add("text-danger");
  }
});

psw2.addEventListener("input", function () {
  const match =
    checkPsw(psw1.value, psw2.value) &&
    patternPsw(psw1.value) &&
    patternPsw(psw2.value);
  if (match) {
    psw2.style.border = "2px solid var(--green)";
    pswInfo2.classList.remove("text-danger");
    pswInfo2.textContent = "Mot de passe ✅";
  } else if (psw2.value === "") {
    psw2.style.border = "none";
    pswInfo2.classList.remove("text-success");
    pswInfo2.classList.remove("text-danger");
    pswInfo2.textContent = "Confirmer le mot de passe.";
  } else {
    psw2.style.border = "2px solid var(--red)";
    pswInfo2.classList.add("text-danger");
    pswInfo2.textContent = "Les mots de passe ne correspondent pas.";
  }
});

inputName.addEventListener("input", function () {
  const name = this.value.trim();
  if (name === "") {
    inputName.style.border = "none";
    nameInfo.classList.remove("text-success", "text-danger");
    return;
  }
  if (/^[A-Za-zÀ-ÖØ-öø-ÿ0-9_-]{3,}$/.test(name)) {
    inputName.style.border = "2px solid var(--green)";
    // Vérifie que le nom d'utilisateur n'est pas déjà pris
    fetch("../ajax/check_name.php?name=" + encodeURIComponent(name))
      .then((response) => response.json())
      .then((data) => {
        if (data.taken) {
          nameInfo.classList.remove("text-success");
          nameInfo.classList.add("text-danger");
          nameInfo.textContent = "Nom d'utilisateur déjà pris.";
          inputName.style.border = "2px solid var(--red)";
        } else {
          nameInfo.classList.remove("text-danger");
          nameInfo.classList.add("text-success");
          nameInfo.textContent = "Nom d'utilisateur disponible.";
          inputName.style.border = "2px solid var(--green)";
        }
      })
      .catch((error) => {
        console.error("Erreur :", error);
      });
  } else {
    inputName.style.border = "2px solid var(--red)";
    nameInfo.textContent = "Nom d'utilisateur invalide.";
    nameInfo.classList.add("text-danger");
  }
});
