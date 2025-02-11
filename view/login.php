<?php include "./navbar.php"; ?>
<div class="section col-xxl-6 col-md-9 col-12 mx-auto px-5 pt-4  home">
    <form method="POST" action="../controller/login_controller.php">
        <h1 class="text-center text-primary mb-4">Connexion</h1>
        <div class="mb-4 col">
            <label for="user_mail" class="form-label">E-Mail</label>
            <input type="mail" class="form-control ms-2" name="user_mail" placeholder="Votre email de connexion">
        </div>
        <div class="mb-4">
            <label for="user_psw" class="form-label">Mot de passe</label>
            <input type="password" class="form-control ms-2" name="user_psw">
        </div>
        <div class="col-xl-5 col-sm-9 mx-auto">
            <input type="submit" class="form-control ms-2 rounded-pill btn btn-outline-primary py-2" value="Se connecter">
        </div>
        <?php if (isset($_GET["message"]) && isset($_GET["status"])) {
            $message = $_GET["message"];
            $status = $_GET["status"];
            echo "<h4 class='text-center $status' >$message.</h4>";
        }; ?> 
    </form>
</div>
<?php include "./footer.php"; ?>