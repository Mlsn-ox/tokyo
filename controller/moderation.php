<?php
    require_once "../includes/pdo.php";
    session_start();
try {
    if (isset($_GET['id'], $_GET['action'], $_GET['token'])) {
        if ($_GET['token'] === $_SESSION['token']){
            $id = htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
            $action = htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8'); ;
            if ($action) {
                $sql = "UPDATE articles SET status = :action WHERE id = :id";
            } else {
                throw new Exception("param_not_find");
            }
            $stmt = $pdo->prepare($sql);
            $verif = $stmt->execute(['action' => $action, 'id' => $id]);
            if ($verif) {
                header("Location: ../view/admin.php?message_code=article_updated&status=success");
                exit;
            } else {
                throw new Exception("server_error");
            }
        } else {
            throw new Exception("connect_error");
        }
    } else {
        throw new Exception("server_error");
    }
} catch (Exception $e) {
    $error_code = urlencode($e->getMessage());
    header("Location: ../view/homepage.php?message_code=" . $error_code . "&status=error");
    exit();
}
// if (isset($_GET['id'], $_GET['delete'], $_GET['token']) && $_GET['token'] === $_SESSION['token']) {
//     try {
//         $id = $_GET['id'];
//         $delete = $_GET['delete'];
//         if ($delete) {
//             $sql = "DELETE FROM articles WHERE id = :id";
//         } else {
//             throw new Exception("param_not_find");
//         }
//         $stmt = $pdo->prepare($sql);
//         $woosh = $stmt->execute(['id' => $id]);
//         if ($woosh) {
//             header("Location: ../view/admin.php?message_code=article_deleted&status=success");
//             exit;
//         } else {
//             throw new Exception("server_error");
//         }
//     } catch (Exception $e) {
//         $error_code = urlencode($e->getMessage());
//         header("Location: ../view/admin.php?message_code=" . $error_code . "&status=error");
//         exit();
//     }
// }

?>
