<?php
/**
 * Modèle Message pour TomTroc
 * Représente un message dans une conversation
 */
class Message
{
    private $id;
    private $conversation_id;
    private $sender_id;
    private $content;
    private $created_at;
    private $is_read;

    /**
     * Constructeur
     * @param array|mixed ...$args Données pour initialiser le message
     */
    public function __construct(...$args)
    {
        if (count($args) === 1 && is_array($args[0])) {
            $data = $args[0];
            $this->id = $data['id'] ?? null;
            $this->conversation_id = $data['conversation_id'] ?? null;
            $this->sender_id = $data['sender_id'] ?? null;
            $this->content = $data['content'] ?? '';
            $this->created_at = $data['created_at'] ?? null;
            $this->is_read = $data['is_read'] ?? 0;
        }
        // Sinon PDO assignera automatiquement les propriétés par nom
    }

    // Getters

    /**
     * Get message ID
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get conversation ID
     * @return int|null
     */
    public function getConversationId()
    {
        return $this->conversation_id;
    }

    /**
     * Get sender ID
     * @return int|null
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * Get message content
     * @return string
     */
    public function getContent()
    {
        return $this->content;
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
     * Get read status
     * @return int
     */
    public function getIsRead()
    {
        return $this->is_read;
    }

    /**
     * Get formatted date (d.m)
     * @return string
     */
    public function getOnlyDate()
    {
        try {
            if ($this->created_at === null) {
                return '';
            }
            $date = new DateTime($this->created_at);
            return $date->format('d.m');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Get formatted time (H:i)
     * @return string
     */
    public function getOnlyTime()
    {
        try {
            if ($this->created_at === null) {
                return '';
            }
            $date = new DateTime($this->created_at);
            return $date->format('H:i');
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * Check if message is read
     * @return bool
     */
    public function isRead()
    {
        return $this->is_read == 1;
    }

    // Setters

    /**
     * Set message ID
     * @param int $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set conversation ID
     * @param int $conversation_id
     * @return void
     */
    public function setConversationId($conversation_id)
    {
        $this->conversation_id = $conversation_id;
    }

    /**
     * Set sender ID
     * @param int $sender_id
     * @return void
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    /**
     * Set message content
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
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

    /**
     * Set read status
     * @param int $is_read
     * @return void
     */
    public function setIsRead($is_read)
    {
        $this->is_read = $is_read;
    }
}
