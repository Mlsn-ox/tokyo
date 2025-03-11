<?php
require_once '../vendor/autoload.php';

use Dotenv\Dotenv;

//Dotenv a besoin de savoir où se situe le fichier .env
//Dans ce cas, à la racine du projet.
$dotenv = Dotenv::createImmutable("../");
//Ensuite il le charge
$dotenv->load();

$host = $_ENV['DB_HOST'];
$name = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$psw = $_ENV['DB_PSW'];

$pdo = new PDO("mysql:host=$host;dbname=$name", $user, $psw);