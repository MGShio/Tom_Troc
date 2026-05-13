<?php
/**
 * UserManager - Gestionnaire des utilisateurs
 * Sépare la logique métier de la base de données
 */
class UserManager extends AbstractEntityManager
{
    /**
     * Récupère un utilisateur par son ID
     * @param int $id ID de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public function getUserById(int $id): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data);
        }
        return null;
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email Email de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public function getUserByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data);
        }
        return null;
    }

    /**
     * Récupère un utilisateur par son pseudo
     * @param string $pseudo Pseudo de l'utilisateur
     * @return User|null L'utilisateur trouvé ou null
     */
    public function getUserByPseudo(string $pseudo): ?User
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE pseudo = :pseudo');
        $stmt->execute(['pseudo' => $pseudo]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data);
        }
        return null;
    }

    /**
     * Crée un nouvel utilisateur
     * @param User $user Utilisateur à créer
     * @return User L'utilisateur créé avec son ID
     */
    public function createUser(User $user): User
    {
        $avatar = $user->getAvatar();
        if (empty($avatar)) {
            $avatar = 'Avatar_default.png';
        }
        
        $stmt = $this->db->prepare('INSERT INTO users (pseudo, email, password, avatar, created_at) VALUES (:pseudo, :email, :password, :avatar, NOW())');
        $stmt->execute([
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'avatar' => $avatar
        ]);
        
        $user->setId($this->db->lastInsertId());
        return $user;
    }

    /**
     * Met à jour un utilisateur
     * @param User $user Utilisateur à mettre à jour
     * @return bool Succès de l'opération
     */
    public function updateUser(User $user): bool
    {
        $sql = 'UPDATE users SET pseudo = :pseudo, email = :email, avatar = :avatar';
        $params = [
            'pseudo' => $user->getPseudo(),
            'email' => $user->getEmail(),
            'avatar' => $user->getAvatar(),
            'id' => $user->getId()
        ];
        
        // Si un nouveau mot de passe est défini
        if (!empty($user->getPassword())) {
            $sql .= ', password = :password';
            $params['password'] = $user->getPassword();
        }
        
        $sql .= ' WHERE id = :id';
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Supprime un utilisateur
     * @param int $id ID de l'utilisateur
     * @return bool Succès de l'opération
     */
    public function deleteUser(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Récupère tous les utilisateurs
     * @return User[] Liste des utilisateurs
     */
    public function getAllUsers(): array
    {
        $stmt = $this->db->query('SELECT * FROM users ORDER BY pseudo');
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($data);
        }
        return $users;
    }

    /**
     * Vérifie si un pseudo existe déjà
     * @param string $pseudo Pseudo à vérifier
     * @param int|null $excludeId ID à exclure (pour l'édition)
     * @return bool True si le pseudo existe déjà
     */
    public function pseudoExists(string $pseudo, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE pseudo = :pseudo';
        $params = ['pseudo' => $pseudo];
        
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Vérifie si un email existe déjà
     * @param string $email Email à vérifier
     * @param int|null $excludeId ID à exclure (pour l'édition)
     * @return bool True si l'email existe déjà
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $params = ['email' => $email];
        
        if ($excludeId !== null) {
            $sql .= ' AND id != :id';
            $params['id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }
}
