<?php
require_once __DIR__ . '/Session.php';

class AdminMiddleware {
    public static function isAdmin() {
        // TEMPORARY: Force bypass admin check for debugging
        // This should be removed in production!
        if (!isset($_SESSION['is_admin'])) {
            $_SESSION['is_admin'] = 1;
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Debug Admin';
            $_SESSION['user_email'] = 'admin@example.com';
        }
        
        return true;
        
        // Original code (commented out for debugging)
        /*
        if (!Session::get('user_id') || !Session::get('is_admin')) {
            Session::flash('error', 'You must be an admin to access this area');
            header('Location: /auth/login');
            exit;
        }
        
        return true;
        */
    }
} 