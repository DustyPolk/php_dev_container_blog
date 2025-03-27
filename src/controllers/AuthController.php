<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../lib/Session.php';

class AuthController {
    private $authModel;
    
    public function __construct() {
        $this->authModel = new Auth();
    }
    
    public function showLogin() {
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /auth/login');
            exit;
        }
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            Session::flash('error', 'Email and password are required');
            header('Location: /auth/login');
            exit;
        }
        
        $user = $this->authModel->login($email, $password);
        
        if ($user) {
            // Store user data in session
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('is_admin', $user['is_admin']);
            
            Session::flash('success', 'Login successful');
            
            // Redirect based on user role
            if ($user['is_admin']) {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /');
            }
            exit;
        } else {
            Session::flash('error', 'Invalid credentials');
            header('Location: /auth/login');
            exit;
        }
    }
    
    public function logout() {
        Session::destroy();
        header('Location: /');
        exit;
    }
} 