<?php 
$pageTitle = 'Debug Login';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $pageTitle ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Debug Login Form</h3>
                    </div>
                    <div class="card-body">
                        <form action="/debug/process-login" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="admin@example.com" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" id="password" name="password" value="securepassword123" required>
                                <small class="text-muted">Password is visible for debugging</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Login (Debug)</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 