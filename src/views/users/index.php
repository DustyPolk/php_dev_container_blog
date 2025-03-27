<?php 
$pageTitle = 'User Information Form';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="mb-4">User Information Form</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <div class="col-md-6 mb-5">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Submit Your Information</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="/users/store">
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
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="h5 mb-0">Registered Users</h2>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <p class="text-muted">No users have registered yet.</p>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($users as $user): ?>
                                <div class="list-group-item">
                                    <h5 class="mb-1"><?= htmlspecialchars($user['name']) ?></h5>
                                    <p class="mb-1"><small><?= htmlspecialchars($user['email']) ?></small></p>
                                    <?php if (!empty($user['message'])): ?>
                                        <p class="mb-1"><?= htmlspecialchars($user['message']) ?></p>
                                    <?php endif; ?>
                                    <small class="text-muted">
                                        Registered on <?= htmlspecialchars(date('F j, Y', strtotime($user['created_at']))) ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?> 