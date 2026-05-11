<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="messaging-container">
    <aside class="conversations-list">
        <h2>Messagerie</h2>
        <?php if (empty($participants)): ?>
            <div class="message-empty">Vous n'avez pas de messages.</div>
        <?php else: ?>
            <?php foreach ($participants as $participant): ?>
                <a href="<?= BASE_URL ?>?controller=message&action=conversation&id=<?= $participant->getId() ?>" class="conversation-item">
                    <div class="conversation-avatar">
                        <img src="<?= BASE_URL ?>assets/images/avatar-default.png" alt="Avatar de <?= htmlspecialchars($participant->getName()) ?>">
                    </div>
                    <div class="conversation-info">
                        <h4><?= htmlspecialchars($participant->getName()) ?></h4>
                        <p class="conversation-preview">Dernier message...</p>
                    </div>
                    <div class="conversation-time">--:--</div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>
    <main class="conversation-main">
        <div class="message-empty">Sélectionnez une conversation pour commencer.</div>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>