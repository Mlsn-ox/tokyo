<?php
if (isset($_GET["message_code"]) && isset($_GET["status"])) {
  $message = getMessage($_GET["message_code"]);
  $status = $_GET["status"];
  echo "<div class='message-code'>
            <h3 class='text-center rounded-5 home p-3 $status'>$message</h3>
          </div>";
}
try {
  $sql = "SELECT COUNT(*) FROM article WHERE art_status = 'pending';";
  $stmt = $pdo->query($sql);
  $notif = $stmt->fetch(PDO::FETCH_ASSOC);
  $sql = "SELECT COUNT(*) FROM comment WHERE com_status = 'pending';";
  $stmt = $pdo->query($sql);
  $notifCom = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erreur : " . $e->getMessage();
}
?>
<nav class="navbar navbar-expand-lg home">
  <div class="container-xl">
    <a href="<?= $config['url'] ?>/view/homepage.php" class="navbar-brand"></a>
    <button class="btn bg-primary navbar-toggler rounded-pill px-4" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= $menu === "accueil" ? "active-page" : '' ?>"
            href="<?= $config['url'] ?>/view/homepage.php">
            Accueil
          </a>
        </li>
        <li class="text-primary">
          <hr class="mx-auto my-0 menu-line">
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $menu === "index" ? "active-page" : '' ?>"
            href="<?= $config['url'] ?>/view/index_articles.php">
            Voir les spots
          </a>
        </li>
        <li class="text-primary">
          <hr class="mx-auto my-0 menu-line">
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $menu === "map_all" ? "active-page" : '' ?>"
            href="<?= $config['url'] ?>/view/map_all.php">
            Explorer la map
          </a>
        </li>
        <li class="text-primary">
          <hr class="mx-auto my-0 menu-line">
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $menu === "add_article" ? "active-page" : '' ?>"
            href="<?= $config['url'] ?>/view/add_article.php">
            Ajouter un spot
          </a>
        </li>
        <li class="text-primary">
        </li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (!isConnected()) { ?>
          <li class="nav-item text-center me-lg-3 my-1 my-lg-0">
            <a class="btn btn-outline-success <?= $menu === "add_user" ? "active-btn" : '' ?>" role="button"
              href="<?= $config['url'] ?>/view/add_user.php">
              S'inscrire
            </a>
          </li>
          <li class="text-primary">
          </li>
          <li class="nav-item text-center my-1 my-lg-0">
            <a class="btn btn-outline-primary <?= $menu === "login" ? "active-btn" : '' ?>" role="button"
              href="<?= $config['url'] ?>/view/login.php">
              Se connecter
            </a>
          </li>
          <?php } else {
          if (isAdmin()) { ?>
            <li class="text-primary">
              <hr class="mx-auto my-0 menu-line">
            </li>
            <li class="nav-item d-flex position-relative admin pe-4">
              <a class="nav-link moderation btn-admin <?= $menu === "admin" ? "active-page" : '' ?>"
                href="<?= $config['url'] ?>/view/admin.php">
                Modération
              </a>
              <?php if ($notif['COUNT(*)'] > 0) {
                echo "<span class='badge rounded bg-danger'>" . $notif['COUNT(*)'] . "</span>";
              } else if ($notifCom['COUNT(*)'] > 0) {
                echo "<span class='badge rounded bg-danger'>" . $notifCom['COUNT(*)'] . "</span>";
              } ?>
            </li>
            <li class="text-primary">
              <hr class="mx-auto my-0 menu-line">
            </li>
          <?php } ?>
          <li class="text-primary">
            <hr class="mx-auto my-0 menu-line">
          </li>
          <li class="nav-item">
            <a class="nav-link page-profil d-flex justify-content-center align-items-center <?= $menu === "profil" ? "active-page" : '' ?>"
              href="<?= $config['url'] ?>/view/read_user.php?id=<?= $_SESSION['id'] ?>">
              <img src="../assets/img_profil/<?= $_SESSION['img'] ?>" alt="Photo de profil" class="px-2">
              <span id="id-giver" data-id="<?= $_SESSION['id'] ?>">
                Profil
              </span>
            </a>
          </li>
          <li class="text-primary">
          </li>
          <li class="nav-item text-center ms-lg-2">
            <a class="btn btn-outline-danger my-1 my-lg-0" role="button" href="<?= $config['url'] ?>/controller/logout_controller.php">
              Déconnexion
            </a>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>