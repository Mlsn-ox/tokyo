<?php
    require_once "../config.php";

if (
    empty($_SESSION['id']) || 
    empty($_SESSION['token']) ||
    $_POST['id'] != $_SESSION['id'] || 
    $_POST['token'] != $_SESSION['token']
) {
    session_destroy();
    header("Location: ../view/login.php?message_code=connect_error&status=error");
    exit();
}
try {
    // Vérifie que les champs obligatoires sont présents
    if (empty($_POST['mail']) || empty($_POST['name'])) {
        throw new Exception("form_error");
    }
    $id = $_SESSION['id'];
    // Nettoyage des données
    $name = trim($_POST["name"]);
    if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-]+$/u', $name)) {
        throw new Exception("name_invalid");
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{7,}$/u', $_POST["psw"])) {
        throw new Exception("psw_invalid");
    }
    $mail = filter_var(trim($_POST['mail']), FILTER_VALIDATE_EMAIL);
    if (!$mail) {
        throw new Exception("mail_error");
    }
    // Vérification du nom
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE user_name = ? AND user_id != ?");
    $stmt->execute([$name, $id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("name_taken");
    }
    // Vérification du mail
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE user_mail = ? AND user_id != ?");
    $stmt->execute([$mail, $id]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("signup_error");
    }
    // Vérification du mot de passe
    $sql = "SELECT user_psw FROM user WHERE user_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $checkPsw = password_verify($_POST["psw"], $user['user_psw']);
    if (!$checkPsw) {
        throw new Exception("login_error");
    }
    // Mise à jour
    $sql = "UPDATE user SET user_name=?, user_mail=? WHERE user_id=?;";
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute([
        $name,
        $mail,
        $id
    ]);
    $_SESSION['name'] = $name;
    $_SESSION['mail'] = $mail;
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
