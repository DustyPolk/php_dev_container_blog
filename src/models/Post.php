<?php
require_once __DIR__ . '/../lib/Database.php';

class Post {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM posts ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->query('SELECT * FROM posts WHERE id = ?', [$id]);
        return $stmt->fetch();
    }
    
    public function create($title, $content, $author) {
        return $this->db->query(
            'INSERT INTO posts (title, content, author) VALUES (?, ?, ?)',
            [$title, $content, $author]
        );
    }
    
    public function update($id, $title, $content) {
        return $this->db->query(
            'UPDATE posts SET title = ?, content = ? WHERE id = ?',
            [$title, $content, $id]
        );
    }
    
    public function delete($id) {
        return $this->db->query('DELETE FROM posts WHERE id = ?', [$id]);
    }
} 