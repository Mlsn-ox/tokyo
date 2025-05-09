<?php
// Valeurs par défaut ou écrasées
$article = $article ?? [];
$buttonSubmit = $buttonSubmit ?? 'Publier mon spot';
$buttonPrevious = $buttonPrevious ?? 'Retour';
$href = $href ?? './homepage.php';
$mode = $mode ?? 'add';
?>

<form method="POST" action="../controller/article_controller.php" enctype="multipart/form-data"
    data-mode="<?= $mode ?>" aria-label="Formulaire d'article" class="redim">
    <input type="hidden" name="author" value="<?= $author ?? null ?>">
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?? null ?>">
    <?php if ($mode === "update") { ?>
        <input type="hidden" name="art_id" value="<?= htmlspecialchars_decode($article['art_id']) ?>">
    <?php } ?>
    <div class="mb-3">
        <input type="text" name="title" class="form-control form-control-lg"
            value="<?= htmlspecialchars_decode($article['art_title'] ?? $title) ?>"
            placeholder="Titre" maxlength="40" required
            aria-label="Titre de l'article" aria-describedby="titleHelp">
    </div>
    <div class="mb-3">
        <label for="category" class="form-label">Catégorie</label>
        <select id="category" class="form-select category" name="category" required aria-label="Choix de la catégorie">
            <option disabled value="">Choix de la catégorie</option>
            <?php
            try {
                $sql = "SELECT * FROM category;";
                $stmt = $pdo->query($sql);
                $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($cats as $cat) {
                    $isSelected = false;
                    if ($mode === "update" && $article['art_fk_cat_id'] == $cat['cat_id']) {
                        $isSelected = true;
                    } elseif ($mode === "add" && !empty($_SESSION['temp_cat']) && $_SESSION['temp_cat'] == $cat['cat_id']) {
                        $isSelected = true;
                    }
                    echo "<option value='" . $cat['cat_id'] . "'" . ($isSelected ? " selected" : "") . ">" . ucfirst($cat['cat_name']) . "</option>";
                }
            } catch (PDOException $e) {
                echo "<option disabled>Erreur lors du chargement des catégories</option>";
            }
            ?>
        </select>
    </div>
    <div class="my-3">
        <label for="content" class="form-label">Description du lieu</label>
        <textarea id="content" name="content" class="form-control" rows="3" maxlength="300" required
            placeholder="Décrivez le lieu en quelques mots"
            aria-label="Contenu de l'article" aria-describedby="contentHelp">
            <?= htmlspecialchars_decode($article['art_content'] ?? $content) ?>
        </textarea>
        <div id="contentHelp" class="form-text">300 caractères maximum.</div>
    </div>
    <div class="mb-4">
        <input class="files d-none" type="file" name="image" id="formFile image_uploads"
            accept=".png, .jpg, .jpeg, .webp, .avif, .tiff" aria-describedby="imageHelp" <?= $mode === "add" ? "required" : "" ?>>
        <label for="formFile image_uploads" class="form-label img-label btn btn-primary rounded-0 col-8 col-sm-6 col-md-4 col-xl-3"
            aria-label="Ajouter une photo">Ajouter une photo</label>
        <div id="imageHelp" class="form-text">Photo de 10Mo maximum. Formats : .png, .jpg, .jpeg, .webp, .avif, .tiff.</div>
        <div class="preview text-center my-2" aria-live="polite"></div>
    </div>
    <div class="mb-4">
        <label for="map" class="form-label">Cliquez sur la carte pour marquer l'emplacement de votre spot</label>
        <div id="map" class="leaflet-container mb-3" role="application"
            aria-label="Carte interactive pour sélectionner un emplacement"></div>
        <div class="container-fluid d-flex flex-wrap justify-content-center align-items-center gap-2">
            <input class="form-control text-primary" type="text" id="lat" name="lat"
                value="<?= htmlspecialchars_decode($article['art_lat'] ?? $lat) ?>" hidden required>
            <input class="form-control text-success" type="text" id="lng" name="lng"
                value="<?= htmlspecialchars_decode($article['art_lng'] ?? $lng) ?>" hidden required>
        </div>
        <div class="container-fluid d-flex flex-column justify-content-center">
            <p class="adress text-center">ou</p>
            <input class="btn btn-primary col-8 col-sm-7 col-md-5 col-xl-4 mx-auto rounded-0 localise"
                value="Me localiser" aria-label="Bouton de géolocalisation">
            <div class="container-fluid d-none loading-icon d-flex justify-content-center">
                <div class="spinner" role="status" aria-label="Chargement"></div>
            </div>
        </div>
    </div>
    <div class="container mx-auto mt-4 mb-2 d-flex flex-column flex-wrap justify-content-center align-items-center flex-lg-row justify-content-lg-between">
        <a href="<?= $href ?>" class="btn btn-outline-danger rounded-pill mb-3 px-5 col-xl-4" aria-label="Retourner à la page d'accueil">
            <?= $buttonPrevious ?>
        </a>
        <button type="<?= (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? 'submit' : 'button' ?>" class="btn btn-outline-success rounded-pill mb-3 px-3 submit" aria-label="Envoyer l'article">
            <?= $buttonSubmit ?>
        </button>
    </div>
</form>