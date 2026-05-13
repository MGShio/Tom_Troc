<?php
/**
 * Autoloader pour le projet Tom Troc
 * Gère automatiquement le chargement des classes
 */

// Charger la configuration si elle n'est pas déjà chargée
if (!defined('ROOT_PATH')) {
    require_once __DIR__ . '/config.php';
}

// Autoloader pour les classes (modèles, contrôleurs, managers, services)
spl_autoload_register(function ($className) {
    // Conversion du namespace en chemin de fichier si nécessaire
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // Chemins possibles pour les classes
    $paths = [
        ROOT_PATH . '/models/' . $className . '.php',
        ROOT_PATH . '/controllers/' . $className . '.php',
        ROOT_PATH . '/includes/' . $className . '.php',
        ROOT_PATH . '/managers/' . $className . '.php',
        ROOT_PATH . '/services/' . $className . '.php',
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }

    return false;
});

/**
 * Fonction utilitaire pour charger automatiquement les fichiers includes
 * @param string $filename Nom du fichier sans extension
 * @return bool True si le fichier a été chargé, false sinon
 */
function load_include($filename) {
    $file = ROOT_PATH . '/includes/' . $filename . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
}

/**
 * Fonction utilitaire pour charger automatiquement les vues
 * @param string $viewPath Chemin relatif de la vue (ex: 'users/login')
 * @param array $data Données à passer à la vue
 * @return void
 */
function load_view($viewPath, $data = []) {
    $file = ROOT_PATH . '/views/' . $viewPath . '.php';
    if (file_exists($file)) {
        // Extraction des données dans le scope de la vue
        if (!empty($data)) {
            extract($data);
        }
        require_once $file;
    } else {
        throw new Exception("Vue non trouvée: " . $viewPath);
    }
}

/**
 * Fonction utilitaire pour charger automatiquement les managers
 * @param string $managerName Nom du manager sans extension
 * @param PDO $db Connexion à la base de données
 * @return object Instance du manager
 */
function get_manager($managerName, $db) {
    $className = ucfirst($managerName) . 'Manager';
    if (class_exists($className)) {
        return new $className($db);
    }
    throw new Exception("Manager non trouvé: " . $className);
}
