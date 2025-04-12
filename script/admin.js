const pendingContainer = document.querySelector("#pending")
const userTable = document.querySelector("#table-user")
const token = document.querySelector(".token").dataset.token;

loadStats();

function formatDate(date) {
  const [year, month, day] = date.split("-");
  return `${day}-${month}-${year}`;
}

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
    console.log(data.users);
    if (data.articles_pending.length > 0) {
      data.articles_pending.forEach(art => {
        pendingContainer.innerHTML += `
          <div class="card admin-card p-1 pb-sm-3 pb-2 p-sm-2 g-1 d-flex flex-column justify-content-between fade-rotate">
            <img src="../assets/img_articles/${art.img_name}" class="card-img-top" alt="illustration spot">
            <div class="card-body">
                <h5 class="card-title">${art.art_title}</h5>
                <p class="card-text mb-1 categorie">${art.cat_name}</p>
                <a href="./read_user.php?id=${art.ide}" class="card-text">Par ${art.author} le ${formatDate(art.art_created_at)}</a>
            </div>
            <div class="buttons d-flex justify-content-around ">
                <a href="./read_article.php?id=${art.art_id}" class="btn btn-outline-primary">Inspecter</a>
                <a href="../controller/moderation.php?id=${art.art_id}&action=rejected&token=${token}" class="btn btn-danger">Refuser</a>
            </div>
          </div>`;
      });
    } else {
      pendingContainer.innerHTML = "<p>Tous les spots sont à jours</p>"
    }
    data.users.forEach(user => {
      let lastname = user.user_lastname.toUpperCase()
      let age = getAge(user.user_birthdate)
      let bloked = user.user_is_bloked ? "table-danger" : "";
      let interdit = user.user_is_bloked ? "⛔" : "";
      userTable.innerHTML += `
        <tr class="${bloked}">
          <td><a href="./read_user.php?id=${user.user_id}" class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
            ${interdit} ${user.user_name}</a></td>
          <td class="d-none d-md-table-cell">${user.user_firstname} ${lastname}</td>
          <td class="d-none d-md-table-cell">${age} ans</td>
          <td>${user.user_mail}</td>
          <td class="d-none d-sm-table-cell">${formatDate(user.user_log)}</td>
        </tr>
        `;
    })
  });
}
