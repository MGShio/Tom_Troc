<?php
/**
 * UserController - Gestion des utilisateurs
 */
class UserController
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
     * Affiche la page de connexion
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || !Utils::verifyCsrfToken($_POST['csrf_token'])) {
                $error = "Token CSRF invalide.";
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
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
                    $userManager = new UserManager($this->db);
                    $user = $userManager->getUserByEmail($email);
                    if ($user && $user->verifyPassword($password)) {
                        $_SESSION['user_id'] = $user->getId();
                        $_SESSION['user_email'] = $user->getEmail();
                        $_SESSION['user_pseudo'] = $user->getPseudo();
                        session_regenerate_id(true);
                        Utils::redirect(BASE_URL . '?controller=home');
                    } else {
                        $errors[] = "Email ou mot de passe incorrect.";
                    }
                }

                $error = implode('<br>', $errors);
            }
        }
        
        // Générer un token CSRF
        $_SESSION['csrf_token'] = Utils::generateCsrfToken();
        require __DIR__ . '/../views/users/login.php';
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        session_destroy();
        Utils::redirect(BASE_URL . '?controller=home');
    }

    /**
     * Affiche la page d'inscription
     */
    public function register()
    {
        $userManager = new UserManager($this->db);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || !Utils::verifyCsrfToken($_POST['csrf_token'])) {
                $error = "Token CSRF invalide.";
            } else {
                $pseudo = trim($_POST['pseudo'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';
                $errors = [];

                if (empty($pseudo)) {
                    $errors[] = "Le pseudo est requis.";
                } elseif (strlen($pseudo) < 2) {
                    $errors[] = "Le pseudo doit contenir au moins 2 caractères.";
                }

                if (empty($email)) {
                    $errors[] = "L'email est requis.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "L'email n'est pas valide.";
                }

                if (empty($password)) {
                    $errors[] = "Le mot de passe est requis.";
                } elseif (strlen($password) < 6) {
                    $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
                }

                if ($password !== $passwordConfirm) {
                    $errors[] = "Les mots de passe ne correspondent pas.";
                }

                if (empty($errors)) {
                    if ($userManager->pseudoExists($pseudo)) {
                        $errors[] = "Ce pseudo est déjà utilisé.";
                    } elseif ($userManager->emailExists($email)) {
                        $errors[] = "Cet email est déjà utilisé.";
                    }
                }

                if (empty($errors)) {
                    $user = new User();
                    $user->setPseudo(htmlspecialchars($pseudo));
                    $user->setEmail($email);
                    $user->setPassword($password);
                    $user->setAvatar('Avatar_default.png');
                    
                    if ($userManager->createUser($user)) {
                        Utils::redirect(BASE_URL . '?controller=user&action=login');
                    } else {
                        $errors[] = "Erreur lors de l'inscription. Veuillez réessayer.";
                    }
                }

                $error = implode('<br>', $errors);
            }
        }
        
        $_SESSION['csrf_token'] = Utils::generateCsrfToken();
        require __DIR__ . '/../views/users/register.php';
    }

    /**
     * Affiche le profil de l'utilisateur connecté et gère l'édition
     * @param int|null $id ID de l'utilisateur
     */
    public function profile($id)
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }
        
        // Si aucun ID n'est spécifié, utiliser l'ID de la session
        if ($id === null) {
            $id = $_SESSION['user_id'];
        }
        
        $userManager = new UserManager($this->db);
        $user = $userManager->getUserById($id);
        
        if (!$user) {
            Utils::redirect(BASE_URL . '?controller=home');
        }
        
        $bookManager = new BookManager($this->db);
        $books = $bookManager->getByUserId($id);
        
        // Gestion de la soumission du formulaire d'édition
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validation CSRF
            if (!isset($_POST['csrf_token']) || !Utils::verifyCsrfToken($_POST['csrf_token'])) {
                $error = "Token CSRF invalide.";
            } else {
                $pseudo = trim($_POST['pseudo'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $passwordConfirm = $_POST['password_confirm'] ?? '';
                $errors = [];

                if (empty($pseudo)) {
                    $errors[] = "Le pseudo est requis.";
                } elseif (strlen($pseudo) < 2) {
                    $errors[] = "Le pseudo doit contenir au moins 2 caractères.";
                }

                if (empty($email)) {
                    $errors[] = "L'email est requis.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "L'email n'est pas valide.";
                } elseif ($email !== $user->getEmail() && $userManager->emailExists($email, $id)) {
                    $errors[] = "Cet email est déjà utilisé.";
                }

                if (!empty($password)) {
                    if (strlen($password) < 6) {
                        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
                    }
                    if ($password !== $passwordConfirm) {
                        $errors[] = "Les mots de passe ne correspondent pas.";
                    }
                }

                // Gestion de l'avatar
                $avatar = $user->getAvatar();
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                    $uploadDir = ROOT_PATH . '/assets/images/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid('avatar_') . '.' . $extension;
                    $destinationPath = $uploadDir . $fileName;

                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destinationPath)) {
                        // Supprimer l'ancien avatar si ce n'est pas le default
                        $oldAvatar = $user->getAvatar();
                        if ($oldAvatar && $oldAvatar !== 'Avatar_default.png') {
                            $oldPath = $uploadDir . $oldAvatar;
                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }
                        $avatar = $fileName;
                    } else {
                        $errors[] = "Erreur lors du téléchargement de l'avatar.";
                    }
                }

                if (empty($errors)) {
                    $user->setPseudo(htmlspecialchars($pseudo));
                    $user->setEmail($email);
                    $user->setAvatar($avatar);
                    if (!empty($password)) {
                        $user->setPassword($password);
                    }
                    
                    if ($userManager->updateUser($user)) {
                        $_SESSION['user_pseudo'] = $pseudo;
                        $_SESSION['user_email'] = $email;
                        Utils::redirect(BASE_URL . '?controller=user&action=profile&id=' . $id . '&edit_success=1');
                    } else {
                        $errors[] = "Erreur lors de la mise à jour du profil.";
                    }
                }

                $error = implode('<br>', $errors);
            }
        }

        // Rendre les variables accessibles dans la vue via $GLOBALS
        $GLOBALS['user'] = $user;
        $GLOBALS['books'] = $books;
        $GLOBALS['error'] = $error ?? null;
        
        $_SESSION['csrf_token'] = Utils::generateCsrfToken();
        require __DIR__ . '/../views/users/profile.php';
    }

    /**
     * Affiche le profil public d'un utilisateur
     * Accessible sans être connecté
     * @param int $id ID de l'utilisateur
     */
    public function publicProfile($id)
    {
        $userManager = new UserManager($this->db);
        $user = $userManager->getUserById($id);
        
        if (!$user) {
            Utils::redirect(BASE_URL . '?controller=home');
        }
        
        $bookManager = new BookManager($this->db);
        $books = $bookManager->getByUserId($id);
        
        require __DIR__ . '/../views/users/public_profile.php';
    }

    /**
     * Supprime le compte utilisateur
     * @param int $id ID de l'utilisateur
     */
    public function deleteAccount($id)
    {
        if (!Utils::isUserConnected() || $_SESSION['user_id'] != $id) {
            Utils::redirect(BASE_URL . '?controller=home');
        }

        $userManager = new UserManager($this->db);
        $bookManager = new BookManager($this->db);
        
        // Suppression des livres et de leurs images
        $userBooks = $bookManager->getByUserId($id);
        foreach ($userBooks as $book) {
            $imageName = $book->getImage();
            if ($imageName !== 'Book_default.png') {
                $imagePath = ROOT_PATH . '/assets/images/' . $imageName;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $bookManager->delete($book->getId());
        }

        // Suppression de l'avatar
        $user = $userManager->getUserById($id);
        if ($user) {
            $avatarName = $user->getAvatar();
            if ($avatarName !== 'Avatar_default.png') {
                $avatarPath = ROOT_PATH . '/assets/images/' . $avatarName;
                if (file_exists($avatarPath)) {
                    unlink($avatarPath);
                }
            }
            $userManager->deleteUser($id);
        }

        // Déconnexion
        session_destroy();
        Utils::redirect(BASE_URL . '?controller=home');
    }
}
