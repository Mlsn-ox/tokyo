import { mapping } from "./map.js";
const gps = document.querySelector("#map");
let lat = gps.dataset.lat;
let lng = gps.dataset.lng;
const map = mapping(lat, lng);
let marker = L.marker([lat, lng]).addTo(map);
const favoriteBtn = document.querySelector("#favorite");
const text = document.querySelector(".fav-text");

if (favoriteBtn.dataset.token) {
  // Vérifie que la session est bien active
  favoriteBtn.addEventListener("click", function (event) {
    event.preventDefault();
    const heart = document.querySelector(".heart");
    heart.style.transition = "opacity 0.2s ease";
    heart.style.opacity = "0";
    setTimeout(() => {
      fetch("../controller/favorite_controller.php", getData(favoriteBtn))
        .then((response) => response.json())
        .then((res) => {
          text.innerHTML = res.message;
          text.classList.remove("disappear");
          favoriteBtn.innerHTML = res.added
            ? "<span class='heart'>❤️</span><span class='d-none d-lg-inline'>Retirer des favoris</span>"
            : "<span class='heart'>🤍</span><span class='d-none d-lg-inline'>Ajouter aux favoris</span>";
          // Applique la transition au nouveau coeur
          const newHeart = favoriteBtn.querySelector(".heart");
          newHeart.style.opacity = "0";
          newHeart.style.transition = "opacity 0.3s ease";
          setTimeout(() => {
            // petit délai pour laisser le temps au DOM d'ajouter l'élément
            newHeart.style.opacity = "1";
            text.classList.add("disappear");
          }, 20);
        });
    }, 200);
  });
}

function getData(el) {
  let idPost = el.dataset.post;
  let idUser = el.dataset.user;
  let token = el.dataset.token;
  const formData = new FormData();
  formData.append("art_id", idPost);
  formData.append("user_id", idUser);
  formData.append("token", token);
  const data = {
    method: "POST",
    body: formData,
  };
  return data;
}
