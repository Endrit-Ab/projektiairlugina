<?php
<<<<<<< HEAD
session_start();


$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}


session_destroy();


header("Location: login.php");
exit();
?>
=======
require_once __DIR__ . '/init.php';
$auth = new Auth();
$auth->logout();
header('Location: index.php');
exit;
>>>>>>> 5b46a16e0c24470fe3f79b33b169e80e11f47477
