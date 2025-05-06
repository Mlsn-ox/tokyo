<?php
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;
//Dotenv a besoin de savoir où se situe le fichier .env
$dotenv = Dotenv::createImmutable("../"); // Racine du projet
$dotenv->load();
$host = $_ENV['DB_HOST'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$psw = $_ENV['DB_PSW'];
$port = $_ENV['DB_PORT'];
$pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $psw);
$pdo->exec("SET NAMES 'utf8mb4'");
