<?php
require_once "../includes/pdo.php";
require_once "../includes/filters.php";
require_once "../includes/functions.php";

try {
    $limitArt = isset($_GET['limit']) ? intval($_GET['limit']) : 6; // Nombre d'articles par page
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $sql = "SELECT article.art_id, article.art_title, article.art_content, image.img_name AS img, category.cat_name AS cat FROM article 
            LEFT JOIN `image` ON image.img_fk_art_id = article.art_id
            LEFT JOIN category ON article.art_fk_cat_id = category.cat_id 
            WHERE article.art_status = 'approved' $where $orderBy LIMIT $offset, $limitArt"; // LIMIT donnée de départ, nombre de résultat
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($articles);
    exit;
} catch (PDOException $e) {
    echo json_encode(["error" => "Erreur: " . $e->getMessage()]);
}