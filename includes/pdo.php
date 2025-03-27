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
$pdo = new PDO("mysql:host=$host;dbname=$name", $user, $psw);