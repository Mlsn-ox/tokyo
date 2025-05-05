<?php
require_once "../config.php";
$menu = "read_article";
try {
    if (!isset($_GET["id"])) {
        throw new Exception("article_not_found");
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
    if (!$article) {
        throw new Exception("article_not_found");
    }
    if ($article['art_status'] !== 'approved' && !isAdmin()) {
        throw new Exception("article_not_found");
    }
    if (!$article['author']) {
        $author = "";
    } else {
        $author = htmlentities($article['author']);
    }
    $fav = "";
    if (!empty($_SESSION['id'])) {
        $sql = "SELECT * FROM favorite WHERE fav_art_id=? AND fav_user_id=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $article['art_id'],
            $_SESSION['id']
        ]);
        $fav = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <?php require_once "../includes/head.php"; ?>
    <title><?= $article['art_title'] ?> - TokyoSpot</title>
    <meta name="description" content="<?= $article['art_title'] ?> - TokyoSpot">
</head>

<body>
    <?php require_once "../includes/navbar.php"; ?>

    <!-- Modale pour afficher l'image en taille originale -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent shadow-none border-0">
                <div class="modal-body text-center">
                    <img id="modalImage" src="<?= $config['url'] ?>/assets/img_articles/<?= $article['img'] ?>"
                        class="img-fluid" alt="<?= $config['alt_img'] ?>" <?= $config['alt_img'] ?>">
                </div>
            </div>
        </div>
    </div>

    <div class="section home container-lg mx-auto px-xl-4 py-4">
        <div class="container-fluid fade-up">
            <div class="container-fluid pe-0 title read-title d-flex flex-md-row justify-content-center align-items-center my-2">
                <h1 class="text-center">
                    <?= htmlentities($article['art_title']) ?>
                </h1>
                <a class="btn bg-light-subtle d-flex align-items-center justify-content-center justify-content-lg-between ms-3" id="favorite"
                    <?php if (isConnected()) { ?>
                    data-post="<?= $article["art_id"] ?>" data-user="<?= $_SESSION["id"] ?>" data-token="<?= $_SESSION["token"] ?>"
                    <?php } else { ?>
                    href="<?= $config['url'] ?>/view/login.php?message_code=connect_error&status=success"
                    <?php } ?>>
                    <?= $fav
                        ? "<span class='heart'>❤️</span><span class='d-none d-lg-inline'>Retirer des favoris</span>"
                        : "<span class='heart'>🤍</span><span class='d-none d-lg-inline'>Ajouter aux favoris</span>"
                    ?>
                </a>
                <p class="m-0 fav-text"></p>
            </div>
            <div class="container-fluid">
                <h4 class="categorie">
                    <?= getEmojiCategory($article['cat']) . " " . ucfirst(htmlentities($article['cat'])) ?>
                </h4>
            </div>
        </div>
        <div class="container-fluid d-md-flex justify-content-between pt-3">
            <div class="container-fluid col-12 col-md-6 fade-right pt-md-4 d-flex flex-column">
                <article class="mb-2"><?= htmlspecialchars_decode($article['art_content']) ?></article>
                <p>
                    Posté le <?= date("d/m/Y", strtotime($article['art_created_at'])) ?><?= $author ? ", par " : "" ?>
                    <a href="<?= $config['url'] ?>/view/read_user.php?id=<?= $article['ide'] ?>" class="fst-italic">
                        <?= $author ?>
                    </a>
                </p>
            </div>
            <div class="container-fluid col-12 col-md-6 fade-left img-clickable-container p-2">
                <img src="<?= $config['url'] ?>/assets/img_articles/<?= $article['img'] ?>" alt="<?= $config['alt_img'] ?>"
                    class="rounded-4 img-clickable" data-bs-toggle="modal" data-bs-target="#imageModal" />
            </div>
        </div>
        <div class="container-fluid mx-auto my-4 mb-2">
            <div id="map" data-lat="<?= $article['art_lat'] ?>" data-lng="<?= $article['art_lng'] ?>"
                class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom fade-up">
            </div>
        </div>
        <?php if (isConnected() && isAdmin()) { ?>
            // Si l'utilisateur est admin, on affiche les boutons de modération
            <div class="container moderation mt-4 d-flex flex-column flex-sm-row justify-content-center align-items-center flex-wrap gap-2 gap-md-5">
                <?php if ($article["art_status"] === "pending") { ?>
                    <a href="<?= $config['url'] ?>/controller/moderation.php?id=<?= $article['art_id'] ?>&action=approved&element=article&token=<?= $_SESSION['token'] ?>"
                        class="btn btn-lg btn-success">Valider</a>
                <?php } else { ?>
                    <a href="<?= $config['url'] ?>/controller/moderation.php?id=<?= $article['art_id'] ?>&action=pending&element=article&token=<?= $_SESSION['token'] ?>"
                        class="btn btn-lg btn-warning">Suspendre</a>
                <?php } ?>
                <a href="<?= $config['url'] ?>/view/update_article.php?id=<?= $article['art_id'] ?>&role=<?= $_SESSION['role'] ?>" class="btn btn-lg btn-primary">Modifier</a>
                <a href="<?= $config['url'] ?>/controller/moderation.php?id=<?= $article['art_id'] ?>&action=rejected&element=article&token=<?= $_SESSION['token'] ?>"
                    class="btn btn-lg btn-danger">Refuser</a>
            </div>
        <?php } ?>
        <div class="container-fluid pt-4">
            <h2 class="pb-2">Commentaires</h2>
            <ul class="list-group list-group-flush rounded-4">
                <?php
                if (isConnected() && isAdmin()) {
                    // Si l'utilisateur est admin, on affiche tous les commentaires
                    $sql = "SELECT comment.com_id, comment.com_content, comment.com_status, comment.com_posted_at, 
                    user.user_name AS commenter, user.user_id AS commenter_id FROM comment
                    LEFT JOIN user ON comment.com_fk_user_id = user.user_id 
                    WHERE comment.com_fk_art_id = :id";
                } else {
                    // Sinon, on affiche seulement les commentaires approuvés
                    $sql = "SELECT comment.com_id, comment.com_content, comment.com_posted_at, user.user_name AS commenter, user.user_id AS commenter_id 
                    FROM comment
                    LEFT JOIN user ON comment.com_fk_user_id = user.user_id 
                    WHERE comment.com_fk_art_id = :id AND comment.com_status = 'approved'";
                }
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $id]);
                $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $href = "";
                if ($comments) {
                    foreach ($comments as $comment) {
                        $btn = $class = "";
                        if (isset($comment['com_status']) && isConnected() && isAdmin()) {
                            if ($comment["com_status"] === "pending") {
                                $class = "bg-warning-subtle";
                                $btn = '<a href="../controller/moderation.php?id=' . $comment['com_id'] .
                                    '&action=approved&element=comment&token=' . $_SESSION['token'] .
                                    '" class="btn btn-sm btn-outline-success me-2">Valider</a>' .
                                    '<a href="../controller/delete_controller.php?id=' . $comment['com_id'] .
                                    '&element=comment&element=comment&token=' . $_SESSION['token'] .
                                    '" class="btn btn-sm btn-outline-danger">Supprimer</a>';
                            } else if ($comment["com_status"] === "approved") {
                                $btn = '<a href="../controller/moderation.php?id=' . $comment['com_id'] .
                                    '&action=pending&element=comment&token=' . $_SESSION['token'] .
                                    '" class="btn btn-sm btn-outline-warning">Suspendre</a>';
                            } else if ($comment["com_status"] === "rejected") {
                                $class = "bg-danger-subtle";
                                $btn = '<a href="../controller/moderation.php?id=' . $comment['com_id'] .
                                    '&action=pending&element=comment&token=' . $_SESSION['token'] .
                                    '" class="btn btn-sm btn-outline-warning me-2">Suspendre</a>' .
                                    '<a href="../controller/delete_controller.php?id=' . $comment['com_id'] .
                                    '&element=comment&element=comment&token=' . $_SESSION['token'] .
                                    '" class="btn btn-sm btn-outline-danger">Supprimer</a>';
                            }
                        }
                        if (!$comment['commenter']) {
                            $comment['commenter'] = "Visiteur";
                            $href = '#';
                        } else {
                            $href = $config['url'] . '/view/read_user.php?id=' . $comment['commenter_id'] . '"';
                        } ?>
                        <li class="list-group-item py-3 d-flex flex-column <?= $class ?>">
                            <span><?= htmlentities(htmlspecialchars_decode($comment['com_content'])) ?></span>
                            <span class="fst-italic">
                                <a class="text-decoration-none" href="<?= $href ?>"><?= $comment['commenter'] ?></a>,
                                le <?= $comment['com_posted_at'] ?>
                            </span>
                            <div class="d-flex align-items-center my-1"><?= $btn ?></div>
                        </li>
                    <?php }
                } else { ?>
                    <p class="text-center">Personne n'a encore commenté ce spot.</p>
                <?php } ?>
            </ul>
            <?php if (isConnected()) { ?>
                <form method="POST" action="<?= $config['url'] ?>/controller/add_comment_controller.php"
                    aria-label="Formulaire d'ajout d'un commentaire">
                    <input type="hidden" name="user_comment" value="<?= $_SESSION['id'] ?>">
                    <input type="hidden" name="art_id" value="<?= $article['art_id'] ?>">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <div class="my-3">
                        <textarea id="comment_content" class="form-control" name="comment_content" aria-label="Contenu du commentaire"
                            aria-describedby="contentHelp" placeholder="Ajouter un commentaire" maxlength="300" rows="3" required></textarea>
                        <div id="contentHelp" class="form-text">300 caractères maximum.</div>
                        <p class="mt-2 fst-italic">
                            ⚠️ Merci de vous relire avant d’envoyer votre commentaire. Les messages haineux ou irrespectueux ne seront pas publiés.
                            Les commentaires pleins de respect et de bonne humeur sont les bienvenus !
                            Tous les messages sont relus avant publication pour que cet espace reste convivial pour tous.
                        </p>
                    </div>
                    <div class="container mx-auto mt-4 mb-2 d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center">
                        <a href="./index_articles.php" class="btn btn-outline-danger rounded-pill mb-3 col-11 col-sm-4"
                            aria-label="Retourner à la page précédente">Retour</a>
                        <button type="submit" class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-4 submit"
                            aria-label="Envoyer le commentaire">Envoyer</button>
                    </div>
                </form>
            <?php } else { ?>
                <div class="text-center mt-3">
                    <p>Connectez-vous pour pouvoir commenter !</p>
                    <div class="container mx-auto mt-4 mb-2 d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center">
                        <a href="./index_articles.php" class="btn btn-outline-danger rounded-pill mb-3 col-11 col-sm-4 col-md-4"
                            aria-label="Retourner à la page précédente">Retour</a>
                        <a href="./login.php" class="btn btn-outline-primary rounded-pill mb-3 col-11 col-sm-4 col-md-5 col-xl-4"
                            aria-label="Retourner à la page d'accueil">Se connecter</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script type="module" src="<?= $config['url'] ?>/script/read_article.js"></script>
    <?php require_once "../includes/footer.php" ?>
</body>

</html>