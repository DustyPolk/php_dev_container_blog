<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../lib/Session.php';

class UserController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function index() {
        $users = $this->userModel->getAll();
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/users/index.php';
    }
    
    public function store() {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /users');
            exit;
        }
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $message = $_POST['message'] ?? '';
        
        if (empty($name) || empty($email)) {
            Session::flash('error', 'Name and email are required');
            header('Location: /users');
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Please enter a valid email address');
            header('Location: /users');
            exit;
        }
        
        if (strlen($name) > 100 || strlen($email) > 100 || strlen($message) > 1000) {
            Session::flash('error', 'Input exceeds maximum allowed length');
            header('Location: /users');
            exit;
        }
        
        $result = $this->userModel->create($name, $email, $message);
        
        if ($result) {
            Session::flash('success', 'Thank you! Your information has been saved');
        } else {
            Session::flash('error', 'Failed to save your information');
        }
        
        header('Location: /users');
        exit;
    }
} 