<?php
require_once "../config.php";
if (!isAdmin() || !isTokenValid($_GET['token'])) {
    echo json_encode([
        "status" => "Error",
        "message" => "Accès non autorise"
    ]);
    exit();
}
$id = intval($_GET['id']);
if (!$id) {
    echo json_encode([
        "status" => "Error",
        "message" => "ID non valide"
    ]);
    exit();
}
$status = null;
if (isset($_GET['status']) && $_GET['status'] == "blocked") {
    $status = 1;
} else if (isset($_GET['status']) && $_GET['status'] == "unblocked") {
    $status = null;
} else {
    echo json_encode([
        "status" => "Error",
        "message" => "Statut non valide"
    ]);
    exit();
}
$stmt = $pdo->prepare("UPDATE user SET user_is_blocked = ? WHERE user_id = ?");
$stmt->execute([$status, $id]);
if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "Success",
        "message" => $status === 1 ? "Utilisateur bloqué" : "Utilisateur débloqué"
    ]);
} else {
    echo json_encode([
        "status" => "Warning",
        "message" => "Aucune modification effectuee"
    ]);
}
exit();
