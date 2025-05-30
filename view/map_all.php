<?php
require_once "../config.php";
$menu = "map_all";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">

<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Map - TokyoSpot</title>
    <meta name="description" content="Map - TokyoSpot">
</head>

<body>
    <?php require_once "../includes/navbar.php"; ?>
    <div class="section home container p-xl-3 p-md-2 p-1 mx-auto">
        <h2 class="ms-1">Tous les spots partagés</h2>
        <div class="container-fluid d-flex flex-wrap align-items-center justify-content-center justify-content-md-start p-0 mt-4 ms-1 mx-auto gap-3">
            <button class="btn btn-outline-primary rounded-pill col-9 col-sm-6 col-md-4 col-xl-3 rounded-0 localise">Me localiser sur la map</button>
            <p class="mb-1 m-sm-0 adress"></p>
            <div class="d-none loading-icon d-flex justify-content-center">
                <div class="spinner"></div>
            </div>
        </div>
        <div id="map" class="container-fluid mx-auto p-0 mt-3 all fade-up"></div>
    </div>
    <script type="module" src="<?= $config['url'] ?>/script/map_all.js"></script>
    <?php require_once "../includes/footer.php" ?>
</body>

</html>