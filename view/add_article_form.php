<?php
    require_once "../includes/navbar.php";
    include "../includes/message.php";
    if (!$_SESSION['id']){
        header("Location: ../view/login.php?message_code=connect_error&status=error");
        exit();
    }
?>

<!-- Modal -->

<div class="modal fade" id="modal-error" tabindex="-1" aria-labelledby="modal-error" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?php if (isset($_GET["message_code"]) && isset($_GET["status"])) {
                    $message = getMessage($_GET["message_code"]);
                    $status = $_GET["status"];
                    echo "<p>$message</p>";
                } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="section home col-xxl-7 col-md-9 col-12 mx-auto p-4">
    <div class="container fade-up">
        <h1 class="m-0">Ajouter un article</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4 position-relative needs-validation" novalidate action="../controller/add_article_controller.php" enctype="multipart/form-data">
            <input type="hidden" name="author" value="<?= $_SESSION['id'] ?>">
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
                <div class="container-fluid">
                    <p class="adresse"></p>
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
                <button type="submit" class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-7 col-md-5 col-xl-4">Envoyer</button>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Confirmer votre spot</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="module" src="../script/add_article_form.js"></script>
<?php require_once "../includes/footer.php" ?>
