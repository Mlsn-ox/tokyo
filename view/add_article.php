<?php
require_once "../config.php";
$menu = "add_article";
if (!isConnected()) {
    header("Location: ../view/login.php?message_code=connect_error&status=success");
    exit();
}
if (isBlocked()) {
    header("Location: ../view/homepage.php?message_code=unauthorized&status=error");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Ajouter un spot - TokyoSpot</title>
    <meta name="description" content="Ajouter un spot - TokyoSpot">
</head>

<body>
    <?php require_once "../includes/navbar.php";
    $buttonSubmit = "Publier mon spot";
    $buttonPrevious = "Retour à la page d'accueil";
    $href = "./homepage.php";
    $mode = "add";
    $title = $_SESSION["temp_title"] ?? "";
    $category = $_SESSION["temp_cat"] ?? "";
    $content = $_SESSION["temp_content"] ?? "";
    $lat = $_SESSION["temp_lat"] ?? false;
    $lng = $_SESSION["temp_lng"] ?? false;
    $author = intval($_SESSION['id']);
    ?>
    <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mt-5">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="confirmModalLabel">
                        Avant de publier votre spot…
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer la fenêtre de confirmation"></button>
                </div>
                <div class="modal-body">
                    <p>Assurez-vous que votre spot respecte les règles de publication de
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
                    <p>Tant qu'il n'est pas validé, votre spot reste modifiable via votre page de profil.</p>
                    <p>Les spots non conformes seront supprimés.</p>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary rounded-pill mx-auto" data-bs-dismiss="modal">
                        Retour à la création
                    </button>
                    <button type="button" class="btn btn-success rounded-pill mx-auto" id="confirmSendBtn"
                        aria-label="Continuer vers la création d'un article" data-bs-dismiss="modal">
                        Prêt ? Publier ! 📨
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="section home container mx-auto p-1 p-md-3 p-xl-4">
        <div class="container fade-up redim">
            <h1 class="m-0 pt-2 data-miner" data-lat="<?= $lat ?>"
                data-lng="<?= $lng ?>"
                data-img="<?= $image ?>">
                Partager un spot
            </h1>
            <div class="separator my-3 text-center"></div>
            <?php
            require_once "../includes/form_article.php";
            ?>
        </div>
    </div>
    <script type="module" src="<?= $config['url'] ?>/script/article_form.js"></script>
    <?php require_once "../includes/footer.php" ?>
</body>

</html>