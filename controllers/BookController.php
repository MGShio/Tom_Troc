<?php
/**
 * BookController - Gestion des livres
 */
class BookController
{
    private $db;

    /**
     * Constructeur
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Affiche la liste de tous les livres disponibles
     */
    public function index()
    {
        $bookManager = new BookManager($this->db);
        
        // Recherche
        $search = $_GET['search'] ?? '';
        if (!empty($search)) {
            $books = $bookManager->searchAvailable($search);
        } else {
            $books = $bookManager->getAllAvailable();
        }
        
        require __DIR__ . '/../views/books/index.php';
    }

    /**
     * Affiche les détails d'un livre
     * @param int $id ID du livre
     */
    public function show($id)
    {
        $bookManager = new BookManager($this->db);
        $book = $bookManager->getById($id);
        
        if (!$book) {
            Utils::redirect(BASE_URL . '?controller=book&action=index');
        }
        
        // Récupérer le vendeur
        $userManager = new UserManager($this->db);
        $seller = $userManager->getUserById($book->getUserId());
        
        require __DIR__ . '/../views/books/show.php';
    }

    /**
     * Affiche le formulaire d'ajout d'un livre
     */
    public function create()
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || !Utils::verifyCsrfToken($_POST['csrf_token'])) {
                $error = "Token CSRF invalide.";
            } else {
                $title = trim($_POST['title'] ?? '');
                $author = trim($_POST['author'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $disponibilite = $_POST['disponibilite'] ?? 'disponible';
                $errors = [];

                if (empty($title)) {
                    $errors[] = "Le titre est requis.";
                }

                if (empty($author)) {
                    $errors[] = "L'auteur est requis.";
                }

                if (empty($description)) {
                    $errors[] = "La description est requise.";
                }

                // Gestion de l'image
                $fileName = 'Book_default.png';
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $uploadDir = ROOT_PATH . '/assets/images/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('img_') . '.' . $extension;
                    $destinationPath = $uploadDir . $fileName;

                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $destinationPath)) {
                        $errors[] = "Erreur lors du téléchargement de l'image.";
                    }
                }

                if (empty($errors)) {
                    $bookData = [
                        'title' => htmlspecialchars($title),
                        'author' => htmlspecialchars($author),
                        'description' => htmlspecialchars($description),
                        'statut' => $disponibilite,
                        'disponibilite' => $disponibilite,
                        'user_id' => $_SESSION['user_id'],
                        'image' => $fileName
                    ];
                    
                    $bookManager = new BookManager($this->db);
                    $book = $bookManager->create($bookData);
                    if ($book) {
                        Utils::redirect(BASE_URL . '?controller=user&action=profile&id=' . $_SESSION['user_id']);
                    } else {
                        $errors[] = "Erreur lors de l'ajout du livre.";
                    }
                }

                $error = implode('<br>', $errors);
            }
        }
        
        $_SESSION['csrf_token'] = Utils::generateCsrfToken();
        require __DIR__ . '/../views/books/create.php';
    }

    /**
     * Affiche le formulaire d'édition d'un livre
     * @param int $id ID du livre
     */
    public function edit($id)
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }

        $bookManager = new BookManager($this->db);
        $book = $bookManager->getById($id);
        
        if (!$book || $book->getUserId() != $_SESSION['user_id']) {
            Utils::redirect(BASE_URL . '?controller=home');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || !Utils::verifyCsrfToken($_POST['csrf_token'])) {
                $error = "Token CSRF invalide.";
            } else {
                $title = trim($_POST['title'] ?? '');
                $author = trim($_POST['author'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $disponibilite = $_POST['disponibilite'] ?? 'disponible';
                $errors = [];

                if (empty($title)) {
                    $errors[] = "Le titre est requis.";
                }

                if (empty($author)) {
                    $errors[] = "L'auteur est requis.";
                }

                if (empty($description)) {
                    $errors[] = "La description est requise.";
                }

                // Gestion de l'image
                $fileName = $book->getImage();
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $uploadDir = ROOT_PATH . '/assets/images/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $newFileName = uniqid('img_') . '.' . $extension;
                    $destinationPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $destinationPath)) {
                        // Supprimer l'ancienne image si ce n'est pas le default
                        if ($fileName !== 'Book_default.png') {
                            $oldPath = $uploadDir . $fileName;
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }
                        $fileName = $newFileName;
                    } else {
                        $errors[] = "Erreur lors du téléchargement de l'image.";
                    }
                }

                if (empty($errors)) {
                    $bookData = [
                        'title' => htmlspecialchars($title),
                        'author' => htmlspecialchars($author),
                        'description' => htmlspecialchars($description),
                        'statut' => $disponibilite,
                        'disponibilite' => $disponibilite,
                        'image' => $fileName
                    ];
                    
                    if ($bookManager->update($id, $bookData)) {
                        Utils::redirect(BASE_URL . '?controller=user&action=profile&id=' . $_SESSION['user_id']);
                    } else {
                        $errors[] = "Erreur lors de la mise à jour du livre.";
                    }
                }

                $error = implode('<br>', $errors);
            }
        }

        $_SESSION['csrf_token'] = Utils::generateCsrfToken();
        require __DIR__ . '/../views/books/edit.php';
    }

    /**
     * Supprime un livre
     * @param int $id ID du livre
     */
    public function delete($id)
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=home');
        }

        $bookManager = new BookManager($this->db);
        $book = $bookManager->getById($id);
        
        if ($book && $book->getUserId() == $_SESSION['user_id']) {
            // Supprimer l'image si ce n'est pas le default
            $imageName = $book->getImage();
            if ($imageName !== 'Book_default.png') {
                $imagePath = ROOT_PATH . '/assets/images/' . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            if ($bookManager->delete($id)) {
                Utils::redirect(BASE_URL . '?controller=user&action=profile&id=' . $_SESSION['user_id']);
            }
        }
        
        Utils::redirect(BASE_URL . '?controller=home');
    }
}
