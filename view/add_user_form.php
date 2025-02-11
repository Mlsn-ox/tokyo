<?php include "navbar.php" ?>

<div class="section home col-xxl-7 col-md-9 col-12 mx-auto p-4">
    <div class="container">
        <h1 class="m-0">Rejoignez la communauté</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4 row g-3" action="../controller/add_user_controller.php">
            <div class="col-md-6">
                <label for="mail" class="form-label">Email</label>
                <input type="email" class="form-control" id="mail" name="mail">
            </div>
            <div class="col-md-6">
                <label for="name" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="name" data-bs-toggle="tooltip" data-bs-placement="top" pattern="[A-Za-z0-9_\-]+" name="name"
                    data-bs-title="Lettres, chiffres et tirets uniquement.">
            </div>
            <div class="col-md-6">
                <label for="psw" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="psw" data-bs-toggle="tooltip" data-bs-placement="top" name="password1"
                    data-bs-title="Au moins 7 caractères dont un chiffre et une majuscule.">
            </div>
            <div class="col-md-6">
                <label for="psw" class="form-label">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="psw" data-bs-toggle="tooltip" data-bs-placement="top" name="password2"
                    data-bs-title="Au moins 7 caractères dont un chiffre et une majuscule.">
            </div>
            <p class="mt-4">Choisissez une image de profil</p>
            <div class="col-12 d-flex row justify-content-around mb-3 text-center avatars">
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="sakura" value="Sakura.png" checked>
                    <label class="form-check-label" for="sakura">
                        <img class="avatar" src="../assets/img_profil/Sakura.png" alt="temple japonais et fleur de sakura">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="neko" value="Neko.png">
                    <label class="form-check-label" for="neko">
                        <img class="avatar" src="../assets/img_profil/Neko.png" alt="Maneki Neko">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="godzilla" value="Godzilla.png">
                    <label class="form-check-label" for="godzilla">
                        <img class="avatar" src="../assets/img_profil/Godzilla.png" alt="Kawaii Godzilla">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="ramen" value="Ramen.png">
                    <label class="form-check-label" for="ramen">
                        <img class="avatar" src="../assets/img_profil/Ramen.png" alt="Bol de ramen">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="Kanagawa" value="Kanagawa.png">
                    <label class="form-check-label" for="Kanagawa">
                        <img class="avatar" src="../assets/img_profil/Kanagawa.png" alt="Grande vague de Kanagawa">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check m-3 col-6 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="shiba" value="Shiba.png">
                    <label class="form-check-label" for="shiba">
                        <img class="avatar" src="../assets/img_profil/Shiba.png" alt="Shiba inu">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1">
                    <label class="form-check-label" for="newsletter">
                        Je souhaite être informé(e) en cas de nouveauté sur le site
                    </label>
                </div>
            </div>
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gridCheck" required>
                    <label class="form-check-label" for="gridCheck">
                        J'accepte les <a href="../assets/papers/Mentions Légales - tokyospot .pdf" target="_blank">mentions légales</a>
                        et les <a href="../assets/papers/CGU.pdf" target="_blank">conditions générales d'utilisation</a>
                    </label>
                </div>
            </div>
            <div class="col-3">
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </div>
            <div class="col-3">
                <a href="#" class="btn btn-danger">Retour à l'accueil</a>
            </div>
            <?php if (isset($_GET["message"]) && isset($_GET["status"])) {
                    $message = $_GET["message"];
                    $status = $_GET["status"];
                    echo "<h4 class='text-center $status' >$message.</h4>";
                } ?>
        </form>
    </div>
</div>
<script src="../script/add_user_form.js"></script>
<?php include "footer.php" ?>