<?php 
    require_once "../config.php";
    $menu = "contact";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Contact - TokyoSpot</title>
    <meta name="description" content="Contact - TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php";?>
<div class="section home container mx-auto p-1 p-md-3 p-xl-4">
    <div class="container fade-up redim">
        <h2 class="m-0 pt-2">Contacter un administrateur</h2>
        <div class="separator my-3 text-center"></div>
        <form action="<?= $config['url'] ?>/controller/send_mail.php" method="POST" 
            class="row g-3 mb-4" aria-label="Formulaire de contact">
            <div class="col-lg-5" aria-label="Nom">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" class="form-control" value="<?= isset($_SESSION['name']) ? $_SESSION['name'] : '' ?>" 
                    <?= isset($_SESSION['name']) ? 'readonly' : '' ?> required>
            </div>
            <div class="col-lg-7" aria-label="email">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="@email.com" 
                value="<?= isset($_SESSION['mail']) ? $_SESSION['mail'] : '' ?>" 
                <?= isset($_SESSION['mail']) ? 'readonly' : '' ?> required>
                <div id="mailHelp" class="form-text">
                    L’adresse e-mail fournie sera utilisée pour vous répondre. Assurez-vous de son exactitude.
                </div>
            </div>
            <div class="mb-3 mt-lg-0">
                <label for="subject" class="form-label">Objet</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3 mt-0">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" class="form-control" rows="6" required></textarea>
                <p class="form-text my-1">
                    🗺️ <strong>Si votre message concerne un spot :
                    </strong> merci d’indiquer le <em>titre exact</em> du spot ainsi que le <em>nom de son auteur</em>.
                </p>
                <p class="form-text m-0">
                    💬 <strong>Si votre message concerne un commentaire :
                    </strong> veuillez préciser le <em>titre du spot commenté</em> et le <em>nom de l’auteur du commentaire</em>.
                </p>
            </div>
            <div class="mb-3 mt-1 row gap-3">
                <div class="form-check col-11 col-lg-8 mx-auto">
                    <input class="form-check-input" type="checkbox" id="check" name="accept" required>
                    <label class="form-check-label" for="check">
                        En soumettant ce formulaire, j’accepte que les informations renseignées soient utilisées pour traiter ma demande de contact.</label>
                </div>
                <div class="d-flex justify-content-center captcha-container col-8 col-lg-3 mx-auto">
                    <div class="g-recaptcha" data-sitekey="6LeBuBArAAAAANUNtiLpXMAQUwrx9G5yF7EViSmZ" data-theme="light"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-outline-primary rounded-pill col-sm-8 col-md-6 col-lg-4 mx-auto">Envoyer</button>
        </form>
    </div>
</div>
<script type="module" src="<?= $config['url'] ?>/script/contact_form.js"></script>
<?php require_once "../includes/footer.php" ?>
</body>
</html>