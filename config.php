<?php
session_start();

$config = [
    'url' => 'https://formationalaji.devivv.com/melisiane',
    // 'url' => 'http://localhost/TokyoSpot/',
    'title' => 'TokyoSpot',
    'alt_img' => 'TokyoSpot - Partagez vos bons plans à Tokyo !',
    'email' => 'contact.tokyospot@gmail.com',
];

include_once 'includes/pdo.php';
include_once "includes/functions.php";
