<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
    crossorigin=""></script>
  <link rel="stylesheet" href="../css/style.css" />
  <title>Blog</title>
</head>

<?php

use PhpParser\Node\Stmt\TryCatch;

include "../controller/pdo.php";
try {
  $sql = "SELECT DISTINCT (article_category) AS category FROM articles ORDER BY article_category ASC;";
  $stmt = $pdo->query($sql);
} catch (PDOException $e) {
  echo "Erreur : " . $e->getMessage();
}
?>

<body>
  <nav class="home navbar navbar-expand-lg">
    <div class="container-fluid">
      <a href="./homepage.php"
        class="navbar-brand">
        <img src="../assets/ToriiLogo.svg"
          alt="Torii"
          width="64px">
        <span>
          TokyoSpot
        </span>
      </a>

      <button class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse"
        id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle"
              href="#"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false">
              Voir les articles
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item"
                  href="index_articles.php">
                  Tous les articles
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <?php
              while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
                <li>
                  <a class="dropdown-item"
                    href="index_articles.php?category[]=<?= $cat["category"] ?>">
                    <?= ucfirst($cat["category"]) ?>
                  </a>
                </li>
              <?php }; ?>
            </ul>
          </li>
          <li class="nav-item">
            <a href="./map_all.php"
              class="nav-link">
              Carte des spots
            </a>
          <li class="nav-item">
            <a href="./add_article_form.php"
              class="nav-link">
              Créer un article
            </a>
          </li>
          <?php if (!isset($_SESSION['name'])) { ?>
            <li class="nav-item">
              <a href="./add_user_form.php"
                class="nav-link">
                S'inscrire
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link"
                href="./login.php">
                Connexion
              </a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link page-profil"
                href="./read_user.php?id=<?= $_SESSION['id'] ?>">
                <img src="../assets/img_profil/<?= $_SESSION['img'] ?>" alt="Photo de profil">
                Mon profil
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link"
                href="../controller/logout_controller.php">
                Se déconnecter
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>