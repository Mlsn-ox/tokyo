<?php
    session_start();
    require_once "../includes/pdo.php";
try {
    if (!isset($_GET['id'], $_GET['action'], $_GET['token'])) {  
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
    $sql = "UPDATE article SET art_status = :action WHERE art_id = :id";
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute(['action' => $action, 'id' => $id]);
    if (!$verif) {
        throw new Exception("server_error"); 
    } 
    header("Location: ../view/admin.php?message_code=article_updated&status=success");
    exit;    
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
    exit();
}
?>
