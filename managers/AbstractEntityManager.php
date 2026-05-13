<?php
/**
 * AbstractEntityManager - Classe abstraite pour les gestionnaires d'entités
 * Fournit des fonctionnalités communes à tous les managers
 */
abstract class AbstractEntityManager
{
    /**
     * @var PDO Connexion à la base de données
     */
    protected $db;

    /**
     * Constructeur
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Exécute une requête préparée
     * @param string $sql Requête SQL
     * @param array $params Paramètres pour la requête
     * @return PDOStatement
     */
    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * Récupère le dernier ID inséré
     * @return int
     */
    protected function lastInsertId()
    {
        return (int)$this->db->lastInsertId();
    }

    /**
     * Récupère la connexion PDO
     * @return PDO
     */
    public function getPDO()
    {
        return $this->db;
    }
}
