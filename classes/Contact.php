<?php
class Contact
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function saveMessage($name, $email, $subject, $message)
    {
        $sql = 'INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$name, $email, $subject, $message]);
        return (int) $this->db->lastInsertId();
    }

    public function getAllMessages($unreadOnly = false)
    {
        $sql = 'SELECT * FROM contact_messages';
        if ($unreadOnly) {
            $sql .= ' WHERE read_at IS NULL';
        }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getMessageById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM contact_messages WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function markAsRead($id)
    {
        $stmt = $this->db->prepare('UPDATE contact_messages SET read_at = NOW() WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function getPageBySlug($slug)
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getSliderItems()
    {
        $stmt = $this->db->query('SELECT * FROM slider ORDER BY sort_order ASC, id ASC');
        return $stmt->fetchAll();
    }
}
