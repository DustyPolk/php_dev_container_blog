<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Simple Blog' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding-top: 2rem; padding-bottom: 2rem; }
        .blog-header { margin-bottom: 2rem; border-bottom: 1px solid #e5e5e5; }
        .blog-post { margin-bottom: 2rem; }
        .blog-post-title { font-size: 2rem; }
        .blog-post-meta { margin-bottom: 1.25rem; color: #727272; }
    </style>
</head>
<body>
    <div class="container">
        <header class="blog-header py-3">
            <div class="row flex-nowrap justify-content-between align-items-center">
                <div class="col-4 pt-1">
                    <a class="link-secondary" href="/users">User Form</a>
                </div>
                <div class="col-4 text-center">
                    <a class="blog-header-logo text-dark text-decoration-none" href="/">Simple Blog</a>
                </div>
                <div class="col-4 d-flex justify-content-end align-items-center">
                    <a class="btn btn-sm btn-outline-secondary" href="/posts/create">Create Post</a>
                </div>
            </div>
        </header>
        <div class="nav-scroller py-1 mb-2">
            <nav class="nav d-flex justify-content-between">
                <a class="p-2 link-secondary" href="/">Home</a>
                <a class="p-2 link-secondary" href="/posts">All Posts</a>
            </nav>
        </div> 