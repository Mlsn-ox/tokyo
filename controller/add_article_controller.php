<?php
include "pdo.php";
if (
    !empty($_POST['title'])
    && !empty($_POST['content'])
    && !empty($_POST['lat'])
    && ($_FILES["image"]["error"] === UPLOAD_ERR_OK)
) {
    $fileName = $_FILES["image"]["name"]; // Nom du fichier
    $tmpName = $_FILES["image"]["tmp_name"]; // Nom temporaire du fichier
    $tabExtension = explode('.', $fileName); // On coupe au point, renvoie un tableau
    $extension = strtolower(end($tabExtension)); // Prends le dernier index, donc l'extension fichier
    if (!file_exists($tmpName)) {
        header("Location: ../view/add_article_form.php?message=Erreur image&status=error");
    }
    $title = preg_replace('/[^a-zA-Z0-9-_]/', '_', $_POST['title']);
    $newName = $title . '.' . $extension;
    $uploadDir = "../assets/img_articles/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    if (move_uploaded_file($tmpName, $uploadDir . $newName)) {
        $sql = "INSERT INTO articles (
            article_title, 
            article_category, 
            article_content, 
            article_img,
            article_author,
            article_lat,
            article_lng
            )
        VALUES (?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $verif = $stmt->execute([
            $_POST["title"],
            $_POST["category"],
            $_POST["content"],
            $newName,
            $_POST["author"],
            $_POST["lat"],
            $_POST["lng"]
        ]);
        if ($verif) {
            header("Location: ../view/add_article_form.php?message=Article ajouté&status=success");
        } else {
            header("Location: ../view/add_article_form.php?message=Erreur image&status=error");
        }
    } else {
        header("Location: ../view/add_article_form.php?message=Erreur serveur&status=error");
    }
} else {
    header("Location: ../view/add_article_form.php?message=Veuillez remplir le formulaire correctement&status=error");
};
