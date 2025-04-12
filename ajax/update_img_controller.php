<?php
session_start();
require_once "../includes/pdo.php";

if ($_POST["id"] != $_SESSION['id'] || $_POST["token"] != $_SESSION['token']){
    session_destroy();
    header("Location: ../view/login.php?message_code=connect_error&status=error");
    exit();
}
try {
    if (empty($_POST["profil"])){
        throw new Exception("Erreur lors du chargement de l'image");
    }
    $allowedImages = [
        'Godzilla.png',
        'Kanagawa.png',
        'Neko.png',
        'Ramen.png',
        'Sakura.png',
        'Shiba.png'
    ];
    if (!in_array($_POST["profil"], $allowedImages, true)) {
        throw new Exception("Image non autorisée.");
    }
    $profil = $_POST["profil"];
    $id = $_SESSION['id'];
    $sql = "UPDATE user SET user_image=? WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute([
        $profil,
        $id
    ]);
    $_SESSION['img'] = $profil;
    $message = $verif ? "Image mise à jour" : "Erreur serveur, réessayez plus tard";
    echo json_encode([
        "status" => "Success",
        "message" => $message,
        "image" => $profil
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "Error",
        "message" => "Échec de la mise à jour de l'image : " . $e->getMessage()
    ]);
}
