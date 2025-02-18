let html = document.querySelector("#articles");
let form = document.querySelector("#filter");

const params = new URLSearchParams(window.location.search);
let order = params.get("order"); // Récupère la valeur de order
let categories = params.getAll("category[]"); // Renvoie un array categories

let offset = 6; // Nombre d'articles déjà affichés
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
        loader.textContent = "Fin des articles.";
        window.removeEventListener("scroll", handleScroll); // Désactiver le scroll si plus d'articles
      } else {
        data.forEach((article) => {
          let articleHTML = `
            <a href="read_article.php?id=${article.id}" class="article g-md-2 m-2 mb-3" style="background-image: url('../assets/img_articles/${article.img}');">
                <div class="article-content text-dark">
                    <h2>${article.title}</h2>
                    <div class="content">
                        <p class="m-0 categorie">
                            <img src="../assets/logo_category/${article.category}.svg" alt="Catégorie ${article.category}" style="height: 16px;">
                            ${article.category}
                        </p>
                        <p>${article.content}</p>
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
