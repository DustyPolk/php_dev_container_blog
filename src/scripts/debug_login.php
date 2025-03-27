<?php
require_once __DIR__ . '/../lib/Database.php';

// Get database connection
$db = Database::getInstance();

// Get the admin user
$stmt = $db->query('SELECT * FROM users WHERE email = ? LIMIT 1', ['admin@example.com']);
$user = $stmt->fetch();

if (!$user) {
    echo "ERROR: Admin user not found!\n";
    exit;
}

echo "User info from database:\n";
echo "ID: " . $user['id'] . "\n";
echo "Name: " . $user['name'] . "\n";
echo "Email: " . $user['email'] . "\n";
echo "Stored password hash: " . $user['password'] . "\n";
echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n\n";

// Test password
$testPassword = 'securepassword123';
$isPasswordCorrect = password_verify($testPassword, $user['password']);

echo "Testing password verification:\n";
echo "Password being tested: " . $testPassword . "\n";
echo "Password verification result: " . ($isPasswordCorrect ? 'SUCCESS' : 'FAILURE') . "\n\n";

// Try to create a new test hash
$newHash = password_hash($testPassword, PASSWORD_DEFAULT);
echo "New hash for reference: " . $newHash . "\n";
echo "Verification against new hash: " . (password_verify($testPassword, $newHash) ? 'SUCCESS' : 'FAILURE') . "\n";
?> 