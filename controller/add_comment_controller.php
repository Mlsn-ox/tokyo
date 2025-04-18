<?php
    require_once "../config.php";
    try {
        if ($_SESSION['token'] !== $_POST['token'] || $_SESSION['bloked'] ||
            !$_POST['user_comment'] || !$_POST['art_id'] || 
            $_POST['user_comment'] != $_SESSION['id']) {
            session_destroy();
            header("Location: ../view/login.php?message_code=connect_error&status=error");
            exit();
        }
        if (empty($_POST['comment_content'])) {
            throw new Exception("form_error");
        }
        // Nettoyage des données
        $content = htmlspecialchars(ucfirst(trim($_POST['comment_content'])), ENT_QUOTES, 'UTF-8');
        $author = filter_var($_POST['user_comment'], FILTER_VALIDATE_INT);
        $article = filter_var($_POST['art_id'], FILTER_VALIDATE_INT);
        $today = date('Y-m-d');
        $sql = "INSERT INTO comment (com_posted_at, com_content, com_fk_art_id, com_fk_user_id) 
                VALUES (:today, :content, :article, :author)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);
        $stmt->bindValue(':article', $article, PDO::PARAM_INT); 
        $stmt->bindValue(':author', $author, PDO::PARAM_INT); 
        $verif = $stmt->execute();
        if (!$verif) {
            throw new Exception("server_error");
        }
        header("Location: ../view/read_article.php?id=" . $article . "&message_code=comment_added&status=success");
        exit();
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/read_article.php?id=" . $article . "&message_code=" . $error_code . "&status=error");
        exit();
    }
