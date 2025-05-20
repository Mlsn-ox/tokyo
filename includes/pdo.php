<?php
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable("../"); // Racine du projet
$dotenv->load();
$host = $_ENV['DB_HOST'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$psw = $_ENV['DB_PSW'];
$pdo = new PDO("mysql:host=$host;dbname=$name", $user, $psw);
$pdo->exec("SET NAMES 'utf8mb4'");
