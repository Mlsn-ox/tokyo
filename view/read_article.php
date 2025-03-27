<?php
    require_once "../includes/navbar.php";
    try {
        if (!isset($_GET["id"])) {
            throw new Exception("article_not_find"); 
        }
        $id = filter_var($_GET["id"], FILTER_VALIDATE_INT);
        if (!$id) {
            throw new Exception("param_not_found");
        }
        $sql = "SELECT article.*, user.user_id AS ide, user.user_name AS author, category.cat_name AS cat, image.img_name AS img
                FROM article 
                LEFT JOIN user ON article.art_fk_user_id = user.user_id 
                LEFT JOIN category ON article.art_fk_cat_id = category.cat_id
                LEFT JOIN image ON article.art_id = image.img_fk_art_id 
                WHERE article.art_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article){
            throw new Exception("article_not_find");
        }
        if ($article['art_status'] !== 'approved' && $_SESSION['role'] !== "admin"){
            throw new Exception("article_not_find");
        }
        if(!$article['author']){
            $author = "";
        } else {
            $author = htmlentities($article['author']);
        }
        $sql = "SELECT * FROM favorite WHERE fav_art_id=? AND fav_user_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $article['art_id'],
            $_SESSION['id']
        ]);
        $fav = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    } 
    // echo "<pre>";
    // print_r($article);
    // echo "</pre>";
?>
<!-- Modale pour afficher l'image en taille originale -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent shadow-none border-0">
            <div class="modal-body text-center">
                <img id="modalImage" src="../assets/img_articles/<?= $article['img'] ?>" class="img-fluid" alt="Image en taille réelle">
            </div>
        </div>
    </div>
</div>
<div class="section home col-xxl-8 col-md-10 col-12 mx-auto px-xl-4 py-4">
    <div class="container-fluid fade-up">
        <div class="container-fluid title read-title d-flex flex-column flex-md-row justify-content-center align-items-center pe-3 my-2">
            <h1 class="text-center">
                <?= htmlspecialchars_decode($article['art_title']) ?>
            </h1>
            <?php if($_SESSION){ ?> 
                <a href="../controller/favorite_controller.php" class="favorite mb-2" data-post="<?= $article["art_id"] ?>" 
                data-user="<?= $_SESSION["id"] ?>" data-token="<?= $_SESSION["token"] ?>">
                <?= $fav ? "💙" : "🤍" ?>
            </a>
            <?php } ?>
        </div>
        <div class="container-fluid">
            <h4 class="categorie">
                <?= getEmojiCategory($article['cat']) . " " . ucfirst(htmlspecialchars_decode($article['cat'])) ?>
            </h4>
        </div>
    </div>
    <div class="container-fluid d-md-flex justify-content-between pt-3">
        <div class="container-fluid col-12 col-md-6 fade-right pt-md-4 d-flex flex-column">
            <p><?= htmlspecialchars_decode($article['art_content']) ?></p>
            <p>
                Posté le <?= date("d/m/Y", strtotime($article['art_created_at'])) ?><?= $author ? ", par " : "" ?>
                <a href="read_user.php?id=<?= $article['ide'] ?>" class="fst-italic">
                    <?= $author ?>
                </a>
            </p>
            <?php 
            if (!empty($_SESSION) && $_SESSION['role'] === "admin") { ?>
                <div class="moderation my-2 d-flex justify-content-evenly align-items-center flex-wrap">
                <?php if ($article["art_status"] === "pending") { ?>
                    <a href="../controller/moderation.php?id=<?= $article['art_id'] ?>&action=approved&token=<?= $_SESSION['token'] ?>" class="btn btn-lg btn-success">Valider</a>
                    <?php } else { ?> 
                    <a href="../controller/moderation.php?id=<?= $article['art_id'] ?>&action=pending&token=<?= $_SESSION['token'] ?>" class="btn btn-lg btn-warning">Suspendre</a>
                    <?php } ?> 
                    <a href="../controller/moderation.php?id=<?= $article['art_id'] ?>&action=rejected&token=<?= $_SESSION['token'] ?>" class="btn btn-lg btn-danger">Refuser</a>
                </div>
            <?php } ?> 
        </div>
        <div class="container-fluid col-12 col-md-6 fade-left img-clickable-container py-1">
            <img src="../assets/img_articles/<?= $article['img'] ?>" alt="Photo de l'article"
                class="rounded-4 img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" />
        </div>
    </div>
    <div class="container-fluid mx-auto my-4 mb-2">
        <div id="map" data-lat="<?= $article['art_lat'] ?>" data-lng="<?= $article['art_lng'] ?>"
            class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom fade-up">
        </div>
    </div>
    <div class="container-fluid pt-4">
        <h2 class="pb-2">Commentaires</h2>
        <ul class="list-group list-group-flush rounded-4">
            <?php
            $sql = "SELECT comment.com_content, comment.com_posted_at, user.user_name AS commenter, user.user_id AS commenter_id 
                    FROM comment
                    LEFT JOIN user ON comment.com_fk_user_id = user.user_id 
                    WHERE comment.com_fk_art_id = :id AND comment.com_status = 'approved'";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($comments){
                foreach ($comments as $comment) { 
                    if (!$comment['commenter']){
                        $comment['commenter'] = "Un inconnu";
                        $comment['commenter_id'] = "";
                    } ?>
                    <li class="list-group-item py-3 d-flex flex-column">
                        <span><?= htmlspecialchars_decode($comment['com_content']) ?></span>
                        <span class="fst-italic">
                            <a href="./read_user.php?id=<?= $comment['commenter_id'] ?>"><?= $comment['commenter'] ?></a>,
                            le <?= $comment['com_posted_at'] ?></span>
                    </li>
                <?php }
            } else { ?>
                <p class="text-center">Personne n'a encore commenté ce spot.</p>
            <?php } ?>
        </ul>
        <?php if($_SESSION){ ?>
            <form method="POST" action="../controller/add_comment_controller.php" aria-label="Formulaire d'ajout d'un commentaire">
                <input type="hidden" name="user_comment" value="<?= $_SESSION['id'] ?>">
                <input type="hidden" name="art_id" value="<?= $article['art_id'] ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <div class="my-3">
                    <textarea id="comment_content" class="form-control" name="comment_content" aria-label="Contenu du commentaire" 
                    aria-describedby="contentHelp" placeholder="Ajouter un commentaire" maxlength="300" rows="3" required></textarea>
                    <div id="contentHelp" class="form-text">300 caractères maximum.</div>
                    <p class="mt-2 fst-italic">
                    💬 Merci de vous relire avant d’envoyer votre commentaire. Les messages haineux ou irrespectueux ne seront pas publiés. 
                        Les commentaires pleins de respect et de bonne humeur sont les bienvenus ! 
                        Tous les messages sont relus avant publication pour que cet espace reste convivial pour tous.
                    </p>
                </div>
                <div class="container mx-auto mt-4 mb-2 d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center">
                    <button type="submit" class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-4 col-md-4 submit" 
                        aria-label="Envoyer le commentaire">Envoyer</button>
                    <a href="./index_articles.php" class="btn btn-outline-danger rounded-pill mb-3 col-11 col-sm-4 col-md-4" 
                        aria-label="Retourner à la page précédente">Retour</a>
                </div>
            </form>
        <?php } else { ?>
            <div class="text-center mt-3">
                <p>Connectez-vous pour pouvoir commenter !</p>
                <div class="container mx-auto mt-4 mb-2 d-flex flex-wrap justify-content-center align-items-center">
                    <a href="./login.php" class="btn btn-outline-primary rounded-pill mb-3 col-11 col-sm-4 col-md-5 col-xl-4" 
                    aria-label="Retourner à la page d'accueil">Se connecter</a>
                    <a href="./index_articles.php" class="btn btn-outline-danger rounded-pill mb-3 col-11 col-sm-4 col-md-4" 
                    aria-label="Retourner à la page précédente">Retour</a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<script type="module" src="../script/read_article.js"></script>
<?php require_once "../includes/footer.php" ?>
