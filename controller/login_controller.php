<?php
require_once "../config.php";

if (!empty($_POST['mail']) && !empty($_POST['psw'])) {
    try {
        $mail = filter_var(trim($_POST['mail']), FILTER_VALIDATE_EMAIL);
        if (!$mail) {
            throw new Exception("mail_error");
        }
        setcookie("mail", $mail, time() + 604800, "/");
        $sql = "SELECT * FROM user WHERE user_mail = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$mail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new Exception("mail_error");
        }
        if ($user['user_is_blocked']) {
            throw new Exception("unauthorized");
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
        $_SESSION['id'] = filter_var($user['user_id'], FILTER_VALIDATE_INT);
        $_SESSION['name'] = htmlspecialchars_decode($user['user_name']);
        $_SESSION['mail'] = filter_var($user['user_mail'], FILTER_SANITIZE_EMAIL);
        $_SESSION['role'] = $user['user_role']; // 'client' ou 'admin'
        $_SESSION['img'] = $user['user_image'];
        $_SESSION['blocked'] = $user['user_is_blocked']; // 1 si bloqué, Null si autorisé
        // Création Token
        $_SESSION['token'] = bin2hex(random_bytes(16));
        if ($user['user_role'] === "admin") {
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
