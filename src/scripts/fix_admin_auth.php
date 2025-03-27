<?php
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../lib/Session.php';

// Start session
Session::start();

echo "Running admin authentication fix...\n";

// Get database connection
$db = Database::getInstance();

// Delete existing admin user
echo "Removing existing admin user if any...\n";
$db->query('DELETE FROM users WHERE email = ?', ['admin@example.com']);

// Create a new admin user with secure password
$name = 'Admin User';
$email = 'admin@example.com';
$password = 'securepassword123'; 
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$isAdmin = 1;

echo "Creating new admin user with proper password hash...\n";
$result = $db->query(
    'INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)',
    [$name, $email, $hashedPassword, $isAdmin]
);

if ($result) {
    echo "Admin user created successfully!\n";
} else {
    echo "Failed to create admin user.\n";
    exit;
}

// Verify the admin user exists and password works
$stmt = $db->query('SELECT * FROM users WHERE email = ? LIMIT 1', [$email]);
$user = $stmt->fetch();

if (!$user) {
    echo "ERROR: Admin user not found after creation!\n";
    exit;
}

// Test password verification
$passwordVerified = password_verify($password, $user['password']);
echo "Password verification test: " . ($passwordVerified ? "SUCCESS" : "FAILURE") . "\n";

// Create a special login session to bypass any potential issues
if ($passwordVerified) {
    echo "Setting up admin session directly...\n";
    
    // Store admin data in session
    Session::set('user_id', $user['id']);
    Session::set('user_name', $user['name']);
    Session::set('user_email', $user['email']);
    Session::set('is_admin', 1);
    
    echo "Session data set. You should now be logged in as admin.\n";
    echo "Try accessing admin features at: http://localhost/admin/dashboard\n";
} else {
    echo "Password verification failed. Cannot set up admin session.\n";
}

?> 