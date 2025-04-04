<?php
    require_once "../includes/pdo.php";
    try {
        if (
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
        // Vérifie que l'image de profil est respectée
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
        $profil = ($_POST["profil"]);
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
        $sql = "INSERT INTO `user` (user_name, user_mail, user_psw, user_image, user_ins)
        VALUES (:name, :mail, :psw, :img, :today)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':psw', $password, PDO::PARAM_STR);
        $stmt->bindValue(':img', $profil, PDO::PARAM_STR);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $verif = $stmt->execute();
        if (!$verif) {
            throw new Exception("server_error"); 
        }
        header("Location: ../view/login.php?message_code=user_added&status=success");
        exit();

    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/add_user_form.php?message_code=" . $error_code . "&status=error");
        exit();
    }
