<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public static function get($key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }
    
    public static function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateCsrfToken($token) {
        return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
    }
    
    public static function regenerateCsrfToken() {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
    
    public static function flash($key, $message = null) {
        if ($message === null) {
            $message = self::get($key);
            self::delete($key);
            return $message;
        }
        
        self::set($key, $message);
    }
    
    public static function delete($key) {
        unset($_SESSION[$key]);
    }
} 