<?php
class Utilisateur
{
    private $id;
    private $nom;
    private $email;
    private $mot_de_passe;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->nom = $data['nom'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->mot_de_passe = $data['mot_de_passe'] ?? '';
    }

    public static function getById($db, $id)
    {
        $stmt = mysqli_prepare($db, "SELECT id, nom, email, mot_de_passe FROM utilisateurs WHERE id = ?");
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

    public static function getByEmail($db, $email)
    {
        $stmt = mysqli_prepare($db, "SELECT id, nom, email, mot_de_passe FROM utilisateurs WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            mysqli_stmt_close($stmt);
            return new self($row);
        }
        mysqli_stmt_close($stmt);
        return null;
    }

    public function save($db)
    {
        if ($this->id) {
            // Update
            $stmt = mysqli_prepare($db, "UPDATE utilisateurs SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $this->nom, $this->email, $this->mot_de_passe, $this->id);
        } else {
            // Insert
            $stmt = mysqli_prepare($db, "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $this->nom, $this->email, $this->mot_de_passe);
        }
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if (!$this->id && $result) {
            $this->id = mysqli_insert_id($db);
        }
        return $result;
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->mot_de_passe);
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }

    // Setters
    public function setNom($nom) { $this->nom = $nom; }
    public function setEmail($email) { $this->email = $email; }
    public function setMotDePasse($password) { $this->mot_de_passe = password_hash($password, PASSWORD_DEFAULT); }
}