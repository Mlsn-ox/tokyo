<?php
session_start();
session_destroy();
unset($_SESSION['name']);
unset($_SESSION['role']);
header('Location: ../view/homepage.php');
