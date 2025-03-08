<?php
require_once "../includes/pdo.php";

$sql = "SELECT articles.id, articles.title, articles.category, articles.content, articles.img, articles.lat, articles.lng 
        FROM articles WHERE articles.status = 'approved'";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($articles);
