<?php 
require_once __DIR__ . '/../../includes/header.php';
/** @var Utilisateur $correspondant */
/** @var Message[] $messages */
/** @var Utilisateur[] $correspondants */ 
?>
<div class="messaging-container">
    <aside class="conversations-list">
        <h2>Messagerie</h2>
        <?php if (!empty($correspondants)): ?>
            <?php foreach ($correspondants as $conv): ?>
                <a href="<?= BASE_URL ?>?controller=message&action=conversation&id=<?= $conv->getId() ?>" class="conversation-item <?= $conv->getId() == $correspondant->getId() ? 'active' : '' ?>">
                    <div class="conversation-avatar">
                        <img src="<?= BASE_URL ?>assets/images/avatar-default.png" alt="Avatar de <?= htmlspecialchars($conv->getNom()) ?>">
                    </div>
                    <div class="conversation-info">
                        <h4><?= htmlspecialchars($conv->getNom()) ?></h4>
                        <p class="conversation-preview">Dernier message...</p>
                    </div>
                    <div class="conversation-time">15:43</div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>
    <main class="conversation-main">
        <div class="conversation-header">
            <h2><?= htmlspecialchars($correspondant->getNom()) ?></h2>
        </div>
        
        <?php if (isset($_SESSION['message_error'])): ?>
            <p style="color: red; padding: var(--spacing-md);"><?= $_SESSION['message_error'] ?></p>
            <?php unset($_SESSION['message_error']); ?>
        <?php endif; ?>
        
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="message <?= $message->getExpediteurId() == $_SESSION['user_id'] ? 'sent' : 'received' ?>">
                    <p><?= htmlspecialchars($message->getContenu()) ?></p>
                    <small><?= htmlspecialchars($message->getDateEnvoi()) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        
        <form method="post" action="<?= BASE_URL ?>?controller=message&action=send" class="message-form">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="destinataire_id" value="<?= $correspondant->getId() ?>">
            <textarea name="contenu" placeholder="Tapez votre message ici" required></textarea>
            <button type="submit" class="btn">Envoyer</button>
        </form>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>