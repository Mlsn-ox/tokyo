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
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script defer src="../script/navbar.js"></script>
  <link rel="stylesheet" href="../css/style.css" />
  <title>TokyoSpot</title>
</head>

<?php
  require_once "../includes/pdo.php";
  require_once "../includes/message.php";
  require_once "../includes/functions.php";
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
<body>
<nav class="navbar navbar-expand-lg home">
    <div class="container-xl">
      <a href="/TokyoSpot/view/homepage.php" class="navbar-brand"></a>
      <button class="btn bg-primary navbar-toggler rounded-pill px-4" type="button" data-bs-toggle="collapse" 
        data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
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
          </ul>
          <ul class="navbar-nav ms-auto">
          <?php if (empty($_SESSION['id'])) { ?>
            <li class="nav-item text-center me-lg-3">
              <a class="btn btn-outline-success" role="button" href="/TokyoSpot/view/add_user_form.php">
                S'inscrire
              </a>
            </li>
            <li class="text-primary">
              <hr class="mx-auto my-1 menu-line">
            </li>
            <li class="nav-item text-center">
              <a class="btn btn-outline-primary" role="button" href="/TokyoSpot/view/login.php">
                Se connecter
              </a>
            </li>
          <?php } else {
            if ($_SESSION['role'] === "admin") { ?>
                <li class="nav-item d-flex position-relative admin pe-4">
                  <a class="nav-link moderation btn-admin" href="/TokyoSpot/view/admin.php">
                    Modération 
                  </a>
                  <?php if ($notif['COUNT(*)'] > 0){
                    echo "<span class='badge rounded bg-danger'>" . $notif['COUNT(*)'] . "</span>";
                  } else if ($notifCom['COUNT(*)'] > 0){
                    echo "<span class='badge rounded bg-danger'>" . $notifCom['COUNT(*)'] . "</span>";
                  } ?>
                </li>
                <li class="text-primary">
                  <hr class="mx-auto my-0 menu-line">
                </li>
            <?php } ?>
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
            <li class="nav-item text-center ms-lg-2">
              <a class="btn btn-outline-danger" role="button" href="../controller/logout_controller.php">
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