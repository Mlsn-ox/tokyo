const navLink = document.querySelectorAll(".nav-link");
const currentPath = window.location.pathname;
const urlParams = new URLSearchParams(window.location.search);
const toggleBtn = document.getElementById("toggle-theme");
const themeIcon = document.getElementById("theme-icon");

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

// Fonction pour mettre à jour l’icône
function updateIcon(theme) {
  themeIcon.textContent = theme === "dark" ? "🌙" : "☀️";
}

// Appliquer le thème au chargement
const savedTheme = localStorage.getItem("theme");
const appliedTheme = savedTheme || "light";
document.documentElement.setAttribute("data-theme", appliedTheme);
document.documentElement.setAttribute("data-bs-theme", appliedTheme);
updateIcon(appliedTheme);

// Toggle du thème
toggleBtn.addEventListener("click", () => {
  const currentTheme = document.documentElement.getAttribute("data-theme");
  const newTheme = currentTheme === "dark" ? "light" : "dark";
  document.documentElement.setAttribute("data-theme", newTheme);
  document.documentElement.setAttribute("data-bs-theme", newTheme);
  localStorage.setItem("theme", newTheme);
  updateIcon(newTheme);
});
