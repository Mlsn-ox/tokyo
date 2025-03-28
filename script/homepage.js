import { escapeHTML } from "./functions.js";
const openWeather = document.getElementById("meteo-tokyo");
const horaire = document.getElementById("heure-tokyo");
const webcam = document.getElementById("cam");
const sakura = document.querySelector(".petales");
let petalInterval;

updateTime();
setInterval(updateTime, 1000);

window.addEventListener("resize", handlePetalEffect);
handlePetalEffect();

/**
 * Crée une pétale animée qui tombe depuis une position aléatoire du haut de l’écran.
 */
function createPetal() {
  const petal = document.createElement("div");
  petal.classList.add("petal");
  let startPosition = Math.random() * (window.innerWidth - 30);
  let duration = Math.random() * 5 + 5;
  let size = Math.random() * 10 + 10;
  petal.style.left = `${startPosition}px`;
  petal.style.animationDuration = `${duration}s`;
  petal.style.width = `${size}px`;
  petal.style.height = `${size}px`;
  document.body.appendChild(petal);
  setTimeout(() => {
    petal.remove();
  }, duration * 1000);
}

/**
 * Active ou désactive l’effet de pétales en fonction de la largeur de l’écran.
 */
function handlePetalEffect() {
  if (window.innerWidth > 992) {
    if (!petalInterval) {
      petalInterval = setInterval(createPetal, 700);
    }
  } else {
    if (petalInterval) {
      clearInterval(petalInterval);
      petalInterval = null;
    }
  }
}

/**
 * Met à jour dynamiquement l’heure actuelle à Tokyo, au format `HH:MM:SS`,
 * Fonction réactualisée toutes les secondes.
 */
function updateTime() {
  const options = {
    timeZone: "Asia/Tokyo",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  };
  const time = new Date().toLocaleTimeString("fr-FR", options);
  horaire.textContent = time;
}

/**
 * Récupère les données météorologiques actuelles de Tokyo via l'API OpenWeatherMap.
 * - Affiche la température arrondie en °C
 * - Affiche la description météo (capitalisée)
 * - Affiche une icône correspondante
 */
const apiKeyOpenW = "e1edbd55f7fda615ba1c2906bf6454e9";
const urlOpenW = `https://api.openweathermap.org/data/2.5/weather?q=Tokyo&appid=${apiKeyOpenW}&units=metric&lang=fr`;
fetch(urlOpenW)
  .then((response) => response.json())
  .then((data) => {
    const temperature = Math.round(data.main.temp);
    let meteo = data.weather[0].description;
    meteoCapital = meteo.charAt(0).toUpperCase() + meteo.slice(1);
    const iconCode = data.weather[0].icon;
    let iconUrl = "";
    if (iconCode === "01n") {
      iconUrl = `../assets/logo_category/clear-night.png`;
    } else if (iconCode === "02n") {
      iconUrl = `../assets/logo_category/partly-cloudy-night.png`;
    } else {
      iconUrl = `https://openweathermap.org/img/wn/${iconCode}@2x.png`;
    }
    openWeather.innerHTML = `<p>🌡️ ${escapeHTML(
      temperature
    )}°c &nbsp;</p><p> - &nbsp; ${escapeHTML(meteoCapital)} &nbsp;</p>
      <img src="${escapeHTML(iconUrl)}" alt="${escapeHTML(
      meteo
    )}" class="icon-meteo">`;
  })
  .catch((error) => console.error("Erreur :", error));
