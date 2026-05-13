<?php
/**
 * MessageController - Gestion de la messagerie
 */
class MessageController
{
    private $db;

    /**
     * Constructeur
     * @param PDO $db Connexion à la base de données
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Affiche la page de messagerie avec les conversations
     */
    public function index()
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }

        $userId = $_SESSION['user_id'];
        $messageManager = new MessageManager($this->db);
        $userManager = new UserManager($this->db);
        $bookManager = new BookManager($this->db);

        // Gestion de la création automatique de conversation
        if (isset($_GET['create_chat_with'])) {
            $targetId = (int)$_GET['create_chat_with'];
            if ($targetId !== $userId) {
                $conversationId = $messageManager->createConversation($userId, $targetId);
                Utils::redirect(BASE_URL . '?controller=message&action=conversation&id=' . $conversationId);
            }
        }

        // Récupérer les conversations
        $conversations = $messageManager->getMyConversations($userId);
        
        // Enrichir les conversations avec pseudo et avatar
        foreach ($conversations as $key => $conv) {
            // Qui est l'autre personne ?
            $otherId = ($conv['user1_id'] == $userId) ? $conv['user2_id'] : $conv['user1_id'];
            
            // Récupérer ses infos
            $otherUser = $userManager->getUserById($otherId);
            
            if ($otherUser) {
                $conversations[$key]['other_pseudo'] = $otherUser->getPseudo();
                $conversations[$key]['other_avatar'] = $otherUser->getAvatar();
                $conversations[$key]['other_user_id'] = $otherId;
            } else {
                $conversations[$key]['other_pseudo'] = "Utilisateur supprimé";
                $conversations[$key]['other_avatar'] = "Avatar_default.png";
                $conversations[$key]['other_user_id'] = 0;
            }
        }

        // Gestion de la conversation sélectionnée
        $selectedConversationId = null;
        $messages = [];
        $otherUserPseudo = "";
        $otherUserAvatar = "";
        $otherUserId = null;

        if (isset($_GET['id'])) {
            $selectedConversationId = (int)$_GET['id'];
        } elseif (!empty($conversations)) {
            $selectedConversationId = (int)$conversations[0]['id'];
        }

        if ($selectedConversationId) {
            $messages = $messageManager->getMessagesByConversationId($selectedConversationId);
            
            // Récupérer les infos de l'interlocuteur
            foreach ($conversations as $conv) {
                if ($conv['id'] == $selectedConversationId) {
                    $otherUserPseudo = $conv['other_pseudo'];
                    $otherUserAvatar = $conv['other_avatar'];
                    $otherUserId = $conv['other_user_id'];
                    break;
                }
            }
            
            // Marquer les messages comme lus
            $messageManager->markAsRead($selectedConversationId, $userId);
        }

        // Compter les messages non lus
        $_SESSION['unread_count'] = $messageManager->countUnreadMessages($userId);

        require __DIR__ . '/../views/messages/index.php';
    }

    /**
     * Affiche une conversation spécifique
     * @param int $id ID de la conversation
     */
    public function conversation($id)
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }

        $userId = $_SESSION['user_id'];
        $messageManager = new MessageManager($this->db);
        $userManager = new UserManager($this->db);

        // Vérifier que l'utilisateur fait partie de cette conversation
        $conversation = $messageManager->getConversationById($id);
        if (!$conversation) {
            Utils::redirect(BASE_URL . '?controller=message&action=index');
        }

        $otherId = $conversation->getOtherUserId($userId);
        if ($otherId === null) {
            Utils::redirect(BASE_URL . '?controller=message&action=index');
        }

        $otherUser = $userManager->getUserById($otherId);
        if (!$otherUser) {
            Utils::redirect(BASE_URL . '?controller=message&action=index');
        }

        // Récupérer les conversations pour la sidebar
        $conversations = $messageManager->getMyConversations($userId);
        foreach ($conversations as $key => $conv) {
            $otherConvId = ($conv['user1_id'] == $userId) ? $conv['user2_id'] : $conv['user1_id'];
            $otherConvUser = $userManager->getUserById($otherConvId);
            if ($otherConvUser) {
                $conversations[$key]['other_pseudo'] = $otherConvUser->getPseudo();
                $conversations[$key]['other_avatar'] = $otherConvUser->getAvatar();
            }
        }

        // Récupérer les messages
        $messages = $messageManager->getMessagesByConversationId($id);
        
        // Marquer comme lu
        $messageManager->markAsRead($id, $userId);

        // Compter les messages non lus
        $_SESSION['unread_count'] = $messageManager->countUnreadMessages($userId);

        // Fix: Définir selectedConversationId pour la vue
        $selectedConversationId = $id;

        // Récupérer le pseudo et avatar de l'autre utilisateur
        $otherUserPseudo = $otherUser->getPseudo();
        $otherUserAvatar = $otherUser->getAvatar();
        $otherUserId = $otherId;

        require __DIR__ . '/../views/messages/index.php';
    }

    /**
     * Envoie un message
     */
    public function send()
    {
        if (!Utils::isUserConnected()) {
            Utils::redirect(BASE_URL . '?controller=user&action=login');
        }

        $userId = $_SESSION['user_id'];
        $content = trim($_POST['content'] ?? '');
        $receiverId = (int)($_POST['receiver_id'] ?? 0);

        if (empty($content) || $receiverId <= 0) {
            Utils::redirect(BASE_URL . '?controller=message&action=index');
        }

        $messageManager = new MessageManager($this->db);
        
        // Créer ou récupérer la conversation
        $conversationId = $messageManager->createConversation($userId, $receiverId);
        
        // Envoyer le message
        $messageManager->postMessage($conversationId, $userId, $content);
        
        // Marquer comme lu pour l'expéditeur
        $messageManager->markAsRead($conversationId, $userId);
        
        Utils::redirect(BASE_URL . '?controller=message&action=conversation&id=' . $conversationId);
    }
}
