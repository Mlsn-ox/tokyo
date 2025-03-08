import { mapping } from "./map.js";
const inputFiles = document.querySelector(".files");
const preview = document.querySelector(".preview");
const form = document.querySelector("form");
const dis = document.querySelector(".disappear");
let image = document.createElement("img");
const cat = document.querySelector(".category");
cat[0].selected = true;
image.src = "";

// Outils Map LEAFLET
const map = mapping(35.705, 139.74);
let layerGroup = L.layerGroup();

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
 * Affichage de la taille des img téléchargés.
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
  if (map.hasLayer(layerGroup)) {
    layerGroup.clearLayers();
  }
  let marker = L.marker([e.latlng.lat, e.latlng.lng]);
  layerGroup.addLayer(marker);
  map.addLayer(layerGroup);
  document.querySelector("#lat").value = marker._latlng.lat;
  document.querySelector("#lng").value = marker._latlng.lng;
}

// Ouvrir une modale en fonction d'un param URL

// Fonction pour récupérer les param de l'URL en fonction du "name=""
function getParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name); // Ne retourne que le param souhaité dans l'url
}

if (getParam("showModal") === "1") {
  let myModal = new bootstrap.Modal(document.getElementById("exampleModal"));
  myModal.show();
}
