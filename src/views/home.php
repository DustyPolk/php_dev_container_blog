<?php 
$pageTitle = 'Simple Blog - Home';
require __DIR__ . '/layout/header.php'; 
?>

<div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
    <div class="col-md-6 px-0">
        <h1 class="display-4">Welcome to our Simple Blog</h1>
        <p class="lead my-3">A clean and simple blog application built with PHP.</p>
    </div>
</div>

<div class="row mb-2">
    <?php if (!empty($latestPosts)): ?>
        <?php foreach ($latestPosts as $post): ?>
            <div class="col-md-6">
                <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
                    <div class="col p-4 d-flex flex-column position-static">
                        <h3 class="mb-0"><?= htmlspecialchars($post['title']) ?></h3>
                        <div class="mb-1 text-muted"><?= htmlspecialchars(date('M d', strtotime($post['created_at']))) ?></div>
                        <p class="card-text mb-auto"><?= htmlspecialchars(substr($post['content'], 0, 120)) ?>...</p>
                        <a href="/posts/<?= $post['id'] ?>" class="stretched-link">Continue reading</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p>No posts yet. <a href="/posts/create">Create the first post</a>.</p>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?> 