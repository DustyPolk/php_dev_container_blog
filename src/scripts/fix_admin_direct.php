<?php
// Direct database connection to ensure we're using the right database
$dbPath = '/var/www/database/database.sqlite';
try {
    echo "Connecting directly to database at: $dbPath\n";
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connected successfully. Checking tables...\n";
    
    // List tables to confirm structure
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table';")->fetchAll();
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- " . $table['name'] . "\n";
    }
    
    // Create user table if not exists
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT,
        password TEXT,
        is_admin BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    // Delete existing admin users
    $stmt = $pdo->prepare('DELETE FROM users WHERE email = ?');
    $stmt->execute(['admin@example.com']);
    
    // Create new admin user
    $name = 'Admin User';
    $email = 'admin@example.com';
    $password = 'securepassword123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$name, $email, $hashedPassword, 1]);
    
    if ($result) {
        echo "Admin user created successfully:\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
        
        // Verify user exists
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            echo "User found in database:\n";
            echo "ID: " . $user['id'] . "\n";
            echo "Name: " . $user['name'] . "\n";
            echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
            
            // Test password
            $verifyPassword = password_verify($password, $user['password']);
            echo "Password verification: " . ($verifyPassword ? 'SUCCESS' : 'FAILURE') . "\n";
        } else {
            echo "ERROR: User not found after creation!\n";
        }
    } else {
        echo "Failed to create admin user.\n";
    }
    
    // Set proper permissions
    echo "Setting proper permissions on database file...\n";
    chmod($dbPath, 0666);
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?> 