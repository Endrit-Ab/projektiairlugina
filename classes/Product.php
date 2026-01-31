<?php
class Product
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
        $sql = "SELECT p.*, 
                u1.first_name AS created_by_name, u1.last_name AS created_by_surname,
                u2.first_name AS updated_by_name, u2.last_name AS updated_by_surname
                FROM products p
                LEFT JOIN users u1 ON p.created_by = u1.id
                LEFT JOIN users u2 ON p.updated_by = u2.id
                ORDER BY p.created_at DESC LIMIT " . $limit;
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $sql = "SELECT p.*, 
                u1.first_name AS created_by_name, u1.last_name AS created_by_surname,
                u2.first_name AS updated_by_name, u2.last_name AS updated_by_surname
                FROM products p
                LEFT JOIN users u1 ON p.created_by = u1.id
                LEFT JOIN users u2 ON p.updated_by = u2.id
                WHERE p.id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create($data, $createdBy)
    {
        $sql = 'INSERT INTO products (title, description, from_location, to_location, price, image_path, pdf_path, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['from_location'] ?? null,
            $data['to_location'] ?? null,
            $data['price'] ?? null,
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $createdBy
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update($id, $data, $updatedBy)
    {
        $sql = 'UPDATE products SET title = ?, description = ?, from_location = ?, to_location = ?, price = ?, image_path = ?, pdf_path = ?, updated_by = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? null,
            $data['from_location'] ?? null,
            $data['to_location'] ?? null,
            $data['price'] ?? null,
            $data['image_path'] ?? null,
            $data['pdf_path'] ?? null,
            $updatedBy,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM products WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
