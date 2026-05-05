<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../models/Livre.php';
require_once __DIR__ . '/../includes/database.php';

class UserController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function profile($id)
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }
        
        $user = Utilisateur::getById($this->db, $id);
        if (!$user) {
            // Handle not found
            header('Location: ' . BASE_URL . '?controller=home');
            exit;
        }
        // Get user's books
        $stmt = mysqli_prepare($this->db, "SELECT * FROM livres WHERE utilisateur_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $livres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $livres[] = new Livre($row);
        }
        mysqli_stmt_close($stmt);
        require __DIR__ . '/../views/users/profile.php';
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $errors[] = "Token CSRF invalide.";
            }
            
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Server-side validation
            $errors = [];
            
            if (empty($nom)) {
                $errors[] = "Le nom est requis.";
            } elseif (strlen($nom) < 2) {
                $errors[] = "Le nom doit contenir au moins 2 caractères.";
            }
            
            if (empty($email)) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            } else {
                // Check if email already exists
                $existingUser = Utilisateur::getByEmail($this->db, $email);
                if ($existingUser) {
                    $errors[] = "Cet email est déjà utilisé.";
                }
            }
            
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }
            
            if (empty($errors)) {
                $user = new Utilisateur(['nom' => htmlspecialchars($nom), 'email' => $email]);
                $user->setMotDePasse($password);
                if ($user->save($this->db)) {
                    header('Location: ' . BASE_URL . '?controller=user&action=login');
                    exit;
                } else {
                    error_log("Registration failed for email: $email");
                    $errors[] = "Erreur lors de l'inscription. Veuillez réessayer.";
                }
            }
            
            $error = implode('<br>', $errors);
        }
        require __DIR__ . '/../views/users/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $errors[] = "Token CSRF invalide.";
            }
            
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Server-side validation
            $errors = [];
            
            if (empty($email)) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            }
            
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            }
            
            if (empty($errors)) {
                $user = Utilisateur::getByEmail($this->db, $email);
                if ($user && $user->verifyPassword($password)) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_email'] = $user->getEmail();
                    // Regenerate session ID to prevent session fixation
                    session_regenerate_id(true);
                    header('Location: ' . BASE_URL . '?controller=home');
                    exit;
                } else {
                    error_log("Failed login attempt for email: $email");
                    $errors[] = "Email ou mot de passe incorrect.";
                }
            }
            
            $error = implode('<br>', $errors);
        }
        require __DIR__ . '/../views/users/login.php';
    }

    public function edit($id)
    {
        if (!is_logged_in() || $_SESSION['user_id'] != $id) {
            header('Location: ' . BASE_URL . '?controller=home');
            exit;
        }

        $user = Utilisateur::getById($this->db, $id);
        if (!$user) {
            header('Location: ' . BASE_URL . '?controller=home');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF validation
            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
                $errors[] = "Token CSRF invalide.";
            }
            
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($nom)) {
                $errors[] = "Le nom est requis.";
            } elseif (strlen($nom) < 2) {
                $errors[] = "Le nom doit contenir au moins 2 caractères.";
            }

            if (empty($email)) {
                $errors[] = "L'email est requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email n'est pas valide.";
            } elseif ($email !== $user->getEmail()) {
                $existingUser = Utilisateur::getByEmail($this->db, $email);
                if ($existingUser && $existingUser->getId() !== $id) {
                    $errors[] = "Cet email est déjà utilisé.";
                }
            }

            if (!empty($password) && strlen($password) < 6) {
                $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
            }

            if (empty($errors)) {
                $user->setNom(htmlspecialchars($nom));
                $user->setEmail($email);
                if (!empty($password)) {
                    $user->setMotDePasse($password);
                }
                if ($user->save($this->db)) {
                    $_SESSION['user_email'] = $email;
                    header('Location: ' . BASE_URL . '?controller=user&action=profile&id=' . $id);
                    exit;
                }
                error_log("Failed to update profile for user: $id");
                $errors[] = "Erreur lors de la mise à jour du profil.";
            }

            $error = implode('<br>', $errors);
        }

        require __DIR__ . '/../views/users/edit.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASE_URL . '?controller=home');
        exit;
    }
}
