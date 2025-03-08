<?php
require_once "../includes/pdo.php";

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    $sql = "SELECT trottinette_like FROM trottinette WHERE trottinette_id=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $like = $stmt->fetch(PDO::FETCH_ASSOC);
    $likePlusOne = $like["trottinette_like"] + 1;
    $sqlLike = "UPDATE trottinette SET trottinette_like=? WHERE trottinette_id=?";
    $stmtLike = $pdo->prepare($sqlLike);
    $verif = $stmtLike->execute([$likePlusOne, $id]);
    if ($verif) {
        $response = [
            "like" => $likePlusOne,
            "id" => $id
        ];
        echo json_encode($response);
    }
}
