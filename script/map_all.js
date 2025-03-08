import { mapping } from "./map.js";
// import { icon } from "./map.js";
const map = mapping(35.679432, 139.763259);

markerAll();

function markerAll() {
  fetch("../controller/map_all_controller.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((article) => {
        let gpsLat = Number(article.lat);
        let gpsLng = Number(article.lng);
        let words = article.content.split(" "); // Sépare le texte en mots
        if (words.length > 14) {
          article.content = words.slice(0, 14).join(" ") + "..."; // Prend les 14 premiers mots et ajoute "..."
        }

        let marker = L.marker([gpsLat, gpsLng]).addTo(map);
        marker.bindPopup(
          `<a href="read_article.php?id=${article.id}" class="pop-up">
            <h5>${article.title}</h5>
            <p class="categorie">${article.category}</p>
            <div class="img-contain">
            <img src="../assets/img_articles/${article.img}" class="text-center" alt="Photo de l'article">
            </div>
            <p>${article.content}</p>
          </a>`
        );
      });
    })
    .catch((error) => {
      console.error("Erreur AJAX:", error);
    });
}
