<?php
/**
 * Gestionnaire pour les opérations métier sur les livres
 * Sépare la logique métier des données du modèle Book
 */
class BookManager
{
    /**
     * Récupère tous les livres disponibles
     * @param PDO $db Connexion à la base de données
     * @return Book[] Liste des livres disponibles
     */
    public static function getAllAvailable($db)
    {
        $stmt = $db->prepare("SELECT * FROM books WHERE statut = 'disponible'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Recherche des livres disponibles par titre
     * @param PDO $db Connexion à la base de données
     * @param string $query Terme de recherche
     * @return Book[] Liste des livres correspondants
     */
    public static function searchAvailable($db, $query)
    {
        $stmt = $db->prepare("SELECT * FROM books WHERE statut = 'disponible' AND title LIKE ?");
        $stmt->execute(['%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Récupère un livre par son ID
     * @param PDO $db Connexion à la base de données
     * @param int $id ID du livre
     * @return Book|null Le livre trouvé ou null
     */
    public static function getById($db, $id)
    {
        $stmt = $db->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Book');
        return $stmt->fetch();
    }

    /**
     * Récupère tous les livres d'un utilisateur
     * @param PDO $db Connexion à la base de données
     * @param int $userId ID de l'utilisateur
     * @return Book[] Liste des livres de l'utilisateur
     */
    public static function getByUserId($db, $userId)
    {
        $stmt = $db->prepare('SELECT * FROM books WHERE user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Crée un nouveau livre
     * @param PDO $db Connexion à la base de données
     * @param array $data Données du livre
     * @return Book Le livre créé
     */
    public static function create($db, $data)
    {
        $stmt = $db->prepare('INSERT INTO books (title, author, description, statut, user_id, image, date_ajout) VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['description'],
            $data['statut'] ?? 'disponible',
            $data['user_id'],
            $data['image'] ?? null
        ]);

        $bookId = $db->lastInsertId();
        return self::getById($db, $bookId);
    }

    /**
     * Met à jour un livre
     * @param PDO $db Connexion à la base de données
     * @param int $id ID du livre
     * @param array $data Données à mettre à jour
     * @return bool Succès de la mise à jour
     */
    public static function update($db, $id, $data)
    {
        $fields = [];
        $values = [];

        if (isset($data['title'])) {
            $fields[] = 'title = ?';
            $values[] = $data['title'];
        }
        if (isset($data['author'])) {
            $fields[] = 'author = ?';
            $values[] = $data['author'];
        }
        if (isset($data['description'])) {
            $fields[] = 'description = ?';
            $values[] = $data['description'];
        }
        if (isset($data['statut'])) {
            $fields[] = 'statut = ?';
            $values[] = $data['statut'];
        }
        if (isset($data['image'])) {
            $fields[] = 'image = ?';
            $values[] = $data['image'];
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $stmt = $db->prepare('UPDATE books SET ' . implode(', ', $fields) . ' WHERE id = ?');
        return $stmt->execute($values);
    }

    /**
     * Supprime un livre
     * @param PDO $db Connexion à la base de données
     * @param int $id ID du livre
     * @return bool Succès de la suppression
     */
    public static function delete($db, $id)
    {
        $stmt = $db->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }
}