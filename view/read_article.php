<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT articles.*, users.user_id, users.user_name FROM articles LEFT JOIN users ON articles.user_ide = users.user_id WHERE articles.id = $id";
        $stmt = $pdo->query($sql); $article = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { 
        header("Location:../view/homepage.php?message_code=article_not_find&status=error"); 
    }
    if (isset($_GET["message_code"]) && isset($_GET["status"])) { 
        $message = getMessage($_GET["message_code"]);
    } 
    if (isset($_SESSION['error_message'])) {
        echo "<p style='color: red'>Erreur : " . htmlspecialchars($_SESSION['error_message']) . "</p>"; 
        unset($_SESSION['error_message']); // Supprime l'erreur après l'affichage 
    }
?>
<div class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Modal title</h5>
            <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
            ></button>
        </div>
        <div class="modal-body">
            <p><?= $message ?></p>
        </div>
        <div class="modal-footer text-center">
            <button type="button" class="btn btn-primary">Retour</button>
        </div>
        </div>
    </div>
</div>

<div class="section home col-xxl-7 col-md-10 col-12 mx-auto px-xl-5 py-4">
    <div class="container d-flex align-items-center">
        <div class="container-fluid fade-right">
        <div class="container-fluid title d-flex align-items-center read-title">
            <a href="../controller/favorite_controller.php" class="favorite">
            <img
                src="../assets/logo_category/heart_white.svg"
                class="heart"
                alt="Ajouter aux favoris"
            />
            </a>
            <h1 class="text-center">
            <?= htmlentities(ucfirst($article['title'])) ?>
            </h1>
        </div>
        <h4 class="categorie">
            <img src="../assets/logo_category/<?= $article['category'] ?>.svg"
            alt="Catégorie
            <?= htmlentities($article["category"]) ?>">
            <?= htmlentities($article['category']) ?>
        </h4>
        <p><?= htmlentities(ucfirst($article['content'])) ?></p>
        <p>
            Posté par
            <a
            href="read_user.php?id=<?= $article['user_id'] ?>"
            class="fst-italic"
            >
            <?= htmlentities($article['user_name']) ?>
            </a>
        </p>
        </div>
        <div class="container-fluid fade-left">
        <img
            src="../assets/img_articles/<?= $article['img'] ?>"
            class="rounded-4"
            alt="Photo de l'article"
            style="width: 100%"
        />
        </div>
    </div>
    <div class="container-fluid mx-auto my-3">
        <div
        id="map"
        data-lat="<?= $article['lat'] ?>"
        data-lng="<?= $article['lng'] ?>"
        class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom fade-up"
        ></div>
    </div>
</div>
<script type="module" src="../script/read_article.js"></script>
<?php require_once "../includes/footer.php" ?>
