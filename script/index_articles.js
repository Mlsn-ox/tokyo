import { getEmojiCategory } from "./functions.js";
import { escapeHTML } from "./functions.js";
let html = document.querySelector("#articles");
const params = new URLSearchParams(window.location.search);
let order = params.get("order"); // Récupère la valeur de order
let categories = params.getAll("category[]"); // Renvoie un array categories
let offset = 0; // Nombre d'articles déjà affichés
const limit = 6; // Nombre d'articles à charger à chaque fois
let loading = false;

let ajaxURL = `../controller/fetch_articles.php?offset=${offset}&limit=${limit}`;

// Ajoute les catégories si présent
if (categories) {
  categories.forEach((category) => {
    ajaxURL += `&category[]=${encodeURIComponent(category)}`;
    // encodeURIComponent: protection en cas de caractères spéciaux
  });
}

// Ajoute l'ordre si présent
if (order) {
  ajaxURL += `&order=${encodeURIComponent(order)}`;
}

loadMoreArticles();

window.addEventListener("scroll", handleScroll);

function handleScroll() {
  if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
    // Requête déclenchée 200px avant le bas
    loadMoreArticles();
  }
}

/**
 * Ajax pour pagination dynamique
 * @return document html
 */
function loadMoreArticles() {
  if (loading) return; // Evite les appels multiples si loading = true
  loading = true;
  fetch(ajaxURL)
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.length === 0) {
        loader.textContent = "Tous les spots ont été chargés";
        window.removeEventListener("scroll", handleScroll); // Désactiver le scroll si plus d'articles
      } else {
        data.forEach((article) => {
          let emoji = getEmojiCategory(article.cat);
          let articleHTML = `
            <a href="read_article.php?id=${escapeHTML(
              article.art_id
            )}" class="article mb-3" style="background-image: url('../assets/img_articles/${escapeHTML(
            article.img
          )}');">
                <div class="article-content text-dark">
                    <h2 class="mx-1">${escapeHTML(article.art_title)}</h2>
                    <div class="content">
                        <p class="m-0 categorie">
                            ${emoji} ${escapeHTML(article.cat)}
                        </p>
                        <p>${escapeHTML(article.art_content)}</p>
                    </div>
                </div>
            </a>`;
          html.insertAdjacentHTML("beforeend", articleHTML);
        });
        offset += limit; // Mise à jour de l'offset
        ajaxURL = `../controller/fetch_articles.php?offset=${offset}&limit=${limit}`;
        loading = false;
      }
    })
    .catch((error) => {
      console.error("Erreur:", error);
      loading = false;
    });
}
