<?php 
$pageTitle = 'All Posts';
require __DIR__ . '/../layout/header.php'; 
?>

<h1 class="mb-4">All Blog Posts</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (empty($posts)): ?>
    <div class="alert alert-info">No posts found. <a href="/posts/create">Create one</a>.</div>
<?php else: ?>
    <div class="row row-cols-1 g-4">
        <?php foreach ($posts as $post): ?>
            <div class="col">
                <div class="card h-100 shadow-sm hover-shadow">
                    <div class="card-body">
                        <h2 class="card-title h4">
                            <a href="/posts/<?= $post['id'] ?>" class="text-decoration-none stretched-link">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="card-subtitle text-muted small mb-2">
                            <?= htmlspecialchars(date('F j, Y', strtotime($post['created_at']))) ?> by 
                            <span class="text-primary"><?= htmlspecialchars($post['author']) ?></span>
                        </p>
                        <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                        <small class="text-muted">Click anywhere on the card to read</small>
                        <a href="/posts/<?= $post['id'] ?>" class="btn btn-sm btn-outline-primary">Read more →</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        cursor: pointer;
    }
</style>

<?php require __DIR__ . '/../layout/footer.php'; ?> 