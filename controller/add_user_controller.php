<?php
require_once "../includes/pdo.php";
if (
    !empty($_POST['mail']) && 
    !empty($_POST['name']) && 
    !empty($_POST['password1']) && 
    !empty($_POST['password2']) &&
    ($_POST['password1'] === $_POST['password2'])
) {
     try {
        // Validation des entrées
        $name = htmlspecialchars(trim($_POST["name"]));
        $mail = filter_var(trim($_POST["mail"]), FILTER_VALIDATE_EMAIL);
        $password1 = $_POST["password1"];
        $profil = !empty($_POST["profil"]) ? htmlspecialchars(trim($_POST["profil"])) : null;
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;

        if (!$mail) {
            throw new Exception("Adresse email invalide.");
        }

        // Vérification de l'unicité de l'email
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE mail = ?");
        $stmt->execute([$mail]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Cet email est déjà utilisé.");
        }

        // Hash du mot de passe
        $passwordHash = password_hash($password1, PASSWORD_DEFAULT);

        // Insertion de l'utilisateur
        $sql = "INSERT INTO users (name, mail, psw, img, newsletter, time) 
                VALUES (:name, :mail, :password, :profil, :newsletter, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindValue(':password', $passwordHash, PDO::PARAM_STR);
        $stmt->bindValue(':profil', $profil, PDO::PARAM_STR);
        $stmt->bindValue(':newsletter', $newsletter, PDO::PARAM_INT);
        $verif = $stmt->execute();

        // Ajout à la newsletter si activé
        if ($newsletter) {
            $sql_news = "INSERT INTO newsletters (newsletter_mail, user_ide) VALUES (:mail, :user_id)";
            $stmt_news = $pdo->prepare($sql_news);
            $stmt_news->bindValue(':mail', $mail, PDO::PARAM_STR);
            $stmt_news->bindValue(':user_id', 1, PDO::PARAM_INT); // Attention, user_id en dur
            $stmt_news->execute();
        }
        if ($verif) {
            header("Location: ../view/login.php?message=Profil ajouté&status=success");
            exit();
        } else {
            throw new Exception("Erreur lors de l'ajout de l'utilisateur.");
        }
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: ../view/add_user_form.php?message=Veuillez remplir le formulaire correctement&status=error");
    exit();
}
