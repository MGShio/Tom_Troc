<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="messaging-container">
    <aside class="conversations-list">
        <h2>Messagerie</h2>
        <?php if (empty($correspondants)): ?>
            <p style="padding: var(--spacing-md); text-align: center;">Vous n'avez pas de messages.</p>
        <?php else: ?>
            <?php foreach ($correspondants as $correspondant): ?>
                <a href="<?= BASE_URL ?>?controller=message&action=conversation&id=<?= $correspondant->getId() ?>" class="conversation-item">
                    <div class="conversation-avatar">
                        <img src="<?= BASE_URL ?>assets/images/avatar-default.png" alt="Avatar de <?= htmlspecialchars($correspondant->getNom()) ?>">
                    </div>
                    <div class="conversation-info">
                        <h4><?= htmlspecialchars($correspondant->getNom()) ?></h4>
                        <p class="conversation-preview">Dernier message...</p>
                    </div>
                    <div class="conversation-time">15:43</div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>
    <main class="conversation-main">
        <p style="text-align: center; padding: var(--spacing-2xl); color: var(--text-gray);">Sélectionnez une conversation pour commencer</p>
    </main>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>