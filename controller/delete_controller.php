<?php
    session_start();
    require_once "../includes/pdo.php";
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
    if ($element === "article"){
        $sql = "SELECT art_status FROM article WHERE art_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $art = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($art['art_status'] !== "rejected"){
            throw new Exception("reject_first");
        }
        $sql = "DELETE FROM article WHERE art_id = :id";
    } else if ($element === "comment"){
        $sql = "SELECT com_status FROM comment WHERE com_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $com = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($com['com_status'] !== "rejected"){
            throw new Exception("reject_first");
        }
        $sql = "DELETE FROM comment WHERE com_id = :id";
    }
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute(['id' => $id]);
    if (!$verif) {
        throw new Exception("server_error"); 
    }
    header("Location: ../view/admin.php?message_code=deleted&status=success");
    exit;    
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
    exit();
}
?>