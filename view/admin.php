<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    include "../includes/message.php";

    if (!isset($_SESSION['role']) || $_SESSION['role'] == 0){
        header("Location: ../view/homepage.php");
        exit;
    }
    try {
        $sqlNews = "SELECT COUNT(*) as total_abo, 
                    (SELECT COUNT(*) FROM newsletters WHERE newsletters.user_ide IS NOT NULL) AS users_abo 
                    FROM newsletters";
        $stmtNews = $pdo->prepare($sqlNews);
        $stmtNews->execute();
        $news = $stmtNews->fetchAll(PDO::FETCH_ASSOC);

        $sqlUser = "SELECT users.name, users.mail, users.newsletter, users.start_date, 
					MAX(users.start_date) AS newest_user, 
                    COUNT(articles.id) AS total_articles,
                    (SELECT COUNT(users.id) FROM users) AS total_users
                    FROM users LEFT JOIN articles ON users.id = articles.user_ide 
                    GROUP BY users.id ORDER BY total_articles DESC ;";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute();
        $users = $stmtUser->fetchAll(PDO::FETCH_ASSOC);

        $sqlPending = "SELECT articles.*, users.id AS ide, users.name AS author FROM articles 
                        LEFT JOIN users ON articles.user_ide = users.id WHERE articles.status = 'pending' ;";
        $stmtPending = $pdo->prepare($sqlPending);
        $stmtPending->execute();
        $articlesPending = $stmtPending->fetchAll(PDO::FETCH_ASSOC);

        $sqlStat = "SELECT 
                    (SELECT COUNT(*) FROM articles WHERE status = 'approved') AS total_approved,
                    (SELECT COUNT(*) FROM articles WHERE status = 'pending') AS total_pending,
                    (SELECT COUNT(*) FROM articles WHERE status = 'rejected') AS total_rejected, 
                    articles.category, COUNT(*) AS total_by_category,
                    (SELECT MAX(create_date) FROM articles WHERE status = 'approved') AS latest_article_date
                    FROM articles WHERE status = 'approved' GROUP BY articles.category ;";
        $stmtStat = $pdo->prepare($sqlStat);
        $stmtStat->execute();
        $articlesStat= $stmtStat->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    // echo "<pre>";
    // print_r($news);
    // echo "</pre>";
?>
<div class="section col-xxl-9 col-md-11 col-12 mx-auto p-lg-4 p-1 fade">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 home my-3 my-md-4 fade-up hero d-flex flex-wrap">
        <?php if ($articlesStat && $users){ ?>
            <ul class="container-fluid col-12 col-xl-5 list-group list-group-flush list-stat">
                <li class="list-group-item">
                    <span>Spots approuvés :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="text-success fw-bolder"><?= $articlesStat[0]["total_approved"] ?></span>
                </li>
                <li class="list-group-item">
                    <span>Spots en attente :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="text-primary fw-bolder"><?= $articlesStat[0]["total_pending"] ?></span>
                </li>
                <li class="list-group-item">
                    <span>Spots rejetés :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="text-danger fw-bolder"><?= $articlesStat[0]["total_rejected"] ?></span>
                </li>
                <li class="list-group-item">
                    <span>Dernier spot posté le :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><?= $articlesStat[0]["latest_article_date"] ?></span>
                </li>
                <li class="list-group-item">
                    <span>Nombre d'inscrits :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><?= $users[0]["total_users"] ?></span>
                </li>       
                <li class="list-group-item">
                    <span>Plus grand nombre de post :</span>&nbsp;&nbsp;
                    <span><?= $users[0]["name"] . " (" . $users[0]["total_articles"] . " posts)" ?></span>
                </li>       
                <li class="list-group-item">
                    <span>Dernier inscrit le :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><?= $users[0]["newest_user"] ?></span>
                </li>            
            </ul>
            <ul class="container-fluid col-12 col-xl-5 list-group list-group-flush list-stat">
                <li class="list-group-item">
                    <span>Abonnés newsletters :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><?= $news[0]["total_abo"] ?></span>
                </li>   
                <li class="list-group-item">&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>Dont utilisateurs :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                    <span><?= $news[0]["users_abo"] ?></span>
                </li>   
                <li class="list-group-item">
                    <span>Spots actuels dans chaque catégorie :</span>
                </li>
                <?php foreach ($articlesStat as $stat) { ?>
                    <li class="list-group-item">&nbsp;&nbsp;&nbsp;&nbsp;
                        <span><?= ucfirst($stat['category']) ?> :</span>&nbsp;&nbsp;&nbsp;&nbsp;
                        <span><?= $stat['total_by_category'] ?></span>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { 
            echo "<p> Erreur de chargement des données </p>";
        };?>
    </div>
    <?php if ($articlesPending) { ?>
    <div class="container-fluid p-xl-5 p-2 rounded-5 my-3 my-md-4 mx-auto row justify-content-around flex-wrap fade-up home" id="articles">
        <?php foreach ($articlesPending as $article) { ?>
                <div class="card admin-card p-1 pb-sm-3 pb-2 p-sm-2 g-1 d-flex flex-column justify-content-between fade-rotate">
                    <img src="../assets/img_articles/<?= $article["img"] ?>" class="card-img-top" alt="illustration spot">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlentities(ucfirst($article["title"])) ?></h5>
                        <p class="card-text mb-1"><?= htmlentities($article["category"]) ?></p>
                        <a href="./read_user.php?id=<?= $article['ide'] ?>" class="card-text">Par <?= htmlentities($article["author"]) ?></a>
                    </div>
                    <div class="buttons d-flex justify-content-around ">
                        <a href="./read_article.php?id=<?= $article['id'] ?>" class="btn btn-outline-primary">Inspecter</a>
                        <a href="../controller/moderation.php?id=<?= $article['id'] ?>&action=rejected&token=<?= $_SESSION['token'] ?>" class="btn btn-danger">Refuser</a>
                    </div>
                </div>
            <?php }
        } ?>
    </div>
</div>

<script src="../script/admin.js"></script>
<?php require_once "../includes/footer.php" ?>
