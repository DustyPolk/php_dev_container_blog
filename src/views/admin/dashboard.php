<?php 
$pageTitle = 'Admin Dashboard';
require __DIR__ . '/../layout/header.php'; 
?>

<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Posts</h5>
            <a href="/admin/posts/create" class="btn btn-primary btn-sm">Create New Post</a>
        </div>
        <div class="card-body">
            <?php if (empty($posts)): ?>
                <p class="text-muted">No posts found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($posts as $post): ?>
                                <tr>
                                    <td><?= htmlspecialchars($post['id']) ?></td>
                                    <td><?= htmlspecialchars($post['title']) ?></td>
                                    <td><?= htmlspecialchars($post['author']) ?></td>
                                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($post['created_at']))) ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="/posts/<?= htmlspecialchars($post['id']) ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                                            <a href="/admin/posts/edit/<?= htmlspecialchars($post['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="/admin/posts/delete/<?= htmlspecialchars($post['id']) ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="mt-4">
        <a href="/auth/logout" class="btn btn-secondary">Logout</a>
    </div>
</div>

<?php require __DIR__ . '/../layout/footer.php'; ?> 