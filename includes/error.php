<?php 
    require_once "../config.php";
    $menu = "";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Error - TokyoSpot</title>
    <meta name="description" content="TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php";?>
<div class="section p-1 container mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
<div class="p-xl-5 p-md-4 p-3 rounded-5 p-4 my-3 my-md-4 home fading user">
        <h1 class="mb-3 text-center">Oups... Une erreur est survenue. 🚨</h1>
        <div class="separator my-3 text-center"></div>
        <div class="container text-center">
            <p class="m-0">
                La page que tu cherches est introuvable ou une erreur interne est survenue.
            </p>
            <p class="m-0">
                🚧 Nous travaillons pour que tout fonctionne au mieux ! 🚧
            </p>
            <a href="<?= $config['url'] ?>/view/homepage.php" class="btn btn-outline-primary rounded-pill mt-3 mx-auto" aria-label="Retour à la page d'accueil">Retour à la page d'accueil</a>
        </div>
    </div>
</div>
<?php require_once "../includes/footer.php" ?>
</body>
</html>