<?php
require_once "../includes/pdo.php";


if (!empty($_POST['user_mail']) && !empty($_POST['user_psw'])) {
    $mail = $_POST['user_mail'];
    $sql = "SELECT * FROM users WHERE user_mail=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$mail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC); //Si le mail existe return array user, sinon False
    if ($user) {
        if (password_verify($_POST["user_psw"], $user['user_psw'])) {
            session_start();
            $_SESSION['id'] = $user['user_id'];
            $_SESSION['name'] = $user['user_name'];
            $_SESSION['mail'] = $user['user_mail'];
            $_SESSION['role'] = $user['user_admin'];
            $_SESSION['img'] = $user['user_img'];
            $_SESSION['token'] = bin2hex(random_bytes(16));
            header("Location: ../view/homepage.php");
            exit;
        } else {
            header("Location: ../view/login.php?message=Identifiants incorrects&status=error");
            exit;
        }
    } else {
        header("Location: ../view/login.php?message=Identifiants incorrects&status=error");
        exit;
    }
} else {
    header("Location: ../view/login.php?message=Veuillez entrez vos identifiants correctement&status=error");
    exit;
}
