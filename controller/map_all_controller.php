<?php
include "pdo.php";
$sql = "SELECT article_id, article_title, article_category, article_content, article_img, article_lat, article_lng FROM articles";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($articles);
