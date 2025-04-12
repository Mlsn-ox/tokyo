
<?php if(!empty($_SESSION['id'])){ ?>
    <script src="../script/session_timeout.js"></script>
<?php } ?>
<footer class="d-flex flex-column justify-content-center p-3">
    <ul class="container list-group list-group-horizontal-md mb-2 p-0">
        <a href="../view/homepage.php" class="list-group-item list-group-item-action text-center">Accueil</a>
        <a href="#" class="list-group-item list-group-item-action text-center">CGU</a>
        <a href="#" class="list-group-item list-group-item-action text-center">Mentions Légales</a>
        <a href="#" class="list-group-item list-group-item-action text-center">Contact</a>
    </ul>
    <div class="container bottom mx-auto mt-sm-1 d-flex flex-column flex-sm-row align-items-center flex-wrap justify-content-between">
        <div class="d-flex flex-column flex-sm-row align-items-center">
            <div class="d-flex align-items-center">
                <img src="../assets/logo_category/Torii-sans.png" alt="Logo TokyoSpot">
                <p class="m-0">©TokyoSpot - 2025 -</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="m-0">&nbsp Conception Mlsn</p>
                <img src="../assets/logo_category/Mlsn-logo.png" alt="Logo Mlsn" class="ps-2">
            </div>
        </div>
        <div class="d-flex align-items-center my-2">
            <div class="container-switcher ms-3">
                <div class="theme-switcher">
                    <input type="radio" id="light-theme" name="themes" checked />
                    <label for="light-theme"><span>☀️</span></label>
                    <input type="radio" id="dark-theme" name="themes" />
                    <label for="dark-theme"><span>🌙</span></label>
                    <span class="slider"></span>
                </div>
            </div>
            <a href="#" class="text-decoration-none to-top ms-3">🔝</a>
        </div>
    </div>
</footer>
</body>
</html>