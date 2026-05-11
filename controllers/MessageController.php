<?php

class MessageController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }
        $participants = Message::getConversationParticipants($this->db, $_SESSION['user_id']);
        require __DIR__ . '/../views/messages/index.php';
    }

    public function conversation($participant_id)
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }
        
        $participant_id = (int)$participant_id;
        if ($participant_id <= 0) {
            header('Location: ' . BASE_URL . '?controller=message&action=index');
            exit;
        }
        
        $messages = Message::getConversation($this->db, $_SESSION['user_id'], $participant_id);
        $participant = User::getById($this->db, $participant_id);
        
        if (!$participant) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../includes/header.php';
            echo '<p>User non trouvé.</p>';
            require __DIR__ . '/../includes/footer.php';
            exit;
        }
        
        $participants = Message::getConversationParticipants($this->db, $_SESSION['user_id']);
        require __DIR__ . '/../views/messages/conversation.php';
    }

    public function send()
    {
        if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }
        
        // CSRF validation
        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
            $_SESSION['message_error'] = "Token CSRF invalide.";
            header('Location: ' . BASE_URL . '?controller=message&action=index');
            exit;
        }
        
        $receiver_id = (int)($_POST['receiver_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');
        
        // Server-side validation
        $errors = [];
        
        if ($receiver_id <= 0) {
            $errors[] = "Receiver invalide.";
        } else {
            // Check if recipient exists
            $recipient = User::getById($this->db, $receiver_id);
            if (!$recipient) {
                $errors[] = "Receiver introuvable.";
            }
        }
        
        if (empty($content)) {
            $errors[] = "Le message ne peut pas être vide.";
        } elseif (strlen($content) > 1000) {
            $errors[] = "Le message ne peut pas dépasser 1000 caractères.";
        }
        
        if (empty($errors)) {
            $message = new Message([]);
            $message->setSenderId($_SESSION['user_id']);
            $message->setReceiverId($receiver_id);
            $message->setContent(htmlspecialchars($content));
            if ($message->save($this->db)) {
                header('Location: ' . BASE_URL . '?controller=message&action=conversation&id=' . $receiver_id);
                exit;
            } else {
                error_log("Failed to send message from user {$_SESSION['user_id']} to $receiver_id");
                $errors[] = "Erreur lors de l'envoi du message.";
            }
        }
        
        // If errors, redirect back with error
        $_SESSION['message_error'] = implode('<br>', $errors);
        header('Location: ' . BASE_URL . '?controller=message&action=conversation&id=' . $receiver_id);
        exit;
    }
}
