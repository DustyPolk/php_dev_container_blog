<?php
require_once __DIR__ . '/../lib/Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login($email, $password) {
        $stmt = $this->db->query('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }
    
    public function register($name, $email, $password, $isAdmin = false) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        return $this->db->query(
            'INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)',
            [$name, $email, $hashedPassword, $isAdmin ? 1 : 0]
        );
    }
} 