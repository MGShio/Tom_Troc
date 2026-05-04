<?php
require_once __DIR__ . '/Utilisateur.php';

class Message
{
    private $id;
    private $expediteur_id;
    private $destinataire_id;
    private $contenu;
    private $date_envoi;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->expediteur_id = $data['expediteur_id'] ?? null;
        $this->destinataire_id = $data['destinataire_id'] ?? null;
        $this->contenu = $data['contenu'] ?? '';
        $this->date_envoi = $data['date_envoi'] ?? null;
    }

    public static function getByUser($db, $user_id)
    {
        $stmt = mysqli_prepare($db, "SELECT * FROM messages WHERE destinataire_id = ? ORDER BY date_envoi DESC");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $messages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = new self($row);
        }
        mysqli_stmt_close($stmt);
        return $messages;
    }

    public static function getConversationPartners($db, $user_id)
    {
        $query = "SELECT u.id, u.nom, u.email, u.mot_de_passe
                  FROM utilisateurs u
                  INNER JOIN (
                      SELECT CASE WHEN expediteur_id = ? THEN destinataire_id ELSE expediteur_id END AS correspondant_id,
                             MAX(date_envoi) AS dernier_message
                      FROM messages
                      WHERE expediteur_id = ? OR destinataire_id = ?
                      GROUP BY correspondant_id
                  ) m ON u.id = m.correspondant_id
                  ORDER BY m.dernier_message DESC";

        $stmt = mysqli_prepare($db, $query);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $correspondants = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $correspondants[] = new Utilisateur($row);
        }
        mysqli_stmt_close($stmt);
        return $correspondants;
    }

    public static function getConversation($db, $user_id, $correspondant_id)
    {
        $stmt = mysqli_prepare($db, "SELECT * FROM messages WHERE (expediteur_id = ? AND destinataire_id = ?) OR (expediteur_id = ? AND destinataire_id = ?) ORDER BY date_envoi ASC");
        mysqli_stmt_bind_param($stmt, "iiii", $user_id, $correspondant_id, $correspondant_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $messages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = new self($row);
        }
        mysqli_stmt_close($stmt);
        return $messages;
    }

    public function save($db)
    {
        $stmt = mysqli_prepare($db, "INSERT INTO messages (expediteur_id, destinataire_id, contenu, date_envoi) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt, "iis", $this->expediteur_id, $this->destinataire_id, $this->contenu);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        if ($result) {
            $this->id = mysqli_insert_id($db);
        }
        return $result;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getExpediteurId() { return $this->expediteur_id; }
    public function getDestinataireId() { return $this->destinataire_id; }
    public function getContenu() { return $this->contenu; }
    public function getDateEnvoi() { return $this->date_envoi; }

    // Setters
    public function setExpediteurId($id) { $this->expediteur_id = $id; }
    public function setDestinataireId($id) { $this->destinataire_id = $id; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
}