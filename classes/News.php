<?php
class News
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getAll($limit = 50)
    {
        $limit = (int) $limit;
        if ($limit <= 0) $limit = 50;
        $sql = "SELECT n.*, 
                u1.first_name AS created_by_name, u1.last_name AS created_by_surname,
                u2.first_name AS updated_by_name, u2.last_name AS updated_by_surname
                FROM news n
                LEFT JOIN users u1 ON n.created_by = u1.id
                LEFT JOIN users u2 ON n.updated_by = u2.id
                ORDER BY n.created_at DESC LIMIT " . $limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT n.*, 
                u1.first_name AS created_by_name, u1.last_name AS created_by_surname,
                u2.first_name AS updated_by_name, u2.last_name AS updated_by_surname
                FROM news n
                LEFT JOIN users u1 ON n.created_by = u1.id
                LEFT JOIN users u2 ON n.updated_by = u2.id
                WHERE n.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getBySlug($slug)
    {
        $sql = "SELECT n.*, 
                u1.first_name AS created_by_name, u1.last_name AS created_by_surname,
                u2.first_name AS updated_by_name, u2.last_name AS updated_by_surname
                FROM news n 
                LEFT JOIN users u1 ON n.created_by = u1.id
                LEFT JOIN users u2 ON n.updated_by = u2.id
                WHERE n.slug = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create($data, $createdBy)
    {
        $slug = $this->generateSlug($data['title']);
        $sql = 'INSERT INTO news (title, slug, content, image_path, pdf_path, created_by) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $slug,
            $data['content'] ?? '',
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $createdBy
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data, $updatedBy)
    {
        $sql = 'UPDATE news SET title = ?, content = ?, image_path = ?, pdf_path = ?, updated_by = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['content'] ?? '',
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $updatedBy,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM news WHERE id = ?');
        return $stmt->execute([$id]);
    }

    private function generateSlug($title)
    {
        $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower(trim($title)));
        $slug = trim($slug, '-');
        $base = $slug;
        $i = 0;
        while (true) {
            $stmt = $this->db->prepare('SELECT 1 FROM news WHERE slug = ? LIMIT 1');
            $stmt->execute([$slug]);
            if (!$stmt->fetch()) {
                return $slug;
            }
            $slug = $base . '-' . (++$i);
        }
    }
}
