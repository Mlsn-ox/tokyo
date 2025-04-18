<?php 
    require_once "../config.php";
    $menu = "admin";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title>Admin - TokyoSpot</title>
    <meta name="description" content="Administration - TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php"; 
    if (!isset($_SESSION['id']) || $_SESSION['role'] !== "admin"){
        header("Location: ../view/homepage.php?message_code=unauthorized&status=error");
        exit();
    }
    // echo "<pre>";
    // print_r($news);
    // echo "</pre>";
?>
<div class="section home col-12 col-xl-10 col-lg-11 p-0 mx-auto d-flex token"  data-token="<?= $_SESSION["token"] ?>">
    <div class="container-fluid px-xl-4 p-sm-3 p-1 rounded-5 my-3 fading user">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold active" id="active-tab" data-bs-toggle="tab" href="#active" role="tab"
                aria-controls="active" aria-selected="true">
                    Publications <?= $notif['COUNT(*)'] > 0 
                    ? "<span class='badge rounded bg-danger'>" . $notif['COUNT(*)'] . "</span>" 
                    : "" ?>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold" id="comments-tab" data-bs-toggle="tab" href="#comments" role="tab" 
                aria-controls="commentaires" aria-selected="false" >
                    Commentaires <?= $notifCom['COUNT(*)'] > 0 
                    ? "<span class='badge rounded bg-warning text-black'>" . $notifCom['COUNT(*)'] . "</span>" 
                    : "" ?>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold" id="users-tab" data-bs-toggle="tab" href="#users" role="tab"
                aria-controls="utilisateurs" aria-selected="false" >
                    Utilisateurs
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold" id="stats-tab" data-bs-toggle="tab" href="#stats" role="tab"
                aria-controls="statistiques" aria-selected="false" >
                    Statistiques
                </a>
            </li>
        </ul>
        
        <div class="tab-content mt-4" id="myTabContent">
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <h2 class="mb-3">Spots en attente</h2>
                <div class="d-flex flex-wrap justify-content-around gap-2" id="pending">
                </div>
            </div>
            
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab" >
                <p>plop2</p>
            </div>
            
            <div class="tab-pane fade" id="users" role="tabpanel" aria-labelledby="users-tab" >
                <table class="table table-striped text-center">
                    <thead>
                        <tr class="en-tete table-info">
                            <th>Pseudo</th>
                            <th class="d-none d-lg-table-cell">Nom Complet</th>
                            <th class="d-none d-lg-table-cell">Âge</th>
                            <th class="d-none d-sm-table-cell">Email</th>
                            <th class="d-none d-sm-table-cell">Connexion</th>
                            <th>Statut</th>
                        </tr>
                        <tbody id="table-user"></tbody>
                    </thead>
                </table>
            </div>

            <div class="tab-pane fade" id="stats" role="tabpanel" aria-labelledby="stats-tab" >
                <p>plop4</p>
            </div>
        </div>
    </div>
</div>
<script type="module" src="<?= $config['url'] ?>/script/admin.js"></script>
<?php require_once "../includes/footer.php" ?>
</body>
</html>
