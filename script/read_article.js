import { mapping } from "./map.js";
const gps = document.querySelector("#map");
let lat = gps.dataset.lat;
let lng = gps.dataset.lng;
const map = mapping(lat, lng);
let marker = L.marker([lat, lng]).addTo(map);
const favoriteBtn = document.querySelector(".favorite");
const favoriteText = document.querySelector(".text-fav");

favoriteBtn.addEventListener("click", function (event) {
  event.preventDefault();
  fetch("../controller/favorite_controller.php", getData(favoriteBtn))
    .then((response) => response.json())
    .then((res) => {
      console.log(res.status);
      console.log(res.message);
      console.log(res.added);
      if (res.added) {
        favoriteBtn.innerHTML = `<span class='text-fav disappear'>${res.message}</span>💙`;
      } else {
        favoriteBtn.innerHTML = `<span class='text-fav disappear'>${res.message}</span>🤍`;
      }
    });
});

console.log(getData(favoriteBtn));
console.log(favoriteBtn.dataset.user);

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
