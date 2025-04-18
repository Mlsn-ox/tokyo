<?php

$config = [
    'url' => 'http://localhost/TokyoSpot',
    'title' => 'TokyoSpot',
    'alt_img' => 'TokyoSpot - Partagez vos bons plans à Tokyo !',
    'email' => 'contact.tokyospot@gmail.com',
];

session_start();
include_once 'includes/pdo.php';
require_once "includes/functions.php";