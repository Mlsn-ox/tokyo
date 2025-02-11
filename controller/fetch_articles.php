<?php
include "./pdo.php";
require "./filters.php";

try {
    $limitArt = isset($_GET['limit']) ? intval($_GET['limit']) : 6; // Nombre d'articles par page
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $sql = "SELECT article_id, article_title, article_category, article_content, article_img FROM articles $where $orderBy LIMIT $offset, $limitArt";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($articles);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
}
