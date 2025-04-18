<?php 
    require_once "../config.php";
    $menu = "confidentialite";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Politique de confidentialité - <?= $config['title'] ?></title>
    <meta name="description" content="Politique de confidentialité - <?= $config['title'] ?>">
</head>
<body>
<?php require_once "../includes/navbar.php";?>
<div class="section p-1 container mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 my-3 my-md-4 home fade-up">
        <h1 class="mb-3">Politique de confidentialité</h1>
        <div class="separator my-3 text-center"></div>
        <h3 class="my-3">1. Introduction</h3>
        <p class="m-0">Cette politique de confidentialité a pour but d’informer les utilisateurs du site 
            <strong><?= $config['title'] ?></strong> de la manière dont leurs données personnelles sont collectées, utilisées et protégées.
        </p>
        <h3 class="my-3">2. Données collectées</h3>
        <p class="m-0">Les données suivantes peuvent être collectées :</p>
        <ul>
            <li>Nom et prénom</li>
            <li>Adresse e-mail</li>
            <li>Date de naissance</li>
            <li>Contenus publiés (lieux, descriptions, photos, etc.)</li>
        </ul>
        <p class="m-0">Ces données sont collectées lors de l’inscription, lors de la soumission de contenu, ou via les formulaires de contact.</p>
        <h3 class="my-3">3. Finalité de la collecte</h3>
        <p class="m-0">Les données sont collectées dans le but de :</p>
        <ul>
            <li>Permettre la création et la gestion des comptes utilisateurs</li>
            <li>Assurer la modération du contenu</li>
            <li>Améliorer l’expérience utilisateur</li>
            <li>Répondre aux demandes envoyées via le formulaire de contact</li>
        </ul>
        <h3 class="my-3">4. Stockage et durée de conservation</h3>
        <p class="m-0">Les données sont conservées sur des serveurs sécurisés et pour une durée n’excédant pas ce qui est nécessaire au regard des finalités.</p>
        <p class="m-0">Les comptes inactifs peuvent être supprimés au bout de 2 ans d’inactivité.</p>
        <h3 class="my-3">5. Partage des données</h3>
        <p class="m-0">Les données ne sont jamais cédées, vendues ou transmises à des tiers sans votre consentement, sauf en cas d’obligation légale.</p>
        <h3 class="my-3">6. Sécurité</h3>
        <p class="m-0"><?= $config['title'] ?> met en œuvre les mesures techniques et organisationnelles nécessaires pour assurer la sécurité des données personnelles.</p>
        <h3 class="my-3">7. Cookies</h3>
        <p class="m-0">Le site utilise des cookies pour améliorer la navigation, mesurer l’audience, et personnaliser le contenu. 
            Vous pouvez refuser ou désactiver les cookies via les paramètres de votre navigateur.</p>
        <h3 class="my-3">8. Vos droits</h3>
        <p class="m-0">Conformément au RGPD, vous disposez des droits suivants :</p>
        <ul>
            <li>Droit d’accès à vos données</li>
            <li>Droit de rectification</li>
            <li>Droit à l’effacement</li>
            <li>Droit à la portabilité</li>
            <li>Droit d’opposition au traitement</li>
        </ul>
        <p class="m-0">Pour exercer vos droits, contactez-nous à : 
        <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" 
            href="<?= $config['url'] ?>/includes/contact_form.php"><?= $config['email'] ?></a></p>
        <h3 class="my-3">9. Modification de la politique</h3>
        <p class="m-0">Cette politique peut être mise à jour à tout moment. La date de dernière mise à jour sera indiquée ci-dessous.</p>
        <p class="mt-3"><em>Dernière mise à jour : avril 2025</em></p>
    </div>
</div>
<?php require_once "../includes/footer.php" ?>
</body>
</html>