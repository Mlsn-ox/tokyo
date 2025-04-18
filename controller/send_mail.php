<?php
    require_once "../config.php";
    try {
        if (isset($_POST['g-recaptcha-response'])) {
            $recaptcha = $_POST['g-recaptcha-response'];
            $secret = "6LeBuBArAAAAAJCwjjPwIFQo43b-2XpYGNSPdmRe";
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha");
            $responseData = json_decode($response);
            if (!$responseData->success) {
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
        function validateNames($str) {
            return preg_match("/^[\p{L}' -]+$/u", $str);
        }
        $firstname = ucfirst(trim($_POST['firstname']));
        $lastname = ucfirst(trim($_POST['lastname']));
        if (!validateNames($firstname) || !validateNames($lastname)) {
            throw new Exception("identity_invalid");
        }
        $firstname = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
        $lastname = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');

    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/add_user.php?message_code=" . $error_code . "&status=error");
        exit();
    }
?>