<?php
    include "../includes/message.php";
    require_once "../includes/navbar.php";
?>
<div class="section col-xxl-7 col-md-9 col-12 mx-auto p-4 home">
    <form method="POST" action="../controller/login_controller.php" class="fade-up">
    <h1 class="m-0">Connexion</h1>
    <div class="separator my-3 text-center"></div>
        <div class="mb-4 mx-auto col-xl-6 col-sm-8">
            <label for="user_mail" class="form-label">E-Mail</label>
            <input type="email" class="form-control ms-2" name="mail" placeholder="Votre email de connexion">
        </div>
        <div class="mb-4 mx-auto col-xl-6 col-sm-8">
            <label for="user_psw" class="form-label">Mot de passe</label>
            <input type="password" class="form-control ms-2" name="psw">
        </div>
        <div class="col-xl-4 col-sm-8 mx-auto my-2 text-center">
            <input type="submit" class="form-control rounded-pill btn btn-outline-primary py-2 login-btn" value="Se connecter">
        </div>
    <div class="separator my-5 text-center"></div>
        <div class="mx-auto text-center">
            <p>Vous n'avez pas encore de compte ? c'est par ici !</p>
            <a class="rounded-pill btn btn-outline-success py-2 login-btn" href="./add_user_form.php">S'inscrire</a>
        </div>
    </form>
</div>
<?php require_once "../includes/footer.php" ?>