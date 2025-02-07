<?php
include "navbar.php";
include "../controller/pdo.php";
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "SELECT articles.*, users.user_id, users.user_name
            FROM articles
            LEFT JOIN users ON articles.article_author = users.user_id
            WHERE articles.article_id = $id";
    $stmt = $pdo->query($sql);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    switch ($article["article_category"]) {
        case "gastronomie":
            $logo = "gastronomie.svg";
            break;
        case "loisir":
            $logo = "loisir.svg";
            break;
        case "panorama":
            $logo = "panorama.svg";
            break;
        case "shopping":
            $logo = "shopping.svg";
            break;
    }
}
?>
<div class="section bg-white col-xxl-7 col-md-10 col-12 mx-auto px-xl-5 py-4">
    <div class="container d-flex align-items-center ">
        <div class="container-fluid fade-right">
            <h1 class="text-center my-4">
                <?= htmlentities(ucfirst($article['article_title'])) ?>
            </h1>
            <h4 class="categorie">
                <img src="../assets/logo_category/<?= $logo ?>"
                    alt="Catégorie <?= htmlentities($article["article_category"]) ?>"
                    style="height: 16px;">
                <?= htmlentities($article['article_category']) ?>
            </h4>
            <p>
                <?= htmlentities(ucfirst($article['article_content'])) ?>
            </p>
            <p>Posté par <a href="read_user.php?id=<?= $article['user_id'] ?>" class="fst-italic"> <?= htmlentities($article['user_name']) ?></a></p>
        </div>
        <div class="container-fluid fade-left">
            <img src="../assets/img_articles/<?= $article['article_img'] ?>"
                class="rounded-4"
                alt="Photo de l'article"
                style="width: 100%;">
        </div>
    </div>
    <div class="container-fluid bg-white mx-auto py-3">
        <div id="map"
            data-lat="<?= $article['article_lat'] ?>"
            data-lng="<?= $article['article_lng'] ?>"
            class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom fade-up">
        </div>
    </div>
</div>
<script type="module" src="../script/read_article.js"></script>

<?php
include "footer.php";
?>