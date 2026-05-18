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
                WHERE c.user1_id = ? OR c.user2_id = ?
                ORDER BY last_message_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les messages d'une conversation
     * @param int $conversationId ID de la conversation
     * @return Message[] Liste des messages
     */
    public function getMessagesByConversationId(int $conversationId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC');
        $stmt->execute([$conversationId]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
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
                     WHERE (user1_id = ? AND user2_id = ?)
                        OR (user1_id = ? AND user2_id = ?)";

        $stmt = $this->db->prepare($sqlCheck);
        $stmt->execute([$senderId, $receiverId, $receiverId, $senderId]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Conversation');
        $existing = $stmt->fetch();

        if ($existing) {
            return (int)$existing->id;
        }

        // Sinon, créer une nouvelle conversation
        $sqlInsert = "INSERT INTO conversations (user1_id, user2_id, created_at) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sqlInsert);
        $stmt->execute([$senderId, $receiverId]);

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
        $stmt = $this->db->prepare(
            'INSERT INTO messages (conversation_id, sender_id, content, created_at, is_read)
            VALUES (?, ?, ?, NOW(), 0)'
        );
        $stmt->execute([$conversationId, $senderId, $content]);

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
        $stmt = $this->db->prepare('SELECT * FROM messages WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetch();
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
                WHERE conversation_id = ?
                AND sender_id != ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$conversationId, $userId]);
    }

    /**
     * Supprime une conversation
     * @param int $conversationId ID de la conversation
     * @return bool Succès de l'opération
     */
    public function deleteConversation(int $conversationId): bool
    {
        // D'abord supprimer les messages
        $stmt = $this->db->prepare('DELETE FROM messages WHERE conversation_id = ?');
        $stmt->execute([$conversationId]);

        // Puis supprimer la conversation
        $stmt = $this->db->prepare('DELETE FROM conversations WHERE id = ?');
        return $stmt->execute([$conversationId]);
    }

    /**
     * Récupère une conversation par son ID
     * @param int $id ID de la conversation
     * @return Conversation|null La conversation trouvée ou null
     */
    public function getConversationById(int $id): ?Conversation
    {
        $stmt = $this->db->prepare('SELECT * FROM conversations WHERE id = ?');
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Conversation');
        return $stmt->fetch();
    }
}
