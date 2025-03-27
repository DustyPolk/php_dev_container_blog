<?php
// Direct database connection
$dbPath = '/var/www/database/database.sqlite';
try {
    echo "Connecting to database at: $dbPath\n";
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connected successfully.\n";
    
    // Check if the is_admin column exists
    $columns = $pdo->query("PRAGMA table_info(users);")->fetchAll(PDO::FETCH_ASSOC);
    $isAdminExists = false;
    
    echo "Current columns in users table:\n";
    foreach ($columns as $column) {
        echo "- " . $column['name'] . " (" . $column['type'] . ")\n";
        if ($column['name'] === 'is_admin') {
            $isAdminExists = true;
        }
    }
    
    if ($isAdminExists) {
        echo "is_admin column already exists.\n";
    } else {
        echo "Adding is_admin column to users table...\n";
        
        // In SQLite, we need to create a new table with the desired structure,
        // copy the data, then rename the tables
        $pdo->exec("
            BEGIN TRANSACTION;
            
            -- Create a new table with the desired structure
            CREATE TABLE users_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT NOT NULL,
                message TEXT,
                password TEXT,
                is_admin BOOLEAN DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            
            -- Copy data from the old table to the new one
            INSERT INTO users_new (id, name, email, message, password, created_at)
            SELECT id, name, email, message, password, created_at FROM users;
            
            -- Drop the old table and rename the new one
            DROP TABLE users;
            ALTER TABLE users_new RENAME TO users;
            
            COMMIT;
        ");
        
        echo "is_admin column added successfully.\n";
    }
    
    // Now create/update the admin user
    echo "Creating/updating admin user...\n";
    
    // Check if admin user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(['admin@example.com']);
    $adminUser = $stmt->fetch();
    
    if ($adminUser) {
        // Update existing user
        $stmt = $pdo->prepare("UPDATE users SET is_admin = 1, password = ? WHERE email = ?");
        $hashedPassword = password_hash('securepassword123', PASSWORD_DEFAULT);
        $stmt->execute([$hashedPassword, 'admin@example.com']);
        echo "Existing admin user updated.\n";
    } else {
        // Create new admin user
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_admin) VALUES (?, ?, ?, ?)");
        $hashedPassword = password_hash('securepassword123', PASSWORD_DEFAULT);
        $stmt->execute(['Admin User', 'admin@example.com', $hashedPassword, 1]);
        echo "New admin user created.\n";
    }
    
    // Verify the admin user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@example.com']);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "Admin user verification:\n";
        echo "ID: " . $user['id'] . "\n";
        echo "Name: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "\n";
        echo "Password verification: " . (password_verify('securepassword123', $user['password']) ? 'SUCCESS' : 'FAILURE') . "\n";
    } else {
        echo "ERROR: Admin user not found after creation/update!\n";
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
?> 