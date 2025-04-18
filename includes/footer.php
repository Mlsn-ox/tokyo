<footer class="d-flex flex-column justify-content-center p-3">
    <ul class="container-xl list-group list-group-horizontal-md mb-2 p-0">
        <a href="<?= $config['url'] ?>/view/homepage.php" 
            class="list-group-item list-group-item-action text-center <?= $menu === "accueil" ? "active-footer" : '' ?>">
            Accueil
        </a>
        <a href="<?= $config['url'] ?>/papers/cgu.php" 
            class="list-group-item list-group-item-action text-center <?= $menu === "cgu" ? "active-footer" : '' ?>">
            CGU
        </a>
        <a href="<?= $config['url'] ?>/papers/confidentialite.php" 
            class="list-group-item list-group-item-action text-center <?= $menu === "confidentialite" ? "active-footer" : '' ?>">
            Confidentialité
        </a>
        <a href="<?= $config['url'] ?>/papers/mentions_legales.php" 
            class="list-group-item list-group-item-action text-center <?= $menu === "mentions_legales" ? "active-footer" : '' ?>">
            Mentions Légales
        </a>
        <a href="<?= $config['url'] ?>/papers/contact_form.php" 
            class="list-group-item list-group-item-action text-center <?= $menu === "contact" ? "active-footer" : '' ?>">
            Contact
        </a>
    </ul>
    <div class="container bottom mx-auto d-flex flex-column flex-sm-row align-items-center flex-wrap justify-content-between">
        <div class="d-flex flex-column flex-sm-row align-items-center">
            <div class="d-flex align-items-center">
                <img src="<?= $config['url'] ?>/assets/logo_category/Torii-sans.png" alt="Logo TokyoSpot">
                <p class="m-0">© <?= $config['title'] ?> - <?= date('Y') ?> -</p>
            </div>
            <div class="d-flex align-items-center">
                <p class="m-0">&nbsp Conception Mlsn</p>
                <img src="<?= $config['url'] ?>/assets/logo_category/Mlsn-logo.png" alt="Logo Mlsn" class="ps-2">
            </div>
        </div>
        <div class="d-flex align-items-center">
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