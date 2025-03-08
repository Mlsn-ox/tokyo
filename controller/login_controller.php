<?php
require_once "../includes/pdo.php";


if (!empty($_POST['mail']) && !empty($_POST['psw'])) {
    try {
        $mail = $_POST['mail'];
        $sql = "SELECT * FROM users WHERE mail=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if (password_verify($_POST["psw"], $user['psw'])) {
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['mail'] = $user['mail'];
                $_SESSION['role'] = $user['admin'];
                $_SESSION['img'] = $user['img'];
                $_SESSION['token'] = bin2hex(random_bytes(16));
                if ($user['admin']){
                    header("Location: ../view/admin.php");
                    exit;
                } else {
                    header("Location: ../view/homepage.php");
                    exit;
                }
            } else {
                throw new Exception("login_error"); 
            }
        } else {
            throw new Exception("mail_error"); 
        }
    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/login.php?message_code=" . $error_code . "&status=error");
        exit();
    }
} else {
    header("Location: ../view/login.php?message_code=form_error&status=error");
    exit;
}
