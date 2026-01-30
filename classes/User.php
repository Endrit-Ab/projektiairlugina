<?php
/**
 * Modeli User - përdoruesit (OOP)
 * AirLugina Faza 2
 */

use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(string $email, string $password, string $firstName, string $lastName, string $role = 'user'): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $hash, $firstName, $lastName, $role]);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = 'SELECT id, email, password_hash, first_name, last_name, role, created_at FROM users WHERE email = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, email, first_name, last_name, role, created_at FROM users WHERE id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function verifyPassword(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT 1 FROM users WHERE email = ?';
        $params = [$email];
        if ($excludeId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }
}
