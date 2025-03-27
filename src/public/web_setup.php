<?php
// Web-based database setup script

// Get the actual database path from configuration
$config = require __DIR__ . '/../config/database.php';
$dbPath = $config['path'];

echo "<h1>Web-based Database Setup</h1>";
echo "<p>Using database at: " . htmlspecialchars($dbPath) . "</p>";

try {
    // Make sure directory exists
    $dbDir = dirname($dbPath);
    if (!is_dir($dbDir)) {
        if (mkdir($dbDir, 0777, true)) {
            echo "<p>Created database directory: " . htmlspecialchars($dbDir) . "</p>";
        } else {
            echo "<p style='color:red'>Failed to create directory: " . htmlspecialchars($dbDir) . "</p>";
            echo "<p>Current script is running as: " . exec('whoami') . "</p>";
            echo "<p>Directory permissions: " . exec('ls -la ' . escapeshellarg(dirname($dbDir))) . "</p>";
        }
    } else {
        echo "<p>Database directory already exists</p>";
    }
    
    // Check if we can write to the directory
    if (is_writable($dbDir)) {
        echo "<p>Database directory is writable</p>";
    } else {
        echo "<p style='color:red'>Database directory is not writable!</p>";
    }
    
    // Connect to database
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "<p>Connected to database successfully</p>";
    
    // Drop existing tables to start fresh
    echo "<p>Dropping existing tables if any...</p>";
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("DROP TABLE IF EXISTS posts");
    
    // Create tables with proper structure
    echo "<p>Creating tables with proper structure...</p>";
    
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
    
    echo "<p>Tables created successfully</p>";
    
    // Create admin user
    echo "<p>Creating admin user...</p>";
    $name = 'Admin User';
    $email = 'admin@example.com';
    $password = 'securepassword123';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)');
    $result = $stmt->execute([$name, $email, $hashedPassword, 1]);
    
    if ($result) {
        echo "<p>Admin user created successfully</p>";
        echo "<p>Email: " . htmlspecialchars($email) . "</p>";
        echo "<p>Password: " . htmlspecialchars($password) . "</p>";
    } else {
        echo "<p style='color:red'>Failed to create admin user</p>";
    }
    
    // Create a sample post
    echo "<p>Creating sample post...</p>";
    $title = 'Welcome to the Blog';
    $content = 'This is a sample blog post created during database setup.';
    $author = 'Admin User';
    
    $stmt = $pdo->prepare('INSERT INTO posts (title, content, author) VALUES (?, ?, ?)');
    $result = $stmt->execute([$title, $content, $author]);
    
    if ($result) {
        echo "<p>Sample post created successfully</p>";
    } else {
        echo "<p style='color:red'>Failed to create sample post</p>";
    }
    
    // Verify structure and data
    echo "<h2>Verifying database structure and data:</h2>";
    
    // Get table info
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll();
    echo "<p>Tables in database: " . count($tables) . "</p><ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table['name']) . "</li>";
    }
    echo "</ul>";
    
    // Check users table columns
    $columns = $pdo->query("PRAGMA table_info(users)")->fetchAll();
    echo "<p>Users table columns:</p><ul>";
    foreach ($columns as $column) {
        echo "<li>" . htmlspecialchars($column['name']) . " (" . htmlspecialchars($column['type']) . ")</li>";
    }
    echo "</ul>";
    
    // Check users table data
    $users = $pdo->query('SELECT * FROM users')->fetchAll();
    echo "<p>Users in database: " . count($users) . "</p><ul>";
    foreach ($users as $user) {
        echo "<li>ID: " . htmlspecialchars($user['id']) . ", Name: " . htmlspecialchars($user['name']) . 
             ", Email: " . htmlspecialchars($user['email']) . ", Is Admin: " . htmlspecialchars($user['is_admin']) . "</li>";
    }
    echo "</ul>";
    
    // Check posts table data
    $posts = $pdo->query('SELECT * FROM posts')->fetchAll();
    echo "<p>Posts in database: " . count($posts) . "</p><ul>";
    foreach ($posts as $post) {
        echo "<li>ID: " . htmlspecialchars($post['id']) . ", Title: " . htmlspecialchars($post['title']) . 
             ", Author: " . htmlspecialchars($post['author']) . "</li>";
    }
    echo "</ul>";
    
    // Set permissions on database file
    if (file_exists($dbPath)) {
        if (chmod($dbPath, 0666)) {
            echo "<p>Set permissions on database file to ensure web server can write to it</p>";
        } else {
            echo "<p style='color:red'>Failed to set permissions on database file!</p>";
        }
    } else {
        echo "<p style='color:red'>Database file doesn't exist after setup!</p>";
    }
    
    echo "<h2>Database setup completed successfully!</h2>";
    echo "<p><a href='/auto_login.php'>Try Auto Login</a></p>";
    echo "<p><a href='/auth/login'>Try Normal Login</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Show PHP info for debugging
echo "<h2>PHP Environment Information:</h2>";
echo "<p>Current working directory: " . htmlspecialchars(getcwd()) . "</p>";
echo "<p>Running as user: " . htmlspecialchars(exec('whoami')) . "</p>";
echo "<pre>";
echo "Environment Variables:\n";
foreach ($_ENV as $key => $value) {
    echo htmlspecialchars("$key = $value") . "\n";
}
echo "\nServer Variables:\n";
foreach ($_SERVER as $key => $value) {
    if (!is_array($value)) {
        echo htmlspecialchars("$key = $value") . "\n";
    }
}
echo "</pre>";
?> 