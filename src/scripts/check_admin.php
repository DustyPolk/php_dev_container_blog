<?php
require_once __DIR__ . '/../lib/Database.php';

// Get database connection
$db = Database::getInstance();

// Check if the user exists
$stmt = $db->query('SELECT * FROM users WHERE email = ?', ['admin@example.com']);
$user = $stmt->fetch();

if ($user) {
    echo "Admin user exists:\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Name: " . $user['name'] . "\n";
    echo "Email: " . $user['email'] . "\n";
    echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
    echo "Has Password: " . (empty($user['password']) ? 'No' : 'Yes') . "\n";
} else {
    echo "Admin user does not exist.\n";
}
?> 