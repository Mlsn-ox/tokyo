<?php
    require_once "../includes/pdo.php";
    require_once "../includes/navbar.php";
    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        $sql = "SELECT users.*, COUNT(articles.id) AS total_articles, 
                    CASE 
                        WHEN newsletters.newsletter_mail IS NOT NULL THEN 1 
                        ELSE 0 
                    END AS is_subscribed
                    FROM users
                    LEFT JOIN articles ON users.user_id = articles.user_ide
                    LEFT JOIN newsletters ON users.user_mail = newsletters.newsletter_mail
                    WHERE users.user_id = $id";
        $stmt = $pdo->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>
<div class="section home col-xxl-7 col-md-10 col-12 mx-auto px-5 py-4">
    <div class="container user-container d-flex flex-column align-items-center">
        <div class="container-fluid text-center fade-rotate">
            <img src="../assets/img_profil/<?= $user['user_img'] ?>" alt="Photo de profil" class="rounded-circle" style="width: 30%;">
        </div>
        <div class="fade-up">
            <h1 class="text-center my-4"><?= htmlentities($user["user_name"]) ?></h1>
            <ul class="list-group list-group-flush px-5 rounded">
                <li class="list-group-item bg-light-subtle">
                    Mail : <?= htmlentities($user["user_mail"]) ?>
                </li>
                <li class="list-group-item bg-light-subtle">
                    Nombre de post : <?= htmlentities($user["total_articles"]) ?>
                </li>
                <li class='list-group-item bg-light-subtle'>
                    Newsletter : <?= $user['is_subscribed'] ? "Abonné" : "Non abonné" ?>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php require_once "../includes/footer.php" ?>