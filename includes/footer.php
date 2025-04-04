<div class="container-switcher">
    <div class="theme-switcher">
        <input type="radio" id="light-theme" name="themes" checked />
        <label for="light-theme"><span>☀️</span></label>
        <input type="radio" id="dark-theme" name="themes" />
        <label for="dark-theme"><span>🌙</span></label>
        <span class="slider"></span>
    </div>
</div>
<?php if(!empty($_SESSION['id'])){ ?>
    <script src="../script/session_timeout.js"></script>
<?php } ?>
<footer class="d-flex justify-content-sm-center p-3">
    <img src="../assets/logo_category/Torii-sans.png" alt="Logo TokyoSpot">
    <p class="m-0">©TokyoSpot - 2025</p>
</footer>
</body>
</html>