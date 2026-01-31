<?php
class Auth
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login($email, $password)
    {
        $errors = [];
        if (empty(trim($email))) {
            $errors['email'] = 'Email-i eshte i detyrueshm.';
        }
        if (empty($password)) {
            $errors['password'] = 'Fjalekalimi eshte i detyrueshm.';
        }
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $u = $this->user->findByEmail(trim($email));
        if (!$u || !$this->user->verifyPassword($password, $u['password_hash'])) {
            $errors['login'] = 'Email ose fjalekalim i gabuar.';
            return ['success' => false, 'errors' => $errors];
        }

        $_SESSION['user_id'] = (int) $u['id'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_role'] = $u['role'];
        $_SESSION['user_name'] = trim($u['first_name'] . ' ' . $u['last_name']);
        return ['success' => true, 'user' => $u];
    }

    public function register($data)
    {
        $errors = $this->validateRegister($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $email = trim($data['email']);
        if ($this->user->emailExists($email)) {
            $errors['email'] = 'Ky email eshte i regjistruar tashme.';
            return ['success' => false, 'errors' => $errors];
        }

        $this->user->create(
            $email,
            $data['password'],
            trim($data['first_name']),
            trim($data['last_name']),
            'user'
        );
        return ['success' => true];
    }

    private function validateRegister($data)
    {
        $errors = [];
        if (empty(trim($data['first_name'] ?? ''))) {
            $errors['first_name'] = 'Emri eshte i detyrueshm.';
        }
        if (empty(trim($data['last_name'] ?? ''))) {
            $errors['last_name'] = 'Mbiemri eshte i detyrueshm.';
        }
        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Email-i eshte i detyrueshm.';
        } elseif (!filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email-i nuk eshte i vlefshem.';
        }
        $pass = $data['password'] ?? '';
        if (strlen($pass) < 6) {
            $errors['password'] = 'Fjalekalimi duhet te kete te pakten 6 karaktere.';
        }
        if (($data['password_confirm'] ?? '') !== $pass) {
            $errors['password_confirm'] = 'Fjalekalimi nuk perputhen.';
        }
        return $errors;
    }

    public function logout()
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public function isLoggedIn()
    {
        return !empty($_SESSION['user_id']);
    }

    public function isAdmin()
    {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    public function requireLogin($redirectTo = 'login.php')
    {
        if (!$this->isLoggedIn()) {
            $prefix = (defined('IS_ADMIN') && IS_ADMIN) ? '../' : '';
            $url = $prefix . $redirectTo;
            if ($redirectTo === 'login.php' && $prefix === '../') {
                $url .= '?redirect=' . urlencode('admin/dashboard.php');
            }
            header('Location: ' . $url);
            exit;
        }
    }

    public function requireAdmin($redirectTo = 'index.php')
    {
        $prefix = (defined('IS_ADMIN') && IS_ADMIN) ? '../' : '';
        $this->requireLogin('login.php');
        if (!$this->isAdmin()) {
            header('Location: ' . $prefix . $redirectTo);
            exit;
        }
    }

    public function userId()
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public function userRole()
    {
        return $_SESSION['user_role'] ?? null;
    }

    public function userName()
    {
        return $_SESSION['user_name'] ?? 'Guest';
    }
}
