let html = document.getElementById("articles");
let form = document.getElementById("filter");

const params = new URLSearchParams(window.location.search);
let categories = params.getAll("category[]"); // Renvoie un tableau
let order = params.get("order"); // Récupère la valeur de 'order'

let loading = false;
let offset = 6; // Nombre d'articles déjà affichés
const limit = 6; // Nombre d'articles à charger à chaque fois

let ajaxURL = `../controller/fetch_articles.php?offset=${offset}&limit=${limit}`;

// Ajoute les catégories si présent
if (categories) {
  categories.forEach((category) => {
    ajaxURL += `&category[]=${encodeURIComponent(category)}`;
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
 * @return documuent html
 */
function loadMoreArticles() {
  if (loading) return; // Evite les appels multiples si loading = true
  loading = true;
  fetch(ajaxURL)
    .then((response) => response.json())
    .then((data) => {
      if (data.length === 0) {
        loader.textContent = "Fin des articles.";
        window.removeEventListener("scroll", handleScroll); // Désactiver le scroll si plus d'articles
      } else {
        data.forEach((article) => {
          let articleHTML = `
                            <a href="read_article.php?id=${article.article_id}" class="article g-md-2 m-2 mb-3" style="background-image: url('../assets/img_articles/${article.article_img}');">
                                <div class="article-content text-dark">
                                    <h2>${article.article_title}</h2>
                                    <div class="content">
                                        <p class="m-0 categorie">
                                            <img src="../assets/logo_category/${article.article_category}.svg" alt="Catégorie ${article.article_category}" style="height: 16px;">
                                            ${article.article_category}
                                        </p>
                                        <p>${article.article_content}</p>
                                    </div>
                                </div>
                            </a>
                        `;
          html.insertAdjacentHTML("beforeend", articleHTML);
        });
        console.log(offset);

        offset += limit; // Mise à jour de l'offset
        loading = false;
      }
    })
    .catch((error) => {
      console.error("Erreur:", error);
      loading = false;
    });
}
