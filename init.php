<?php
/**
 * Bootstrap - ngarkon klasat dhe konfigurimin (OOP)
 * Përdor: require_once __DIR__ . '/init.php'; ose require_once 'init.php';
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('BASE_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Auth.php';
require_once __DIR__ . '/classes/News.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Contact.php';
require_once __DIR__ . '/classes/Validator.php';
