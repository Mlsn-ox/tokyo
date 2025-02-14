<?php
include "pdo.php";
$sql = "SELECT id, title, category, content, img, lat, lng FROM articles";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($articles);
