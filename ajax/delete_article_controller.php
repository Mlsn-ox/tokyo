<?php
require_once "../config.php";

if (
    !isTokenValid($_POST['token']) ||
    !isOwner($_POST['user_id'])
) {
    session_destroy();
    header("Location: ../view/login.php?message_code=connect_error&status=error");
    exit();
}
try {
    $idUser = $_SESSION['id'];
    $idArticle = $_POST['art_id'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM article WHERE art_id=? AND art_fk_user_id=?");
    $stmt->execute([$idArticle, $idUser]);
    if ($stmt->fetchColumn() == 0) {
        throw new Exception("article_error");
    }
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
