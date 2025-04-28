import { escapeHtml } from "./functions.js";
const dropFilters = document.querySelector(".dropfilters");
const collapseFilters = document.querySelector("#collapseFilters");
const resetBtn = document.getElementById("reset");
const input = document.querySelector("input[name='search']");
const select = document.querySelector("select");
const urlParams = new URLSearchParams(window.location.search);
const search = urlParams.get("search") || "";
const cat = urlParams.get("cat") || "";

if (search || cat) {
  dropFilters.style.boxShadow = "var(--inner-shadow)";
} else {
  dropFilters.style.boxShadow = "var(--shadow)";
}

input.value = search;
for (const option of select.options) {
  if (option.value === cat) {
    option.selected = true;
  }
}

resetBtn.addEventListener("click", function () {
  input.value = "";
  select.selectedIndex = 0;
});

dropFilters.addEventListener("click", function () {
  dropFilters.classList.add("fade-out");
  setTimeout(() => {
    if (collapseFilters.classList.contains("show")) {
      dropFilters.innerHTML = "Fermer ⤴";
      dropFilters.style.boxShadow = "var(--inner-shadow)";
    } else {
      dropFilters.innerHTML = "Rechercher ⤵";
      dropFilters.style.boxShadow = "var(--shadow)";
    }
    dropFilters.classList.remove("fade-out");
  }, 400);
});
