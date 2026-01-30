<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();

if ($auth->isLoggedIn()) {
    $redirect = $_GET['redirect'] ?? 'index.php';
    header('Location: ' . $redirect);
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $result = $auth->login($email, $password);
    if ($result['success']) {
        $redirect = $_GET['redirect'] ?? 'index.php';
        header('Location: ' . $redirect);
        exit;
    }
    $errors = $result['errors'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AirLugina</title>
    <link rel="stylesheet" href="Assets/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="Assets/Images/AirLugina.png" alt="">
        <img src="Assets/Images/Rectangle_20.png" alt="">
        <br><br><br><br>
        <h1>Login</h1>
        <p class="p1">Login to access your AirLugina account.</p>
        <?php if (!empty($errors['login'])): ?>
            <p class="error-msg"><?= htmlspecialchars($errors['login']) ?></p>
        <?php endif; ?>
        <div class="table">
            <form method="post" action="" id="loginForm">
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Email</legend>
                        <input type="email" name="email" placeholder="name@email.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </fieldset>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Password</legend>
                        <input id="passwordField" type="password" name="password" placeholder="password" required>
                        <i id="togglePassword" class='bx bx-hide'></i>
                    </fieldset>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
                <div class="remember">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" id="button-submit">Login</button>
                <div class="register">
                    <p>Don't have an account?
                        <a href="signup.php">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <script src="Assets/login.js"></script>
</body>
</html>
