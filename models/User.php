<?php
/**
 * Modèle User pour TomTroc
 * Représente un utilisateur de la plateforme
 */
class User
{
    private $id;
    private $pseudo;
    private $email;
    private $password;
    private $avatar;
    private $created_at;

    /**
     * Constructeur
     * @param array|null $data Données pour initialiser l'utilisateur
     */
    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->id = $data['id'] ?? null;
            $this->pseudo = $data['pseudo'] ?? ($data['name'] ?? '');
            $this->email = $data['email'] ?? '';
            $this->password = $data['password'] ?? '';
            $this->avatar = $data['avatar'] ?? 'Avatar_default.png';
            $this->created_at = $data['created_at'] ?? null;
        }
    }

    /**
     * Récupère un utilisateur par son ID
     * @param PDO $db Connexion à la base de données
     * @param int $id ID de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public static function getById($db, $id)
    {
        $stmt = $db->prepare('SELECT id, pseudo, email, password, avatar, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    /**
     * Récupère un utilisateur par son email
     * @param PDO $db Connexion à la base de données
     * @param string $email Email de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public static function getByEmail($db, $email)
    {
        $stmt = $db->prepare('SELECT id, pseudo, email, password, avatar, created_at FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }

    /**
     * Sauvegarde l'utilisateur en base de données
     * @param PDO $db Connexion à la base de données
     * @return bool Succès de l'opération
     */
    public function save($db)
    {
        if ($this->id) {
            // Mise à jour
            if (!empty($this->password)) {
                $stmt = $db->prepare('UPDATE users SET pseudo = ?, email = ?, password = ?, avatar = ? WHERE id = ?');
                $result = $stmt->execute([$this->pseudo, $this->email, $this->password, $this->avatar, $this->id]);
            } else {
                // Ne pas mettre à jour le password s'il est vide
                $stmt = $db->prepare('UPDATE users SET pseudo = ?, email = ?, avatar = ? WHERE id = ?');
                $result = $stmt->execute([$this->pseudo, $this->email, $this->avatar, $this->id]);
            }
        } else {
            // Création
            $stmt = $db->prepare('INSERT INTO users (pseudo, email, password, avatar, created_at) VALUES (?, ?, ?, ?, NOW())');
            $result = $stmt->execute([$this->pseudo, $this->email, $this->password, $this->avatar]);
            if ($result) {
                $this->id = $db->lastInsertId();
            }
        }
        return $result;
    }

    /**
     * Vérifie si le mot de passe correspond
     * @param string $password Mot de passe à vérifier
     * @return bool True si le mot de passe est correct
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    // Getters

    /**
     * Get user ID
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get user pseudo
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Get user name (alias pour getPseudo pour compatibilité)
     * @return string
     */
    public function getName()
    {
        return $this->pseudo;
    }

    /**
     * Get user email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get user avatar
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar ?? 'Avatar_default.png';
    }

    /**
     * Get user creation date
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get user password (hashed)
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    // Setters

    /**
     * Set user pseudo
     * @param string $pseudo
     * @return void
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }

    /**
     * Set user name (alias pour setPseudo)
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->pseudo = $name;
    }

    /**
     * Set user email
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Set user password (hashed)
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Set user avatar
     * @param string $avatar
     * @return void
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * Set user creation date
     * @param string $created_at
     * @return void
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}
