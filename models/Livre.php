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
        $stmt = mysqli_prepare($db, "SELECT * FROM livres WHERE statut = 'disponible'");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $livres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $livres[] = new self($row);
        }
        mysqli_stmt_close($stmt);
        return $livres;
    }

    public static function getById($db, $id)
    {
        $stmt = mysqli_prepare($db, "SELECT * FROM livres WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return new self($row);
        }
        mysqli_stmt_close($stmt);
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
