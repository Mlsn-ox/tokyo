<?php
$categories = [];
$where = $orderBy = $order = "";

// Gestion catégorie
if (isset($_GET["category"]) && !empty($_GET["category"])) { // Récupération catégorie(s) ET tableau non vide
    $categories = $_GET["category"];
    $categoString = "'" . implode("','", $categories) . "'"; // Explosion du tableau en string
    $where = 'WHERE category IN(' . $categoString . ')'; // Formation de la requète sql
}

// Gestion tri
if (isset($_GET["order"])) { // Récupération order
    $order = $_GET["order"];
    $orderBy = ' ORDER BY id ' . $order; // Formation de la requète sql
}
