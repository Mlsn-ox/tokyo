<?php 
    require_once "../config.php";
    $menu = "";
    try {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            throw new Exception("user_not_found");
        }
        $id = intval($_GET["id"]);
        $sql = "SELECT user.*,
                (SELECT COUNT(*) FROM article WHERE art_fk_user_id = user.user_id AND art_status = 'approved') AS total_articles,
                (SELECT COUNT(*) FROM comment WHERE com_fk_user_id = user.user_id) AS total_comments,
                (SELECT COUNT(*) FROM favorite WHERE fav_user_id = user.user_id) AS total_favorites,
                (SELECT MAX(art_created_at) FROM article WHERE art_fk_user_id = user.user_id) AS last_article_date,
                (SELECT MAX(com_posted_at) FROM comment WHERE com_fk_user_id = user.user_id) AS last_comment_date
                FROM user LEFT JOIN favorite ON user.user_id = favorite.fav_user_id WHERE user.user_id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            header("Location: ../view/login.php?message_code=user_not_found&status=error");
            session_destroy();
            exit();
        }
        $age = getAge($user["user_birthdate"]);
        $sql = "SELECT favorite.*, article.art_title, article.art_status, image.img_name FROM `favorite` 
                LEFT JOIN article ON article.art_id = favorite.fav_art_id 
                LEFT JOIN image ON article.art_id = image.img_fk_art_id
                WHERE favorite.fav_user_id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $favoritesAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $favorites = array_filter($favoritesAll, fn($a) => $a['art_status'] === "approved");
        $sql = "SELECT article.art_id, article.art_title, article.art_status, article.art_created_at, image.img_name FROM `article` 
                LEFT JOIN image ON image.img_fk_art_id = article.art_id 
                WHERE article.art_fk_user_id = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $approvedArticles = array_filter($articles, fn($a) => $a['art_status'] === "approved");
        $pendingArticles = array_filter($articles, fn($a) => $a['art_status'] === "pending");
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    }
    if (isset($_SESSION['id']) && $_SESSION['id'] == $user['user_id']) {
        $menu = "profil";
    }
    $token = $_SESSION['token'] ?? "";
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
    <?php require_once "../includes/head.php"; ?>
    <title><?= $user['user_name'] ?> - TokyoSpot</title>
    <meta name="description" content="Profil de <?= $user['user_name'] ?> - TokyoSpot">
</head>
<body>
<?php require_once "../includes/navbar.php";
    //echo "<script>console.log(" . json_encode($user) . ");</script>";
?>
<div class="section container-fluid p-0 mx-auto d-flex flex-column justify-content-center overflow-hidden token petales" 
    data-token="<?= $token ?>">

    <div class="modal fade" id="imgUpdateModal" tabindex="-1" aria-labelledby="imgUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="imgUpdateModalLabel">Choisissez une nouvelle image</h1>
                    <button type="button" class="btn-close btn-close-img" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" class="d-flex flex-wrap justify-content-around img-update">
                    <div class="modal-body avatars d-flex flex-wrap justify-content-evenly gap-3">
                        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="sakura" value="Sakura.png" checked required>
                            <label class="form-check-label" for="sakura">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Sakura.png" alt="temple japonais et fleur de sakura">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="neko" value="Neko.png">
                            <label class="form-check-label" for="neko">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Neko.png" alt="Maneki Neko">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="godzilla" value="Godzilla.png">
                            <label class="form-check-label" for="godzilla">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Godzilla.png" alt="Kawaii Godzilla">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="ramen" value="Ramen.png">
                            <label class="form-check-label" for="ramen">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Ramen.png" alt="Bol de ramen">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="Kanagawa" value="Kanagawa.png">
                            <label class="form-check-label" for="Kanagawa">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Kanagawa.png" alt="Grande vague de Kanagawa">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                        <div class="form-check p-0 img-select">
                            <input class="form-check-input" type="radio" name="profil" id="shiba" value="Shiba.png">
                            <label class="form-check-label" for="shiba">
                                <img class="avatar-canva" src="<?= $config['url'] ?>/assets/img_profil/Shiba.png" alt="Shiba inu">
                                <img class="check" src="<?= $config['url'] ?>/assets/img_profil/check-circle.svg" alt="checked">
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer container-fluid d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-5" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-outline-success rounded-pill px-4 submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userUpdateModal" tabindex="-1" aria-labelledby="userUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="userUpdateModalLabel">
                        Modifier vos informations
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= $config['url'] ?>/controller/update_user_controller.php" class="user-update">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom d'utilisateur</label>
                            <input type="text" name="name" class="form-control form-control" id="name" required pattern="[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-]+"
                                aria-label="Nom d'utilisateur" aria-describedby="nameHelp" value="<?= htmlentities($user["user_name"]) ?>" maxlength="20">
                            <div id="nameHelp" class="form-text">Entre 3 et 20 caractères.</div>
                        </div>
                        <div class="mb-3">
                            <label for="mail" class="form-label">Adresse e-mail</label>
                            <input type="email" name="mail" class="form-control form-control" id="mail" required
                            aria-label="Adresse e-mail" aria-describedby="mailHelp" value="<?= htmlentities($user["user_mail"]) ?>" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="user_psw" class="form-label">Entrer votre mot de passe pour confirmer les modifications</label>
                            <div class="password-wrapper d-flex align-items-center">
                                <input type="password" name="psw" class="form-control password-input" id="password4" maxlength="100" required>
                                <span class="toggle-password fs-5 ms-1 me-2 me-sm-3" data-target="password4">👁️</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-outline-success rounded-pill btn-confirm">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pswUpdateModal" tabindex="-1" aria-labelledby="pswUpdateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="pswUpdateModalLabel">Modifier vos informations</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?= $config['url'] ?>/controller/update_psw_controller.php" class="user-update">
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                        <div class="mb-3">
                            <label for="password1" class="form-label">Votre mot de passe actuel</label>
                            <div class="password-wrapper d-flex align-items-center">
                                <input type="password" name="current" class="form-control password-input" id="password1" required>
                                <span class="toggle-password fs-5 ms-1 me-2 me-sm-3" data-target="password1">👁️</span>
                            </div>
                        </div>
                        <div class="mb-3">
                        <label for="password3" class="form-label">Nouveau mot de passe</label>
                            <div class="password-wrapper d-flex align-items-center">
                                <input type="password" name="psw1" class="form-control password-input verify" id="password2" 
                                maxlength="100" pattern="(?=.*[A-Z])(?=.*\d).{7,}" required>
                                <span class="toggle-password fs-5 ms-1 me-2 me-sm-3" data-target="password2">👁️</span>
                            </div>
                            <div id="passwordHelp" class="form-text">
                                Le mot de passe doit contenir au moins 7 caractères, une majuscule et un chiffre.
                            </div>
                        </div>
                        <div class="mb-3">
                        <label for="password3" id="pswHelp" class="form-label">Confirmer le nouveau mot de passe</label>
                            <div class="password-wrapper d-flex align-items-center">
                                <input type="password" name="psw2" class="form-control password-input verify" id="password3" 
                                maxlength="100" pattern="(?=.*[A-Z])(?=.*\d).{7,}" required>
                                <span class="toggle-password fs-5 ms-1 me-2 me-sm-3" data-target="password3">👁️</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-outline-success rounded-pill">Sauvegarder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="artDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" 
        tabindex="-1" aria-labelledby="artDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="artDeleteModalLabel">Supprimer le spot ?</h1>
                <button type="button" class="btn-close btn-close-delete" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>⚠️ <span class="text-danger fw-bold">Attention</span> : la suppression est définitive. Êtes-vous sûr de vouloir continuer ?</p>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-outline-danger rounded-pill btn-delete">Supprimer</button>
            </div>
            </div>
        </div>
    </div>

    <div class="container p-xl-5 p-md-4 p-3 rounded-5 p-4 my-3 my-md-4 home fading user">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold active" id="active-tab" data-bs-toggle="tab" href="#active" role="tab"
                aria-controls="active" aria-selected="true">
                    Profil
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold" id="favorites-tab" data-bs-toggle="tab" href="#favorites" role="tab" 
                aria-controls="favorites" aria-selected="false" >
                    Favoris
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link fw-bold" id="publications-tab" data-bs-toggle="tab" href="#publications" role="tab"
                aria-controls="publications" aria-selected="false" >
                    Publications
                </a>
            </li>
        </ul>
        
        <div class="tab-content mt-4" id="myTabContent">

            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <div class="d-flex flex-wrap flex-column flex-sm-row justify-content-around align-items-center">
                    <div class="container-text d-flex flex-column align-items-center order-2 order-md-1 mt-3 mt-md-0">
                        <h1 class="user-name">
                            <?= htmlentities($user["user_name"]) ?>
                        </h1>
                        <?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $user['user_id']){ ?>
                            <p class="text-center border-top m-0 py-2"><?= htmlentities($user["user_mail"]) ?></p>
                            <div class="btn-container d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-outline-primary mb-2" 
                                data-bs-toggle="modal" data-bs-target="#userUpdateModal">
                                    ⚙️ Profil
                                </button>
                                <button type="button" class="btn btn-outline-danger mb-2" 
                                data-bs-toggle="modal" data-bs-target="#pswUpdateModal">
                                    🔐 Mot de passe
                                </button>
                            </div>
                        <?php } else if (!empty($_SESSION['id']) && $_SESSION['role'] == "admin"){ ?>
                            <p class="text-center border-top m-0 py-2"><?= htmlentities($user["user_mail"]) ?></p>
                            <p class="text-center border-top m-0 py-2">
                                <?= htmlentities($user["user_firstname"] . " " 
                                . $user["user_lastname"]) . " (" . $age . " ans)" ?></p>
                        <?php } ?>
                        <ul class="list-group list-group-flush user-info">
                            <li class="list-group-item">
                                Inscrit depuis le : <?= date("d/m/Y", strtotime(htmlentities($user['user_ins']))) ?>
                            </li>
                            <li class="list-group-item">
                                Dernière connexion le : <?= date("d/m/Y", strtotime(htmlentities($user['user_log']))) ?>
                            </li>
                            <li class="list-group-item">
                                Spots publiés : <?= htmlentities($user["total_articles"]) ?>
                            </li>
                            <?php if (intval($user["total_articles"]) > 0){ ?> 
                            <li class="list-group-item">
                                Dernière publication le : <?= date("d/m/Y", strtotime(htmlentities($user['last_article_date']))) ?>
                            </li>
                            <?php } ?>
                            <li class="list-group-item">
                                Commentaires postés : <?= htmlentities($user["total_comments"]) ?>
                            </li>
                            <li class="list-group-item">
                                Spots en favoris : <?= htmlentities($user["total_favorites"]) ?>
                            </li>
                        </ul>
                    </div>
                    <?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $user['user_id']){ ?>
                    <div class="container-img bg-update order-1 order-md-2">
                        <a data-bs-toggle="modal" data-bs-target="#imgUpdateModal" class="offcanvas-link">
                            <img src="<?= $config['url'] ?>/assets/img_profil/<?= htmlentities($user['user_image']) ?>" alt="Photo de profil" 
                            class="rounded-circle fade-rotate profil-pic update-img">
                        </a>
                    </div>
                    <?php } else { ?>
                    <div class="container-img order-1 order-md-2">
                        <img src="<?= $config['url'] ?>/assets/img_profil/<?= htmlentities($user['user_image']) ?>" alt="Photo de profil" 
                        class="rounded-circle fade-rotate profil-pic mt-3">
                    </div>
                    <?php } ?>
                </div>
            </div>
            
            <div class="tab-pane fade" id="favorites" role="tabpanel" aria-labelledby="favorites-tab" >
                <h2 class="my-2">❤️ Spots favoris</h2>
                <div class="card-container d-flex flex-wrap justify-content-center gap-3">
                    <?php if (empty($favorites)) { ?>
                        <p class="text-center fst-italic">Aucun favoris enregistré</p>
                    <?php } else { 
                        foreach ($favorites as $favorite) { ?>
                            <div class="card">
                                <img src="<?= $config['url'] ?>/assets/img_articles/<?= $favorite["img_name"] ?>" 
                                    class="card-img-top mx-auto" alt="Illustration de l'article">
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="card-title text-center"><?= $favorite["art_title"] ?></h5>
                                    <p class="fst-italic">Favoris ajouté le : <?= date("d/m/Y", strtotime(htmlentities($favorite['fav_added_at']))) ?></p>
                                    <a href="<?= $config['url'] ?>/view/read_article.php?id=<?= $favorite['fav_art_id'] ?>" 
                                        class="btn btn-outline-primary rounded-pill mb-2">Voir le spot</a>
                                </div>
                            </div>
                        <?php }
                        } ?>
                </div>
            </div>
            
            <div class="tab-pane fade" id="publications" role="tabpanel" aria-labelledby="publications-tab" >
                <h2 class="my-2">📌 Spots publiés</h2>
                <div class="card-container d-flex flex-wrap justify-content-center gap-3">
                    <?php if (empty($approvedArticles)) { ?>
                        <p class="text-center fst-italic">Aucun spots publiés</p>
                    <?php } else { 
                        foreach ($approvedArticles as $approvedArticle) { ?>
                            <div class="card">
                                <img src="<?= $config['url'] ?>/assets/img_articles/<?= $approvedArticle["img_name"] ?>" 
                                class="card-img-top mx-auto" alt="Illustration de l'article">
                                <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                    <h5 class="card-title text-center"><?= $approvedArticle["art_title"] ?></h5>
                                    <p class="fst-italic">Spot créé le : <?= date("d/m/Y", strtotime(htmlentities($approvedArticle['art_created_at']))) ?></p>
                                    <a href="<?= $config['url'] ?>/view/read_article.php?id=<?= $approvedArticle['art_id'] ?>" 
                                    class="btn btn-outline-primary rounded-pill mb-2">Voir le spot</a>
                                </div>
                            </div>
                        <?php };
                    }?>
                </div>
                <?php if (!empty($_SESSION['id']) && $_SESSION['id'] == $user['user_id']){ ?>
                    <h2 class="my-3">⏰ Spots en attente</h2>
                    <div class="card-container d-flex flex-wrap justify-content-center gap-3">
                        <?php if (empty($pendingArticles)) { ?>
                            <p class="text-center fst-italic">Aucun spots en attente de validation</p>
                        <?php } else { 
                            foreach ($pendingArticles as $pendingArticle) { ?>
                                <div class="card pending-card"  id='art-<?= $pendingArticle["art_id"] ?>'>
                                    <img src="<?= $config['url'] ?>/assets/img_articles/<?= $pendingArticle["img_name"] ?>" 
                                    class="card-img-top mx-auto" alt="Illustration de l'article">
                                    <div class="card-body d-flex flex-column justify-content-between align-items-center">
                                        <h5 class="card-title text-center"><?= $pendingArticle["art_title"] ?></h5>
                                        <p class="fst-italic">Spot créé le : <?= date("d/m/Y", strtotime(htmlentities($pendingArticle['art_created_at']))) ?></p>
                                        <a href="<?= $config['url'] ?>/view/update_article.php?id=<?= $pendingArticle['art_id'] ?>" 
                                        class="btn btn-outline-success rounded-pill mb-2">Modifier le spot</a>
                                        <button type="button" class="btn btn-outline-danger rounded-pill mb-2 btn-delete-modal" data-id="<?= $pendingArticle["art_id"] ?>" 
                                            data-bs-toggle="modal" data-bs-target="#artDeleteModal" 
                                            data-session="<?= $_SESSION["id"] ?>" data-token="<?= $_SESSION["token"] ?>">
                                            Annuler la publication
                                        </button>
                                    </div>
                                </div>
                            <?php };
                        }?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="module" src="<?= $config['url'] ?>/script/read_user.js"></script>
<?php require_once "../includes/footer.php" ?>
</body>
</html>