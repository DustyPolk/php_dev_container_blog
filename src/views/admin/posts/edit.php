<?php 
$pageTitle = 'Edit Post';
require __DIR__ . '/../../layout/header.php'; 
?>

<div class="container">
    <h1 class="mb-4">Edit Blog Post</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Post Details</h5>
        </div>
        <div class="card-body">
            <form action="/admin/posts/update/<?= htmlspecialchars($post['id']) ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?= htmlspecialchars($post['content']) ?></textarea>
                </div>
                
                <div class="mb-3">
                    <p class="text-muted">Author: <?= htmlspecialchars($post['author']) ?></p>
                    <p class="text-muted">Created: <?= htmlspecialchars(date('F j, Y', strtotime($post['created_at']))) ?></p>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="/admin/dashboard" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layout/footer.php'; ?> 