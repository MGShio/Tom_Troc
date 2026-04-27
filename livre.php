<?php
require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/controllers/LivreController.php';

$db = db_connect();
$controller = new LivreController($db);

if (isset($_GET['id'])) {
    $controller->show($_GET['id']);
} else {
    $controller->index();
}
