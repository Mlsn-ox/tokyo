<?php
include "pdo.php";
if (
    !empty($_POST['mail'])
    && !empty($_POST['name'])
    && ($_POST['password1'] === $_POST['password2'])
) {
    $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
    $newsletter = $_POST['newsletter'] ? 1 : 0;
    $sql = "INSERT INTO users (
                        user_name, 
                        user_mail, 
                        user_psw, 
                        user_img,
                        user_newsletter
                        )
                    VALUES (?,?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $verif = $stmt->execute([
        $_POST["name"],
        $_POST["mail"],
        $password,
        $_POST["profil"],
        $newsletter
    ]);
    if ($_POST['newsletter']) {
        $sql_news = "INSERT INTO newsletters ( 
            newsletter_mail, 
            newsletter_user
            )
        VALUES (?,?)";
        $stmt_news = $pdo->prepare($sql_news);
        $stmt_news->execute([
            $_POST["mail"],
            1
        ]);
    }
    if ($verif) {
        header("Location: ../view/add_user_form.php?message=Profil ajouté&status=success");
    } else {
        header("Location: ../view/add_user_form.php?message=Erreur serveur&status=error");
    }
} else {
    header("Location: ../view/add_user_form.php?message=Veuillez remplir le formulaire correctement&status=error");
};
