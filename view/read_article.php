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

<!-- 
<div class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    < balise echo $message ?>
                </p>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-primary">Retour</button>
            </div>
        </div>
    </div>
</div> -->

<div class="section home col-xxl-8 col-md-10 col-12 mx-auto px-xl-4 py-4">
    <div class="container-fluid fade-up">
        <div class="container-fluid title read-title d-flex align-items-center my-2">
            <a href="../controller/favorite_controller.php" class="favorite">
                <img src="../assets/logo_category/heart_white.svg" class="heart" alt="Ajouter aux favoris" />
            </a>
            <h1 class="text-center">
                <?= htmlentities(ucfirst($article['title'])) ?>
            </h1>
        </div>
        <div class="container-fluid">
            <h4 class="categorie">
                <img src="../assets/logo_category/<?= $article['category'] ?>.svg" alt="Catégorie
                <?= htmlentities($article["category"]) ?>">
                <?= htmlentities($article['category']) ?>
            </h4>
        </div>
    </div>
    <div class="container-fluid d-md-flex justify-content-between pt-3">
        <div class="container-fluid col-12 col-md-6 fade-right pt-md-4">
            <p><?= htmlentities(ucfirst($article['content'])) ?></p>
            <p>
                Posté par
                <a href="read_user.php?id=<?= $article['id'] ?>" class="fst-italic">
                    <?= htmlentities($article['name']) ?>
                </a>
            </p>
        </div>
        <div class="container-fluid col-12 col-md-6 fade-left img-clickable-container py-1">
            <img src="../assets/img_articles/<?= $article['img'] ?>" alt="Photo de l'article"
                class="rounded-4 img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" />
        </div>
    </div>
    <div class="container-fluid mx-auto mt-4 mb-2">
        <div id="map" data-lat="<?= $article['lat'] ?>" data-lng="<?= $article['lng'] ?>"
            class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom fade-up">
        </div>
    </div>
</div>
<!-- Modale pour afficher l'image en taille originale -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" alt="Image en taille réelle">
            </div>
        </div>
    </div>
</div>

<script type="module" src="../script/read_article.js"></script>
<?php require_once "../includes/footer.php" ?>
