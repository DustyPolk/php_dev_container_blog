<?php
require_once __DIR__ . '/../models/Post.php';
require_once __DIR__ . '/../lib/Session.php';
require_once __DIR__ . '/../lib/AdminMiddleware.php';

class AdminController {
    private $postModel;
    
    public function __construct() {
        // Check admin access
        AdminMiddleware::isAdmin();
        
        $this->postModel = new Post();
    }
    
    public function dashboard() {
        $posts = $this->postModel->getAll();
        require __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function createPost() {
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/admin/posts/create.php';
    }
    
    public function storePost() {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /admin/posts/create');
            exit;
        }
        
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $author = $_POST['author'] ?? Session::get('user_name');
        
        if (empty($title) || empty($content) || empty($author)) {
            Session::flash('error', 'All fields are required');
            header('Location: /admin/posts/create');
            exit;
        }
        
        $this->postModel->create($title, $content, $author);
        Session::flash('success', 'Post created successfully');
        header('Location: /admin/dashboard');
        exit;
    }
    
    public function editPost($id) {
        $post = $this->postModel->getById($id);
        if (!$post) {
            Session::flash('error', 'Post not found');
            header('Location: /admin/dashboard');
            exit;
        }
        
        $csrf_token = Session::generateCsrfToken();
        require __DIR__ . '/../views/admin/posts/edit.php';
    }
    
    public function updatePost($id) {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /admin/posts/edit/' . $id);
            exit;
        }
        
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        
        if (empty($title) || empty($content)) {
            Session::flash('error', 'Title and content are required');
            header('Location: /admin/posts/edit/' . $id);
            exit;
        }
        
        $this->postModel->update($id, $title, $content);
        Session::flash('success', 'Post updated successfully');
        header('Location: /admin/dashboard');
        exit;
    }
    
    public function deletePost($id) {
        if (!Session::validateCsrfToken($_POST['csrf_token'] ?? '')) {
            Session::flash('error', 'CSRF token validation failed');
            header('Location: /admin/dashboard');
            exit;
        }
        
        $this->postModel->delete($id);
        Session::flash('success', 'Post deleted successfully');
        header('Location: /admin/dashboard');
        exit;
    }
} 