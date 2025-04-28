<?php 
    require_once "../config.php";
    $menu = "cgu";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>CGU - TokyoSpot</title>
    <meta name="description" content="Conditions générales d'utilisation - TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php";?>
<div class="section p-1 container mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 my-3 my-md-4 home fade-up">
        <h1 class="mb-3">Conditions Générales d’Utilisation</h1>
        <div class="separator my-3 text-center"></div>

        <h3 class="my-3">1. Objet</h3>
        <p class="m-0">Les présentes Conditions Générales d’Utilisation (CGU) ont pour objet de définir les modalités et conditions d’utilisation du site <strong><?= $config['title'] ?></strong>, 
            accessible à l’adresse 
            <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" 
            href="<?= $config['url'] ?>/view/homepage.php"><?= $config['url'] ?></a>, 
            ainsi que les droits et obligations des utilisateurs. En accédant au site, vous acceptez sans réserve les présentes CGU.</p>

        <h3 class="my-3">2. Accès au site</h3>
        <p class="m-0">Le site est accessible gratuitement à tout utilisateur disposant d’une connexion Internet. Certaines fonctionnalités peuvent nécessiter la création d’un compte utilisateur. 
            L’éditeur se réserve le droit de suspendre ou d’interrompre l’accès au site à tout moment, notamment pour des raisons techniques ou de maintenance.</p>

        <h3 class="my-3">3. Inscription</h3>
        <p class="m-0">L’utilisateur s’engage à fournir des informations exactes lors de son inscription et à ne pas usurper l’identité d’un tiers. 
            Le compte est personnel et l’utilisateur est responsable de la confidentialité de ses identifiants.</p>

        <h3 class="my-3">4. Contenu utilisateur</h3>
        <p class="m-0">Les utilisateurs peuvent publier du contenu (lieux, photos, descriptions, etc.). En publiant, ils garantissent être titulaires des droits ou avoir obtenu les autorisations nécessaires. 
            Ils autorisent Tokyospot à diffuser ces contenus gratuitement et pour une durée illimitée sur le site. Tokyospot se réserve le droit de modérer ou supprimer les contenus à tout moment.</p>

        <h3 class="my-3">5. Règles de publication</h3>
        <p class="m-0">Il est interdit de publier :</p>
        <ul class="m-0">
            <li>des contenus illégaux, violents, haineux ou discriminatoires</li>
            <li>des propos diffamatoires ou portant atteinte à autrui</li>
            <li>du contenu promotionnel ou publicitaire sans autorisation</li>
        </ul>
        <p class="m-0">Les comptes diffusant de manière répétée des contenus non conformes pourront être suspendus.</p>

        <h3 class="my-3">6. Modération</h3>
        <p class="m-0">Le site dispose d’un système de modération. Les contenus publiés doivent être validés avant publication et peuvent être retirés après signalement. La modération vise à garantir le respect des présentes CGU.</p>

        <h3 class="my-3">7. Propriété intellectuelle</h3>
        <p class="m-0">Le site Tokyospot (contenu, design, code, etc.) est protégé par les lois relatives à la propriété intellectuelle.</p>
        <p class="m-0">Toute reproduction, distribution ou utilisation non autorisée des éléments du site est interdite sans accord préalable de l’éditeur.</p>

        <h3 class="my-3">8. Données personnelles</h3>
        <p class="m-0">Tokyospot collecte des données personnelles lors de l’inscription ou de l’envoi de contenu. Ces données sont utilisées uniquement pour le bon fonctionnement du site et ne sont jamais transmises à des tiers sans consentement.</p>
        <p class="m-0">Conformément au RGPD, vous pouvez exercer vos droits à l’adresse : 
            <a class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover" 
            href="mailto:<?= $config['url'] ?>/includes/contact_form.php"><?= $config['email'] ?></a></p>

        <h3 class="my-3">9. Cookies</h3>
        <p class="m-0">Tokyospot peut utiliser des cookies à des fins de navigation, d’analyse ou de personnalisation. Vous pouvez à tout moment modifier les paramètres de votre navigateur pour bloquer les cookies.</p>

        <h3 class="my-3">10. Responsabilité</h3>
        <p class="m-0">Tokyospot ne peut être tenu responsable :</p>
        <ul>
        <li>du contenu publié par les utilisateurs,</li>
        <li>des dommages directs ou indirects liés à l’utilisation du site,</li>
        <li>des erreurs ou omissions dans les informations diffusées.</li>
        </ul>

        <h3 class="my-3">11. Liens externes</h3>
        <p class="m-0">Le site peut contenir des liens vers d’autres sites. Tokyospot n’est pas responsable de leur contenu ni de leurs pratiques en matière de données personnelles.</p>

        <h3 class="my-3">12. Modifications des CGU</h3>
        <p class="m-0">Tokyospot se réserve le droit de modifier les présentes CGU à tout moment. Les utilisateurs sont invités à les consulter régulièrement.</p>

        <h3 class="my-3">13. Droit applicable</h3>
        <p class="m-0">Les présentes CGU sont régies par le droit français. En cas de litige, les parties s’engagent à rechercher une solution amiable. À défaut, les tribunaux compétents seront saisis.</p>
        <p class="mt-3"><em>Dernière mise à jour : <?= date('m/Y') ?></em></p>
    </div>
</div>
<?php require_once "../includes/footer.php" ?>
</body>
</html>