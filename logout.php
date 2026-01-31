<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$auth->logout();
header('Location: login.php');
exit;
