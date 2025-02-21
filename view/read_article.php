<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";

    try {
        if (!isset($_GET["id"])) {
            throw new Exception("article_not_find"); 
        }
        $id = $_GET["id"];
        $sql = "SELECT articles.*, users.id, users.name FROM articles LEFT JOIN users ON articles.user_ide = users.id WHERE articles.id = $id";
        $stmt = $pdo->query($sql); 
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article){
            throw new Exception("article_not_find");
        }
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    } 

    if (isset($_GET["message_code"]) && isset($_GET["status"])) { 
        $message = getMessage($_GET["message_code"]);
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
            href="read_user.php?id=<?= $article['id'] ?>"
            class="fst-italic"
            >
            <?= htmlentities($article['name']) ?>
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
