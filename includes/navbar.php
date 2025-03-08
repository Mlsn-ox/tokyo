<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="../assets/logo_category/Torii-sans.ico">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <link rel="stylesheet" href="../css/style.css" />
  <title>TokyoSpot</title>
</head>

<?php
  include "../includes/pdo.php";
  try {
    $sql = "SELECT DISTINCT category FROM articles ORDER BY category ASC;";
    $stmt = $pdo->query($sql);
    $sqlNotif = "SELECT COUNT(*) FROM articles WHERE articles.status = 'pending';";
    $stmtNotif = $pdo->query($sqlNotif);
    $notif = $stmtNotif->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
?>

<body>
  <nav class="home navbar navbar-expand-lg">
    <div class="container-fluid">
      <a href="../view/homepage.php" class="navbar-brand">
        <img src="../assets/logo_category/Torii.png" alt="Torii">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Les spots
            </a>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="../view/index_articles.php">
                  Voir tout
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <?php while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                <li>
                  <a href="../view/index_articles.php?category[]=<?= $cat["category"] ?>" class="dropdown-item">
                    <?= ucfirst($cat["category"]) ?>
                  </a>
                </li>
              <?php }; ?>
            </ul>
          </li>
          <li class="nav-item">
            <a href="../view/map_all.php" class="nav-link">
              Plan de Tokyo
            </a>
          <li class="nav-item">
            <a 
              <?php if (!isset($_SESSION['name'])) { ?>
                href="../view/login.php?message_code=connect_error&status=error"
              <?php } else { ?> 
                href="../view/add_article_form.php"
              <?php } ?>
              class="nav-link">
              Ajouter un spot
            </a>
          </li>
          <?php if (!isset($_SESSION['name'])) { ?>
            <li class="nav-item">
              <a href="../view/add_user_form.php" class="nav-link">
                S'inscrire
              </a>
            </li>
            <li class="nav-item">
              <a href="../view/login.php" class="nav-link">
                Connexion
              </a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a href="../view/read_user.php?id=<?= $_SESSION['id'] ?>" class="nav-link page-profil">
                <img src="../assets/img_profil/<?= $_SESSION['img'] ?>" alt="Photo de profil">
                Mon profil
              </a>
            </li>
            <?php if ($_SESSION['role']) { ?>
              <li class="nav-item">
                <a href="../view/admin.php?id=<?= $_SESSION['id'] ?>" class="nav-link page-profil">
                  Modération
                  <?= $notif['COUNT(*)'] > 0 ? "<span class='badge text-bg-danger rounded'>" . $notif['COUNT(*)'] . "</span>" : "" ?>
                </a>
              </li>           
            <?php } ?> 
            <li class="nav-item">
              <a href="../controller/logout_controller.php" class="nav-link">
                Se déconnecter
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>