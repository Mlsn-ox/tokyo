<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    include "../includes/message.php";
?>
<div class="section col-xxl-6 col-md-9 col-12 mx-auto px-5 pt-4  home">
    <form method="POST" action="../controller/login_controller.php">
    <h1 class="m-0">Connexion</h1>
    <div class="separator my-3 text-center"></div>
        <div class="mb-4 mx-auto col-xl-7 col-sm-9">
            <label for="user_mail" class="form-label">E-Mail</label>
            <input type="mail" class="form-control ms-2" name="mail" placeholder="Votre email de connexion">
        </div>
        <div class="mb-4 mx-auto col-xl-7 col-sm-9">
            <label for="user_psw" class="form-label">Mot de passe</label>
            <input type="password" class="form-control ms-2" name="psw">
        </div>
        <div class="col-xl-4 col-sm-8 mx-auto my-2">
            <input type="submit" class="form-control rounded-pill btn btn-outline-primary py-2" value="Se connecter">
        </div>
    <div class="separator my-5 text-center"></div>
        <div class="mx-auto text-center">
            <p>Vous n'avez pas encore de compte ? c'est par ici !</p>
            <a class="px-5 rounded-pill btn btn-outline-success py-2" href="./add_user_form.php">S'inscrire</a>
        </div>
        <?php if (isset($_GET["message_code"]) && isset($_GET["status"])) {
                    $message = getMessage($_GET["message_code"]);
                    $status = $_GET["status"];
                    echo "<h4 class='text-center mt-5 $status' >$message</h4>";
                } ?>
    </form>
</div>
<?php require_once "../includes/footer.php" ?>