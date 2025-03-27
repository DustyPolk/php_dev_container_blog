<?php
require_once __DIR__ . '/../models/Auth.php';

// Create initial admin user
$auth = new Auth();
$name = 'Admin User';
$email = 'admin@example.com';
$password = 'securepassword123'; // Change this!
$isAdmin = true;

$result = $auth->register($name, $email, $password, $isAdmin);

if ($result) {
    echo "Admin user created successfully!\n";
} else {
    echo "Failed to create admin user.\n";
} 