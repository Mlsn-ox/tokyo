const modalImg = document.querySelector(".img-clickable");

modalImg.addEventListener("click", function () {
  let imgSrc = this.getAttribute("src");
  document.getElementById("modalImage").setAttribute("src", imgSrc);
});

import { mapping } from "./map.js";
import { icon } from "./map.js";

const gps = document.querySelector("#map");
let lat = gps.dataset.lat;
let lng = gps.dataset.lng;
const map = mapping(lat, lng);
let marker = L.marker([lat, lng]).addTo(map);
