<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms = isset($_POST['terms']);
    
    
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (!$terms) {
        $error = 'You must agree to the Terms and Privacy Policies.';
    } else {
       
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $success = 'Account created successfully! You can now login.';
               
                $first_name = $last_name = $email = '';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="assets/signupfinal.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .alert {
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #ffe6e6;
            color: #cc0000;
            border: 1px solid #cc0000;
        }
        .alert-success {
            background-color: #e6ffe6;
            color: #006600;
            border: 1px solid #006600;
        }
    </style>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="assets/Images/Air-Lugina-Logo.png" alt="">
        <img src="assets/Images/Rectangle_20.png" alt="">
        <br><br>
        <h1>Sign Up</h1>
        <p class="p1">Let's get you all set up so you can access your personal account.</p>
        <div class="table">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            <form id="signupForm" method="POST" action="">
                <div class="inputbox" id="box1">
                    <fieldset class="fieldset">
                        <legend>First Name</legend>
                        <input type="text" name="first_name" placeholder="First Name" id="nameField" value="<?php echo htmlspecialchars($first_name ?? ''); ?>" required>
                    </fieldset>
                </div>
                <div class="inputbox" id="box2">
                    <fieldset class="fieldset">
                        <legend>Last Name</legend>
                        <input type="text" name="last_name" placeholder="Last Name" id="surnameField" value="<?php echo htmlspecialchars($last_name ?? ''); ?>" required>
                    </fieldset>
                </div>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Email</legend>
                        <input type="email" name="email" placeholder="name@email.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </fieldset>
                </div>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Password</legend>
                        <input type="password" name="password" placeholder="Password" id="passwordField" required>
                        <i class="bx bx-hide toggle-password" id="togglePassword"></i>
                    </fieldset>
                </div>
                <div class="inputbox">
                    <fieldset class="fieldset">
                        <legend>Confirm Password</legend>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirmPasswordField" required>
                        <i class="bx bx-hide toggle-password" id="toggleConfirmPassword"></i>
                    </fieldset>
                </div>
                <div class="remember">
                    <label>
                        <input type="checkbox" name="terms" id="terms" required> I agree to all the Terms and Privacy Policies
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
        
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passwordField');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirmPasswordField');
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
    </script>
</body>
</html>
