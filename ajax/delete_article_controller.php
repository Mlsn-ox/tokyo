<?php
session_start();
require_once "../includes/pdo.php";

if (
    empty($_SESSION['id']) || 
    empty($_SESSION['token']) ||
    $_POST['user_id'] != $_SESSION['id'] || 
    $_POST['token'] != $_SESSION['token']
) {
    session_destroy();
    header("Location: ../view/login.php?message_code=connect_error&status=error");
    exit();
}
try {
    $idUser = $_SESSION['id'];
    $idArticle = $_POST['art_id'];
    // Vérification du de l'article
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM article WHERE art_id=? AND art_fk_user_id=?");
    $stmt->execute([$idArticle, $idUser]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception("article_error");
    }
    // Suppression
    $stmt = $pdo->prepare("DELETE FROM article WHERE art_id=?");
    $verif = $stmt->execute([$idArticle]);
    if (!$verif) {
        throw new Exception("server_error");
    }
    echo json_encode([
        "success" => true,
    ]);
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Échec de la suppression : " . $e->getMessage()
    ]);
}