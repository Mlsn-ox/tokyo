<?php
    require_once "../config.php";
    
try {
    if (!isset($_GET['id'], $_GET['action'],$_GET['element'], $_GET['token'])) {  
        throw new Exception("server_error");
    }
    if ($_GET['token'] !== $_SESSION['token']){
        throw new Exception("connect_error");
    }
    if ($_SESSION['role'] !== "admin"){
        throw new Exception("unauthorized");
    }
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $action = $_GET['action'];
    $allowedActions = ['approved', 'rejected', 'pending'];
    if (!$id || !in_array($action, $allowedActions)) {
        throw new Exception("param_not_found");
    }
    $element = $_GET['element'];
    $articleId = $location = "";
    if ($element === "article"){
        $sql = "UPDATE article SET art_status = :action WHERE art_id = :id";
        $location = "Location: ../view/admin.php?message_code=article_updated&status=success";
    } else if ($element === "comment"){
        $sql = "SELECT com_fk_art_id FROM comment WHERE com_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $articleId = $stmt->fetch(PDO::FETCH_ASSOC);
        $location = "Location: ../view/read_article.php?id=" . $articleId['com_fk_art_id'] . "&message_code=comment_updated&status=success";
        $sql = "UPDATE comment SET com_status = :action WHERE com_id = :id";
    } else {
        throw new Exception("param_not_found");
    }
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute(['action' => $action, 'id' => $id]);
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
