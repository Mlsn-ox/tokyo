<?php
    require_once "../includes/navbar.php";
    if ($_SESSION['role'] !== "admin"){
        header("Location: ../view/homepage.php");
        exit;
    }
    // echo "<pre>";
    // print_r($news);
    // echo "</pre>";
?>

<table class="table table-striped text-center">
        <thead>
            <tr class="en-tete">
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Date de naissance</th>
                <th>Nationalité</th>
                <th>Date d'arrivée</th>
                <th>Tuteur</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include "../controller/pdo.php";
            if (isset($_GET["page"])) {
                $page = $_GET["page"];
            } else {
                header('Location: ../view/index_child.php?page=1');
                exit;
            }
            $sql_children_count = "SELECT COUNT(*) AS total_children FROM child";
            $stmt_children_count = $pdo->query($sql_children_count);
            $children_count = $stmt_children_count->fetch(PDO::FETCH_ASSOC);
            $total = $children_count['total_children'];
            $limitPage = 20;
            $pageTotal = ceil($total / $limitPage);

            $offset = ($page * $limitPage) - $limitPage;

            //$sql = "SELECT * FROM child ORDER BY id_child ASC LIMIT $offset, $limitPage";

            $sql = "SELECT child.*, CONCAT(user.firstname_user, ' ', user.lastname_user) AS fullname_user
                    FROM child
                    LEFT JOIN user ON child.tutor_child = user.id_user
                    GROUP BY child.id_child
                    ORDER BY lastname_child
                    LIMIT $offset, $limitPage";

            //On envoie aucune variable, on récupère seulement les données :
            //utilisation de la méthode query
            $stmt = $pdo->query($sql);
            //Avec fetchAll, je récupère toute la liste des enfants
            $children = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($children as $child) {
            ?>
                <tr class="bg-light-subtle">
                    <td><?= $child["lastname_child"] ?></td>
                    <td><?= $child["firstname_child"] ?></td>
                    <td class=<?= $child["gender_child"] ? "text-danger-emphasis" : "text-primary" ?>><?= $child["gender_child"] ? "♀" : "♂" ?></td>
                    <td><?= $child["birthdate_child"] ?></td>
                    <td><?= $child["nationality_child"] ?></td>
                    <td><?= $child["arrivaldate_child"] ?></td>
                    <td class=<?= $child["fullname_user"] ? "" : "text-danger" ?>>
                        <?= $child["fullname_user"] ? $child["fullname_user"] : "Aucun tuteur" ?>
                    </td>
                    <td>
                        <span data-toggle="tooltip" title="informations">
                            <a class="text-info px-3"
                                href="view/read_child.php?id=<?= $child['id_child'] ?>&token=<?= $_SESSION['token'] ?>">
                                <i class="bi bi-zoom-in"></i>
                            </a>
                        </span>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] >= Role::SECRETARY->value) {
                        ?>
                            <span data-toggle="tooltip" title="modifier">
                                <a class="text-warning px-3"
                                    href="view/update_child_form.php?id=<?= $child['id_child'] ?>">
                                    <i class="bi bi-pen"></i>
                                </a>
                            </span>
                            <span data-toggle="tooltip" title="supprimer">
                                <a class="text-danger px-3 delete_btn"
                                    href="controller/delete_child_controller.php?id=<?= $child['id_child'] ?>&token=<?= $_SESSION['token'] ?>"
                                    data-bs-toggle="modal"
                                    data-bs-target="#validation_delete">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php
            }
            ob_end_flush();
            ?>
        </tbody>
    </table>
<div class="section col-xxl-9 col-md-11 col-12 mx-auto p-lg-4 p-1 fading">
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
                    <span><?= $usersStat[0]["total_users"] ?></span>
                </li>       
                <li class="list-group-item">
                    <span>Plus grand nombre de post :</span>&nbsp;&nbsp;
                    <span><?= $usersStat[0]["top_poster"] . " (" . $users[0]["total_articles"] . " posts)" ?></span>
                </li>       
                <li class="list-group-item">
                    <span>Dernier inscrit :</span>&nbsp;&nbsp;
                    <span><?= $usersStat[0]["newest_user"] ?> (<?= date("d-m-Y", strtotime($usersStat[0]['newest_user_date'])) ?>)</span>
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
        <?php } else { ?>
            <p class="ms-2 mb-0 fs-5">Erreur de chargement des données</p>
        <?php } ?>
    </div>
    <div class="container-fluid p-xl-5 p-md-3 p-1 rounded-5 my-3 my-md-4 mx-auto fade-up home">
        <?php if ($articlesPending) { ?>
            <a class="btn btn-outline-primary btn-lg ms-3" data-bs-toggle="collapse" href="#collapseArticles" role="button" aria-expanded="false" aria-controls="collapseArticles">
                Articles en attente ⤵
            </a>
            <div class="container-fluid collapse mt-3 row justify-content-around flex-wrap show" id="collapseArticles">
                <?php foreach ($articlesPending as $article) { ?>
                    <div class="card admin-card p-1 pb-sm-3 pb-2 p-sm-2 g-1 d-flex flex-column justify-content-between fade-rotate">
                        <img src="../assets/img_articles/<?= $article["img"] ?>" class="card-img-top" alt="illustration spot">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars_decode($article["title"]) ?></h5>
                            <p class="card-text mb-1"><?= htmlspecialchars_decode($article["category"]) ?></p>
                            <a href="./read_user.php?id=<?= $article['ide'] ?>" class="card-text">Par <?= htmlspecialchars_decode($article["author"]) ?></a>
                        </div>
                        <div class="buttons d-flex justify-content-around ">
                            <a href="./read_article.php?id=<?= $article['id'] ?>" class="btn btn-outline-primary">Inspecter</a>
                            <a href="../controller/moderation.php?id=<?= $article['id'] ?>&action=rejected&token=<?= $_SESSION['token'] ?>" class="btn btn-danger">Refuser</a>
                        </div>
                    </div>
                <?php }?>
            </div>
        <?php } else { ?>
            <p class="ms-2 mb-0 fs-5">Aucun article à modérer</p>
        <?php } ?>
    </div>
    <div class="container-fluid p-xl-5 p-md-3 p-1 rounded-5 my-3 my-md-4 mx-auto fade-up home">
        <?php if ($users) { ?>
            <a class="btn btn-outline-primary btn-lg ms-2" data-bs-toggle="collapse" href="#collapseUsers" role="button" aria-expanded="false" aria-controls="collapseArticles">
                Liste des utlisateurs ⤵
            </a>
            <table class="table table-striped collapse fade-up mt-3" id="collapseUsers">
                <thead>
                    <tr>
                        <th scope="col" class="text-primary">Nom</th>
                        <th scope="col" class="text-primary">Mail</th>
                        <th scope="col" class="text-primary">Newsletter</th>
                        <th scope="col" class="text-primary">Date d'inscription</th>
                        <th scope="col" class="text-primary">Dernière connexion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>
                        <tr>
                            <th scope="row">
                                <a href="./read_user.php?id=<?= $user["id"] ?>" class="text-decoration-none text-danger"><?= $user["name"] ?></a>
                            </th>
                            <td><?= $user["mail"] ?></td>
                            <td><?= $user["newsletter"] ? "✅" : "❌"?></td>
                            <td><?= date("d-m-Y", strtotime($user['user_ins'])) ?></td>
                            <td><?= $user['last_co'] ? date("d-m-Y", strtotime($user['last_co'])) : "/" ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p class="ms-2 mb-0 fs-5">Erreur de chargement des données</p>
        <?php } ?>
    </div>
</div>
<script type="module" src="../script/admin.js"></script>
<?php require_once "../includes/footer.php" ?>
