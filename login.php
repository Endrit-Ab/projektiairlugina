<?php
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
    
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
              
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                
               
                if ($remember) {
                    setcookie('user_email', $email, time() + (86400 * 30), "/"); 
                }
                
            
                header("Location: landingpage.php");
                exit();
            } else {
                $error = 'Invalid email or password.';
            }
        } else {
            $error = 'Invalid email or password.';
        }
        $stmt->close();
    }
}


$remembered_email = $_COOKIE['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="assets/login.css">
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
    </style>
</head>
<body>
    <div class="container">
        <img class="AirLugina" src="assets/Images/AirLugina.png" alt="">
        <img src="assets/Images/Rectangle_20.png" alt="">
        <br><br><br><br>
        <h1>Login</h1>
        <p class="p1">Login to access your AirLugina account.</p>
        <div class="table">
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="inputbox"> 
                    <fieldset class="fieldset">
                        <legend>Email</legend>
                        <input type="email" name="email" placeholder="name@email.com" value="<?php echo htmlspecialchars($remembered_email); ?>" required>
                    </fieldset>
                </div>
                <div class="inputbox"> 
                    <fieldset class="fieldset">
                        <legend>Password</legend>
                        <input id="passwordField" name="password" type="password" placeholder="password" required>
                        <i id="togglePassword" class='bx bx-hide'></i>
                    </fieldset>
                </div>
                <div class="remember">
                    <label><input type="checkbox" name="remember" <?php echo $remembered_email ? 'checked' : ''; ?>>Remember me</label>
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
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('passwordField');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.classList.toggle('bx-hide');
            this.classList.toggle('bx-show');
        });
    </script>
</body>
</html>
