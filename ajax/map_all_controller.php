<?php
require_once "../config.php";

$sql = "SELECT article.art_id, article.art_title, article.art_content, article.art_lat, article.art_lng, 
                image.img_name AS img, category.cat_name AS cat FROM article 
        LEFT JOIN `image` ON image.img_fk_art_id = article.art_id
        LEFT JOIN category ON article.art_fk_cat_id = category.cat_id 
        WHERE article.art_status = 'approved';";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($articles);
