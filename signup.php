<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();

$errors = [];
$success = false;
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $auth->register($_POST);
    if ($result['success']) {
        $success = true;
    } else {
        $errors = $result['errors'];
        $old = $_POST;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - AirLugina</title>
    <link rel="stylesheet" href="assets/signupfinal.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>.field-error{color:red;font-size:0.85rem;display:block;margin-top:4px}.success-msg{color:green;margin-bottom:1rem}</style>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="assets/Images/Air-Lugina-Logo.png" alt="">
        <img src="assets/Images/Rectangle_20.png" alt="">
        <br><br>
        <h1>Sign Up</h1>
        <p class="p1">Let's get you all set up so you can access your personal account.</p>
        <div class="table">
            <?php if ($success): ?>
                <p class="success-msg">Llogaria u krijua me sukses! <a href="login.php">Kyçu tani</a></p>
            <?php endif; ?>
            <form id="signupForm" method="POST" action="">
                <div class="inputbox" id="box1">
                    <fieldset class="fieldset">
                        <legend>First Name</legend>
                        <input type="text" name="first_name" placeholder="First Name" value="<?= htmlspecialchars($old['first_name'] ?? '') ?>" required>
                    </fieldset>
                    <?php if (!empty($errors['first_name'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['first_name']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="inputbox" id="box2">
                    <fieldset class="fieldset">
                        <legend>Last Name</legend>
                        <input type="text" name="last_name" placeholder="Last Name" value="<?= htmlspecialchars($old['last_name'] ?? '') ?>" required>
                    </fieldset>
                    <?php if (!empty($errors['last_name'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['last_name']) ?></span>
                    <?php endif; ?>
                </div>
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
                        <input type="password" name="password" placeholder="Password" id="passwordField" required>
                        <i class="bx bx-hide toggle-password" id="togglePassword"></i>
                    </fieldset>
                    <?php if (!empty($errors['password'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['password']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Confirm Password</legend>
                        <input type="password" name="password_confirm" placeholder="Confirm Password" id="confirmPasswordField" required>
                        <i class="bx bx-hide toggle-password" id="toggleConfirmPassword"></i>
                    </fieldset>
                    <?php if (!empty($errors['password_confirm'])): ?>
                        <span class="field-error"><?= htmlspecialchars($errors['password_confirm']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="remember">
                    <label><input type="checkbox" name="terms" required> I agree to all the Terms and Privacy Policies</label>
                </div>
                <button type="submit">Create account</button>
                <div class="register">
                    <p>Already have an account? <a href="login.php">Login</a></p>
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
    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        var f = document.getElementById('confirmPasswordField');
        f.type = f.type === 'password' ? 'text' : 'password';
        this.classList.toggle('bx-hide');
        this.classList.toggle('bx-show');
    });
    document.getElementById('signupForm').addEventListener('submit', function(e) {
        var fn = document.querySelector('input[name="first_name"]').value.trim();
        var ln = document.querySelector('input[name="last_name"]').value.trim();
        var em = document.querySelector('input[name="email"]').value.trim();
        var pw = document.getElementById('passwordField').value;
        var pc = document.getElementById('confirmPasswordField').value;
        if (!fn) { e.preventDefault(); alert('Emri është i detyrueshëm.'); return; }
        if (!ln) { e.preventDefault(); alert('Mbiemri është i detyrueshëm.'); return; }
        if (!em) { e.preventDefault(); alert('Email-i është i detyrueshëm.'); return; }
        if (pw.length < 6) { e.preventDefault(); alert('Fjalëkalimi duhet të ketë të paktën 6 karaktere.'); return; }
        if (pw !== pc) { e.preventDefault(); alert('Fjalëkalimet nuk përputhen.'); return; }
    });
    </script>
</body>
</html>
