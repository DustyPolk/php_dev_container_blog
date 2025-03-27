<?php 
$pageTitle = htmlspecialchars($post['title']) . ' - Simple Blog';
require __DIR__ . '/../layout/header.php'; 
?>

<article class="blog-post">
    <h1 class="blog-post-title mb-3"><?= htmlspecialchars($post['title']) ?></h1>
    
    <p class="blog-post-meta">
        <?= htmlspecialchars(date('F j, Y', strtotime($post['created_at']))) ?> by 
        <span class="text-primary"><?= htmlspecialchars($post['author']) ?></span>
    </p>
    
    <div class="blog-post-content">
        <?= nl2br(htmlspecialchars($post['content'])) ?>
    </div>
    
    <hr class="my-5">
    
    <div class="d-flex justify-content-between">
        <a href="/posts" class="btn btn-outline-secondary">&larr; Back to all posts</a>
    </div>
</article>

<?php require __DIR__ . '/../layout/footer.php'; ?> 