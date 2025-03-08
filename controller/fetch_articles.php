<?php
require_once "../includes/pdo.php";
require_once "../includes/filters.php";

try {
    $limitArt = isset($_GET['limit']) ? intval($_GET['limit']) : 6; // Nombre d'articles par page
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $sql = "SELECT articles.id, articles.title, articles.category, articles.content, articles.img FROM articles WHERE articles.status = 'approved' $where $orderBy LIMIT $offset, $limitArt";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($articles);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
}