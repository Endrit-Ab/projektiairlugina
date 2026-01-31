<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
define('IS_ADMIN', (strpos($script, '/admin/') !== false));

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/News.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Contact.php';
require_once __DIR__ . '/classes/Validator.php';
