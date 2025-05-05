import { mapping } from "./functions.js";
const map = mapping(35.679432, 139.763259);
const locate = document.querySelector(".localise");
const adress = document.querySelector(".adress");
const spinny = document.querySelector(".loading-icon");
let layerGroup = L.layerGroup().addTo(map);
let redIcon = L.icon({
  iconUrl: "../assets/logo_category/red-mark.png",
  iconSize: [44, 45], // size of the icon
  iconAnchor: [22, 45], // point of the icon which will correspond to marker's location
});

markerAll();

/**
 * Ajout de tous les markers sur la map
 */
function markerAll() {
  fetch("../ajax/map_all_controller.php")
    .then((response) => response.json())
    .then((data) => {
      data.forEach((article) => {
        let gpsLat = Number(article.art_lat);
        let gpsLng = Number(article.art_lng);
        let words = article.art_content.split(" ");
        if (words.length > 14) {
          article.art_content = words.slice(0, 14).join(" ") + "..."; // Prend les 14 premiers mots et ajoute "..."
        }
        let marker = L.marker([gpsLat, gpsLng]).addTo(map);
        marker.bindPopup(
          `<a href="read_article.php?id=${article.art_id}" class="pop-up">
            <h5>${article.art_title}</h5>
            <p class="categorie">${article.cat}</p>
            <div class="img-contain">
              <img src="../assets/img_articles/${article.img}" 
                class="text-center" alt="Photo de l'article">
            </div>
            <p>${article.art_content}</p>
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

/**
 * Récupération de l'adresse à partir des coordonnées GPS via API OpenStreetMap Nominatim
 * @param {number} x latitude
 * @param {number} y longitude
 * @returns {string} adresse
 */
async function getAdresse(x, y) {
  const url = `https://nominatim.openstreetmap.org/reverse?lat=${x}&lon=${y}&format=json`;
  try {
    const response = await fetch(url, {
      headers: {
        "User-Agent": "MonApplication/1.0", // Obligatoire
      },
    });
    const data = await response.json();
    adress.innerHTML =
      "Adresse : " + data.display_name || "Adresse non trouvée";
  } catch (error) {
    console.error("Erreur :", error);
  }
}

// Géolocalisation
function success(pos) {
  var crd = pos.coords;
  let geolat = crd.latitude;
  let geolng = crd.longitude;
  // Vérifier si les coordonnées correspondent à Tokyo
  if (!isInTokyo(geolat, geolng)) {
    adress.innerHTML = "Vous n'êtes pas à Tokyo... 🙇";
    adress.classList.add("text-danger", "fw-bold");
    spinny.classList.add("d-none");
    return; // Arrête la fonction si on n'est pas dans la zone de Tokyo
  }
  getAdresse(geolat, geolng); // Récupération de l'adresse
  layerGroup.clearLayers();
  map.setView([geolat, geolng], 16); // Recentrer sur position géolocalisée
  L.marker(
    [geolat, geolng],
    { icon: redIcon } // Ajout marker rouge
  ).addTo(layerGroup);
  spinny.classList.add("d-none"); // Disparition du spinner, remise du bouton
  locate.classList.remove("d-none");
}
function error(err) {
  console.warn(`ERREUR LOCALISATION (${err.code}): ${err.message}`);
  adress.innerHTML = "Adresse non trouvée";
  spinny.classList.add("d-none");
  locate.classList.remove("d-none");
}
const options = {
  enableHighAccuracy: true, // Demande une position la plus précise possible
  timeout: 7000, // Temps max d'attente
  maximumAge: 0, // Ne pas utiliser une position mise en cache
};
const localisation = function () {
  locate.classList.add("d-none"); // Disparition du bouton, apparition du spinner
  spinny.classList.remove("d-none");
  navigator.geolocation.getCurrentPosition(success, error, options);
};
locate.addEventListener("click", localisation);
