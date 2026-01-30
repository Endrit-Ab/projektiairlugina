<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();

if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old = $_POST;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->register([
        'first_name' => $_POST['first_name'] ?? '',
        'last_name'  => $_POST['last_name'] ?? '',
        'email'      => $_POST['email'] ?? '',
        'password'   => $_POST['password'] ?? '',
        'password_confirm' => $_POST['password_confirm'] ?? '',
    ]);
    if ($result['success']) {
        header('Location: login.php?registered=1');
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
    <title>Sign Up - AirLugina</title>
    <link rel="stylesheet" href="Assets/signupfinal.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="Assets/Images/Air-Lugina-Logo.png" alt="">
        <img src="Assets/Images/Rectangle_20.png" alt="">
        <br><br>
        <h1>Sign Up</h1>
        <p class="p1">Let's get you all set up so you can access your personal account.</p>
        <div class="table">
            <form id="signupForm" method="post" action="">
                <div class="inputbox" id="box1">
                    <fieldset class="fieldset">
                        <legend>First Name</legend>
                        <input type="text" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required>
                    </fieldset>
                </div>
                <?php if (!empty($errors['first_name'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['first_name']) ?></p>
                <?php endif; ?>
                <div class="inputbox" id="box2">
                    <fieldset class="fieldset">
                        <legend>Last Name</legend>
                        <input type="text" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required>
                    </fieldset>
                </div>
                <?php if (!empty($errors['last_name'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['last_name']) ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Email</legend>
                        <input type="email" name="email" placeholder="name@email.com" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                    </fieldset>
                </div>
                <?php if (!empty($errors['email'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Password</legend>
                        <input type="password" name="password" placeholder="Password" id="passwordField" required minlength="6">
                        <i class="bx bx-hide toggle-password" id="togglePassword"></i>
                    </fieldset>
                </div>
                <?php if (!empty($errors['password'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Confirm Password</legend>
                        <input type="password" name="password_confirm" placeholder="Confirm Password" id="confirmPasswordField" required>
                        <i class="bx bx-hide toggle-password" id="togglePassword2"></i>
                    </fieldset>
                </div>
                <?php if (!empty($errors['password_confirm'])): ?>
                    <p class="field-error"><?= htmlspecialchars($errors['password_confirm']) ?></p>
                <?php endif; ?>
                <div class="remember">
                    <label>
                        <input type="checkbox" name="terms" required> I agree to all the Terms and Privacy Policies
                    </label>
                </div>
                <button type="submit">Create account</button>
                <div class="register">
                    <p>Already have an account?
                        <a href="login.php">Login</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <script>
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        var p = document.querySelector('input[name="password"]').value;
        var c = document.querySelector('input[name="password_confirm"]').value;
        if (p.length < 6) {
            e.preventDefault();
            alert('Fjalëkalimi duhet të ketë të paktën 6 karaktere.');
            return false;
        }
        if (p !== c) {
            e.preventDefault();
            alert('Fjalëkalimet nuk përputhen.');
            return false;
        }
    });
    </script>
</body>
</html>
