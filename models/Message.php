<?php
require_once __DIR__ . '/User.php';

class Message
{
    private $id;
    private $sender_id;
    private $receiver_id;
    private $content;
    private $send_at;

    public function __construct($data = null)
    {
        if ($data !== null) {
            $this->id = $data['id'] ?? null;
            $this->sender_id = $data['sender_id'] ?? null;
            $this->receiver_id = $data['receiver_id'] ?? null;
            $this->content = $data['content'] ?? '';
            $this->send_at = $data['send_at'] ?? null;
        }
    }

    public static function getByUser($db, $user_id)
    {
        $stmt = $db->prepare('SELECT * FROM messages WHERE receiver_id = ? ORDER BY send_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
    }

    public static function getConversationParticipants($db, $user_id)
    {
        $query = "SELECT u.id, u.name, u.email, u.password
                  FROM users u
                  INNER JOIN (
                      SELECT CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END AS participant_id,
                             MAX(send_at) AS last_message
                      FROM messages
                      WHERE sender_id = ? OR receiver_id = ?
                      GROUP BY participant_id
                  ) m ON u.id = m.participant_id
                  ORDER BY m.last_message DESC";

        $stmt = $db->prepare($query);
        $stmt->execute([$user_id, $user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
        return $participants;
    }

    public static function getConversation($db, $user_id, $participant_id)
    {
        $stmt = $db->prepare('SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY send_at ASC');
        $stmt->execute([$user_id, $participant_id, $participant_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
    }

    public function save($db)
    {
        $stmt = $db->prepare('INSERT INTO messages (sender_id, receiver_id, content, send_at) VALUES (?, ?, ?, NOW())');
        $result = $stmt->execute([$this->sender_id, $this->receiver_id, $this->content]);
        if ($result) {
            $this->id = $db->lastInsertId();
        }
        return $result;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getSenderId()
    {
        return $this->sender_id;
    }
    public function getReceiverId()
    {
        return $this->receiver_id;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function getDateEnvoi()
    {
        return $this->send_at;
    }

    // Setters
    public function setSenderId($id)
    {
        $this->sender_id = $id;
    }
    public function setReceiverId($id)
    {
        $this->receiver_id = $id;
    }
    public function setContent($content)
    {
        $this->content = $content;
    }
}
