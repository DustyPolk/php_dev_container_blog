<?php
// Database setup
$dbPath = getenv('SQLITE_DATABASE_PATH') ?: '/var/www/database/database.sqlite';
$message = '';

// Add CSRF protection with more reliable implementation
session_start();

// Initialize token variables
$csrf_token = '';
$session_error = false;

// Check if sessions are working properly
if (!isset($_SESSION['test'])) {
    $_SESSION['test'] = true;
    $session_error = !isset($_SESSION['test']);
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

try {
    // Create or connect to SQLite database
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create users table if it doesn't exist
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )');
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Debug output during development
        // echo "Session token: " . $_SESSION['csrf_token'] . "<br>";
        // echo "POST token: " . ($_POST['csrf_token'] ?? 'none') . "<br>";
        
        // More flexible CSRF check with session issue fallback
        if ($session_error || !isset($_POST['csrf_token'])) {
            // During development, you might want to bypass for testing
            // $message = '<div class="alert alert-warning">Session warning: CSRF check bypassed for testing</div>';
            throw new Exception('CSRF token validation failed - session storage issue detected');
        } elseif ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception('CSRF token validation failed - tokens do not match');
        }
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $userMessage = $_POST['message'] ?? '';
        
        // Enhanced validation
        if (empty($name) || empty($email)) {
            $message = '<div class="alert alert-danger">Please fill out all required fields.</div>';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = '<div class="alert alert-danger">Please enter a valid email address.</div>';
        } elseif (strlen($name) > 100 || strlen($email) > 100 || strlen($userMessage) > 1000) {
            $message = '<div class="alert alert-danger">Input exceeds maximum allowed length.</div>';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, message) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $userMessage]);
            $message = '<div class="alert alert-success">Thank you! Your information has been saved.</div>';
            // Regenerate CSRF token after successful submission
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $csrf_token = $_SESSION['csrf_token'];
        }
    }
    
    // Get all entries
    $stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
    $entries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $message = '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
    $entries = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .container { max-width: 800px; }
        .form-container { margin-bottom: 30px; }
        .entries-container { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-4 mb-4">User Information Form</h1>
        
        <?php echo $message; ?>
        
        <div class="form-container">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="entries-container">
            <h2>Submitted Entries</h2>
            
            <?php if (count($entries) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entries as $entry): ?>
                                <tr>
                                    <td><?= htmlspecialchars($entry['id']) ?></td>
                                    <td><?= htmlspecialchars($entry['name']) ?></td>
                                    <td><?= htmlspecialchars($entry['email']) ?></td>
                                    <td><?= htmlspecialchars($entry['message']) ?></td>
                                    <td><?= htmlspecialchars($entry['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No entries yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 