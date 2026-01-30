<?php
/**
 * Modeli Contact - mesazhe kontakti dhe faqe (OOP)
 * AirLugina Faza 2
 */

use PDO;

class Contact
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function saveMessage(string $name, string $email, string $subject, string $message): int
    {
        $sql = 'INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $email, $subject, $message]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllMessages(bool $unreadOnly = false): array
    {
        $sql = 'SELECT * FROM contact_messages';
        if ($unreadOnly) {
            $sql .= ' WHERE read_at IS NULL';
        }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getMessageById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM contact_messages WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function markAsRead(int $id): bool
    {
        $stmt = $this->db->prepare('UPDATE contact_messages SET read_at = NOW() WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getPageBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getSliderItems(): array
    {
        $stmt = $this->db->query('SELECT * FROM slider ORDER BY sort_order ASC, id ASC');
        return $stmt->fetchAll();
    }
}
