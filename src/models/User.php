<?php
require_once __DIR__ . '/../lib/Database.php';

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function getAll() {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
    
    public function create($name, $email, $message) {
        if (empty($name) || empty($email)) {
            return false;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        if (strlen($name) > 100 || strlen($email) > 100 || strlen($message) > 1000) {
            return false;
        }
        
        return $this->db->query(
            'INSERT INTO users (name, email, message) VALUES (?, ?, ?)',
            [$name, $email, $message]
        );
    }
} 