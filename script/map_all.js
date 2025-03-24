import { mapping } from "./map.js";
const map = mapping(35.679432, 139.763259);
const locate = document.querySelector(".localise");
const adress = document.querySelector(".adress");
const spinny = document.querySelector(".loading-icon");
let layerGroup = L.layerGroup().addTo(map);

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

/**
 * Vérification que les coordonnées soient bien à Tokyo
 * @param {number} lat latitude
 * @param {number} lng longitude
 * @returns {boolean}
 */
function isInTokyo(lat, lng) {
  return lat >= 35.52 && lat <= 35.8 && lng >= 139.46 && lng <= 139.91;
}

// Géolocalisation
function success(pos) {
  var crd = pos.coords;
  let geolat = crd.latitude;
  let geolng = crd.longitude;
  // Vérifier si les coordonnées correspondent à Tokyo
  if (!isInTokyo(geolat, geolng)) {
    adress.innerHTML = "Dommage, vous n'êtes pas à Tokyo.";
    adress.classList.add("text-danger", "fw-bold");
    spinny.classList.add("d-none");
    return; // Arrête la fonction si on n'est pas dans la zone de Tokyo
  }
  layerGroup.clearLayers();
  map.setView([geolat, geolng], 16); // Recentrer sur position géolocalisée
  L.marker([geolat, geolng]).addTo(layerGroup); // Ajout marker
  spinny.classList.add("d-none"); // Disparition du spinner, remise du bouton
  locate.classList.remove("d-none");
}
function error(err) {
  console.warn(`ERREUR LOCALISATION (${err.code}): ${err.message}`);
  adress.innerHTML = "Adresse non trouvée";
  spinny.classList.add("d-none");
  locate.classList.remove("d-none");
}
var options = {
  enableHighAccuracy: true,
  timeout: 7000,
  maximumAge: 0,
};
const localisation = function () {
  locate.classList.add("d-none"); // Disparition du bouton, apparition du spinner
  spinny.classList.remove("d-none");
  navigator.geolocation.getCurrentPosition(success, error, options);
};
locate.addEventListener("click", localisation); // Appel de la fonction de géolocalisation
