<?php
require_once "../config.php";
try {
    if (isset($_POST['recaptcha_token'])) {
        $recaptchaToken = $_POST['recaptcha_token'];
        $secretKey = '6LeLsi4rAAAAAGHJC9zhBceTfmElHZ2yFLV3Tmcf';
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaToken}");
        $responseData = json_decode($response);
        if (!$responseData->success || $responseData->score < 0.5) {
            throw new Exception("captcha_invalid");
        }
    } else {
        throw new Exception("captcha_invalid");
    }
    if (
        empty($_POST['firstname']) ||
        empty($_POST['lastname']) ||
        empty($_POST['birthdate']) ||
        empty($_POST['mail']) ||
        empty($_POST['name']) ||
        empty($_POST['password1']) ||
        empty($_POST['password2']) ||
        ($_POST['password1'] !== $_POST['password2'])
    ) {
        throw new Exception("form_error");
    }
    // Nettoyage des données
    $name = trim($_POST["name"]);
    if (!preg_match('/^[A-Za-zÀ-ÖØ-öø-ÿ0-9_\-]+$/u', $name)) {
        throw new Exception("name_invalid");
    }
    // Vérifie si le pseudo existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE user_name = ?");
    $stmt->execute([$name]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("name_taken");
    }
    $birthdate = str_replace('/', '-', $_POST["birthdate"]);
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
        throw new Exception("birthdate_error");
    }
    function validateNames($str)
    {
        return preg_match("/^[\p{L}' -]+$/u", $str);
    }
    $firstname = ucfirst(trim($_POST['firstname']));
    $lastname = ucfirst(trim($_POST['lastname']));
    if (!validateNames($firstname) || !validateNames($lastname)) {
        throw new Exception("identity_invalid");
    }
    $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
    $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
    $allowedImages = [
        'Godzilla.png',
        'Kanagawa.png',
        'Neko.png',
        'Ramen.png',
        'Sakura.png',
        'Shiba.png'
    ];
    if (!in_array($_POST["profil"], $allowedImages, true)) {
        throw new Exception("form_error");
    }
    $profil = $_POST["profil"];
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d).{7,}$/', $_POST["password1"])) {
        throw new Exception("psw_invalid");
    }
    $password = password_hash($_POST["password1"], PASSWORD_DEFAULT);
    $today = date("Y-m-d");
    $mail = filter_var(trim($_POST["mail"]), FILTER_VALIDATE_EMAIL);
    // Vérifie email valide
    if (!$mail) {
        throw new Exception("mail_error");
    }
    // Vérifie si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE user_mail = ?");
    $stmt->execute([$mail]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("signup_error");
    }
    $sql = "INSERT INTO `user` (user_name, user_firstname, user_lastname, 
        user_birthdate, user_mail, user_psw, user_image, user_ins)
        VALUES (:name, :firstname, :lastname, :birthdate, :mail, :psw, :img, :today)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindValue(':birthdate', $birthdate, PDO::PARAM_STR);
    $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
    $stmt->bindValue(':psw', $password, PDO::PARAM_STR);
    $stmt->bindValue(':img', $profil, PDO::PARAM_STR);
    $stmt->bindValue(':today', $today, PDO::PARAM_STR);
    $verif = $stmt->execute();
    if (!$verif) {
        throw new Exception("server_error");
    }
    setcookie("temp_first", "", time() - 3600, "/");
    setcookie("temp_last", "", time() - 3600, "/");
    setcookie("temp_birth", "", time() - 3600, "/");
    setcookie("temp_mail", "", time() - 3600, "/");
    setcookie("temp_name", "", time() - 3600, "/");
    setcookie("temp_avatar", "", time() - 3600, "/");
    header("Location: ../view/login.php?message_code=user_added&status=success");
    exit();
} catch (Exception $e) {
    setcookie("temp_first", $_POST["firstname"] ?? "", time() + 600, "/");
    setcookie("temp_last", $_POST["lastname"] ?? "", time() + 600, "/");
    setcookie("temp_birth", $_POST["birthdate"] ?? "", time() + 600, "/");
    setcookie("temp_mail", $_POST["mail"] ?? "", time() + 600, "/");
    setcookie("temp_name", $_POST["name"] ?? "", time() + 600, "/");
    setcookie("temp_avatar", $_POST["profil"] ?? "Sakura.png", time() + 600, "/");
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/add_user.php?message_code=" . $error_code . "&status=error");
    exit();
}
