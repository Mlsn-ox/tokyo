<?php
require_once "../config.php";

if (
    !isConnected() ||
    !isTokenValid($_POST['token'])
) {
    session_destroy();
    header("Location: ../view/login.php?message_code=connect_error&status=error");
    exit();
}
try {
    // Vérifie que les champs obligatoires sont présents
    if (
        empty($_POST['current']) ||
        empty($_POST['psw1']) ||
        empty($_POST['psw2'] ||
            $_POST['psw1'] != $_POST['psw2'])
    ) {
        throw new Exception("form_error");
    }
    $id = $_SESSION['id'];
    // Vérification du mot de passe
    $sql = "SELECT user_psw FROM user WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $checkPsw = password_verify($_POST["current"], $user['user_psw']);
    if (!$checkPsw) {
        throw new Exception("login_error");
    }
    // Nettoyage des données
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{7,}$/u', $_POST["psw1"])) {
        throw new Exception("psw_invalid");
    }
    $psw = password_hash($_POST["psw1"], PASSWORD_DEFAULT);
    // Mise à jour
    $sql = "UPDATE user SET user_psw=? WHERE user_id=?;";
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute([
        $psw,
        $id
    ]);
    if (!$verif) {
        throw new Exception("server_error");
    }
    header("Location: ../view/read_user.php?id=$id&message_code=user_updated&status=success");
    exit();
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/read_user.php?id=$id&message_code=$error_code&status=error");
    exit();
}
