const formUpdateImg = document.querySelector("form.img-update");
const formUpdateUser = document.querySelector("form.user-update");
const updateImg = document.querySelector(".update-img");
const modalOpen = document.querySelector(".offcanvas-link");
const psw1 = document.querySelector("#password2");
const psw2 = document.querySelector("#password3");
const pswInfo1 = document.querySelector("#passwordHelp");
const pswInfo2 = document.querySelector("#pswHelp");
const passwords = document.querySelectorAll(".toggle-password");
const confirmBtn = document.querySelector(".btn-confirm");
const deleteBtnModal = document.querySelectorAll(".btn-delete-modal");
const deleteBtn = document.querySelector(".btn-delete");
const closeImgBtn = document.querySelector(".btn-close-img");
const closeDeleteBtn = document.querySelector(".btn-close-delete");
const avatars = document.querySelectorAll(".avatar-canva");
const inputName = document.querySelector("#name");
const nameInfo = document.querySelector("#nameHelp");
let idToDelete = null;
let idSession = null;
let token = null;

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

deleteBtnModal.forEach((btn) => {
  btn.addEventListener("click", () => {
    idToDelete = btn.dataset.id;
    idSession = btn.dataset.session;
    token = btn.dataset.token;
  });
});

deleteBtn.addEventListener("click", () => {
  if (!idToDelete || !idSession || !token) return;
  const formData = new FormData();
  formData.append("art_id", idToDelete);
  formData.append("user_id", idSession);
  formData.append("token", token);
  fetch("../ajax/delete_article_controller.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.success) {
        const selecteur = "art-" + idToDelete;
        const article = document.getElementById(selecteur);
        article.remove();
        closeDeleteBtn.click();
      }
    });
});

modalOpen.addEventListener("click", () => {
  avatars.forEach((avatar) => {
    const input = avatar.closest(".form-check");
    if (avatar.src === updateImg.src) {
      input.style.display = "none";
    } else {
      input.style.display = "block";
    }
  });
});

formUpdateImg.addEventListener("submit", (e) => {
  e.preventDefault();
  const formData = new FormData(formUpdateImg);
  fetch("../ajax/update_img_controller.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((res) => {
      updateImg.src = "../assets/img_profil/" + res.image;
      closeImgBtn.click();
    });
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
    fetch("../ajax/check_name.php?name=" + encodeURIComponent(name))
      .then((response) => response.json())
      .then((data) => {
        if (data.taken && inputName.value !== data.actualName) {
          nameInfo.classList.remove("text-success");
          nameInfo.classList.add("text-danger");
          nameInfo.textContent = "Nom d'utilisateur déjà pris.";
          inputName.style.border = "2px solid var(--red)";
        } else if (inputName.value === data.actualName) {
          inputName.style.border = "none";
          nameInfo.classList.remove("text-success", "text-danger");
          nameInfo.textContent = "Entre 3 et 20 caractères.";
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
