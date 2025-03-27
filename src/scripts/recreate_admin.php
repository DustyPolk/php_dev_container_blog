<?php
require_once __DIR__ . '/../lib/Database.php';

// Get database connection
$db = Database::getInstance();

// Delete existing admin user if any
$db->query('DELETE FROM users WHERE email = ?', ['admin@example.com']);

// Create admin user with proper password hash
$name = 'Admin User';
$email = 'admin@example.com';
$password = 'securepassword123'; // Change this in production!
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$isAdmin = 1;

$result = $db->query(
    'INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)',
    [$name, $email, $hashedPassword, $isAdmin]
);

if ($result) {
    echo "Admin user recreated successfully!\n";
    echo "Email: admin@example.com\n";
    echo "Password: securepassword123\n";
} else {
    echo "Failed to recreate admin user.\n";
}
?> 