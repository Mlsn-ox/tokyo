const formUpdateImg = document.querySelector("form.img-update");
const formUpdateUser = document.querySelector("form.user-update");
const updateImg = document.querySelector(".update-img");
const modalOpen = document.querySelector(".offcanvas-link");
const confirmPsw = document.querySelectorAll(".verify");
const passwords = document.querySelectorAll(".toggle-password");
const confirmBtn = document.querySelector(".btn-confirm");
const closeBtn = document.querySelector(".btn-close");
const avatars = document.querySelectorAll(".avatar-canva");

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

confirmPsw.forEach((psw) => {
  psw.addEventListener("input", () => {
    if (psw.value.length < 7) {
      psw;
      psw.setCustomValidity("7 caractères, un chiffre, une majuscule.");
      psw.reportValidity();
    } else {
      psw.setCustomValidity("");
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
  fetch("../controller/update_img_controller.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((res) => {
      console.log(res.image);
      updateImg.src = "../assets/img_profil/" + res.image;
      closeBtn.click();
    });
});
