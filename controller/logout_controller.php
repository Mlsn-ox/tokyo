<?php
session_start();
$_SESSION = [];
session_destroy();
header('Location: ../view/homepage.php?message_code=deconnected&status=success');
exit();
