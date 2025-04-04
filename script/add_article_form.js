import { mapping } from "./functions.js";
const modal = document.querySelector("#confirmModal");
const inputFiles = document.querySelector(".files");
const preview = document.querySelector(".preview");
const cat = document.querySelector(".category");
const lat = document.getElementById("lat");
const lng = document.getElementById("lng");
const locate = document.querySelector(".localise");
const adress = document.querySelector(".adress");
const spinny = document.querySelector(".loading-icon");
const inputs = document.querySelectorAll("input, textarea, select");
const submit = document.querySelector(".submit");
let image = document.createElement("img");
image.src = "";

cat[0].selected = true; // Sélectionne la première option de la liste déroulante

// Tooltip Bootstrap
const tooltipTriggerList = [].slice.call(
  document.querySelectorAll('[data-bs-toggle="tooltip"]') // Sélectionne attribut data-bs-toggle="tooltip"
);
tooltipTriggerList.forEach(function (input) {
  let tooltip = new bootstrap.Tooltip(input, { trigger: "manual" }); // Permet l'affichage du tooltip quand l'utilisateur saisit des données
  input.addEventListener("focus", function () {
    tooltip.show();
  });
  input.addEventListener("blur", function () {
    tooltip.hide();
  });
});

window.addEventListener("DOMContentLoaded", () => {
  const maModale = new bootstrap.Modal(modal);
  maModale.show();
});

/**
 * Vérification de la saisie des champs du formulaire
 * @returns {boolean} true si tous les champs sont remplis
 * @returns {boolean} false si un champ est vide
 * @returns {boolean} active submit si tous les champs sont remplis
 */
function checkForm() {
  // spread operator pour transformer la NodeList en tableau pour pouvoir utiliser la méthode every
  const allFilled = [...inputs].every((input) => {
    return input.value.trim() !== ""; // retourne true si tous les champs sont remplis
  });
  submit.disabled = !allFilled; // Désactive le bouton submit si un champ est vide
  if (allFilled) {
    submit.innerHTML = "Envoyer";
    submit.classList.add("notif-bounce", "btn-success");
    submit.classList.remove("btn-outline-success");
  } else {
    submit.innerHTML = "Veuillez remplir tous les champs demandés";
    submit.classList.add("btn-outline-success");
    submit.classList.remove("notif-bounce", "btn-success");
  }
}

inputs.forEach((input) => {
  input.addEventListener("input", checkForm);
});

// Initialisation de la map
const map = mapping(35.705, 139.74);
let layerGroup = L.layerGroup().addTo(map);
map.on("click", onMapClick);

// Désactivation de la première option de la liste déroulante
cat.addEventListener("change", function () {
  cat[0].disabled = true;
});

/**
 * Affichage de l'image miniature et contrôle de la taille du fichier
 * @param {object} e événement change
 * @returns {string} nom fichier
 * @returns {string} taille fichier
 * @returns {string} message d'erreur si fichier trop volumineux
 */
inputFiles.addEventListener("change", function () {
  preview.innerHTML = "";
  const file = this.files[0];
  const maxSize = 10 * 1024 * 1024; // 10 Mo en octets
  let para = document.createElement("p");
  let size = fileSize(file.size);
  if (file.size > maxSize) {
    para.textContent = `Fichier trop volumineux : ${size} (10 Mo maximum)`;
    this.value = "";
  } else {
    let name =
      file.name.length > 25 ? file.name.slice(0, 22) + "..." : file.name; // Limite le nom du fichier à 25 caractères
    para.textContent = `${name}, ${size}.`;
    image.src = window.URL.createObjectURL(file);
    preview.appendChild(image);
  }
  preview.appendChild(para);
});

/**
 * Conversion taille fichier en octets, Ko, Mo
 * @param {number} size taille fichier
 * @returns {string} taille fichier en octets, Ko, Mo
 */
function fileSize(size) {
  if (size < 1024) {
    return size + " octets";
  } else if (size >= 1024 && size < 1048576) {
    return (size / 1024).toFixed(1) + " Ko";
  } else {
    return (size / 1048576).toFixed(1) + " Mo";
  }
}

/**
 * Récupération des coordonnées GPS à partir du clic sur la map
 * @param {object} e événement click
 * @returns {number} valeurs input lat
 * @returns {number} valeurs input lng
 */
function onMapClick(e) {
  // Nettoyer les anciens marqueurs dans le layerGroup
  layerGroup.clearLayers();
  let marker = L.marker([e.latlng.lat, e.latlng.lng]);
  layerGroup.addLayer(marker); // Ajouter le marqueur au layerGroup
  map.addLayer(layerGroup);
  lat.value = marker._latlng.lat;
  lng.value = marker._latlng.lng;
  checkForm();
  getAdresse(marker._latlng.lat, marker._latlng.lng);
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

/**
 * Vérification que les coordonnées soient bien à Tokyo
 * @param {number} x latitude
 * @param {number} y longitude
 * @returns {boolean}
 */
function isInTokyo(x, y) {
  return x >= 35.52 && x <= 35.8 && y >= 139.46 && y <= 139.91;
}

// Géolocalisation
function success(pos) {
  var crd = pos.coords;
  let geolat = crd.latitude;
  let geolng = crd.longitude;
  // Vérifier si les coordonnées correspondent à Tokyo
  if (!isInTokyo(geolat, geolng)) {
    adress.innerHTML = "Dommage, vous n'êtes pas à Tokyo.";
    spinny.classList.add("d-none");
    locate.classList.remove("d-none");
    return; // Arrête la fonction si on n'est pas dans la zone de Tokyo
  }
  lat.value = geolat; // Mise à jour valeurs des inputs
  lng.value = geolng;
  checkForm();
  getAdresse(geolat, geolng); // Appel de la fonction pour récupérer l'adresse
  layerGroup.clearLayers();
  map.setView([geolat, geolng], 16); // Recentrer sur position géolocalisée
  L.marker([geolat, geolng]).addTo(layerGroup); // Ajout marker
  spinny.classList.add("d-none"); // Disparition du spinner, remise du bouton
  locate.classList.remove("d-none");
  setTimeout(() => {
    // Message d'attention avec Timeout pour être sur de l'affichage
    let precise =
      "<span class='text-danger fw-bold d-block'>La géolocalisation peut être approximative, n'hésitez pas à modifier la position du marker sur la map.</span>";
    adress.innerHTML += precise;
  }, 1000);
  console.log(lat.value, lng.value);
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
