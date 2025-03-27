<?php 
$pageTitle = 'Create New Post';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="mb-4">Create New Blog Post</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form action="/posts/store" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
        
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Publish Post</button>
        <a href="/posts" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?> 