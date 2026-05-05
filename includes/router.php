<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/autoload.php';

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
$valid_controllers = ['home', 'user', 'livre', 'message'];
$valid_actions = [
    'home' => ['index'],
    'user' => ['register', 'login', 'profile', 'edit', 'logout'],
    'livre' => ['index', 'show', 'create'],
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
        // Home page
        require_once 'models/Livre.php';
        $livres = Livre::getAllDisponibles($db);
        // Take only the first 6 for the home page
        $livres = array_slice($livres, 0, 6);
        require_once 'includes/header.php';
        ?>
        <!-- Section Hero -->
        <section class="hero">
            <div class="hero-content">
                <h1>Rejoignez nos lecteurs passionnés</h1>
                <p>
                    Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
                    Découvrez notre vaste sélection et partagez des connaissances et d'histoires à travers nos livres.
                </p>
                <a href="<?= BASE_URL ?>?controller=livre&action=index" class="btn">Découvrir</a>
            </div>
            <div class="hero-image">
                <img src="<?= BASE_URL ?>assets/images/hamza-nouasria-KXrvPthkmYQ-unsplash 1@2x.png" alt="Pile de livres anciens">
            </div>
        </section>

        <!-- Section "Les derniers livres ajoutés" -->
        <section class="section">
            <h2>Les derniers livres ajoutés</h2>
            <div class="books-grid">
                <?php foreach ($livres as $livre): ?>
                    <div class="book-card">
                        <?php if (!empty($livre->getImage())): ?>
                            <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre->getImage()) ?>" alt="<?= htmlspecialchars($livre->getTitre()) ?>">
                        <?php else: ?>
                            <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                                Pas d'image
                            </div>
                        <?php endif; ?>
                        <h3><?= htmlspecialchars($livre->getTitre()) ?></h3>
                        <p>par <?= htmlspecialchars($livre->getAuteur()) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="<?= BASE_URL ?>?controller=livre&action=index" class="btn">Voir tous les livres</a>
            </div>
        </section>

        <!-- Section "Comment ça marche ?" -->
        <section class="how-it-works">
            <h2>Comment ça marche ?</h2>
            <p>
                Échanger des livres avec TomTroc, c'est simple et amusant !
                Suivez ces étapes pour commencer :
            </p>
            <div class="steps">
                <a href="<?= BASE_URL ?>?controller=user&action=register" class="step">
                    <div class="step-icon">
                        <img src="<?= BASE_URL ?>assets/images/icon1.png" alt="Inscription">
                    </div>
                    <h3>Inscrivez-vous gratuitement sur notre plateforme.</h3>
                </a>
                <a href="<?= BASE_URL ?>?controller=livre&action=create" class="step">
                    <div class="step-icon">
                        <img src="<?= BASE_URL ?>assets/images/icon2.png" alt="Ajouter des livres">
                    </div>
                    <h3>Ajoutez les livres que vous souhaitez échanger à votre profil.</h3>
                </a>
                <a href="<?= BASE_URL ?>?controller=livre&action=index" class="step">
                    <div class="step-icon">
                        <img src="<?= BASE_URL ?>assets/images/icon3.png" alt="Parcourir les livres">
                    </div>
                    <h3>Parcourez les livres disponibles et choisissez ceux qui vous intéressent.</h3>
                </a>
                <a href="<?= BASE_URL ?>?controller=livre&action=index" class="step">
                    <div class="step-icon">
                        <img src="<?= BASE_URL ?>assets/images/icon4.png" alt="Proposer un échange">
                    </div>
                    <h3>Proposez un échange et organisez la livraison des livres.</h3>
                </a>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="<?= BASE_URL ?>?controller=livre&action=index" class="btn">Voir tous les livres</a>
            </div>
        </section>

        <!-- Section image de bibliothèque -->
        <section class="section">
            <img src="<?= BASE_URL ?>assets/images/bookshelf.jpg" alt="Bibliothèque de livres" style="width: 100%; max-width: 1200px; margin-bottom: 2rem; border-radius: 8px;">
        </section>

        <!-- Section "Nos valeurs" -->
        <section class="values">
            <h2>Nos valeurs</h2>
            <div class="values-content">
                <p>
                    Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté.
                    Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer un espace où les lecteurs
                    peuvent se connecter et partager leurs histoires préférées. Nous croyons que le partage de livres enrichit nos vies
                    et crée des conversations enrichissantes.
                </p>
                <p>
                    Notre association a été fondée avec une conviction simple : le partage de livres peut transformer des vies.
                    Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter,
                    de partager leurs livres préférés et de découvrir de nouveaux trésors littéraires. En échangeant des livres qui
                    attendaient patiemment sur les étagères, nous encourageons une culture de la lecture et du partage.
                </p>
            </div>
            <div class="signature">Illustration Tom Troc</div>
        </section>
        <?php
        require_once 'includes/footer.php';
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
            default:
                header('Location: ' . BASE_URL . '?controller=home');
                break;
        }
        break;

    case 'livre':
        $livreController = new LivreController($db);
        switch ($action) {
            case 'index':
                $livreController->index();
                break;
            case 'show':
                $livreController->show($id);
                break;
            case 'create':
                $livreController->create();
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