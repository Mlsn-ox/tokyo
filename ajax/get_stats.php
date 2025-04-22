<?php
require_once "../config.php";
$stats = [];

try {
        // Articles en attente de modération
        $sql = "SELECT article.art_id, article.art_title, article.art_created_at, image.img_name, 
                user.user_id AS ide, user.user_name AS author, category.cat_name FROM article
                LEFT JOIN user ON article.art_fk_user_id = user.user_id
                LEFT JOIN image ON image.img_fk_art_id = article.art_id 
                LEFT JOIN category ON category.cat_id = article.art_fk_cat_id 
                WHERE article.art_status = 'pending' ORDER BY article.art_id ASC";
        $stmt = $pdo->query($sql);
        $stats['articles_pending'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Commentaires en attente de modération
        $sql = "SELECT comment.com_id, comment.com_content, comment.com_posted_at, article.art_id, article.art_title, 
                user.user_name AS author, user.user_id FROM comment
                LEFT JOIN article ON comment.com_fk_art_id = article.art_id
                LEFT JOIN user ON comment.com_fk_user_id = user.user_id
                WHERE comment.com_status = 'pending' ORDER BY comment.com_posted_at ASC";
        $stmt = $pdo->query($sql);
        $stats['comments_pending'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Utilisateurs
        $sql = "SELECT user.user_id, user.user_is_blocked, user.user_name, user.user_firstname, user.user_lastname, user.user_birthdate, user.user_mail, user.user_log FROM user 
                LEFT JOIN article ON user.user_id = article.art_fk_user_id 
                LEFT JOIN comment ON user.user_id = comment.com_fk_user_id 
                GROUP BY user.user_id ORDER BY user.user_log DESC;";
        $stmt = $pdo->query($sql);
        $stats['users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Totaux globaux articles + date dernier approuvé
        $sql = "SELECT 
                SUM(art_status = 'approved') AS total_approved,
                SUM(art_status = 'pending') AS total_pending,
                SUM(art_status = 'rejected') AS total_rejected,
                MAX(CASE WHEN art_status = 'approved' THEN art_created_at END) AS latest_article_date
                FROM article";
        $stmt = $pdo->query($sql);
        $stats['articles_global'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Totaux globaux commentaires + date dernier approuvé
        $sql = "SELECT 
                SUM(com_status = 'approved') AS total_approved,
                SUM(com_status = 'pending') AS total_pending,
                SUM(com_status = 'rejected') AS total_rejected,
                MAX(CASE WHEN com_status = 'approved' THEN com_posted_at END) AS latest_com_date
                FROM comment";
        $stmt = $pdo->query($sql);
        $stats['comments_global'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Articles approuvés par catégorie
        $sql = "SELECT art_fk_cat_id, COUNT(*) AS total_by_category, cat_name FROM article
                LEFT JOIN category ON category.cat_id = article.art_fk_cat_id
                WHERE art_status = 'approved' GROUP BY art_fk_cat_id";
        $stmt = $pdo->query($sql);
        $stats['articles_by_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Infos globales sur les utilisateurs
        $sql = "SELECT COUNT(*) AS total_users, MAX(user_ins) AS newest_user_date, COUNT(user_is_blocked) AS total_blocked,
                (SELECT user_name FROM user ORDER BY user_ins DESC LIMIT 1) AS newest_user FROM user";
        $stmt = $pdo->query($sql);
        $stats['users_global'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Top posteur (utilisateur avec le plus d'articles)
        $sql = "SELECT user.user_name, COUNT(article.art_id) AS article_count FROM user
                LEFT JOIN article ON article.art_fk_user_id = user.user_id
                GROUP BY user.user_id ORDER BY article_count DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['top_poster'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Article avec le plus de commentaires
        $sql = "SELECT article.art_id, article.art_title, COUNT(comment.com_id) AS comment_count FROM article
                JOIN comment ON article.art_id = comment.com_fk_art_id
                GROUP BY article.art_id ORDER BY comment_count DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['most_commented_article'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Utilisateur qui commente le plus
        $sql = "SELECT user.user_id, user.user_name, COUNT(comment.com_id) AS comment_count FROM user
                JOIN comment ON comment.com_fk_user_id = user.user_id
                GROUP BY user.user_id ORDER BY comment_count DESC LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['top_commenter'] = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($stats);

} catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
}
