import { mapping } from "./map.js";
const map = mapping(35.679432, 139.763259);

markerAll();

function markerAll() {
  fetch("../controller/map_all_controller.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((article) => {
        let gpsLat = Number(article.article_lat);
        let gpsLng = Number(article.article_lng);

        let words = article.article_content.split(" "); // Sépare le texte en mots
        if (words.length > 15) {
          article.article_content = words.slice(0, 15).join(" ") + "..."; // Prend les 20 premiers mots et ajoute "..."
        }

        let marker = L.marker([gpsLat, gpsLng]).addTo(map);
        marker.bindPopup(
          `<a href="read_article.php?id=${article.article_id}" class="pop-up">
            <h5>${article.article_title}<h5>
            <p class="categorie">${article.article_category}<p>
            <p>${article.article_content}<p>
            <img src="../assets/img_articles/${article.article_img}"
                class="text-center"
                alt="Photo de l'article">
            </a>
            `
        );
      });
    })
    .catch((error) => {
      console.error("Erreur AJAX:", error);
    });
}
