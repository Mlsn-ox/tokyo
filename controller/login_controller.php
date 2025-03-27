<?php
require_once "../includes/pdo.php";

if (!empty($_POST['mail']) && !empty($_POST['psw'])) {
    try {
        $mail = filter_var(trim($_POST['mail']), FILTER_VALIDATE_EMAIL);
        if (!$mail) {
            throw new Exception("mail_error");
        }
        $sql = "SELECT * FROM user WHERE user_mail=?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception("mail_error");
        }
        $checkPsw = password_verify($_POST["psw"], $user['user_psw']);
        if (!$checkPsw) {
            throw new Exception("login_error");
        }
        $today = date('Y-m-d');
        $update = "UPDATE user SET user_log = :today WHERE user_id = :id";
        $stmtUpdate = $pdo->prepare($update);
        $stmtUpdate->execute([
            'today' => $today, 
            'id' => $user['user_id']
        ]);
        session_start();
        $_SESSION['id'] = $user['user_id'];
        $_SESSION['name'] = $user['user_name'];
        $_SESSION['mail'] = $user['user_mail'];
        $_SESSION['role'] = $user['user_role'];
        $_SESSION['img'] = $user['user_image'];
        $_SESSION['token'] = bin2hex(random_bytes(16));
        if ($user['user_role']=== "admin"){
            header("Location: ../view/admin.php");
            exit;
        } else {
            header("Location: ../view/homepage.php");
            exit;
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
