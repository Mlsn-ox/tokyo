const navLink = document.querySelectorAll(".nav-link");
const currentPath = window.location.pathname;
const urlParams = new URLSearchParams(window.location.search);
const lightInput = document.getElementById("light-theme");
const darkInput = document.getElementById("dark-theme");
const savedTheme = localStorage.getItem("theme");
const appliedTheme = savedTheme || "light";
document.documentElement.setAttribute("data-theme", appliedTheme);
document.documentElement.setAttribute("data-bs-theme", appliedTheme);

/**
 * Met automatiquement en surbrillance le lien actif dans la navigation.
 * - Si le lien correspond exactement à la page actuelle (hors page profil utilisateur),
 *   il reçoit la classe "active-page".
 * - Si on est sur `read_user.php?id=...` et que l'ID dans l'URL correspond à
 *   l'utilisateur connecté (récupéré via un `data-id`), alors le lien devient actif.
 */
navLink.forEach((a) => {
  const href = a.getAttribute("href");
  const baseHref = href.split("?")[0];
  if (baseHref === currentPath && !href.includes("read_user.php")) {
    a.classList.add("active-page");
    a.setAttribute("aria-current", "page");
  }
  if (currentPath.includes("read_user.php") && href.includes("read_user.php")) {
    const sessionId = document.querySelector("#id-giver").dataset.id;
    if (urlParams.get("id") === sessionId) {
      a.classList.add("active-page");
      a.setAttribute("aria-current", "page");
    }
  }
});

// Appliquer le thème au chargement
if (appliedTheme === "dark") {
  darkInput.checked = true;
} else {
  lightInput.checked = true;
}

// Mettre à jour le thème selon l'input choisi
function applyTheme(theme) {
  document.documentElement.setAttribute("data-theme", theme);
  document.documentElement.setAttribute("data-bs-theme", theme);
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
