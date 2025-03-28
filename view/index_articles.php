<?php
    require_once "../includes/navbar.php";
    require_once "../includes/filters.php";
    function filterSet() {
        return isset($_GET["category"]) || isset($_GET["order"]);
    }  
?>
<div class="section col-12 col-xl-11 col-xxl-10 p-xl-3 p-lg-2 p-1 mx-auto home d-flex flex-column align-items-center">
    <div class="container d-flex justify-content-center my-4 filters">
        <button class="btn btn-lg btn-outline-success rounded-pill text-black d-flex align-items-center justify-content-center px-3 gap-2" 
            type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
            Filtrer
        </button>
    </div>
    <div class="collapse <?= filterSet() ? "show" : "" ?>" id="collapseFilters">
        <div class="container col-8 mb-5">
            <form method="GET" class="d-flex flex-column align-items-center" id="filter">
                <div class="container row my-3">
                    <?php try {
                        $sql = "SELECT cat_name FROM category;";
                        $stmt = $pdo->query($sql);
                        $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($cats as $cat) { ?>
                            <div class="form-check form-switch col-12 col-md-6 col-xl-3 d-flex justify-content-center catego-filter me-4">
                                <input class="form-check-input" type="checkbox" role="switch" name="category[]" id="<?= $cat["cat_name"] ?>" 
                                    value="<?= $cat["cat_name"] ?>" <?= in_array($cat["cat_name"], $categories) ? "checked" : "" ?>>
                                <label class="ms-2 form-check-label" for="<?= $cat["cat_name"] ?>">
                                    <?= ucfirst($cat["cat_name"]) ?>
                                </label>
                            </div>
                        <?php }
                    } catch (PDOException $e) {
                        echo "Erreur : " . $e->getMessage();
                    } ?>
                </div>
                <select class="form-select mb-3 mx-auto col-xl-6 col-sm-8" name="order">
                    <option value="desc" <?= $order === "desc" ? "selected" : "" ?>>Du plus récent au plus ancien</option>
                    <option value="asc" <?= $order === "asc" ? "selected" : "" ?>>Du plus ancien au plus récent</option>
                </select>
                <button type="submit" class="btn btn-outline-primary rounded-pill ">Rechercher</button>
            </form>
        </div>
    </div>
    <div class="container-fluid row justify-content-around flex-wrap" id="articles"></div>
    <div class="container mx-auto text-center my-3" id="loader"></div>
</div>
<script type="module" src="../script/index_articles.js"></script>
<?php require_once "../includes/footer.php" ?>