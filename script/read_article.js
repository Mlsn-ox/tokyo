import { mapping } from "./map.js";
// import { icon } from "./map.js";

const modalImg = document.querySelector(".img-clickable");
const gps = document.querySelector("#map");
let lat = gps.dataset.lat;
let lng = gps.dataset.lng;
const map = mapping(lat, lng);
let marker = L.marker([lat, lng]).addTo(map);

favoriteBtn.addEventListener("click", function () {
  fetch("../controller/favorite_controller.php", getData(favoriteBtn))
    .then((response) => response.json())
    .then((res) => {
      nbrLike.forEach((nl) => {
        if (nl.dataset.id == res.id) {
          nl.innerHTML = res.like;
        }
      });
    });
});

function getData(el) {
  let id = el.dataset.id;
  const formData = new FormData();
  formData.append("id", id);
  const data = {
    method: "POST",
    body: formData,
  };
  return data;
}

// modalImg.addEventListener("click", function () {
//   let imgSrc = this.getAttribute("src");
//   document.getElementById("modalImage").setAttribute("src", imgSrc);
// });
