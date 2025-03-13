<?php
    require_once "../includes/navbar.php";
    include "../includes/message.php";
    if (!$_SESSION['id']){
        header("Location: ../view/login.php?message_code=connect_error&status=error");
        exit();
    }
?>

<div class="section home col-xxl-7 col-md-10 col-12 mx-auto p-1 p-md-3 p-xl-4">
    <div class="container fade-up">
        <h1 class="m-0 pt-2">Ajouter un article</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4 position-relative needs-validation" novalidate action="../controller/add_article_controller.php" enctype="multipart/form-data">
            <input type="hidden" name="author" value="<?= $_SESSION['id'] ?>">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <div class="mb-3">
                <input type="text" name="title" class="form-control form-control-lg" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="40 caractères maximum." placeholder="Titre" maxlength="40" required>
                <div class="invalid-feedback">
                    Veuillez entrer un titre valide.
                </div>
            </div>
            <select class="form-select category" name="category" required>
                <option selected disabled value="">Choix de la catégorie</option>
                <option value="panorama">Panorama</option>
                <option value="gastronomie">Gastronomie</option>
                <option value="shopping">Shopping</option>
                <option value="loisir">Loisir</option>
            </select>
            <div class="invalid-feedback">
                Veuillez sélectionner une catégorie.
            </div>
            <div class="my-3">
                <textarea class="form-control" name="content" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="300 caractères maximum." placeholder="Décrivez le lieu en quelques mots" maxlength="350" rows="3" required></textarea>
                <div class="invalid-feedback">
                    Veuillez entrer une description valide.
                </div>
            </div>
            <div class="mb-4">
                <label for="map" class="form-label">Cliquez sur la carte pour marquer l'emplacement</label>
                <div id="map" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom mb-3"></div>
                <div class="container-fluid d-flex flex-wrap justify-content-center align-items-center">
                    <span>Latitude :         
                    <input class="form-control me-2 text-primary" type="text" id="lat" name="lat" value="" disabled required></span>  
                    <span>Longitude :          
                    <input class="form-control text-success" type="text" id="lng" name="lng" value="" disabled required></span> 
                    <div class="invalid-feedback">
                        Veuillez sélectionner les coordonnées de votre spot.
                    </div>
                </div>
                <div class="container-fluid d-flex flex-column justify-content-center my-2">
                    <p class="adress text-center"></p>
                    <input class="btn btn-primary col-11 col-sm-7 col-md-5 col-xl-4 mx-auto rounded-0 localise" value="Me localiser">
                    <div class="container-fluid d-none loading-icon d-flex justify-content-center" style="">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            <div class="container-fluid text-center">
                <div class="mb-4">
                    <input class="files d-none" type="file" name="image" id="formFile image_uploads" accept=".png, .jpg, .jpeg, .webp, .avif, .tiff" required>
                    <label for="formFile image_uploads" class="form-label btn btn-warning rounded-0 col-11 col-sm-7 col-md-5 col-xl-4">Ajouter une photo</label>
                    <div class="invalid-feedback">
                        Veuillez sélectionner une photo valide.
                    </div>
                    <div class="preview my-2"></div>
                </div>
                <input class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-7 col-md-5 col-xl-4" data-bs-toggle="modal" data-bs-target="#confirmModal" value="Envoyer">
            </div>
            <div
                class="modal fade"
                id="confirmModal"
                tabindex="-1"
                aria-labelledby="confirmModalLabel"
                aria-hidden="true"
                >
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="confirmModalLabel">
                        Avant de publier votre spot…
                        </h1>
                        <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <p>
                        Assurez-vous que votre spot respecte les règles de publication de
                        TokyoSpot.
                        </p>
                        <p>
                        Votre spot doit être en lien avec le tourisme à Tokyo (lieu
                        intéressant, bon plan, expérience originale…). Pas de publicité ni
                        de contenu hors sujet.
                        </p>
                        <p>Pas de propos offensants, discriminatoires ou inappropriés.</p>
                        <p>
                        Relisez-vous avant d’envoyer ! Un texte bien écrit est plus
                        agréable à lire et aide la communauté à mieux comprendre votre
                        découverte.
                        </p>
                        <p>
                        Assurez-vous que votre image est nette et bien représentative de
                        l’endroit que vous partagez.
                        </p>
                        <p>Les spots non conformes seront supprimés.</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button
                        type="button"
                        class="btn btn-outline-danger rounded-pill"
                        data-bs-dismiss="modal"
                        >
                        Se relire une dernière fois
                        </button>
                        <button type="submit" class="btn btn-outline-success rounded-pill">
                        Prêt ? Envoyer !
                        </button>
                    </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="module" src="../script/add_article_form.js"></script>
<?php require_once "../includes/footer.php" ?>
