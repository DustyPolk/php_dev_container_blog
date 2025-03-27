<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../lib/Session.php';

class DebugController {
    private $authModel;
    
    public function __construct() {
        $this->authModel = new Auth();
    }
    
    public function loginForm() {
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/debug/login-form.php';
    }
    
    public function processLogin() {
        // Create a log file for debugging
        $logFile = __DIR__ . '/../logs/login-debug.log';
        
        // Log the request method
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Request Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
        
        // Log POST data
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - POST data: " . print_r($_POST, true) . "\n", FILE_APPEND);
        
        // Log session state
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Session before login: " . print_r($_SESSION, true) . "\n", FILE_APPEND);
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Log credentials (only for debugging, remove in production)
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - Email: $email, Password: $password\n", FILE_APPEND);
        
        $user = $this->authModel->login($email, $password);
        
        if ($user) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Login SUCCESS, user data: " . print_r($user, true) . "\n", FILE_APPEND);
            
            // Store user data in session
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('is_admin', $user['is_admin']);
            
            // Log session after setting data
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Session after login: " . print_r($_SESSION, true) . "\n", FILE_APPEND);
            
            echo "Login successful! <a href='/admin/dashboard'>Go to Dashboard</a>";
        } else {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Login FAILED\n", FILE_APPEND);
            echo "Login failed!";
        }
    }
} 