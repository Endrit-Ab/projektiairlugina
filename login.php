<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->login($_POST['email'] ?? '', $_POST['password'] ?? '');
    if ($result['success']) {
        $redirect = $_POST['redirect'] ?? $_GET['redirect'] ?? 'index.php';
        if ($redirect === '' || strpos($redirect, '..') !== false) $redirect = 'index.php';
        header('Location: ' . $redirect);
        exit;
    }
    $errors = $result['errors'];
    $old = $_POST;
}
$redirectParam = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : 'index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AirLugina</title>
    <link rel="stylesheet" href="assets/login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>.field-error{color:red;font-size:0.85rem;display:block;margin-top:4px}</style>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="assets/Images/AirLugina.png" alt="">
        <img src="assets/Images/Rectangle_20.png" alt="">
        <br><br><br><br>
        <h1>Login</h1>
        <p class="p1">Login to access your AirLugina account.</p>
        <div class="table">
            <?php if (!empty($errors['login'])): ?>
                <p class="field-error"><?= htmlspecialchars($errors['login']) ?></p>
            <?php endif; ?>
            <form method="POST" action="" id="loginForm">
                <input type="hidden" name="redirect" value="<?= $redirectParam ?>">
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Email</legend>
                        <input type="email" name="email" placeholder="name@email.com" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    </fieldset>
                    <?php if (!empty($errors['email'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['email']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Password</legend>
                        <input id="passwordField" name="password" type="password" placeholder="password" required>
                        <i id="togglePassword" class="bx bx-hide"></i>
                    </fieldset>
                    <?php if (!empty($errors['password'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['password']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="remember">
                    <label><input type="checkbox" name="remember">Remember me</label>
                </div>
                <button type="submit" id="button-submit">Login</button>
                <div class="register">
                    <p>Don't have an account? <a href="signup.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var f = document.getElementById('passwordField');
        f.type = f.type === 'password' ? 'text' : 'password';
        this.classList.toggle('bx-hide');
        this.classList.toggle('bx-show');
    });
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        var email = document.querySelector('input[name="email"]').value.trim();
        var pass = document.getElementById('passwordField').value;
        if (!email) { e.preventDefault(); alert('Email-i është i detyrueshëm.'); return; }
        if (!pass) { e.preventDefault(); alert('Fjalëkalimi është i detyrueshëm.'); return; }
    });
    </script>
</body>
</html>
