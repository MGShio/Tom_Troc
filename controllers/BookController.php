<?php

class BookController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $query = trim($_GET['q'] ?? '');
        if ($query !== '') {
            $books = BookManager::searchAvailable($this->db, $query);
        } else {
            $books = BookManager::getAllAvailable($this->db);
        }
        require __DIR__ . '/../views/books/index.php';
    }

    public function show($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . '?controller=book&action=index');
            exit;
        }
        
        $book = BookManager::getById($this->db, $id);
        if (!$book) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../includes/header.php';
            echo '<p>Book non trouvé.</p>';
            require __DIR__ . '/../includes/footer.php';
            exit;
        }
        require __DIR__ . '/../views/books/show.php';
    }

    public function create()
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $errors[] = "Token CSRF invalide.";
            }
            
            $title = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $statut = $_POST['statut'] ?? '';
            
            // Server-side validation
            $errors = [];
            
            if (empty($title)) {
                $errors[] = "Le titre est requis.";
            } elseif (strlen($title) > 255) {
                $errors[] = "Le titre ne peut pas dépasser 255 caractères.";
            }
            
            if (empty($author)) {
                $errors[] = "L'auteur est requis.";
            } elseif (strlen($author) > 255) {
                $errors[] = "Le name de l'auteur ne peut pas dépasser 255 caractères.";
            }
            
            if (strlen($description) > 1000) {
                $errors[] = "La description ne peut pas dépasser 1000 caractères.";
            }
            
            $valid_statuts = ['disponible', 'indisponible', 'echange'];
            if (!in_array($statut, $valid_statuts)) {
                $errors[] = "Statut invalide.";
            }
            
            $imageName = '';
            if (!empty($_FILES['image']['name'])) {
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                $imageInfo = $_FILES['image'];
                
                if ($imageInfo['error'] !== UPLOAD_ERR_OK) {
                    error_log("File upload error: " . $imageInfo['error']);
                    $errors[] = "Erreur lors du téléchargement de l'image.";
                } elseif (!in_array($imageInfo['type'], $allowedTypes)) {
                    $errors[] = "Format d'image non pris en charge. Utilisez PNG, JPG ou GIF.";
                } else {
                    $extension = strtolower(pathinfo($imageInfo['name'], PATHINFO_EXTENSION));
                    if (!in_array($extension, $allowedExtensions)) {
                        $errors[] = "Extension de fichier non autorisée.";
                    } elseif ($imageInfo['size'] > 2 * 1024 * 1024) {
                        $errors[] = "L'image ne peut pas dépasser 2 Mo.";
                    } else {
                        // Verify actual file content
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mime = finfo_file($finfo, $imageInfo['tmp_name']);
                        finfo_close($finfo);
                        if (!in_array($mime, $allowedTypes)) {
                            $errors[] = "Type de fichier non autorisé détecté.";
                        } else {
                            $imageName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
                            $uploadDir = __DIR__ . '/../assets/images/';
                            
                            // Create directory if it doesn't exist
                            if (!file_exists($uploadDir)) {
                                mkdir($uploadDir, 0755, true);
                            }
                            
                            if (!move_uploaded_file($imageInfo['tmp_name'], $uploadDir . $imageName)) {
                                error_log("Failed to move uploaded file: " . $imageInfo['tmp_name']);
                                $errors[] = "Impossible d'enregistrer l'image.";
                            }
                        }
                    }
                }
            }
            
            if (empty($errors)) {
                $bookData = [
                    'title' => htmlspecialchars($title),
                    'author' => htmlspecialchars($author),
                    'description' => htmlspecialchars($description),
                    'statut' => $statut,
                    'user_id' => $_SESSION['user_id'],
                    'image' => $imageName
                ];

                $book = BookManager::create($this->db, $bookData);
                if ($book) {
                    header('Location: ' . BASE_URL . '?controller=user&action=profile&id=' . $_SESSION['user_id']);
                    exit;
                } else {
                    error_log('Database error adding book.');
                    $errors[] = "Erreur lors de l'ajout du livre.";
                }
            }
            
            $error = implode('<br>', $errors);
        }
        
        require __DIR__ . '/../views/books/create.php';
    }
}
