<?php
require_once "../config.php";
$menu = "login";
if (isConnected()) {
    header("Location: ../view/homepage.php?message_code=connect_error&status=success");
    exit();
}
if (isset($_COOKIE["mail"])) {
    $mail = htmlspecialchars($_COOKIE["mail"], ENT_QUOTES, 'UTF-8');
} else {
    $mail = "";
}
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Connexion - TokyoSpot</title>
    <meta name="description" content="Connexion - TokyoSpot">
</head>

<body>
    <?php require_once "../includes/navbar.php"; ?>
    <div class="section container mx-auto p-4 home">
        <form method="POST" action="../controller/login_controller.php" class="fade-up">
            <h1 class="m-0">Connexion</h1>
            <div class="separator my-3 text-center"></div>
            <div class="mb-4 mx-auto col-xl-6 col-sm-8">
                <label for="user_mail" class="form-label">E-Mail</label>
                <input type="email" class="form-control ms-2" name="mail" placeholder="@" value="<?= htmlspecialchars($mail) ?>">
            </div>
            <div class="mb-4 mx-auto col-xl-6 col-sm-8">
                <label for="user_psw" class="form-label">Mot de passe</label>
                <div class="password-wrapper d-flex align-items-center">
                    <input type="password" class="form-control ms-2" name="psw" id="psw-login">
                    <span class="toggle-password fs-5 ms-1 me-sm-3" data-target="psw-login">👁️</span>
                </div>
            </div>
            <div class="col-xl-4 col-sm-8 mx-auto my-2 text-center">
                <input type="submit" class="form-control rounded-pill btn btn-outline-primary py-2 login-btn" value="Se connecter">
            </div>
            <div class="separator my-5 text-center"></div>
            <div class="mx-auto text-center">
                <p>Vous n'avez pas encore de compte ? C'est par ici !</p>
                <a class="rounded-pill btn btn-outline-success py-2 login-btn" href="./add_user.php">S'inscrire</a>
            </div>
        </form>
    </div>
    <script type="module" src="<?= $config['url'] ?>/script/login.js"></script>
    <?php require_once "../includes/footer.php" ?>
</body>

</html>