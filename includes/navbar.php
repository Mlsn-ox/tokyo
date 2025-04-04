<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
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
  <script defer src="../script/navbar.js"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <title>TokyoSpot</title>
</head>

<?php
  require_once "../includes/pdo.php";
  require_once "../includes/message.php";
  require_once "../includes/icon_category.php";
  try {
    $sqlNotif = "SELECT COUNT(*) FROM article WHERE art_status = 'pending';";
    $stmtNotif = $pdo->query($sqlNotif);
    $notif = $stmtNotif->fetch(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
  }
?>
<body>
<nav class="navbar navbar-expand-xl home">
    <div class="container">
      <a href="/TokyoSpot/view/homepage.php" class="navbar-brand"></a>
      <button class="btn bg-primary navbar-toggler rounded-pill px-4" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item">
            <a class="nav-link" href="/TokyoSpot/view/homepage.php">
              Accueil
            </a>
          </li>
          <li class="text-primary">
            <hr class="mx-auto my-0 menu-line">
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/TokyoSpot/view/index_articles.php">
              Voir les spots
            </a>
          </li>
          <li class="text-primary">
            <hr class="mx-auto my-0 menu-line">
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/TokyoSpot/view/map_all.php">
              Explorer la map
            </a>
          </li>
          <li class="text-primary">
            <hr class="mx-auto my-0 menu-line">
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/TokyoSpot/view/add_article_form.php">
              Ajouter un spot
            </a>
          </li>
          <li class="text-primary">
            <hr class="mx-auto my-0 menu-line">
          </li>
          <?php if (empty($_SESSION['id'])) { ?>
            <li class="nav-item">
              <a class="nav-link" href="/TokyoSpot/view/add_user_form.php">
                S'inscrire
              </a>
            </li>
            <li class="text-primary">
              <hr class="mx-auto my-0 menu-line">
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/TokyoSpot/view/login.php">
                Se connecter
              </a>
            </li>
          <?php } else { ?>
            <li class="nav-item">
              <a class="nav-link page-profil d-flex justify-content-center align-items-center" href="/TokyoSpot/view/read_user.php?id=<?= $_SESSION['id'] ?>">
                <img src="../assets/img_profil/<?= $_SESSION['img'] ?>" alt="Photo de profil" class="px-2">
                <span id="id-giver" data-id="<?= $_SESSION['id'] ?>">
                  Profil
                </span>
              </a>
            </li>
            <li class="text-primary">
              <hr class="mx-auto my-0 menu-line">
            </li>
            <?php if ($_SESSION['role'] === "admin") { ?>
                <li class="nav-item d-flex">
                  <a class="nav-link moderation" href="/TokyoSpot/view/admin.php">
                    Modération 
                  </a>
                  <?= $notif['COUNT(*)'] > 0 
                    ? "<span class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'>" . $notif['COUNT(*)'] . "</span>" 
                    : "<span class='badge rounded-pill bg-danger'>" . 10 . "</span>" ?>
                </li>
                <li class="text-primary">
                  <hr class="mx-auto my-0 menu-line">
                </li>
            <?php } ?>
            <li class="nav-item">
              <a class="nav-link" href="../controller/logout_controller.php">
                Déconnexion
              </a>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </nav>
  <?php 
  if (isset($_GET["message_code"]) && isset($_GET["status"])) {
    $message = getMessage($_GET["message_code"]);
    $status = $_GET["status"];
    echo "<div class='message-code'>
            <h3 class='text-center rounded-5 home p-3 $status'>$message</h3>
          </div>";
  } ?>