<?php
    require_once "../includes/pdo.php";
    session_start();

    try {
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
        $title = htmlspecialchars(ucfirst(trim($_POST['title'])), ENT_QUOTES, 'UTF-8'); // ENT_QUOTES : conversion des " et '
        $content = htmlspecialchars(ucfirst(trim($_POST['content'])), ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
        $author = $_POST['author'];
        $today = date('Y-m-d'); 

        if (!$_POST['author']){
            header("Location: ../view/login.php?message_code=connect_error&status=success");
            exit();
        }

        // Validation du type float
        $lat = filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT);
        $lng = filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT);
        if (!$lat || !$lng) {
            throw new Exception("map_error");
        }

        // Gestion de l'image
        $fileName = $_FILES["image"]["name"]; // Nom de l'image
        $tmpName = $_FILES["image"]["tmp_name"]; // Nom temporaire de l'image
        $tabExtension = explode('.', $fileName); // On coupe au point, renvoie un tableau
        $extension = strtolower(end($tabExtension)); // Prends le dernier index, donc l'extension
        if (!file_exists($tmpName)) {
            throw new Exception("img_error");
        }
        $safeTitle = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title);
        $newName = $title . '.' . $extension;
        $uploadDir = "../assets/img_articles/";
        if (!is_dir($uploadDir)) { // Vérifie si $uploadDir existe et est un dossier
            mkdir($uploadDir, 0777, true); // Si false, créé le dossier
        }
        if (!move_uploaded_file($tmpName, $uploadDir . $newName)) {
            throw new Exception("img_error"); 
        }

        $sql = "INSERT INTO articles (title, category, content, img, user_ide, lat, lng, create_date) 
                VALUES (:title, :category, :content, :image, :user_ide, :lat, :lng, :today)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':image', $newName, PDO::PARAM_STR);
        $stmt->bindValue(':user_ide', $author, PDO::PARAM_INT); 
        $stmt->bindValue(':lat', $lat, PDO::PARAM_STR); // SQL convertit les float en string
        $stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $verif = $stmt->execute();

        if (!$verif) {
            throw new Exception("server_error"); 
        }

        header("Location: ../view/read_user.php?id=" . $_SESSION['id'] . "message_code=article_added&status=success");
        exit();

    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/add_article_form.php?message_code=" . $error_code . "&status=error");
        exit();
    }
