<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/autoload.php';
require_once 'includes/auth.php';

// Démarrer la session si ce n'est pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = db_connect();

if ($db === false) {
    header("HTTP/1.1 500 Internal Server Error");
    die('Erreur de connexion à la base de données. Veuillez réessayer plus tard.');
}

// Simple router based on GET parameters
// Sanitize and validate input
$controller = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['controller'] ?? 'home');
$action = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['action'] ?? 'index');
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Validate controller and action
$valid_controllers = ['home', 'user', 'book', 'message'];
$valid_actions = [
    'home' => ['index'],
    'user' => ['register', 'login', 'profile', 'edit', 'logout', 'public_profile', 'deleteAccount'],
    'book' => ['index', 'show', 'create', 'edit', 'delete'],
    'message' => ['index', 'conversation', 'send']
];

// Redirect to home if controller is invalid
if (!in_array($controller, $valid_controllers)) {
    error_log("Invalid controller: $controller");
    header("HTTP/1.1 404 Not Found");
    header('Location: ' . BASE_URL . '?controller=home');
    exit;
}

// Redirect to default action if action is invalid
if (!isset($valid_actions[$controller]) || !in_array($action, $valid_actions[$controller])) {
    error_log("Invalid action: $action for controller: $controller");
    header('Location: ' . BASE_URL . '?controller=' . $controller);
    exit;
}

switch ($controller) {
    case 'home':
        $homeController = new HomeController($db);
        $homeController->index();
        break;

    case 'user':
        $userController = new UserController($db);
        switch ($action) {
            case 'register':
                $userController->register();
                break;
            case 'login':
                $userController->login();
                break;
            case 'profile':
                $userController->profile($id);
                break;
            case 'edit':
                $userController->edit($id);
                break;
            case 'logout':
                $userController->logout();
                break;
            case 'public_profile':
                $userController->publicProfile($id);
                break;
            case 'deleteAccount':
                $userController->deleteAccount($id);
                break;
            default:
                header('Location: ' . BASE_URL . '?controller=home');
                break;
        }
        break;

    case 'book':
        $bookController = new BookController($db);
        switch ($action) {
            case 'index':
                $bookController->index();
                break;
            case 'show':
                $bookController->show($id);
                break;
            case 'create':
                $bookController->create();
                break;
            case 'edit':
                $bookController->edit($id);
                break;
            case 'delete':
                $bookController->delete($id);
                break;
            default:
                header('Location: ' . BASE_URL . '?controller=home');
                break;
        }
        break;

    case 'message':
        $messageController = new MessageController($db);
        switch ($action) {
            case 'index':
                $messageController->index();
                break;
            case 'conversation':
                $messageController->conversation($id);
                break;
            case 'send':
                $messageController->send();
                break;
            default:
                header('Location: ' . BASE_URL . '?controller=home');
                break;
        }
        break;

    default:
        header('Location: ' . BASE_URL . '?controller=home');
        break;
}
?>
