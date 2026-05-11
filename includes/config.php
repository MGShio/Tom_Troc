<?php
session_start();

// Configuration de base
define('SITE_NAME', 'Tom Troc');
define('BASE_URL', 'http://localhost/tom_troc/');

// Connexion à la base de données - using environment variables for security
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'tom_troc');

// Chemins
define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Content-Type header
header('Content-Type: text/html; charset=UTF-8');

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Error reporting for development (disable in production)
// In production, set display_errors to 0 and log errors to a file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// In production, you should use:
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/../error.log');
