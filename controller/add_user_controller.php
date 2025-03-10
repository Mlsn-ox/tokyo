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
        $name = htmlspecialchars(trim($_POST["name"]), ENT_QUOTES, 'UTF-8');
        $mail = filter_var(trim($_POST["mail"]), FILTER_VALIDATE_EMAIL);
        // Vérifie email valide
        if (!$mail) {
            throw new Exception("mail_error");
        }
        $password = password_hash($_POST["password1"], PASSWORD_DEFAULT);
        $profil = $_POST["profil"];
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;
        $today = date("Y-m-d");

        // Vérifie si l'email existe déjà
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mail = ?");
        $stmt->execute([$mail]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("signup_error"); 
        }

        $sql = "INSERT INTO users (name, mail, psw, img, newsletter, start_date) 
                VALUES (:name, :mail, :password, :img, :newsletter, :today)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':img', $profil, PDO::PARAM_STR);
        $stmt->bindValue(':newsletter', $newsletter, PDO::PARAM_INT);
        $stmt->bindValue(':today', $today, PDO::PARAM_STR);
        $verif = $stmt->execute();
        
        if (!$verif) {
            throw new Exception("server_error"); 
        }
        
        // Ajoute à la newsletter si coché
        if ($newsletter) {
            $lastId = $pdo->lastInsertId();
            $sql_news = "INSERT INTO newsletters (newsletter_mail, user_ide) VALUES (:mail, :user_id)";
            $stmt_news = $pdo->prepare($sql_news);
            $stmt_news->bindValue(':mail', $mail, PDO::PARAM_STR);
            $stmt_news->bindValue(':user_ide', $lastId, PDO::PARAM_INT);
            $verif_news = $stmt_news->execute();
            if (!$verif_news) {
                throw new Exception("server_error");
            }
        }
        header("Location: ../view/login.php?message_code=user_added&status=success");
        exit();

    } catch (Exception $e) {
        $error_code = urlencode($e->getMessage());
        header("Location: ../view/add_user_form.php?message_code=" . $error_code . "&status=error");
        exit();
    }
