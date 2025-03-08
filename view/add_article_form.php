<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    include "../includes/message.php";
?>

<div class="section home col-xxl-7 col-md-9 col-12 mx-auto p-4">
    <div class="container fade-up">
        <h1 class="m-0">Ajouter un article</h1>
        <div class="separator my-3 text-center"></div>
        <form method="POST" class="mt-4" action="../controller/add_article_controller.php" enctype="multipart/form-data">
            <input type="hidden" name="author" value="<?= $_SESSION['id'] ?>">
            <div class="mb-3">
                <input type="text" name="title" class="form-control form-control-lg" placeholder="Titre" maxlength="40">
            </div>
            <select class="form-select mb-3 category" name="category">
                <option value="" selected>Choix de la catégorie</option>
                <option value="panorama">Panorama</option>
                <option value="gastronomie">Gastronomie</option>
                <option value="shopping">Shopping</option>
                <option value="loisir">Loisir</option>
            </select>
            <div class="mb-3">
                <textarea class="form-control" name="content" placeholder="Décrivez le lieu en quelques mots" maxlength="300" rows="3"></textarea>
            </div>
            <div class="mb-4">
                <label for="map" class="form-label">Cliquez sur la carte pour marquer l'emplacement</label>
                <div id="map" class="leaflet-container leaflet-touch leaflet-fade-anim leaflet-grab leaflet-touch-drag leaflet-touch-zoom mb-3"></div>
                <input type="hidden" id="lat" name="lat" value="">
                <input type="hidden" id="lng" name="lng" value="">
            </div>
            <div class="container-fluid text-center">
                <div class="mb-4">
                    <input class="files d-none" type="file" name="image" id="formFile image_uploads" accept=".png, .jpg, .jpeg, .webp, .avif, .tiff">
                    <label for="formFile image_uploads" class="form-label btn btn-warning rounded-0 col-11 col-sm-7 col-md-5 col-xl-4">Ajouter une photo</label>
                    <div class="preview my-2"></div>
                </div>
                <button type="submit" class="btn btn-outline-success rounded-pill mb-3 col-11 col-sm-7 col-md-5 col-xl-4">Envoyer</button>
            </div>
            <?php if (isset($_GET["message_code"]) && isset($_GET["status"])) {
                    $message = getMessage($_GET["message_code"]);
                    $status = $_GET["status"];
                    echo "<h4 class='text-center $status' >$message</h4>";
                } ?>
        </form>
    </div>
</div>
<script type="module" src="../script/add_article_form.js"></script>
<?php require_once "../includes/footer.php" ?>
