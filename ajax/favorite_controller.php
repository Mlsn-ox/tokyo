<?php
    require_once "../config.php";

if ($_POST["user_id"] != $_SESSION['id'] || $_POST["token"] != $_SESSION['token']){
    echo json_encode([
        "status" => "Error",
        "message" => "Accès non autorise"
    ]);
    exit();
}
try {
    $sql = "SELECT * FROM favorite WHERE fav_art_id=? AND fav_user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST["art_id"],
        $_POST["user_id"]
    ]);
    $fav = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($fav) {
        $sql = "DELETE FROM favorite WHERE fav_art_id=? AND fav_user_id=?";
        $stmt = $pdo->prepare($sql);
        $verif = $stmt->execute([
            $_POST["art_id"],
            $_POST["user_id"]
        ]);
    } else {
        $today = date('Y-m-d');
        $sql = "INSERT INTO favorite (fav_art_id, fav_user_id, fav_added_at) VALUES (?,?,?);";
        $stmt = $pdo->prepare($sql);
        $verif = $stmt->execute([
            $_POST["art_id"],
            $_POST["user_id"],
            $today
        ]);
    }
    $message = $fav ? "Favori supprimé" : "Favori ajouté";
    $added = $fav ? false : true ;
    echo json_encode([
        "status" => "success",
        "message" => $message,
        "added" => $added,
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "Erreur: " . $e->getMessage(),
        "message" => "Échec de la mise à jour des favoris"
    ]);
}
