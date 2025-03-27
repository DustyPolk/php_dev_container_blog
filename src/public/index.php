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
    
    case 'auth':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        
        if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->showLogin();
        } elseif ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } elseif ($action === 'logout') {
            $controller->logout();
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    
    case 'admin':
        require_once __DIR__ . '/../controllers/AdminController.php';
        $controller = new AdminController();
        
        if (empty($action) || $action === 'dashboard') {
            $controller->dashboard();
        } elseif ($action === 'posts') {
            if ($param === 'create') {
                $controller->createPost();
            } elseif ($param === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->storePost();
            } elseif ($param === 'edit' && isset($uri[3])) {
                $controller->editPost($uri[3]);
            } elseif ($param === 'update' && isset($uri[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->updatePost($uri[3]);
            } elseif ($param === 'delete' && isset($uri[3]) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $controller->deletePost($uri[3]);
            } else {
                http_response_code(404);
                echo '404 Not Found';
            }
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
    
    case 'debug':
        require_once __DIR__ . '/../controllers/DebugController.php';
        $controller = new DebugController();
        
        if ($action === 'login') {
            $controller->loginForm();
        } elseif ($action === 'process-login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->processLogin();
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