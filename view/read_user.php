<?php
    require_once "../includes/message.php";
    require_once "../includes/navbar.php";
    try {
        if (!isset($_GET["id"])) {
            throw new Exception("user_not_find"); 
        }
        $id = $_GET["id"];
        $sql = "SELECT users.*, COUNT(articles.id) AS total_articles FROM users
                LEFT JOIN articles ON users.id = articles.user_ide WHERE users.id = $id";
        $stmt = $pdo->query($sql); 
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            throw new Exception("user_not_find");
        }
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    }
?>
<div class="section home col-xxl-7 col-md-10 col-12 mx-auto px-5 py-4">
    <div class="container user-container d-flex flex-column align-items-center">
        <div class="container-fluid text-center fade-rotate">
            <img src="../assets/img_profil/<?= $user['img'] ?>" alt="Photo de profil" class="rounded-circle" style="width: 30%;">
        </div>
        <div class="fade-up">
            <h1 class="text-center my-4"><?= htmlspecialchars_decode($user["name"]) ?></h1>
            <ul class="list-group list-group-flush px-5 rounded">
                <li class="list-group-item bg-light-subtle">
                    Mail : <?= htmlentities($user["mail"]) ?>
                </li>
                <li class="list-group-item bg-light-subtle">
                    Nombre de post : <?= htmlentities($user["total_articles"]) ?>
                </li>
                <li class='list-group-item bg-light-subtle'>
                    Newsletter : <?= $user['newsletter'] ? "Abonné" : "Non abonné" ?>
                </li>
                <li class='list-group-item bg-light-subtle'>
                    Dernière connexion : <?= date("d/m/Y", strtotime($user['last_co'])) ?>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php require_once "../includes/footer.php" ?>