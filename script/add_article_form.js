import { mapping } from "./map.js";
const inputFiles = document.querySelector(".files");
const preview = document.querySelector(".preview");
let image = document.createElement("img");
const cat = document.querySelector(".category");
const lat = document.getElementById("lat");
const lng = document.getElementById("lng");
const locate = document.querySelector(".localise");
const adress = document.querySelector(".adress");
const spinny = document.querySelector(".loading-icon");
cat[0].selected = true;
image.src = "";

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

// Outils Map LEAFLET
const map = mapping(35.705, 139.74);
let layerGroup = L.layerGroup().addTo(map);

map.on("click", onMapClick);

/**
 * Disabled placeholder select form
 */
cat.addEventListener("change", function () {
  cat[0].disabled = true;
});

/**
 * Affichage miniature des images téléchargées.
 * @param {object} inputFiles img input files
 * @returns {document} image miniature
 */
inputFiles.addEventListener("change", function () {
  preview.innerHTML = "";
  const file = this.files[0];
  const maxSize = 2 * 1024 * 1024; // 2 Mo en octets
  let para = document.createElement("p");
  let size = fileSize(file.size);
  if (file.size > maxSize) {
    para.textContent = `Fichier trop volumineux : ${size} (2 Mo maximum)`;
    this.value = "";
  } else {
    let name = file.name.slice(0, 25);
    para.textContent = `${name}, ${size}.`;
    image.src = window.URL.createObjectURL(file);
    preview.appendChild(image);
  }
  preview.appendChild(para);
});

/**
 * Affichage de la taille des img téléchargées.
 * @param {number} size
 * @returns {string} taille en octets
 */
function fileSize(size) {
  if (size < 1024) {
    return size + " octets";
  } else if (size >= 1024 && size < 1048576) {
    return (size / 1024).toFixed(1) + " Ko";
  } else if (size >= 1048576) {
    return (size / 1048576).toFixed(1) + " Mo";
  }
}

/**
 * Données GPS du marker insuflées dans les inputs associés
 * @param {event} e marqueur à l'événement click
 * @returns {number} valeurs latitude et longitude
 */
function onMapClick(e) {
  // Nettoyer les anciens marqueurs dans le layerGroup
  layerGroup.clearLayers();
  let marker = L.marker([e.latlng.lat, e.latlng.lng]);
  layerGroup.addLayer(marker); // Ajouter le marqueur au layerGroup
  map.addLayer(layerGroup);
  lat.value = marker._latlng.lat;
  lng.value = marker._latlng.lng;
  getAdresse(marker._latlng.lat, marker._latlng.lng);
}

// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
  "use strict";
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll(".needs-validation");
  // Loop over them and prevent submission
  Array.from(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        lat.disabled = false;
        lng.disabled = false;
        if (!form.checkValidity() || !lat.value.trim() || !lng.value.trim()) {
          event.preventDefault();
          event.stopPropagation();
          if (!lat.value || !lng.value) {
            lat.classList.add("is-invalid");
            lng.classList.add("is-invalid");
          }
        }
        form.classList.add("was-validated");
        lat.disabled = true;
        lng.disabled = true;
      },
      false
    );
  });
})();

//
async function getAdresse(lat, lon) {
  const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`;
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

function success(pos) {
  var crd = pos.coords;
  let geolat = crd.latitude;
  let geolng = crd.longitude;
  // Mettre à jour les valeurs des inputs
  lat.value = geolat;
  lng.value = geolng;
  // Appel de la fonction pour récupérer l'adresse
  getAdresse(geolat, geolng);
  // Vérifier si la couche de marqueurs existe et la nettoyer
  layerGroup.clearLayers();
  // Ajouter le marqueur à la carte avec Leaflet
  map.setView([geolat, geolng], 16);
  L.marker([geolat, geolng]).addTo(layerGroup);
  spinny.classList.add("d-none");
  locate.classList.remove("d-none");
  setTimeout(() => {
    let precise =
      "<span class='text-danger fw-bold d-block'>ATTENTION : la géolocalisation est approximative, n'hésitez pas à modifier la position du marker sur la map.</span>";
    adress.innerHTML += precise;
  }, 1000);
}

function error(err) {
  console.warn(`ERREUR LOCALISATION (${err.code}): ${err.message}`);
  spinny.classList.add("d-none");
  locate.classList.remove("d-none");
}

var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0,
};

const localisation = function () {
  locate.classList.add("d-none");
  spinny.classList.remove("d-none");
  navigator.geolocation.getCurrentPosition(success, error, options);
};

// Écouteur sur le bouton pour déclencher la localisation
locate.addEventListener("click", localisation);
