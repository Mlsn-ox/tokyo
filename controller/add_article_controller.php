<?php
    require_once "../includes/pdo.php";
    session_start();

    try {
        if ($_SESSION['token'] !== $_POST['token'] ||
            !$_POST['author'] || 
            $_POST['author'] != $_SESSION['id']) {
            session_destroy();
            header("Location: ../view/login.php?message_code=connect_error&status=error");
            exit();
        }
        if (
            empty($_POST['title']) ||
            empty($_POST['content']) ||
            empty($_POST['lat']) ||
            empty($_POST['category']) ||
            !isset($_FILES["image"]) ||
            ($_FILES["image"]["error"] !== UPLOAD_ERR_OK)
        ) {
            throw new Exception("form_error");
        }
        // Nettoyage des données
        $title = htmlspecialchars(ucfirst(trim($_POST['title'])), ENT_QUOTES, 'UTF-8'); // ENT_QUOTES : conversion des ", ', &, <, > ;
        $content = htmlspecialchars(ucfirst(trim($_POST['content'])), ENT_QUOTES, 'UTF-8');
        $category = filter_var($_POST['category'], FILTER_VALIDATE_INT);
        $author = filter_var($_POST['author'], FILTER_VALIDATE_INT);
        $today = date('Y-m-d'); // Date du jour
        $lat = filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT);
        $lng = filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT);
        if (!$lat || !$lng || $lat < 35.52 || $lat > 35.8 || $lng < 139.46 || $lng > 139.91) {
            throw new Exception("map_error");
        }
        // Gestion de l'image
        $fileName = $_FILES["image"]["name"]; // Nom de l'image
        $tmpName = $_FILES["image"]["tmp_name"]; // Nom temporaire de l'image
        $fileSize = $_FILES["image"]["size"]; // Taille du fichier
        if (!file_exists($tmpName)) {
            throw new Exception("img_error");
        }
        if ($fileSize > 10485760) { // Taille max : 10 Mo = 10 * 1024 * 1024 octets
            throw new Exception("img_too_big");
        }
        $tabExtension = explode('.', $fileName); // On coupe au point, renvoie un tableau
        $extension = strtolower(end($tabExtension)); // Prends le dernier index, donc l'extension
        $authorizedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'aviff', 'tiff']; // Liste des extensions autorisées
        if (!in_array($extension, $authorizedExtensions)) {
            throw new Exception("img_wrong_ext");
        }
        $safeTitle = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title); // Sécurisation nom image
        $newName = $safeTitle . '_' . uniqid() . '.' . $extension;
        $uploadDir = "../assets/img_articles/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // crée le dossier récursivement
        }
        if (!is_writable($uploadDir)) {
            throw new Exception("upload_dir_not_writable");
        }
        if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
            throw new Exception("img_error");
        }
        $sql = "INSERT INTO article (art_created_at, art_title, art_content, art_lat, art_lng, art_fk_cat_id, art_fk_user_id) 
                VALUES (:today, :title, :content, :lat, :lng, :category, :author)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':lat', $lat, PDO::PARAM_STR); // SQL convertit les float en string
        $stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_INT);
        $stmt->bindValue(':author', $author, PDO::PARAM_INT); 
        $verif = $stmt->execute();
        if (!$verif) {
            throw new Exception("server_error");
        }
        $lastArtId = $pdo->lastInsertId();
        $sqlImg = "INSERT INTO image (img_name, img_fk_art_id) VALUES (:img_name, :img_fk_art_id)";
        $stmtImg = $pdo->prepare($sqlImg);
        $stmtImg->bindParam(':img_name', $newName);
        $stmtImg->bindParam(':img_fk_art_id', $lastArtId, PDO::PARAM_INT);
        $insert = $stmtImg->execute();
        if (!$insert){
            throw new Exception("server_error");
        }
        header("Location: ../view/homepage.php?message_code=article_added&status=success");
        exit();
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/add_article_form.php?message_code=" . $error_code . "&status=error");
        exit();
    }
