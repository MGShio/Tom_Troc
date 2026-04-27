<?php
class Livre
{
    private $id;
    private $titre;
    private $auteur;
    private $description;
    private $statut;
    private $utilisateur_id;
    private $image;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->titre = $data['titre'] ?? '';
        $this->auteur = $data['auteur'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->statut = $data['statut'] ?? '';
        $this->utilisateur_id = $data['utilisateur_id'] ?? null;
        $this->image = $data['image'] ?? '';
    }

    public static function getAllDisponibles($db)
    {
        $query = "SELECT * FROM livres WHERE statut = 'disponible'";
        $result = $db->query($query);
        $livres = [];
        while ($row = $result->fetch_assoc()) {
            $livres[] = new self($row);
        }
        return $livres;
    }

    public static function getById($db, $id)
    {
        $id = (int)$id;
        $query = "SELECT * FROM livres WHERE id = $id";
        $result = $db->query($query);
        if ($row = $result->fetch_assoc()) {
            return new self($row);
        }
        return null;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getAuteur() { return $this->auteur; }
    public function getDescription() { return $this->description; }
    public function getStatut() { return $this->statut; }
    public function getUtilisateurId() { return $this->utilisateur_id; }
    public function getImage() { return $this->image; }
}
