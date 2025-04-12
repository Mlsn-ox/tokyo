<?php
    require_once "../includes/navbar.php";
    if (!empty($_SESSION['id'])){
        header("Location: ../view/homepage.php?message_code=redirect_error&status=error");
        exit();
    }
?>
<div class="section container home mx-auto p-md-4 p-3">
    <div class="p-0 mx-auto fade-up redim">
        <h1 class="m-0">Rejoignez la communauté</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4 row g-3" action="../controller/add_user_controller.php" aria-label="Formulaire de création de compte utilisateur">
            <div class="col-lg-8 mb-3" aria-label="Nom complet">
                <label for="completeName" class="form-label">Nom complet</label>
                <div class="input-group">
                    <input type="text" aria-label="Prénom" name="firstname" class="form-control" placeholder="Prénom" required>
                    <input type="text" aria-label="Nom" name="lastname" class="form-control" placeholder="Nom" required>
                </div>
            </div>
            <div class="col-lg-4">
                <label for="birthdate" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" id="birthdate" name="birthdate" aria-label="Date de naissance" required/>
            </div>
            <div class="col-lg-6">
                <label for="mail" class="form-label">Email</label>
                <input type="email" class="form-control" id="mail" name="mail" aria-label="Adresse email" placeholder="@email.com" required>
            </div>
            <div class="col-lg-6">
                <label for="name" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="name" data-bs-toggle="tooltip" data-bs-placement="top"
                    pattern="[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-]+" name="name" aria-label="Nom d'utilisateur"
                    data-bs-title="Lettres, chiffres et tirets uniquement." maxlength="20" required>
                <div id="nameHelp" class="form-text">Entre 3 et 20 caractères.</div>
            </div>
            <div class="col-lg-6">
                <label for="psw1" class="form-label">Mot de passe</label>
                <div class="password-wrapper d-flex align-items-center">
                    <input type="password" class="form-control" id="psw1" name="password1"
                        aria-label="Mot de passe" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="Au moins 7 caractères dont un chiffre et une majuscule."
                        pattern="(?=.*[A-Z])(?=.*\d).{7,}" maxlength="100" required>
                    <span class="toggle-password fs-5 ms-1 me-sm-3 me-1" data-target="psw1" aria-label="Afficher ou masquer le mot de passe">👁️</span>
                </div>
                <div id="passwordHelp" class="form-text">Le mot de passe doit contenir au moins 7 caractères, une majuscule et un chiffre.</div>
            </div>
            <div class="col-lg-6">
                <label for="psw2" id="pswHelp" class="form-label">Confirmer le mot de passe</label>
                <div class="password-wrapper d-flex align-items-center">
                    <input type="password" class="form-control" id="psw2" name="password2"
                        aria-label="Confirmation du mot de passe" data-bs-toggle="tooltip" data-bs-placement="top"
                        data-bs-title="Au moins 7 caractères dont un chiffre et une majuscule."
                        pattern="(?=.*[A-Z])(?=.*\d).{7,}" maxlength="100" required>
                    <span class="toggle-password fs-5 ms-1 me-sm-3 me-1" data-target="psw2" aria-label="Afficher ou masquer la confirmation du mot de passe">👁️</span>
                </div>
            </div>
            <p class="mt-4">Choisissez une image de profil</p>
            <div class="col-12 d-flex row justify-content-around mb-3 mx-auto text-center avatars" aria-label="Choix de l'image de profil">
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="sakura" value="Sakura.png" checked required aria-label="Avatar Sakura et temple japonais">
                    <label class="form-check-label" for="sakura">
                        <img class="avatar" src="../assets/img_profil/Sakura.png" alt="temple japonais et fleur de sakura">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="neko" value="Neko.png" aria-label="Avatar Maneki Neko">
                    <label class="form-check-label" for="neko">
                        <img class="avatar" src="../assets/img_profil/Neko.png" alt="Maneki Neko">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="godzilla" value="Godzilla.png" aria-label="Avatar Kawaii Godzilla">
                    <label class="form-check-label" for="godzilla">
                        <img class="avatar" src="../assets/img_profil/Godzilla.png" alt="Kawaii Godzilla">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="ramen" value="Ramen.png" aria-label="Avatar bol de ramen">
                    <label class="form-check-label" for="ramen">
                        <img class="avatar" src="../assets/img_profil/Ramen.png" alt="Bol de ramen">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="Kanagawa" value="Kanagawa.png" aria-label="Avatar grande vague de Kanagawa">
                    <label class="form-check-label" for="Kanagawa">
                        <img class="avatar" src="../assets/img_profil/Kanagawa.png" alt="Grande vague de Kanagawa">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
                <div class="form-check p-0 p-md-2 mx-lg-1 pb-2 col-6 col-sm-5 col-lg-3 img-select">
                    <input class="form-check-input" type="radio" name="profil" id="shiba" value="Shiba.png" aria-label="Avatar shiba Inu">
                    <label class="form-check-label" for="shiba">
                        <img class="avatar" src="../assets/img_profil/Shiba.png" alt="Shiba inu">
                        <img class="check" src="../assets/img_profil/check-circle.svg" alt="checked">
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-evenly align-items-center">
                <div class="form-check col-6 d-flex align-items-center">
                    <input class="form-check-input me-2 fs-5" type="checkbox" id="gridCheck" required aria-label="Accepter les mentions légales et les conditions d'utilisation">
                    <label class="form-check-label" for="gridCheck">
                        <p class="m-0">J'accepte les 
                            <a href="../assets/papers/Mentions Légales - tokyospot .pdf" target="_blank" class="text-decoration-none">
                                <span class="text-decoration-underline link-offset-2 link-underline-opacity-50">mentions légales</span> 🔍
                            </a>
                        </p>
                        <p class="m-0">et les 
                            <a href="../assets/papers/CGU.pdf" target="_blank" class="text-decoration-none">
                                <span class="text-decoration-underline link-offset-2 link-underline-opacity-50">conditions générales d'utilisation</span> 🔍
                            </a>
                        </p>
                    </label>
                </div>
                <div class="col-4 d-flex justify-content-center captcha-container">
                    <div class="g-recaptcha" data-sitekey="6LeBuBArAAAAANUNtiLpXMAQUwrx9G5yF7EViSmZ" data-theme="light"></div>
                </div>
            </div>
            <div class="container mx-auto mt-4 mb-2 d-flex flex-wrap justify-content-between" aria-label="Actions du formulaire">
                <a href="./homepage.php" class="btn btn-outline-danger rounded-pill mb-3 mx-auto" aria-label="Retour à la page d'accueil">Retour à la page d'accueil</a>
                <button type="submit" class="btn btn-outline-success rounded-pill mb-3 mx-auto submit" aria-label="Valider l'inscription">S'inscrire</button>
            </div>
        </form>
    </div>
</div>
<script type="module" src="../script/add_user_form.js"></script>
<?php require_once "../includes/footer.php" ?>