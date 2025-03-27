<?php
// Start session
session_start();

// Database connection
$dbPath = '/var/www/database/database.sqlite';
try {
    echo '<h1>Auto Login Debug</h1>';
    
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo '<p>Connected to database: ' . $dbPath . '</p>';
    
    // Check table structure
    $columns = $pdo->query("PRAGMA table_info(users);")->fetchAll(PDO::FETCH_ASSOC);
    echo '<p>Users table columns:</p><ul>';
    foreach ($columns as $column) {
        echo '<li>' . $column['name'] . ' (' . $column['type'] . ')</li>';
    }
    echo '</ul>';
    
    // Get admin user with fallback query
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND is_admin = 1 LIMIT 1');
        $stmt->execute(['admin@example.com']);
        $user = $stmt->fetch();
    } catch (Exception $e) {
        echo '<p>Error with is_admin query: ' . $e->getMessage() . '</p>';
        echo '<p>Trying fallback query...</p>';
        
        // Fallback without is_admin
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute(['admin@example.com']);
        $user = $stmt->fetch();
    }
    
    if ($user) {
        echo '<p>Found admin user:</p>';
        echo '<pre>';
        print_r($user);
        echo '</pre>';
        
        // Set session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['is_admin'] = 1; // Force this to be 1
        $_SESSION['success'] = 'Auto login successful';
        
        echo '<p>Session data set:</p>';
        echo '<pre>' . print_r($_SESSION, true) . '</pre>';
        
        echo '<p><a href="/admin/dashboard" style="display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Go to Admin Dashboard</a></p>';
    } else {
        echo '<p style="color: red;">Error: Admin user not found.</p>';
        
        // Check all users in the table
        echo '<p>All users in database:</p>';
        $allUsers = $pdo->query('SELECT * FROM users')->fetchAll();
        
        if (count($allUsers) > 0) {
            echo '<pre>';
            print_r($allUsers);
            echo '</pre>';
        } else {
            echo '<p>No users found in database.</p>';
        }
    }
} catch (PDOException $e) {
    echo '<p style="color: red;">Database error: ' . $e->getMessage() . '</p>';
}
?> 