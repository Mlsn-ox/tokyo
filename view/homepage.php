<?php
    require_once "../includes/navbar.php";
    $notif['COUNT(*)'] > 0 ? $badge = "<span class='badge text-bg-danger rounded'>" . $notif['COUNT(*)'] . "</span>" : $badge = "";
?>
<div class="section p-1 col-xxl-8 col-md-10 col-12 mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 my-3 my-md-4 home fading">
    <?php if (empty($_SESSION['id'])){ ?>
        <h1 class="text-center mb-3">Bienvenue chez TokyoSpot !</h1>
        <p class="d-none d-sm-block">
            Tokyo est une ville aux mille visages, entre tradition et modernité, temples centenaires et gratte-ciels futuristes,
            ruelles secrètes et quartiers animés. Ici, chaque coin de rue réserve une surprise,
            et c’est justement ce que nous voulons partager avec vous !
        </p>
        <p class="d-none d-sm-block">
            TokyoSpot est un blog collaboratif où vous êtes les guides.
        </p>
        <p>
            Que vous ayez découvert un café caché, un point de vue spectaculaire, une boutique insolite ou un festival local,
            cet espace est fait pour échanger vos trouvailles et explorer Tokyo autrement. Que vous soyez de passage ou installé depuis longtemps,
            rejoignez la communauté et partagez vos expériences, bons plans et coups de cœur.
            Ensemble, faisons de TokyoSpot la référence pour découvrir Tokyo sous un nouvel angle !
        </p>
        <p>
            Une découverte à partager ? 📍 <a href="./login.php">Venez partager avec nous votre trouvaille</a> et faites voyager les autres à travers votre regard !
        </p>
        <?php } else { ?> 
            <h2 class="mb-3 text-center fw-normal">Bienvenue <?= $_SESSION['name'] ?>, qu'allez-vous faire aujourd'hui ?</h2>
            <div class="container d-flex flex-wrap row-gap-2 column-gap-2 justify-content-center">
                <?= $_SESSION['role'] === "admin" ?  '<a class="btn btn-outline-danger" href="../view/admin.php">Espace modérateur ' . $badge . '</a>' : "" ?>
                <a class="btn btn-outline-primary" href="../view/index_articles.php">Explorer les spots</a>
                <a class="btn btn-outline-primary" href="../view/add_article_form.php">Partager un nouveau lieu</a>
                <a class="btn btn-outline-primary" href="../view/read_user.php?id=<?= $_SESSION['id'] ?>">Inspecter votre profil</a>
            </div>
        <?php } ?>
    </div>
    <?php
        $i = 0;
        try {
            $sql = "SELECT img_name, img_fk_art_id FROM `image`
            LEFT JOIN article ON article.art_id = image.img_fk_art_id
            WHERE article.art_status = 'approved' ORDER BY RAND()";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        };
        if ($articles){ 
            ?>
            <div class="container-fluid p-xl-5 p-md-3 p-2 rounded-5 home fading carousel-container">
                <h1 class="text-center mb-3">Explorer nos spots :</h1>
                <div id="carouselExampleInterval" class="carousel slide carousel-fade mx-auto" data-bs-ride="carousel">
                    <div class="carousel-inner rounded-5">
                        <?php foreach ($articles as $article) { ?>
                            <div class="carousel-item <?= $i === 0 ? "active" : "" ?>" data-bs-interval="4000" >
                                <a href="read_article.php?id=<?= $article['img_fk_art_id'] ?>">
                                    <img src="../assets/img_articles/<?= $article["img_name"] ?>" class="d-block w-100 carousel-img" alt="Photo d'article">
                                </a>
                            </div>
                        <?php $i === 0 ? $i++ : $i = 1 ;
                    } ?> 
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        <?php } ?>

    <div class="container-fluid p-xl-5 p-md-3 py-2 my-3 my-md-4 rounded-5 home direct fading">
        <h1 class="text-center">En direct de Tokyo : carrefour Shibuya</h1>
        <div class="d-flex justify-content-center align-items-center mb-2 flex-wrap">
            <div id="heure-tokyo" class="d-flex align-items-center justify-content-center mx-3 mx-sm-auto col-lg-2"></div>
            <div id="meteo-tokyo" class="d-flex align-items-center justify-content-center mx-3 mx-sm-auto text-center flex-wrap"></div>
        </div>
        <div class="iframe-container">
            <iframe class="rounded-4" src="https://www.youtube.com/embed/tujkoXI8rWM?autoplay=1&mute=1" frameborder="0" allow="autoplay" allowfullscreen></iframe>
        </div>
    </div>
</div>
<script type="module" src="../script/homepage.js"></script>
<?php require_once "../includes/footer.php" ?>