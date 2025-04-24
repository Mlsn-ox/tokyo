<?php 
function handleImageUpload($title, $articleId, $pdo) {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $fileName = $_FILES["image"]["name"]; 
    $tmpName = $_FILES["image"]["tmp_name"]; 
    $fileSize = $_FILES["image"]["size"]; 
    if (!file_exists($tmpName) || $fileSize > 10485760) {
        throw new Exception("img_error");
    }
    if (!getimagesize($tmpName)) {
        throw new Exception("img_error");
    }
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'aviff', 'tiff'];
    if (!in_array($ext, $allowed)) {
        throw new Exception("img_wrong_ext");
    }
    $safeTitle = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title);
    $newName = $safeTitle . '_' . uniqid() . '.' . $ext;
    $uploadDir = "../assets/img_articles/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
        throw new Exception("img_error");
    }
    $sql = "SELECT img_id FROM image WHERE img_fk_art_id = :articleId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->fetch()) {
        $sql = "UPDATE image SET img_name = :img_name WHERE img_fk_art_id = :articleId";
    } else {
        $sql = "INSERT INTO image (img_name, img_fk_art_id) VALUES (:img_name, :articleId)";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':img_name', $newName, PDO::PARAM_STR);
    $stmt->bindValue(':articleId', $articleId, PDO::PARAM_INT);
    $stmt->execute();
    return $newName;
}
?>