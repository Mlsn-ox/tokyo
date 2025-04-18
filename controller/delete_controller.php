<?php
    require_once "../config.php";
    try {
        if (!isset($_GET['id'], $_GET['element'], $_GET['token'])) {  
            throw new Exception("server_error");
        }
        if (!isset($_SESSION['token']) || $_GET['token'] !== $_SESSION['token']){
            throw new Exception("connect_error");
        }
        if ($_SESSION['role'] !== "admin"){
            throw new Exception("unauthorized");
        }
        $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
        $element = $_GET['element'];
        $allowedElement = ['comment', 'article'];
        if (!$id || !in_array($element, $allowedElement)) {
            throw new Exception("param_not_found");
        }
        $articleId = $location = "";
        if ($element === "article"){
            $sql = "SELECT art_status FROM article WHERE art_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $art = $stmt->fetch(PDO::FETCH_ASSOC);
            $location = "Location: ../view/admin.php?message_code=deleted&status=success";
            if ($art['art_status'] === "approved"){
                throw new Exception("reject_first");
            }
            $sql = "DELETE FROM article WHERE art_id = :id";
        } else if ($element === "comment"){
            $sql = "SELECT com_status, com_fk_art_id FROM comment WHERE com_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $com = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($com['com_status'] === "approved"){
                throw new Exception("reject_first");
            }
            $articleId = $com['com_fk_art_id'];
            $location = "Location: ../view/read_article.php?id=" . $articleId . "&message_code=deleted&status=success";
            $sql = "DELETE FROM comment WHERE com_id = :id";
        }
        $stmt = $pdo->prepare($sql);
        $verif = $stmt->execute(['id' => $id]);
        if (!$verif) {
            throw new Exception("server_error"); 
        }
        header($location);
        exit;    
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
        exit();
    }
?>