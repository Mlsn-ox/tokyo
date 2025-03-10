<?php
    require_once "../includes/navbar.php";
?>
<div class="section home col-xl-11 col-xxl-10 col-12 p-xl-3 p-md-2 p-1 mx-auto">
    <div class="container-fluid row justify-content-around flex-wrap p-0 mx-auto">
        <h2 class="mt-4">Tous les spots partagés</h2>
        <div id="map" class="my-3 all fade-up"></div>
    </div>
</div>
<script type="module" src="../script/map_all.js"></script>
<?php require_once "../includes/footer.php" ?>