<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../models/Message.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../includes/database.php';

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
        $correspondants = Message::getConversationPartners($this->db, $_SESSION['user_id']);
        require __DIR__ . '/../views/messages/index.php';
    }

    public function conversation($correspondant_id)
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?controller=user&action=login');
            exit;
        }
        
        $correspondant_id = (int)$correspondant_id;
        if ($correspondant_id <= 0) {
            header('Location: ' . BASE_URL . '?controller=message&action=index');
            exit;
        }
        
        $messages = Message::getConversation($this->db, $_SESSION['user_id'], $correspondant_id);
        $correspondant = Utilisateur::getById($this->db, $correspondant_id);
        
        if (!$correspondant) {
            header("HTTP/1.0 404 Not Found");
            require __DIR__ . '/../includes/header.php';
            echo '<p>Utilisateur non trouvé.</p>';
            require __DIR__ . '/../includes/footer.php';
            exit;
        }
        
        $correspondants = Message::getConversationPartners($this->db, $_SESSION['user_id']);
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
        
        $destinataire_id = (int)($_POST['destinataire_id'] ?? 0);
        $contenu = trim($_POST['contenu'] ?? '');
        
        // Server-side validation
        $errors = [];
        
        if ($destinataire_id <= 0) {
            $errors[] = "Destinataire invalide.";
        } else {
            // Check if recipient exists
            $recipient = Utilisateur::getById($this->db, $destinataire_id);
            if (!$recipient) {
                $errors[] = "Destinataire introuvable.";
            }
        }
        
        if (empty($contenu)) {
            $errors[] = "Le message ne peut pas être vide.";
        } elseif (strlen($contenu) > 1000) {
            $errors[] = "Le message ne peut pas dépasser 1000 caractères.";
        }
        
        if (empty($errors)) {
            $message = new Message([]);
            $message->setExpediteurId($_SESSION['user_id']);
            $message->setDestinataireId($destinataire_id);
            $message->setContenu(htmlspecialchars($contenu));
            if ($message->save($this->db)) {
                header('Location: ' . BASE_URL . '?controller=message&action=conversation&id=' . $destinataire_id);
                exit;
            } else {
                error_log("Failed to send message from user {$_SESSION['user_id']} to $destinataire_id");
                $errors[] = "Erreur lors de l'envoi du message.";
            }
        }
        
        // If errors, redirect back with error
        $_SESSION['message_error'] = implode('<br>', $errors);
        header('Location: ' . BASE_URL . '?controller=message&action=conversation&id=' . $destinataire_id);
        exit;
    }
}
