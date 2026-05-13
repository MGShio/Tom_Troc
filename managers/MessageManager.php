<?php

/**
 * MessageManager - Gestionnaire des messages et conversations
 */
class MessageManager extends AbstractEntityManager
{
    /**
     * Récupère toutes les conversations d'un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return array Liste des conversations avec informations
     */
    public function getMyConversations(int $userId): array
    {
        $sql = "SELECT c.*,
                u1.pseudo as user1_pseudo, u1.avatar as user1_avatar,
                u2.pseudo as user2_pseudo, u2.avatar as user2_avatar,
                last_msg.content as last_message,
                last_msg.created_at as last_message_date,
                last_msg.is_read as last_message_read,
                last_msg.sender_id as last_message_sender_id
                FROM conversations c
                JOIN users u1 ON c.user1_id = u1.id
                JOIN users u2 ON c.user2_id = u2.id
                LEFT JOIN (
                    SELECT m.conversation_id, m.content, m.created_at, m.is_read, m.sender_id,
                           ROW_NUMBER() OVER (PARTITION BY m.conversation_id ORDER BY m.created_at DESC) as rn
                    FROM messages m
                ) last_msg ON c.id = last_msg.conversation_id AND last_msg.rn = 1
                WHERE c.user1_id = :userId OR c.user2_id = :userId2
                ORDER BY last_message_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['userId' => $userId, 'userId2' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les messages d'une conversation
     * @param int $conversationId ID de la conversation
     * @return Message[] Liste des messages
     */
    public function getMessagesByConversationId(int $conversationId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE conversation_id = :conversationId ORDER BY created_at ASC');
        $stmt->execute(['conversationId' => $conversationId]);

        $messages = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $messages[] = new Message($data);
        }
        return $messages;
    }

    /**
     * Crée une nouvelle conversation ou récupère une existante
     * @param int $senderId ID de l'expéditeur
     * @param int $receiverId ID du destinataire
     * @return int ID de la conversation
     */
    public function createConversation(int $senderId, int $receiverId): int
    {
        // Vérifier si une conversation existe déjà
        $sqlCheck = "SELECT id FROM conversations
                     WHERE (user1_id = :u1 AND user2_id = :u2)
                        OR (user1_id = :u2 AND user2_id = :u1)";

        $stmt = $this->db->prepare($sqlCheck);
        $stmt->execute(['u1' => $senderId, 'u2' => $receiverId]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            return (int)$existing['id'];
        }

        // Sinon, créer une nouvelle conversation
        $sqlInsert = "INSERT INTO conversations (user1_id, user2_id, created_at) VALUES (:u1, :u2, NOW())";
        $stmt = $this->db->prepare($sqlInsert);
        $stmt->execute(['u1' => $senderId, 'u2' => $receiverId]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Enregistre un message
     * @param int $conversationId ID de la conversation
     * @param int $senderId ID de l'expéditeur
     * @param string $content Contenu du message
     * @return Message Le message créé
     */
    public function postMessage(int $conversationId, int $senderId, string $content): Message
    {
        $stmt = $this->db->prepare('INSERT INTO messages (conversation_id, sender_id, content, created_at, is_read) VALUES (:conversationId, :senderId, :content, NOW(), 0)');
        $stmt->execute([
            'conversationId' => $conversationId,
            'senderId' => $senderId,
            'content' => $content
        ]);

        $messageId = $this->db->lastInsertId();
        return $this->getMessageById($messageId);
    }

    /**
     * Récupère un message par son ID
     * @param int $id ID du message
     * @return Message|null Le message trouvé ou null
     */
    public function getMessageById(int $id): ?Message
    {
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Message($data);
        }
        return null;
    }

    /**
     * Compte le nombre total de messages non lus pour un utilisateur
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de messages non lus
     */
    public function countUnreadMessages(int $userId): int
    {
        $sql = "SELECT COUNT(*) as unread_count
            FROM messages m
            JOIN conversations c ON m.conversation_id = c.id
            WHERE m.is_read = 0
            AND m.sender_id != ?
            AND (c.user1_id = ? OR c.user2_id = ?)";

        $stmt = $this->db->prepare($sql);
        // On passe 3 fois $userId : pour sender_id, user1_id et user2_id
        $stmt->execute([$userId, $userId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['unread_count'] ?? 0);
    }

    /**
     * Marque les messages d'une conversation comme lus
     * @param int $conversationId ID de la conversation
     * @param int $userId ID de l'utilisateur
     * @return bool Succès de l'opération
     */
    public function markAsRead(int $conversationId, int $userId): bool
    {
        $sql = "UPDATE messages
            SET is_read = 1
            WHERE conversation_id = :conversationId
            AND sender_id != :userId";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'conversationId' => $conversationId,
            'userId' => $userId
        ]);
    }

    /**
     * Supprime une conversation
     * @param int $conversationId ID de la conversation
     * @return bool Succès de l'opération
     */
    public function deleteConversation(int $conversationId): bool
    {
        // D'abord supprimer les messages
        $stmt = $this->db->prepare('DELETE FROM messages WHERE conversation_id = :conversationId');
        $stmt->execute(['conversationId' => $conversationId]);

        // Puis supprimer la conversation
        $stmt = $this->db->prepare('DELETE FROM conversations WHERE id = :id');
        return $stmt->execute(['id' => $conversationId]);
    }

    /**
     * Récupère une conversation par son ID
     * @param int $id ID de la conversation
     * @return Conversation|null La conversation trouvée ou null
     */
    public function getConversationById(int $id): ?Conversation
    {
        $stmt = $this->db->prepare('SELECT * FROM conversations WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            return new Conversation($data);
        }
        return null;
    }
}
