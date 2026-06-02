<?php
/**
 * Modèle Conversation pour TomTroc
 * Représente une conversation entre deux utilisateurs
 */
class Conversation
{
    private $id;
    private $user1_id;
    private $user2_id;
    private $created_at;

    /**
     * Constructeur
     * @param array|mixed ...$args Données pour initialiser la conversation
     */
    public function __construct(...$args)
    {
        if (count($args) === 1 && is_array($args[0])) {
            $data = $args[0];
            $this->id = $data['id'] ?? null;
            $this->user1_id = $data['user1_id'] ?? null;
            $this->user2_id = $data['user2_id'] ?? null;
            $this->created_at = $data['created_at'] ?? null;
        }
        // Sinon PDO assignera automatiquement les propriétés par nom
    }

    // Getters

    /**
     * Get conversation ID
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get first user ID
     * @return int|null
     */
    public function getUser1Id()
    {
        return $this->user1_id;
    }

    /**
     * Get second user ID
     * @return int|null
     */
    public function getUser2Id()
    {
        return $this->user2_id;
    }

    /**
     * Get creation date
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Get the other user ID in the conversation
     * @param int $currentUserId Current user ID
     * @return int|null The other user ID
     */
    public function getOtherUserId($currentUserId)
    {
        if ($this->user1_id == $currentUserId) {
            return $this->user2_id;
        }
        return $this->user1_id;
    }

    // Setters

    /**
     * Set conversation ID
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set first user ID
     * @param int $user1_id
     * @return void
     */
    public function setUser1Id($user1_id)
    {
        $this->user1_id = $user1_id;
    }

    /**
     * Set second user ID
     * @param int $user2_id
     * @return void
     */
    public function setUser2Id($user2_id)
    {
        $this->user2_id = $user2_id;
    }

    /**
     * Set creation date
     * @param string $created_at
     * @return void
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}
