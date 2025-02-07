import { mapping } from "./map.js";
const gps = document.querySelector("#map");
let lat = gps.dataset.lat;
let lng = gps.dataset.lng;
const map = mapping(lat, lng);
let marker = L.marker([lat, lng]).addTo(map);
