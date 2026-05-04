<?php
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
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $correspondants = Message::getConversationPartners($this->db, $_SESSION['user_id']);
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../views/messages/index.php';
        require __DIR__ . '/../includes/footer.php';
    }

    public function conversation($correspondant_id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ?controller=user&action=login');
            exit;
        }
        $messages = Message::getConversation($this->db, $_SESSION['user_id'], $correspondant_id);
        $correspondant = Utilisateur::getById($this->db, $correspondant_id);
        require __DIR__ . '/../includes/header.php';
        require __DIR__ . '/../views/messages/conversation.php';
        require __DIR__ . '/../includes/footer.php';
    }

    public function send()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?controller=user&action=login');
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
                header('Location: ?controller=message&action=conversation&id=' . $destinataire_id);
                exit;
            } else {
                $errors[] = "Erreur lors de l'envoi du message.";
            }
        }
        
        // If errors, redirect back with error (or show in view)
        $_SESSION['message_error'] = implode('<br>', $errors);
        header('Location: ?controller=message&action=conversation&id=' . $destinataire_id);
        exit;
    }
}