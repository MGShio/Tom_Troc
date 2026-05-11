<?php 
require_once __DIR__ . '/../../includes/header.php';
/** @var User $participant */
/** @var Message[] $messages */
/** @var User[] $participants */ 
?>
<div class="messaging-container">
    <aside class="conversations-list">
        <h2>Messagerie</h2>
        <?php if (!empty($participants)): ?>
            <?php foreach ($participants as $conv): ?>
                <a href="<?= BASE_URL ?>?controller=message&action=conversation&id=<?= $conv->getId() ?>" class="conversation-item <?= $conv->getId() == $participant->getId() ? 'active' : '' ?>">
                    <div class="conversation-avatar">
                        <img src="<?= BASE_URL ?>assets/images/avatar-default.png" alt="Avatar de <?= htmlspecialchars($conv->getName()) ?>">
                    </div>
                    <div class="conversation-info">
                        <h4><?= htmlspecialchars($conv->getName()) ?></h4>
                        <p class="conversation-preview">Dernier message...</p>
                    </div>
                    <div class="conversation-time">--:--</div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>
    <main class="conversation-main">
        <div class="conversation-header">
            <h2><?= htmlspecialchars($participant->getName()) ?></h2>
        </div>
        
        <?php if (isset($_SESSION['message_error'])): ?>
            <div class="form-error"><?= $_SESSION['message_error'] ?></div>
            <?php unset($_SESSION['message_error']); ?>
        <?php endif; ?>
        
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message->getSenderId() == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
                    <p><?= htmlspecialchars($message->getContent()) ?></p>
                    <small><?= htmlspecialchars($message->getDateEnvoi()) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form method="post" action="<?= BASE_URL ?>?controller=message&action=send" class="message-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="receiver_id" value="<?= $participant->getId() ?>">
            <textarea name="content" placeholder="Tapez votre message ici" required></textarea>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>