<?php
    require_once "../includes/navbar.php";
    if (!$_SESSION['id']){
        header("Location: ../view/login.php?message_code=connect_error&status=success");
        exit();
    }
?>
<div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mt-5">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="confirmModalLabel">
                    Avant de publier votre spot…
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer la fenêtre de confirmation"></button>
            </div>
            <div class="modal-body">
                <p>Assurez-vous que votre spot respecte les règles de publication de
                    TokyoSpot.</p>
                <p>Votre spot doit être en lien avec le tourisme à Tokyo (lieu
                    intéressant, bon plan, expérience originale…). Pas de publicité ni
                    de contenu hors sujet.</p>
                <p>Pas de propos offensants, discriminatoires ou inappropriés.</p>
                <p>Relisez-vous avant d’envoyer ! Un texte bien écrit est plus
                    agréable à lire et aide la communauté à mieux comprendre votre
                    découverte.</p>
                <p>Assurez-vous que votre image est nette et bien représentative de
                    l’endroit que vous partagez.</p>
                <p>Les spots non conformes seront supprimés.</p>
            </div>
            <div class="modal-footer d-flex justify-content-center row-gap-2">
                <button class="btn btn-outline-success rounded-pill col-8 submit-button" 
                    data-bs-dismiss="modal" aria-label="Continuer vers la création d'un article">
                    Prêt ? Publier ! 📨
                </button>
            </div>
        </div>
    </div>
</div>
<div class="section home col-xxl-7 col-md-10 col-12 mx-auto p-1 p-md-3 p-xl-4">
    <div class="container fade-up">
        <h1 class="m-0 pt-2">Ajouter un article</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4" action="../controller/add_article_controller.php" 
            enctype="multipart/form-data" aria-label="Formulaire d'ajout d'un article">
            <input type="hidden" name="author" value="<?= $_SESSION['id'] ?>">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
            <div class="mb-3">
                <input type="text" name="title" class="form-control form-control-lg" 
                    aria-label="Titre de l'article" aria-describedby="titleHelp" 
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="40 caractères maximum." 
                    placeholder="Titre" maxlength="40" required>
                <div id="titleHelp" class="form-text">40 caractères maximum.</div>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Catégorie</label>
                <select id="category" class="form-select category" name="category" aria-label="Choix de la catégorie" required>
                    <option selected disabled value="">Choix de la catégorie</option>
                    <?php try {
                            $sql = "SELECT * FROM category;";
                            $stmt = $pdo->query($sql);
                            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($cats as $cat) { 
                                echo "<option value='" . $cat['cat_id'] ."'>" . ucfirst($cat['cat_name']) . "</option>";
                            }
                        } catch (PDOException $e) {
                            echo "Erreur : " . $e->getMessage();
                        } ?>
                </select>
            </div>
            <div class="my-3">
                <label for="content" class="form-label">Description du lieu</label>
                <textarea id="content" class="form-control" name="content" aria-label="Contenu de l'article" aria-describedby="contentHelp" 
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="300 caractères maximum." placeholder="Décrivez le lieu en quelques mots" 
                    maxlength="300" rows="3" required></textarea>
                <div id="contentHelp" class="form-text">300 caractères maximum.</div>
            </div>
            <div class="mb-4">
                <input class="files d-none" type="file" name="image" id="formFile image_uploads" 
                    accept=".png, .jpg, .jpeg, .webp, .avif, .tiff" aria-describedby="imageHelp" required>
                <label for="formFile image_uploads" class="form-label btn btn-warning rounded-0 col-8 col-sm-6 col-md-4 col-xl-3" 
                    aria-label="Ajouter une photo">Ajouter une photo</label>
                <p class="fst-italic" id="imageHelp">Photo de 10Mo maximum au format .png, .jpg, .jpeg, .webp, .avif, .tiff.</p>
                <div class="preview text-center my-2" aria-live="polite"></div>
            </div>
            <div class="mb-4">
                <label for="map" class="form-label">Cliquez sur la carte pour marquer l'emplacement de votre spot</label>
                <div id="map" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom mb-3" role="application" 
                    aria-label="Carte interactive pour sélectionner un emplacement"></div>
                <div class="container-fluid d-flex flex-wrap justify-content-center align-items-center gap-2">
                    <input class="form-control text-primary" type="text" id="lat" name="lat" 
                        value="" hidden required aria-label="Latitude du spot"> 
                    <input class="form-control text-success" type="text" id="lng" name="lng" 
                        value="" hidden required aria-label="Longitude du spot">
                </div>
                <div class="container-fluid d-flex flex-column justify-content-center">
                    <p class="adress text-center">ou</p>
                    <input class="btn btn-primary col-8 col-sm-7 col-md-5 col-xl-4 mx-auto rounded-0 localise" value="Me localiser" aria-label="Bouton de géolocalisation">
                    <div class="container-fluid d-none loading-icon d-flex justify-content-center" style="">
                        <div class="spinner" role="status" aria-label="Chargement"></div>
                    </div>
                </div>
            </div>
            <div class="container mx-auto mt-4 mb-2 row justify-content-between">
                <button type="submit" class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-7 col-md-6 submit" disabled 
                    aria-label="Envoyer l'article">Remplissez tous les champs demandés</button>
                <a href="./homepage.php" class="btn btn-outline-danger rounded-pill mb-3 col-11 col-sm-7 col-md-5 col-xl-4" 
                    aria-label="Retourner à la page d'accueil">Retour à la page d'accueil</a>
            </div>
        </form>
    </div>
</div>
<script type="module" src="../script/add_article_form.js"></script>
<?php require_once "../includes/footer.php" ?>
