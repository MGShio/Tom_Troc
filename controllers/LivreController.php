<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../includes/database.php';

class LivreController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        $livres = Livre::getAllDisponibles($this->db);
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../views/livres/index.php';
        require __DIR__ . '/../includes/footer.php';
    }

    public function show($id)
    {
        $livre = Livre::getById($this->db, $id);
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../views/livres/show.php';
        require __DIR__ . '/../includes/footer.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $auteur = trim($_POST['auteur'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $statut = $_POST['statut'] ?? '';
            
            // Server-side validation
            $errors = [];
            
            if (empty($titre)) {
                $errors[] = "Le titre est requis.";
            } elseif (strlen($titre) > 255) {
                $errors[] = "Le titre ne peut pas dépasser 255 caractères.";
            }
            
            if (empty($auteur)) {
                $errors[] = "L'auteur est requis.";
            } elseif (strlen($auteur) > 255) {
                $errors[] = "Le nom de l'auteur ne peut pas dépasser 255 caractères.";
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
                $imageInfo = $_FILES['image'];
                if ($imageInfo['error'] !== UPLOAD_ERR_OK) {
                    $errors[] = "Erreur lors du téléchargement de l'image.";
                } elseif (!in_array($imageInfo['type'], $allowedTypes)) {
                    $errors[] = "Format d'image non pris en charge. Utilisez PNG, JPG ou GIF.";
                } elseif ($imageInfo['size'] > 2 * 1024 * 1024) {
                    $errors[] = "L'image ne peut pas dépasser 2 Mo.";
                } else {
                    $extension = pathinfo($imageInfo['name'], PATHINFO_EXTENSION);
                    $imageName = time() . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($extension);
                    $uploadDir = __DIR__ . '/../assets/images/';
                    if (!move_uploaded_file($imageInfo['tmp_name'], $uploadDir . $imageName)) {
                        $errors[] = "Impossible d'enregistrer l'image.";
                    }
                }
            }
            
            if (empty($errors)) {
                $livreData = [
                    'titre' => htmlspecialchars($titre),
                    'auteur' => htmlspecialchars($auteur),
                    'description' => htmlspecialchars($description),
                    'statut' => $statut,
                    'utilisateur_id' => $_SESSION['user_id'],
                    'image' => $imageName
                ];
                
                $stmt = mysqli_prepare($this->db, "INSERT INTO livres (titre, auteur, description, statut, utilisateur_id, image) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssis", $livreData['titre'], $livreData['auteur'], $livreData['description'], $livreData['statut'], $livreData['utilisateur_id'], $livreData['image']);
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_close($stmt);
                    header('Location: ' . BASE_URL . '?controller=user&action=profile&id=' . $_SESSION['user_id']);
                    exit;
                } else {
                    $errors[] = "Erreur lors de l'ajout du livre.";
                    mysqli_stmt_close($stmt);
                }
            }
            
            $error = implode('<br>', $errors);
        }
        
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../views/livres/create.php';
        require __DIR__ . '/../includes/footer.php';
    }
}
