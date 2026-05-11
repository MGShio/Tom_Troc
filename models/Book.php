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
    private $date_ajout;

    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->id = $data['id'] ?? null;
            $this->title = $data['title'] ?? '';
            $this->author = $data['author'] ?? '';
            $this->description = $data['description'] ?? '';
            $this->statut = $data['statut'] ?? '';
            $this->user_id = $data['user_id'] ?? null;
            $this->image = $data['image'] ?? '';
            $this->date_ajout = $data['date_ajout'] ?? null;
        }
    }

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
     * Get book status
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
     * Set book image
     * @param string $image
     * @return void
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
