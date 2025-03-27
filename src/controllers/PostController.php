<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../lib/Session.php';

class PostController {
    private $postModel;
    
    public function __construct() {
        $this->postModel = new Post();
    }
    
    public function index() {
        $posts = $this->postModel->getAll();
        require __DIR__ . '/../views/posts/index.php';
    }
    
    public function show($id) {
        $post = $this->postModel->getById($id);
        if (!$post) {
            header('Location: /posts');
            exit;
        }
        
        require __DIR__ . '/../views/posts/show.php';
    }
    
    public function create() {
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/posts/create.php';
    }
    
    public function store() {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /posts/create');
            exit;
        }
        
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $author = $_POST['author'] ?? '';
        
        if (empty($title) || empty($content) || empty($author)) {
            Session::flash('error', 'All fields are required');
            header('Location: /posts/create');
            exit;
        }
        
        $this->postModel->create($title, $content, $author);
        Session::flash('success', 'Post created successfully');
        header('Location: /posts');
        exit;
    }
} 