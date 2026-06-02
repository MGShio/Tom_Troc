<?php
/**
 * Modèle Book pour TomTroc
 * Représente un livre de la plateforme
 */
class Book
{
    private $id;
    private $title;
    private $author;
    private $description;
    private $statut;
    private $user_id;
    private $image;
    private $date_ajout;
    private $disponibilite;
    private $seller;

    /**
     * Constructeur
     * @param array|mixed ...$args Données pour initialiser le livre
     */
    public function __construct(...$args)
    {
        if (count($args) === 1 && is_array($args[0])) {
            $data = $args[0];
            $this->id = $data['id'] ?? null;
            $this->title = $data['title'] ?? '';
            $this->author = $data['author'] ?? '';
            $this->description = $data['description'] ?? '';
            $this->statut = $data['statut'] ?? $data['disponibilite'] ?? 'disponible';
            $this->user_id = $data['user_id'] ?? null;
            $this->image = $data['image'] ?? '';
            $this->date_ajout = $data['date_ajout'] ?? $data['created_at'] ?? null;
            $this->disponibilite = $data['disponibilite'] ?? $data['statut'] ?? 'disponible';
            $this->seller = $data['seller'] ?? $data['pseudo'] ?? null;
        }
        // Sinon PDO assignera automatiquement les propriétés par nom
    }

    // Getters

    /**
     * Get book ID
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get book title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get book author
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Get book description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get book status (statut)
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Get book user ID
     * @return int|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Get book image filename
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Get book creation date
     * @return string|null
     */
    public function getDateAjout()
    {
        return $this->date_ajout;
    }

    /**
     * Get book disponibilite
     * @return string
     */
    public function getDisponibilite()
    {
        return $this->disponibilite;
    }

    /**
     * Get seller name
     * @return string|null
     */
    public function getSeller()
    {
        return $this->seller;
    }

    // Setters

    /**
     * Set book title
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set book author
     * @param string $author
     * @return void
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Set book description
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set book status
     * @param string $statut
     * @return void
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * Set book user ID
     * @param int $user_id
     * @return void
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Set book image
     * @param string $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * Set book disponibilite
     * @param string $disponibilite
     * @return void
     */
    public function setDisponibilite($disponibilite)
    {
        $this->disponibilite = $disponibilite;
    }

    /**
     * Set seller name
     * @param string $seller
     * @return void
     */
    public function setSeller($seller)
    {
        $this->seller = $seller;
    }

    /**
     * Set book creation date
     * @param string $date_ajout
     * @return void
     */
    public function setDateAjout($date_ajout)
    {
        $this->date_ajout = $date_ajout;
    }
}
