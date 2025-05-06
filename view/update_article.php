<?php
require_once "../config.php";
$menu = "add_article";
$id = intval($_GET['id']);
if (!$id) {
    header("Location: ../view/homepage.php?message_code=param_not_found&status=error");
    exit();
}
$stmt = $pdo->prepare("SELECT *, img_name FROM article LEFT JOIN image ON img_fk_art_id = art_id WHERE art_id =?");
$stmt->execute([$id]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);
$buttonSubmit = "Enregistrer les modifications";
$buttonPrevious = "Retour au profil";
$href = "./read_user.php?id=" . $_SESSION['id'];
$mode = "update";
$author = $article['art_fk_user_id'];
if ($author != $_SESSION['id'] && $_SESSION['role'] != 'admin') {
    header("Location: ../view/homepage.php?message_code=unauthorized&status=error");
    exit();
}
if (!isConnected()) {
    header("Location: ../view/login.php?message_code=connect_error&status=success");
    exit();
}
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: ../view/login.php?message_code=param_not_found&status=success");
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Modifier le spot - TokyoSpot</title>
    <meta name="description" content="Modification - TokyoSpot">
</head>

<body>
    <?php require_once "../includes/navbar.php"; ?>
    <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmModalLabel">
                        Vous venez de modifier votre spot...
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer la fenêtre de confirmation"></button>
                </div>
                <div class="modal-body">
                    <p>Assurez-vous toujours de respecter les règles de publication de
                        TokyoSpot.</p>
                    <p>Votre spot doit être en lien avec le tourisme à Tokyo (lieu
                        intéressant, bon plan, expérience originale…). Pas de publicité ni
                        de contenu hors sujet.</p>
                    <p>Pas de propos offensants, discriminatoires ou inappropriés.</p>
                    <p>Relisez-vous avant d’envoyer ! Un texte bien écrit est plus
                        agréable à lire et aide la communauté à mieux comprendre votre
                        découverte.</p>
                    <p>Assurez-vous que votre image est nette et bien représentative de
                        l’endroit que vous partagez.</p>
                    <p>Les spots non conformes seront supprimés.</p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill mx-auto" data-bs-disque votre spotmiss="modal">
                        Retour à la création
                    </button>
                    <button type="button" class="btn btn-success rounded-pill mx-auto" id="confirmSendBtn"
                        aria-label="Continuer vers la création d'un article" data-bs-dismiss="modal">
                        Prêt ? (re)Publier ! 📨
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="section home container mx-auto p-1 p-md-3 p-xl-4">
        <div class="container fade-up redim">
            <h1 class="m-0 pt-2 data-miner" data-lat="<?= htmlspecialchars($article['art_lat']) ?>"
                data-lng="<?= htmlspecialchars($article['art_lng']) ?>"
                data-img="<?= htmlspecialchars($article['img_name']) ?>">
                Modifier votre spot
            </h1>
            <div class="separator my-3 text-center"></div>
            <?php
            require_once "../includes/form_article.php";
            ?>
        </div>
    </div>
    <script type="module" src="<?= $config['url'] ?>/script/article_form.js"></script>
    <?php require_once "../includes/footer.php"; ?>
</body>

</html>