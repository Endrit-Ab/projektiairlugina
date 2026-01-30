<?php
/**
 * Auth - autentifikim dhe sesion (OOP)
 * AirLugina Faza 2
 */

class Auth
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function login(string $email, string $password): array
    {
        $errors = [];
        if (empty(trim($email))) {
            $errors['email'] = 'Email-i është i detyrueshëm.';
        }
        if (empty($password)) {
            $errors['password'] = 'Fjalëkalimi është i detyrueshëm.';
        }
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $u = $this->user->findByEmail(trim($email));
        if (!$u || !$this->user->verifyPassword($password, $u['password_hash'])) {
            $errors['login'] = 'Email ose fjalëkalim i gabuar.';
            return ['success' => false, 'errors' => $errors];
        }

        $_SESSION['user_id'] = (int) $u['id'];
        $_SESSION['user_email'] = $u['email'];
        $_SESSION['user_role'] = $u['role'];
        $_SESSION['user_name'] = trim($u['first_name'] . ' ' . $u['last_name']);
        return ['success' => true, 'user' => $u];
    }

    public function register(array $data): array
    {
        $errors = $this->validateRegister($data);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        $email = trim($data['email']);
        if ($this->user->emailExists($email)) {
            $errors['email'] = 'Ky email është i regjistruar tashmë.';
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

    private function validateRegister(array $data): array
    {
        $errors = [];
        if (empty(trim($data['first_name'] ?? ''))) {
            $errors['first_name'] = 'Emri është i detyrueshëm.';
        }
        if (empty(trim($data['last_name'] ?? ''))) {
            $errors['last_name'] = 'Mbiemri është i detyrueshëm.';
        }
        if (empty(trim($data['email'] ?? ''))) {
            $errors['email'] = 'Email-i është i detyrueshëm.';
        } elseif (!filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email-i nuk është i vlefshëm.';
        }
        $pass = $data['password'] ?? '';
        if (strlen($pass) < 6) {
            $errors['password'] = 'Fjalëkalimi duhet të ketë të paktën 6 karaktere.';
        }
        if (($data['password_confirm'] ?? '') !== $pass) {
            $errors['password_confirm'] = 'Fjalëkalimet nuk përputhen.';
        }
        return $errors;
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public function isAdmin(): bool
    {
        return ($_SESSION['user_role'] ?? '') === 'admin';
    }

    public function requireLogin(string $redirectTo = 'login.php'): void
    {
        if (!$this->isLoggedIn()) {
            header('Location: ' . $redirectTo . '?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
    }

    public function requireAdmin(string $redirectTo = 'index.php'): void
    {
        $this->requireLogin('login.php');
        if (!$this->isAdmin()) {
            header('Location: ' . $redirectTo);
            exit;
        }
    }

    public function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public function userRole(): ?string
    {
        return $_SESSION['user_role'] ?? null;
    }

    public function userName(): string
    {
        return $_SESSION['user_name'] ?? 'Guest';
    }
}
