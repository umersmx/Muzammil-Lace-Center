<?php
require_once __DIR__ . '/../includes/db.php';
session_start();
$_SESSION = [];
setcookie(session_name(), '', time() - 42000, '/');
session_destroy();
header('Location: login.php');
exit;
