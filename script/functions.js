/**
 * Initialise carte Leaflet centrée sur les coordonnées rentrées
 * @param {number} lat - Latitude
 * @param {number} lng - Longitude
 * @returns {L.Map} - L'objet carte Leaflet initialisé
 */
export function mapping(lat, lng) {
  const map = L.map("map").setView([lat, lng], 12);
  let layerGroup = L.layerGroup();
  const street = L.tileLayer(
    "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
    {
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      maxZoom: 19,
    }
  ).addTo(map);
  const sat = L.tileLayer(
    "https://api.mapbox.com/styles/v1/mapbox/satellite-streets-v12/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWxzbiIsImEiOiJjbTZpMjR6dzMwMnRmMmlxc2NsdGNpdzBtIn0.ODgHxRTgvTEgXe9jH7r88A",
    {
      attribution: '&copy; <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 19,
    }
  );
  const dark = L.tileLayer(
    "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
    {
      attribution:
        '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors & Carto',
      subdomains: "abcd",
      maxZoom: 19,
    }
  );
  const baseMaps = {
    OpenStreet: street,
    Satellite: sat,
    DarkMap: dark,
  };
  L.control.layers(baseMaps).addTo(map);
  L.control.scale().addTo(map);
  return map;
}

/**
 * Échappe les caractères HTML spéciaux dans une chaîne de caractères.
 * @param {any} input - La chaîne/nombre à sécuriser
 * @returns {string} - La chaîne échappée, prête à être injectée dans le DOM
 */
export function escapeHTML(input) {
  const str = String(input); // Conversion en chaîne au cas où
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#039;");
}

/**
 * Retourne un émoji en fonction de la catégorie.
 * @param string catégorie
 * @return string émoji
 */
export function getEmojiCategory(cat) {
  switch (cat) {
    case "gastronomie":
      return "🍜";
    case "loisir":
      return "🎳";
    case "shopping":
      return "🛍";
    case "panorama":
      return "📷";
    default:
      return "🎎";
  }
}

/**
  * Affiche ou masque le mot de passe dans un champ de saisie.  
  * @param {Event} event - L'événement de clic sur le bouton de basculement.
  */
export function togglePassword(event) {
  const toggle = event.currentTarget;
  const inputId = toggle.getAttribute("data-target");
  const input = document.getElementById(inputId);
  if (!input) return;
  const isPassword = input.getAttribute("type") === "password";
  input.setAttribute("type", isPassword ? "text" : "password");
  toggle.textContent = isPassword ? "🙈" : "👁️";
}
