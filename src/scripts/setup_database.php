<?php
// Database setup script - creates tables and admin user
$dbPath = '/var/www/database/database.sqlite';

try {
    echo "Setting up database at: $dbPath\n";
    
    // Make sure directory exists
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0777, true);
        echo "Created database directory: $dbDir\n";
    }
    
    // Ensure we have write permissions
    if (file_exists($dbPath) && !is_writable($dbPath)) {
        chmod($dbPath, 0666);
        echo "Set permissions on existing database file\n";
    }
    
    // Connect to database
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connected to database successfully\n";
    
    // Drop existing tables to start fresh
    echo "Dropping existing tables if any...\n";
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("DROP TABLE IF EXISTS posts");
    
    // Create tables with proper structure
    echo "Creating tables with proper structure...\n";
    
    // Users table
    $pdo->exec('CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL UNIQUE,
        message TEXT,
        password TEXT,
        is_admin INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    // Posts table
    $pdo->exec('CREATE TABLE posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        content TEXT NOT NULL,
        author TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    echo "Tables created successfully\n";
    
    // Create admin user
    echo "Creating admin user...\n";
    $name = 'Admin User';
    $email = 'admin@example.com';
    $password = 'securepassword123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$name, $email, $hashedPassword, 1]);
    
    if ($result) {
        echo "Admin user created successfully\n";
        echo "Email: $email\n";
        echo "Password: $password\n";
    } else {
        echo "Failed to create admin user\n";
    }
    
    // Create a sample post
    echo "Creating sample post...\n";
    $title = 'Welcome to the Blog';
    $content = 'This is a sample blog post created during database setup.';
    $author = 'Admin User';
    
    $stmt = $pdo->prepare('INSERT INTO posts (title, content, author) VALUES (?, ?, ?)');
    $result = $stmt->execute([$title, $content, $author]);
    
    if ($result) {
        echo "Sample post created successfully\n";
    } else {
        echo "Failed to create sample post\n";
    }
    
    // Verify structure and data
    echo "\nVerifying database structure and data:\n";
    
    // Check users table
    $users = $pdo->query('SELECT * FROM users')->fetchAll();
    echo "Users in database (" . count($users) . "):\n";
    foreach ($users as $user) {
        echo "- ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Is Admin: {$user['is_admin']}\n";
    }
    
    // Check posts table
    $posts = $pdo->query('SELECT * FROM posts')->fetchAll();
    echo "\nPosts in database (" . count($posts) . "):\n";
    foreach ($posts as $post) {
        echo "- ID: {$post['id']}, Title: {$post['title']}, Author: {$post['author']}\n";
    }
    
    // Set permissions on database file
    chmod($dbPath, 0666);
    echo "\nSet permissions on database file to ensure web server can write to it\n";
    
    echo "\nDatabase setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?> 