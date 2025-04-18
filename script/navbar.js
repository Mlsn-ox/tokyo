const captcha = document.querySelector(".g-recaptcha") || false;
const toTop = document.querySelector(".to-top");
const lightInput = document.getElementById("light-theme");
const darkInput = document.getElementById("dark-theme");
const savedTheme = localStorage.getItem("theme");
const appliedTheme = savedTheme || "light";
document.documentElement.setAttribute("data-theme", appliedTheme);
document.documentElement.setAttribute("data-bs-theme", appliedTheme);

// Thème au chargement
if (appliedTheme === "dark") {
  darkInput.checked = true;
} else {
  lightInput.checked = true;
}

// Thème selon switcher
function applyTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  document.documentElement.setAttribute("data-bs-theme", theme);
  if (captcha) {
    captcha.setAttribute("data-theme", theme);
  }
  localStorage.setItem("theme", theme);
}

function changeTheme() {
  if (lightInput.checked) {
    applyTheme("light");
  } else {
    applyTheme("dark");
  }
}

// Écouteurs sur les deux boutons
lightInput.addEventListener("change", () => {
  changeTheme();
});
darkInput.addEventListener("change", () => {
  changeTheme();
});

toTop.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth"
  });
});
