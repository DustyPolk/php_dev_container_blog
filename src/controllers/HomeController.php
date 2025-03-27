<?php
require_once __DIR__ . '/../models/Post.php';

class HomeController {
    private $postModel;
    
    public function __construct() {
        $this->postModel = new Post();
    }
    
    public function index() {
        // Get the 3 most recent posts for the homepage
        $latestPosts = $this->postModel->getAll();
        $latestPosts = array_slice($latestPosts, 0, 3);
        
        // Include the view
        require __DIR__ . '/../views/home.php';
    }
} 