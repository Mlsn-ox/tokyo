<?php
session_start();
require_once "../includes/pdo.php";
require_once "../controller/article_img_controller.php";
try {
    if (
        empty($_SESSION['id']) ||
        empty($_SESSION['token']) ||
        $_SESSION['bloked'] ||
        $_POST['token'] !== $_SESSION['token'] ||
        $_POST['author'] != $_SESSION['id']
    ) {
        session_destroy();
        header("Location: ../view/login.php?message_code=connect_error&status=error");
        exit();
    }
    $author = $_SESSION['id'];
    $role = $_SESSION['role'];
    $today = date('Y-m-d');
    $articleId = !empty($_POST['art_id']) ? filter_var($_POST['art_id'], FILTER_VALIDATE_INT) : null;
    $isUpdate = $articleId !== null;
    // Nettoyage des données
    $title = htmlspecialchars(ucfirst(trim($_POST['title'])), ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars(ucfirst(trim($_POST['content'])), ENT_QUOTES, 'UTF-8');
    $category = filter_var($_POST['category'], FILTER_VALIDATE_INT);
    $lat = filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT);
    $lng = filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT);
    if (
        empty($title) || empty($content) || !$category || !$lat || !$lng ||
        $lat < 35.52 || $lat > 35.8 || $lng < 139.46 || $lng > 139.91
    ) {
        throw new Exception("form_error");
    }
    // Vérifie que l’article existe et appartient au bon utilisateur (sauf si admin)
    if ($isUpdate) {
        $sql = "SELECT art_fk_user_id FROM article WHERE art_id = :articleId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article) {
            throw new Exception("article_not_found");
        }
        if ($role === 'client' && $article['art_fk_user_id'] != $author) {
            session_destroy();
            header("Location: ../view/homepage.php?message_code=unauthorized&status=error");
            exit();
        }
        $sql = "UPDATE article 
                SET art_created_at = :today, art_title = :title, art_content = :content, art_lat = :lat, art_lng = :lng, 
                    art_fk_cat_id = :category, art_fk_user_id = :author
                WHERE art_id = :articleId";
    } else {
        // Requête INSERT
        $sql = "INSERT INTO article (art_created_at, art_title, art_content, art_lat, art_lng, art_fk_cat_id, art_fk_user_id)
                VALUES (:today, :title, :content, :lat, :lng, :category, :author)";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':today', $today, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
    $stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
    $stmt->bindValue(':author', $author, PDO::PARAM_INT);
    if ($isUpdate) {
        $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
    }
    $success = $stmt->execute();
    if (!$success) {
        throw new Exception("server_error");
    }
    if (!$isUpdate) {
        $articleId = $pdo->lastInsertId(); // Récupère l'ID si c'était un insert
    }
    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK 
    ) {
        handleImageUpload($title, $articleId, $pdo);
    }
    $message = $isUpdate ? "article_updated" : "article_added";
    header("Location: ../view/read_user.php?id=$author&message_code=$message&status=success");
    exit();
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    $redirectId = $articleId ?? 'new';
    header("Location: ../view/" . ($isUpdate ? "update_article_form.php?id=$redirectId" : "add_article_form.php") . "&message_code=$error_code&status=error");
    exit();
}
