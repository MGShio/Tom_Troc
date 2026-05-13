<?php
/**
 * BookManager - Gestionnaire des livres
 * Sépare la logique métier de la base de données
 */
class BookManager extends AbstractEntityManager
{
    /**
     * Récupère tous les livres disponibles
     * @return Book[] Liste des livres disponibles
     */
    public function getAllAvailable()
    {
        $stmt = $this->db->prepare("SELECT b.*, u.pseudo AS seller FROM books b INNER JOIN users u ON b.user_id = u.id WHERE b.disponibilite = 'disponible' OR b.statut = 'disponible'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Recherche des livres disponibles par titre
     * @param string $query Terme de recherche
     * @return Book[] Liste des livres correspondants
     */
    public function searchAvailable($query)
    {
        $search = '%' . $query . '%';
        $stmt = $this->db->prepare("SELECT b.*, u.pseudo AS seller FROM books b INNER JOIN users u ON b.user_id = u.id WHERE (b.disponibilite = 'disponible' OR b.statut = 'disponible') AND b.title LIKE ?");
        $stmt->execute([$search]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Récupère un livre par son ID
     * @param int $id ID du livre
     * @return Book|null Le livre trouvé ou null
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare('SELECT b.*, u.pseudo AS seller FROM books b LEFT JOIN users u ON b.user_id = u.id WHERE b.id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Book');
        return $stmt->fetch();
    }

    /**
     * Récupère tous les livres d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return Book[] Liste des livres de l'utilisateur
     */
    public function getByUserId($userId)
    {
        $stmt = $this->db->prepare('SELECT b.*, u.pseudo AS seller FROM books b LEFT JOIN users u ON b.user_id = u.id WHERE b.user_id = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Crée un nouveau livre
     * @param array $data Données du livre
     * @return Book Le livre créé
     */
    public function create($data)
    {
        $stmt = $this->db->prepare('INSERT INTO books (title, author, description, statut, user_id, image, disponibilite, date_ajout) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['description'],
            $data['statut'] ?? 'disponible',
            $data['user_id'],
            $data['image'] ?? 'Book_default.png',
            $data['disponibilite'] ?? 'disponible'
        ]);

        $bookId = $this->db->lastInsertId();
        return $this->getById($bookId);
    }

    /**
     * Met à jour un livre
     * @param int $id ID du livre
     * @param array $data Données à mettre à jour
     * @return bool Succès de la mise à jour
     */
    public function update($id, $data)
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
        if (isset($data['disponibilite'])) {
            $fields[] = 'disponibilite = ?';
            $values[] = $data['disponibilite'];
        }
        if (isset($data['image'])) {
            $fields[] = 'image = ?';
            $values[] = $data['image'];
        }

        if (empty($fields)) {
            return false;
        }

        $values[] = $id;
        $sql = 'UPDATE books SET ' . implode(', ', $fields) . ' WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Supprime un livre
     * @param int $id ID du livre
     * @return bool Succès de la suppression
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare('DELETE FROM books WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Récupère tous les livres avec jointure pour le vendeur
     * @return Book[] Liste de tous les livres
     */
    public function getAllWithSeller()
    {
        $stmt = $this->db->prepare('SELECT b.*, u.pseudo AS seller FROM books b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.date_ajout DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }

    /**
     * Récupère les derniers livres ajoutés
     * @param int $limit Nombre de livres à récupérer
     * @return Book[] Liste des livres récents
     */
    public function getRecent($limit = 4)
    {
        $stmt = $this->db->prepare("SELECT b.*, u.pseudo AS seller FROM books b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.date_ajout DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Book');
    }
}
