<?php
    use PhpParser\Node\Stmt\TryCatch;
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    require_once "../includes/filters.php";
    try {
        $limitArt = 6;
        $sql = "SELECT id, title, category, content, img FROM articles $where $orderBy LIMIT $limitArt";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
?>
<div class="section col-xl-10 col-xxl-9 col-12 p-xl-3 p-md-2 p-1 mx-auto home">
    <h4 class="p-2 text-center">Filtrer les articles</h4>
    <div class="container col-8 mb-5">
        <form method="GET" class="d-flex flex-column align-items-center" id="filter">
            <div class="d-flex justify-content-around my-3">
                <?php try {
                    $sql = "SELECT DISTINCT category FROM articles ORDER BY category ASC;";
                    $stmt = $pdo->query($sql);
                    while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                        <div class="form-check form-switch me-4">
                            <input class="form-check-input" type="checkbox" role="switch" name="category[]" id="<?= $cat["category"] ?>" 
                                value="<?= $cat["category"] ?>" <?= in_array($cat["category"], $categories) ? "checked" : "" ?>>
                            <label class="form-check-label" for="<?= $cat["category"] ?>">
                                <?= ucfirst($cat["category"]) ?>
                            </label>
                        </div>
                    <?php }
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                } ?>  
            </div>
            <select class="form-select mb-3" name="order">
                <option value="desc" <?= $order === "desc" ? "selected" : "" ?>>Du plus récent au plus ancien</option>
                <option value="asc" <?= $order === "asc" ? "selected" : "" ?>>Du plus ancien au plus récent</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
    </div>
    <div class="container mx-auto row justify-content-around flex-wrap" id="articles">
        <?php if ($articles) {
            foreach ($articles as $article) { ?>
                <a href="read_article.php?id=<?= $article['id'] ?>" class="article g-md-2 m-2 mb-3" 
                    style="background-image: url('../assets/img_articles/<?= $article["img"] ?>');">
                    <div class="article-content text-dark">
                        <h2><?= htmlentities(ucfirst($article["title"])) ?></h2>
                        <div class="content">
                            <p class="m-0 categorie">
                                <img src="../assets/logo_category/<?= $article["category"] ?>.svg" alt="Catégorie <?= htmlentities($article["category"]) ?>" style="height: 16px;">
                                <?= htmlentities($article["category"]) ?>
                            </p>
                            <p><?= htmlentities(ucfirst($article["content"])) ?></p>
                        </div>
                    </div>
                </a>
            <?php }
        } else {
            echo "fin";
        }; ?>
    </div>
    <div class="container mx-auto text-center my-3" id="loader"></div>
</div>
<script src="../script/index_articles.js"></script>
<?php require_once "../includes/footer.php" ?>