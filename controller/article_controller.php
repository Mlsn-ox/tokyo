<?php
require_once "../config.php";
try {
    if (
        $_SESSION['blocked'] ||
        !isConnected() ||
        !isTokenValid($_POST['token'])
    ) {
        session_destroy();
        header("Location: ../view/login.php?message_code=connect_error&status=error");
        exit();
    }
    if (!isOwner($_POST['author']) && !isAdmin()) {
        header("Location: ../view/homepage.php?message_code=connect_error&status=error");
        exit();
    }
    $author = filter_var($_POST['author'], FILTER_VALIDATE_INT);
    $role = $_SESSION['role'];
    $today = date('Y-m-d');
    $articleId = !empty($_POST['art_id']) ? filter_var($_POST['art_id'], FILTER_VALIDATE_INT) : null;
    $isUpdate = $articleId !== null; // True si $articleId == Int
    // Nettoyage des données
    $title = ucfirst(trim($_POST['title']));
    $content = ucfirst(trim($_POST['content']));
    $category = filter_var($_POST['category'], FILTER_VALIDATE_INT);
    $lat = filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT);
    $lng = filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT);
    // Remplissage des variables de session pour réinjection si nécessaire
    $_SESSION["temp_title"] = $title;
    $_SESSION["temp_cat"] = $category;
    $_SESSION["temp_content"] = $content;
    $_SESSION["temp_lat"] = $lat;
    $_SESSION["temp_lng"] = $lng;
    if (
        empty($title) || empty($content) ||
        $category === false || $lat === false || $lng === false
    ) {
        throw new Exception("form_error");
    }
    if (
        empty($title) || empty($content) || !$category || !$lat || !$lng ||
        $lat < 35.52 || $lat > 35.8 || $lng < 139.46 || $lng > 139.91
    ) {
        throw new Exception("map_error");
    }
    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {
        $fileName = $_FILES["image"]["name"];
        $tmpName = $_FILES["image"]["tmp_name"];
        $fileSize = $_FILES["image"]["size"];
        if (!file_exists($tmpName) || $fileSize > 10485760 || !getimagesize($tmpName)) {
            throw new Exception("img_error");
        }
        // Vérification du type MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpName);
        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/tiff'];
        if (!in_array($mime, $allowedMime)) {
            throw new Exception("img_wrong_ext");
        }
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/avif' => 'avif',
            'image/tiff' => 'tiff'
        ];
        $ext = $mimeToExt[$mime] ?? null;
        if (!$ext) {
            throw new Exception("img_wrong_ext");
        }
    }
    // Vérifie le mode update ou add
    if ($isUpdate) {
        $sql = "SELECT art_fk_user_id FROM article WHERE art_id = :articleId";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
        $stmt->execute();
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$article) {
            throw new Exception("article_not_found");
        }
        // Requête UPDATE
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
        $articleId = $pdo->lastInsertId(); // Récupère l'ID si c'était Add
    }
    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] === UPLOAD_ERR_OK
    ) {
        $safeTitle = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title);
        $newName = $safeTitle . '_' . uniqid() . '.' . $ext;
        $uploadDir = "../assets/img_articles/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
            throw new Exception("img_error");
        }
        $sql = "SELECT img_id FROM image WHERE img_fk_art_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$articleId]);
        if ($stmt->fetch()) {
            $sql = "UPDATE image SET img_name = ? WHERE img_fk_art_id = ?";
        } else {
            $sql = "INSERT INTO image (img_name, img_fk_art_id) VALUES (?, ?)";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$newName, $articleId]);
    }
    unset($_SESSION["temp_title"], $_SESSION["temp_content"], $_SESSION["temp_cat"], $_SESSION["temp_lat"], $_SESSION["temp_lng"]);
    $message = $isUpdate ? "article_updated" : "article_added";
    header("Location: ../view/read_user.php?id=$author&message_code=$message&status=success");
    exit();
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    $redirectId = $articleId ?? 'new';
    header("Location: ../view/" . ($isUpdate ? "update_article.php?id=$redirectId&" : "add_article.php?") . "message_code=$error_code&status=error");
    exit();
}
