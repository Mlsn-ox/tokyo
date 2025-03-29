<?php
    require_once "../includes/navbar.php";
    try {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            throw new Exception("user_not_found");
        }
        $id = $_GET["id"];
        $sql = "SELECT user.*,
                (SELECT COUNT(*) FROM article WHERE art_fk_user_id = user.user_id) AS total_articles,
                (SELECT COUNT(*) FROM comment WHERE com_fk_user_id = user.user_id) AS total_comments,
                (SELECT COUNT(*) FROM favorite WHERE fav_user_id = user.user_id) AS total_favorites,
                (SELECT MAX(art_created_at) FROM article WHERE art_fk_user_id = user.user_id) AS last_article_date,
                (SELECT MAX(com_posted_at) FROM comment WHERE com_fk_user_id = user.user_id) AS last_comment_date
                FROM user LEFT JOIN favorite ON user.user_id = favorite.fav_user_id WHERE user.user_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            throw new Exception("user_not_found");
        }
        $inscriptionDate = new DateTime($user["user_ins"]);
        $now = new DateTime();
        $diff = $now->diff($inscriptionDate);
        $daysSince = $diff->days;
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    }

?>
<div class="section p-1 col-xxl-7 col-md-10 col-sm-11 col-12 mx-auto d-flex flex-column justify-content-center overflow-hidden petales">
    <div class="container-fluid p-xl-5 p-md-4 p-3 rounded-5 p-4 mb-3 mb-md-4 home fading user">        
        <img src="../assets/img_profil/<?= htmlentities($user['user_image']) ?>" alt="Photo de profil" class="rounded-circle fade-rotate profil-pic">
        <div class="fade-up row justify-content-center">
            <h1 class="user-name"><?= htmlentities($user["user_name"]) ?></h1>
            <?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $user['user_id']){
                echo '<p class="text-center">' . htmlentities($user["user_mail"]) . '</p>';
            } ?>
            <div class="m-5 d-flex justify-content-between">
                <div class="d-flex flex-column align-items-center bg-light-subtle rounded-5 p-3">
                    <p>Nombre de post :</p>
                    <p><?= htmlentities($user["total_articles"]) ?></p>
                </div>
                <div class="d-flex flex-column align-items-center bg-light-subtle rounded-5 p-3">
                    <p>Ancienneté :</p>
                    <p><?= $daysSince ?> jours</p>
                </div>
                <div class="d-flex flex-column align-items-center bg-light-subtle rounded-5 p-3">
                    <p>Dernière connexion :</p>
                    <p>le <?= date("d/m/Y", strtotime(htmlentities($user['user_log']))) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="module" src="../script/read_user.js"></script>
<?php 
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
require_once "../includes/footer.php" ?>