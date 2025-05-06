import { escapeHtml } from "./functions.js";
import { getEmojiCategory } from "./functions.js";
const pendingContainer = document.querySelector("#pending");
const pendingComContainer = document.querySelector("#pending-com");
const statsContainer = document.querySelector("#stats-container");
const catContainer = document.querySelector(".cat-list");
const topList = document.querySelector(".top-list");
const userTable = document.querySelector("#table-user");
const token = document.querySelector(".token").dataset.token;

loadStats();

/**
 * Date format Y-M-D à D-M-Y
 * @param {string} date
 * @returns {string} Formatted date
 */
function formatDate(date) {
  const [year, month, day] = date.split("-");
  return `${day}-${month}-${year}`;
}

/**
 * Calcul l'âge à partir de la date de naissance
 * @param {string} birthdate
 * @returns {number} Age
 */
function getAge(birthdate) {
  const today = new Date();
  const birth = new Date(birthdate);
  let age = today.getFullYear() - birth.getFullYear();
  const monthDiff = today.getMonth() - birth.getMonth();
  const dayDiff = today.getDate() - birth.getDate();
  if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
    age--;
  }
  return age;
}

function loadStats() {
  fetch("../ajax/get_stats.php")
    .then((res) => res.json())
    .then((data) => {
      console.log(data);
      if (data.articles_pending.length > 0) {
        data.articles_pending.forEach((art) => {
          pendingContainer.innerHTML += `
          <div class="card admin-card p-1 pb-sm-3 pb-2 p-sm-2 g-1 d-flex flex-column justify-content-between fade-rotate">
            <img src="../assets/img_articles/${
              art.img_name
            }" class="card-img-top" alt="illustration spot">
            <div class="card-body">
                <h5 class="card-title">${escapeHtml(art.art_title)}</h5>
                <p class="card-text mb-1 categorie">${art.cat_name}</p>
                <a href="./read_user.php?id=${art.ide}" class="card-text">Par ${
            art.author
          } le ${formatDate(art.art_created_at)}</a>
            </div>
            <div class="buttons d-flex justify-content-around ">
                <a href="./read_article.php?id=${
                  art.art_id
                }" class="btn btn-sm btn-outline-primary">Inspecter</a>
                <a href="../controller/moderation.php?id=${
                  art.art_id
                }&element=article&action=rejected&token=${token}" class="btn btn-sm btn-danger">Refuser</a>
            </div>
          </div>`;
        });
      } else {
        pendingContainer.innerHTML = "<p>Tous les spots sont à jours</p>";
      }
      if (data.comments_pending.length > 0) {
        data.comments_pending.forEach((com) => {
          pendingComContainer.innerHTML += `
          <div class="card admin-card comment p-1 pb-sm-3 pb-2 p-sm-2 g-1 d-flex flex-column justify-content-between fade-rotate">
            <div class="card-body">
                <p class="card-title">Spot : <em>${escapeHtml(
                  com.art_title
                )}</em></p>
                <p class="card-text fs-5">${escapeHtml(com.com_content)}</p>
                <a href="./read_user.php?id=${
                  com.user_id
                }" class="card-text">Par ${escapeHtml(
            com.author
          )} le ${formatDate(com.com_posted_at)}</a>
            </div>
            <div class="buttons d-flex justify-content-around ">
                <a href="./read_article.php?id=${
                  com.art_id
                }" class="btn btn-sm btn-outline-primary">Inspecter</a>
                <a href="../controller/moderation.php?id=${
                  com.com_id
                }&element=comment&action=rejected&token=${token}" class="btn btn-sm btn-danger">Refuser</a>
            </div>
          </div>`;
        });
      } else {
        pendingComContainer.innerHTML =
          "<p>Tous les commentaires sont à jours</p>";
      }
      data.users.forEach((user) => {
        let lastname = user.user_lastname.toUpperCase();
        let age = getAge(user.user_birthdate);
        let bloked = user.user_is_blocked ? "blocked" : "";
        let status = user.user_is_blocked ? "unblocked" : "blocked";
        let interdit = user.user_is_blocked ? "⛔" : "🔵";
        userTable.innerHTML += `
        <tr class="${bloked}">
          <td><a href="./read_user.php?id=${user.user_id}" 
            class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
            ${escapeHtml(user.user_name)}
            </a>
          </td>
          <td class="d-none d-lg-table-cell">${escapeHtml(
            user.user_firstname
          )} ${escapeHtml(lastname)}</td>
          <td class="d-none d-lg-table-cell">${age} ans</td>
          <td class="d-none d-sm-table-cell">${escapeHtml(user.user_mail)}</td>
          <td class="d-none d-sm-table-cell">${formatDate(user.user_log)}</td>
          <td><a type="button" data-id="${
            user.user_id
          }" data-status="${status}" class="text-decoration-none toggle-block">
                ${interdit}
              </a></td>
        </tr>
        `;
      });
      data.articles_by_category.forEach((cat) => {
        catContainer.innerHTML += `
        <li class="list-group-item">${getEmojiCategory(cat.cat_name)} ${
          cat.cat_name
        } : ${cat.total_by_category}</li>
      `;
      });
      let topCommenter = data.top_commenter;
      let topArticleCom = data.most_commented_article;
      let topPoster = data.top_poster;
      topList.innerHTML += `
      <li class="list-group-item">🥇 Top poster : ${escapeHtml(
        topPoster.user_name
      )} (${escapeHtml(topPoster.article_count)})</li>
      <li class="list-group-item">⭐ Top commenter : ${escapeHtml(
        topCommenter.user_name
      )} (${escapeHtml(topCommenter.comment_count)})</li>
      <li class="list-group-item">🏆 Article le plus commenté : ${escapeHtml(
        topArticleCom.art_title
      )} (${escapeHtml(topArticleCom.comment_count)})</li>
      `;
      let userStats = data.users_global;
      statsContainer.innerHTML += `
      <div class="card card-stats">
        <div class="card-header">
          Utilisateurs
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">👥 Inscrits : ${escapeHtml(
            userStats.total_users
          )}</li>
          <li class="list-group-item">🔴 Bloqués : ${escapeHtml(
            userStats.total_blocked
          )}</li>
          <li class="list-group-item">🐥 Dernier inscrit : ${escapeHtml(
            userStats.newest_user
          )} (${escapeHtml(userStats.newest_user_date)})</li>
        </ul>
      </div>`;
      let artStats = data.articles_global;
      statsContainer.innerHTML += `
      <div class="card card-stats">
        <div class="card-header">
          Articles
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">✅ Approuvés : ${escapeHtml(
            artStats.total_approved
          )}</li>
          <li class="list-group-item">⚪ En attente : ${escapeHtml(
            artStats.total_pending
          )}</li>
          <li class="list-group-item">❌ Refusés : ${escapeHtml(
            artStats.total_rejected
          )}</li>
          <li class="list-group-item">🗓 Dernier article : ${escapeHtml(
            artStats.latest_article_date
          )}</li>
        </ul>
      </div>
    `;
      let comStats = data.comments_global;
      statsContainer.innerHTML += `
      <div class="card card-stats">
        <div class="card-header">
          Commentaires
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item">✅ Approuvés : ${escapeHtml(
            comStats.total_approved
          )}</li>
          <li class="list-group-item">⚪ En attente : ${escapeHtml(
            comStats.total_pending
          )}</li>
          <li class="list-group-item">❌ Refusés : ${escapeHtml(
            comStats.total_rejected
          )}</li>
          <li class="list-group-item">🗓 Dernier com : ${escapeHtml(
            comStats.latest_com_date
          )}</li>
        </ul>
      </div>
    `;
    });
}

setTimeout(() => {
  document.querySelectorAll(".toggle-block").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const userId = link.dataset.id;
      const status = link.dataset.status;
      fetch(
        `../ajax/user_bloker.php?id=${userId}&status=${status}&token=${token}`
      )
        .then((res) => res.json())
        .then((data) => {
          console.log(data);
          if (data.status === "Success") {
            const tr = link.closest("tr");
            if (status === "blocked") {
              link.dataset.status = "unblocked";
              link.textContent = "⛔";
              tr.classList.add("blocked");
            } else {
              link.dataset.status = "blocked";
              link.textContent = "🔵";
              tr.classList.remove("blocked");
            }
          } else {
            console.error("Erreur : " + data.message);
          }
        })
        .catch((err) => {
          console.error("Erreur AJAX :", err);
        });
    });
  });
}, 1000); // Délai de 1 seconde
