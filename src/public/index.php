<?php
// Bootstrap application
require_once __DIR__ . '/../lib/Session.php';
Session::start();

// Simple router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Default route
$controller = $uri[0] ?? 'home';
$action = $uri[1] ?? 'index';
$param = $uri[2] ?? null;

// Route the request
switch ($controller) {
    case '':
    case 'home':
        require_once __DIR__ . '/../controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
        
    case 'posts':
        require_once __DIR__ . '/../controllers/PostController.php';
        $controller = new PostController();
        
        if (empty($action) || $action === 'index') {
            $controller->index();
        } elseif ($action === 'create') {
            $controller->create();
        } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } elseif (is_numeric($action)) {
            $controller->show($action);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    
    case 'users':
        require_once __DIR__ . '/../controllers/UserController.php';
        $controller = new UserController();
        
        if (empty($action) || $action === 'index') {
            $controller->index();
        } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->store();
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
        
    default:
        http_response_code(404);
        echo '404 Not Found';
        break;
} 