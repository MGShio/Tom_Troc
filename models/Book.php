<?php
class Book
{
    private $id;
    private $title;
    private $author;
    private $description;
    private $statut;
    private $user_id;
    private $image;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->title = $data['title'] ?? '';
        $this->author = $data['author'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->statut = $data['statut'] ?? '';
        $this->user_id = $data['user_id'] ?? null;
        $this->image = $data['image'] ?? '';
    }

    public static function getAllAvailable($db)
    {
        $stmt = $db->prepare("SELECT * FROM books WHERE statut = 'disponible'");
        $stmt->execute();
        $books = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $books[] = new self($row);
        }
        return $books;
    }

    public static function getById($db, $id)
    {
        $stmt = $db->prepare('SELECT * FROM books WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new self($row) : null;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getAuthor() { return $this->author; }
    public function getDescription() { return $this->description; }
    public function getStatut() { return $this->statut; }
    public function getUserId() { return $this->user_id; }
    public function getImage() { return $this->image; }
}
