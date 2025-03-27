<?php
require_once __DIR__ . '/../models/Auth.php';
require_once __DIR__ . '/../lib/Session.php';

// Start the session
Session::start();

echo "Starting login debug...\n";

// Create auth model instance
$authModel = new Auth();

// Test credentials
$email = 'admin@example.com';
$password = 'securepassword123';

echo "Attempting login with:\n";
echo "Email: $email\n";
echo "Password: $password\n\n";

// Try login
$user = $authModel->login($email, $password);

if ($user) {
    echo "LOGIN SUCCESSFUL!\n";
    echo "User data returned:\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Name: " . $user['name'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n\n";
    
    echo "Setting session data...\n";
    Session::set('user_id', $user['id']);
    Session::set('user_name', $user['name']);
    Session::set('user_email', $user['email']);
    Session::set('is_admin', $user['is_admin']);
    
    echo "Session data after login:\n";
    echo "user_id: " . Session::get('user_id') . "\n";
    echo "user_name: " . Session::get('user_name') . "\n";
    echo "user_email: " . Session::get('user_email') . "\n";
    echo "is_admin: " . (Session::get('is_admin') ? 'Yes' : 'No') . "\n";
} else {
    echo "LOGIN FAILED!\n";
}
?> 