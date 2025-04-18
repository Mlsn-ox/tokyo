<?php
require_once "../config.php";
$actualName = $_SESSION['name'] ?? '';
$name = $_GET['name'] ?? '';
$stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE user_name = ?");
$stmt->execute([$name]);
$isTaken = $stmt->fetchColumn() > 0;
echo json_encode([
    'taken' => $isTaken,
    'actualName' => $actualName
]);
