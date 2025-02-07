<?php
include "navbar.php";
include "../controller/pdo.php";

if (isset($_GET["category"])) {
    $category = $_GET["category"];
    $sql = "SELECT * FROM articles
    WHERE article_category='$category' 
    ORDER BY article_id DESC";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $category = false;
    $sql = "SELECT * FROM articles
    ORDER BY articles.article_id DESC";
    $stmt = $pdo->query($sql);
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<div class="section bg-white col-xl-10 col-xxl-9 col-12 p-xl-3 p-md-2 p-1 mx-auto">
    <div class="container mx-auto row justify-content-around flex-wrap">
        <div class="container d-flex justify-content-around my-5">
            <a href="index_articles.php<?= $category === "gastronomie" ? "" : "?category=gastronomie" ?>"
                type="button"
                class="btn btn-lg <?= $category === "gastronomie" ? "btn-active" : "btn-primary" ?>">
                Gastronomie
            </a>
            <a href="index_articles.php<?= $category === "panorama" ? "" : "?category=panorama" ?>"
                type="button"
                class="btn btn-lg <?= $category === "panorama" ? "btn-active" : "btn-primary" ?>">
                Panorama
            </a>
            <a href="index_articles.php<?= $category === "loisir" ? "" : "?category=loisir" ?>"
                type="button"
                class="btn btn-lg <?= $category === "loisir" ? "btn-active" : "btn-primary" ?>">
                Loisir
            </a>
            <a href="index_articles.php<?= $category === "shopping" ? "" : "?category=shopping" ?>"
                type="button"
                class="btn btn-lg <?= $category === "shopping" ? "btn-active" : "btn-primary" ?>">
                Shopping
            </a>
        </div>
        <?php
        ob_start();
        foreach ($articles as $article) {
            //     switch ($article["article_category"]) {
            //         case "gastronomie":
            //             $logo = "gastronomie.svg";
            //             break;
            //         case "loisir":
            //             $logo = "loisir.svg";
            //             break;
            //         case "panorama":
            //             $logo = "panorama.svg";
            //             break;
            //         case "shopping":
            //             $logo = "shopping.svg";
            //             break;
            //     }
        ?>
            <a href="read_article.php?id=<?= $article['article_id'] ?>"
                class="article g-md-2 m-2 mb-3"
                style="background-image: url('../assets/img_articles/<?= $article["article_img"] ?>');">
                <div class="article-content text-dark">
                    <h2><?= htmlentities(ucfirst($article["article_title"])) ?></h2>
                    <div class="content">
                        <p class="m-0 categorie">
                            <img src="../assets/logo_category/<?= $category ?>.svg"
                                alt="Catégorie <?= htmlentities($article["article_category"]) ?>"
                                style="height: 16px;">
                            <?= htmlentities($article["article_category"]) ?>
                        </p>
                        <p>
                            <?= htmlentities(ucfirst($article["article_content"])) ?>
                        </p>
                    </div>
                </div>
            </a>
        <?php
        }
        ob_end_flush();
        ?>
    </div>
</div>
<?php
include "footer.php";
?>