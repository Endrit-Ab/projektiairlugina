<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create($email, $password, $firstName, $lastName, $role = 'user')
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (email, password_hash, first_name, last_name, role) VALUES (?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email, $hash, $firstName, $lastName, $role]);
        return (int) $this->db->lastInsertId();
    }

    public function findByEmail($email)
    {
        $sql = 'SELECT id, email, password_hash, first_name, last_name, role, created_at FROM users WHERE email = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function findById($id)
    {
        $sql = 'SELECT id, email, first_name, last_name, role, created_at FROM users WHERE id = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function verifyPassword($plain, $hash)
    {
        return password_verify($plain, $hash);
    }

    public function emailExists($email, $excludeId = null)
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
