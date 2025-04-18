<?php 
    require_once "../config.php";
    $menu = "mentions_legales";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Mentions légales - TokyoSpot</title>
    <meta name="description" content="Mentions legales - TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php";?>
<div class="section p-1 container mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 my-3 my-md-4 home fade-up">
        <h1 class="mb-3">Mentions légales</h1>
        <div class="separator my-3 text-center"></div>
        <h3 class="my-3">1. Éditeur du site</h3>
        <p class="m-0">Tokyospot est un site personnel à but non lucratif, destiné à partager des lieux intéressants à visiter à Tokyo.
        <p class="m-0">Responsable de la publication : 
            <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" 
            href="<?= $config['url'] ?>/view/read_user.php?id=1">Mlsn</a></p> 
        <p class="m-0">Contact : 
            <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" 
            href="<?= $config['url'] ?>/includes/contact_form.php">contact.tokyospot@gmail.com</a></p>
        <h3 class="my-3">2. Hébergement du site</h3>
        <p class="m-0">Le site est hébergé par : [Nom de l’hébergeur, par ex. : OVH, Hostinger, Infomaniak, etc.]</p>
        <p class="m-0">Adresse : [adresse du siège social de l’hébergeur]</p>
        <p class="m-0">Site web : [URL de l’hébergeur]</p>
        <h3 class="my-3">3. Propriété intellectuelle</h3>
        <p class="m-0">Le contenu publié sur Tokyospot (textes, images, logo, code, etc.) est, sauf mention contraire, la propriété exclusive de son auteur.
        Toute reproduction, distribution, modification ou utilisation sans autorisation préalable est interdite.</p>
        <h3 class="my-3">4. Données personnelles</h3>
        <p class="m-0">Tokyospot peut collecter des données personnelles dans le cadre de l'inscription, de la soumission d’articles ou de la modération.
        Ces données ne sont ni vendues, ni cédées à des tiers, et sont conservées de manière sécurisée.</p>
        <p class="m-0">Conformément au Règlement Général sur la Protection des Données (RGPD), vous disposez d’un droit d’accès, de rectification et de suppression de vos données personnelles.
        Pour exercer ce droit, contactez-nous à : <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" href="../includes/contact_form.php">contact.tokyospot@gmail.com</a></p>
        <h3 class="my-3">5. Cookies</h3>
        <p class="m-0">Le site peut utiliser des cookies à des fins de fonctionnement, d’analyse ou de personnalisation.
        En naviguant sur Tokyospot, vous acceptez l’usage de ces cookies. Vous pouvez modifier vos préférences via votre navigateur.</p>
        <h3 class="my-3">6. Responsabilité</h3>
        <p class="m-0">Les informations proposées sur Tokyospot sont fournies à titre indicatif. L’auteur ne saurait être tenu responsable des erreurs, omissions ou d’un mauvais usage des contenus.
    </div>
</div>
<?php require_once "../includes/footer.php" ?>
</body>
</html>