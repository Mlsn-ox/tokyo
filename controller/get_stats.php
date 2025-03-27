<?php
require_once "../includes/pdo.php";

$stats = [];

try {
        // 1. Totaux globaux articles + date dernier approuvé
        $sql = "SELECT 
                SUM(art_status = 'approved') AS total_approved,
                SUM(art_status = 'pending') AS total_pending,
                SUM(art_status = 'rejected') AS total_rejected,
                MAX(CASE WHEN art_status = 'approved' THEN art_created_at END) AS latest_article_date
                FROM article";
        $stmt = $pdo->query($sql);
        $stats['articles_global'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Articles approuvés par catégorie
        $sql = "SELECT art_fk_cat_id, COUNT(*) AS total_by_category
                FROM article
                WHERE art_status = 'approved'
                GROUP BY art_fk_cat_id";
        $stmt = $pdo->query($sql);
        $stats['articles_by_category'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Infos globales sur les utilisateurs
        $sql = "SELECT 
                COUNT(*) AS total_users,
                MAX(user_ins) AS newest_user_date,
                (SELECT user_name FROM user ORDER BY user_ins DESC LIMIT 1) AS newest_user
                FROM user";
        $stmt = $pdo->query($sql);
        $stats['users_global'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 4. Top posteur (utilisateur avec le plus d'articles)
        $sql = "SELECT user.user_name, COUNT(article.art_id) AS article_count
                FROM user
                LEFT JOIN article ON article.art_fk_user_id = user.user_id
                GROUP BY user.user_id
                ORDER BY article_count DESC
                LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['top_poster'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 5. Utilisateurs avec nombre d’articles
        $sql = "SELECT 
                user.user_id, 
                user.user_name, 
                user.user_mail, 
                user.user_ins, 
                user.user_log,
                COUNT(article.art_id) AS total_articles
                FROM user 
                LEFT JOIN article ON user.user_id = article.art_fk_user_id 
                GROUP BY user.user_id 
                ORDER BY total_articles DESC";
        $stmt = $pdo->query($sql);
        $stats['users'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 6. Articles en attente de modération
        $sql = "SELECT article.*, user.user_id AS ide, user.user_name AS author
                FROM article
                LEFT JOIN user ON article.art_fk_user_id = user.user_id
                WHERE article.art_status = 'pending'
                ORDER BY article.art_id ASC";
        $stmt = $pdo->query($sql);
        $stats['articles_pending'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 7. Nombre total de commentaires
        $sql = "SELECT COUNT(*) AS total_comments FROM comment";
        $stmt = $pdo->query($sql);
        $stats['total_comments'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 8. Nombre de commentaires en attente (si applicable)
        $sql = "SELECT COUNT(*) AS pending_comments FROM comment WHERE com_status = 'pending'"; // à adapter si tu as un champ 'status'
        $stmt = $pdo->query($sql);
        $stats['pending_comments'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 9. Article avec le plus de commentaires
        $sql = "SELECT article.art_id, article.art_title, COUNT(comment.com_id) AS comment_count
                FROM article
                JOIN comment ON article.art_id = comment.com_fk_art_id
                GROUP BY article.art_id
                ORDER BY comment_count DESC
                LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['most_commented_article'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // 10. Utilisateur qui commente le plus
        $sql = "SELECT user.user_id, user.user_name, COUNT(comment.com_id) AS comment_count
                FROM user
                JOIN comment ON comment.com_fk_user_id = user.user_id
                GROUP BY user.user_id
                ORDER BY comment_count DESC
                LIMIT 1";
        $stmt = $pdo->query($sql);
        $stats['top_commenter'] = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($stats);

} catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
}
