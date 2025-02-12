<?php
include "pdo.php";
if (
    !empty($_POST['title']) && 
    !empty($_POST['content']) && 
    !empty($_POST['lat']) && 
    isset($_FILES["image"]) && 
    ($_FILES["image"]["error"] === UPLOAD_ERR_OK)
) {
     // Nettoyage des données
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8'); // ENT_QUOTES : conversion des " et '
    $content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');
    $author = htmlspecialchars($_POST['author'], ENT_QUOTES, 'UTF-8');
    // Validation du type float
    $lat = filter_var($_POST['lat'], FILTER_VALIDATE_FLOAT);
    $lng = filter_var($_POST['lng'], FILTER_VALIDATE_FLOAT);
    if ($lat === false || $lng === false) {
        header("Location: ../view/add_article_form.php?message=Coordonnées invalides&status=error");
        exit();
    }
    // Datage de l'article en timestamp
    $now = time(); 
    // Gestion de l'image
    $fileName = $_FILES["image"]["name"]; // Nom de l'image
    $tmpName = $_FILES["image"]["tmp_name"]; // Nom temporaire de l'image
    $tabExtension = explode('.', $fileName); // On coupe au point, renvoie un tableau
    $extension = strtolower(end($tabExtension)); // Prends le dernier index, donc l'extension
    if (!file_exists($tmpName)) {
        header("Location: ../view/add_article_form.php?message=Erreur image&status=error");
    }
    $safeTitle = preg_replace('/[^a-zA-Z0-9-_]/', '_', $title);
    $newName = $title . '.' . $extension;
    $uploadDir = "../assets/img_articles/";
    if (!is_dir($uploadDir)) { // Vérifie si $uploadDir existe et est un dossier
        mkdir($uploadDir, 0777, true); // Si false, créé le dossier
    }
    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
        try {
            $sql = "INSERT INTO articles (title, category, content, img, author, lat, lng, date) 
                    VALUES (:title, :category, :content, :image, :author, :lat, :lng, :date)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':image', $newName, PDO::PARAM_STR);
            $stmt->bindValue(':author', $author, PDO::PARAM_STR); // SQL convertit les nombres en string
            $stmt->bindValue(':lat', $lat, PDO::PARAM_STR);
            $stmt->bindValue(':lng', $lng, PDO::PARAM_STR);
            $stmt->bindValue(':date', $now, PDO::PARAM_STR);
            $verif = $stmt->execute();
            if ($verif) {
                header("Location: ../view/add_article_form.php?message=Article ajouté&status=success");
                exit();
            } else {
                header("Location: ../view/add_article_form.php?message=Erreur lors de l'ajout&status=error");
                exit();
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        header("Location: ../view/add_article_form.php?message=Erreur serveur&status=error");
        exit();
    }
} else {
    header("Location: ../view/add_article_form.php?message=Veuillez remplir le formulaire correctement&status=error");
    exit();
};
