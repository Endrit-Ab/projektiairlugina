<?php
if (!isset($auth)) {
    require_once __DIR__ . '/../init.php';
    $auth = new Auth();
}
$pageTitle = $pageTitle ?? 'AirLugina';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> - AirLugina</title>
    <link rel="stylesheet" href="Assets/landingpage.css">
    <style>.nav-links{display:flex;gap:1rem;justify-content:center;padding:0.75rem;background:#f5f5f5;flex-wrap:wrap}.nav-links a{color:#333;text-decoration:none}.nav-links a:hover{text-decoration:underline}.user-name-nav{margin-right:0.5rem;align-self:center}</style>
</head>
<body>
    <div class="hero">
        <div class="hero-overlay">
            <nav class="navbar">
                <div style="display: flex;" class="left-nav">
                    <img style="margin-right: 5px;" src="Assets/Images/logo.png" alt="">
                    <a href="index.php">Find Flight</a>
                </div>
                <div class="logo">
                    <span><img src="Assets/Images/AirLuginaBARDH.png" alt=""></span>
                </div>
                <div class="buttons">
                    <?php if ($auth->isLoggedIn()): ?>
                        <span class="user-name-nav"><?= htmlspecialchars($auth->userName()) ?></span>
                        <?php if ($auth->isAdmin()): ?>
                            <button class="log"><a href="admin/dashboard.php">Dashboard</a></button>
                        <?php endif; ?>
                        <button class="sig"><a href="logout.php">Logout</a></button>
                    <?php else: ?>
                        <button class="log"><a href="login.php">Login</a></button>
                        <button class="sig"><a href="signup.php">Sign up</a></button>
                    <?php endif; ?>
                </div>
            </nav>
            <h3><?= htmlspecialchars($heroSubtitle ?? 'Helping Others') ?></h3>
            <h1><?= htmlspecialchars($heroTitle ?? 'LIVE & TRAVEL') ?></h1>
            <p><?= htmlspecialchars($heroDesc ?? 'Special offers to suit your plan') ?></p>
        </div>
    </div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="products.php">Products</a>
        <a href="news.php">News</a>
        <a href="contact.php">Contact Us</a>
    </nav>
