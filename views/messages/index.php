<?php require_once __DIR__ . '/../../includes/header.php'; 
/** @var array $conversations */
/** @var array $messages */
/** @var string $otherUserPseudo */
/** @var string $otherUserAvatar */
/** @var int $otherUserId */
/** @var int $selectedConversationId */
?>

<!-- Overlay pour fermer la sidebar -->
<div class="sidebar-overlay"></div>

<!-- Bouton menu mobile -->
<button class="mobile-menu-btn" aria-label="Ouvrir les conversations">
    <i class="fas fa-comments"></i>
</button>

<main class="messagerie-page-wrapper">
    <div class="messagerie-container">

        <div class="messagerie-sidebar">
            <h1 class="messagerie-title">Messagerie</h1>

            <div class="conversation-list">
                <?php foreach ($conversations as $conversation): ?>
                    <?php if (empty($conversation['last_message']) && empty($conversation['id'])): ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>?controller=message&action=conversation&id=<?= $conversation['id'] ?>"
                        class="conversation-item <?= ($selectedConversationId == $conversation['id']) ? 'active' : '' ?>">

                        <div class="conv-avatar">
                            <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($conversation['other_avatar'] ?? 'Avatar_default.png') ?>" alt="Avatar">
                        </div>

                        <div class="conv-info">
                            <div class="conv-header">
                                <span class="conv-pseudo"><?= htmlspecialchars($conversation['other_pseudo'] ?? 'Utilisateur') ?></span>
                                <?php if (isset($conversation['last_message_read']) && $conversation['last_message_read'] == 0 && isset($conversation['last_message_sender_id']) && $conversation['last_message_sender_id'] != $_SESSION['user_id']): ?>
                                    <span class="unread-dot"></span>
                                <?php endif; ?>
                                <span class="conv-time"><?= date('H.i', strtotime($conversation['last_message_date'] ?? 'now')) ?> </span>
                            </div>
                            <p class="conv-preview"><?= htmlspecialchars($conversation['last_message'] ?? 'Nouvelle conversation') ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>

                <?php if (empty($conversations)): ?>
                    <p class="empty-msg">Aucune conversation pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>

        <section class="messagerie-main">
            <!-- Bouton Retour Mobile -->
            <button class="chat-back-btn" aria-label="Retour">
                <i class="fas fa-arrow-left"></i>
            </button>
            
            <?php if (isset($selectedConversationId) && $selectedConversationId && !empty($conversations)): ?>

                <div class="chat-header">
                    <div class="chat-header-avatar">
                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($otherUserAvatar ?? 'Avatar_default.png') ?>" alt="Avatar">
                    </div>
                    <h2 class="chat-header-pseudo"><?= htmlspecialchars($otherUserPseudo ?? 'Utilisateur') ?></h2>
                </div>

                <div class="chat-messages-area">
                    <?php if (empty($messages)): ?>
                        <p class="mess-int">Dites bonjour !</p>
                    <?php else: ?>
                        <?php 
                        $prevIsMe = null;
                        foreach ($messages as $index => $msg): 
                            $isMe = ($msg->getSenderId() == $_SESSION['user_id']);
                            $classMessage = $isMe ? 'msg-sent' : 'msg-received';
                            
                            // Ajouter classe pour gérer l'avatar
                            $avatarClass = '';
                            if (!$isMe && $index > 0) {
                                $prevMsg = $messages[$index - 1];
                                $prevIsMe = ($prevMsg->getSenderId() == $_SESSION['user_id']);
                                if (!$prevIsMe) {
                                    $classMessage .= ' has-prev-received';
                                }
                            }
                        ?>
                            <div class="message-row <?= $classMessage ?>">

                                <?php if (!$isMe): ?>
                                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($otherUserAvatar ?? 'Avatar_default.png') ?>" alt="Avatar" class="msg-avatar-small">
                                <?php endif; ?>

                                <div class="msg-content-wrapper">
                                    <div class="msg-meta">
                                        <span class="msg-date"><?= $msg->getOnlyDate() ?></span>
                                        <span class="msg-time"><?= $msg->getOnlyTime() ?></span>
                                    </div>
                                    <div class="msg-bubble">
                                        <p><?= htmlspecialchars($msg->getContent()); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="chat-input-wrapper">
                    <form action="<?= BASE_URL ?>?controller=message&action=send" method="POST" class="chat-form">
                        <input type="hidden" name="receiver_id" value="<?= $otherUserId ?? '' ?>">
                        <label for="chat-input" class="visually-hidden">Tapez votre message ici</label>
                        <input type="text" name="content" id="chat-input" class="chat-input-field" placeholder="Tapez votre message ici" required>
                        <button type="submit" class="btn-send-chat">Envoyer</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="chat-empty">
                    <p>Sélectionnez une conversation ou commencez-en une nouvelle.</p>
                </div>
            <?php endif; ?>

        </section>

    </div>
</main>

<!-- JavaScript pour la messagerie mobile -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.messagerie-sidebar');
    const main = document.querySelector('.messagerie-main');
    const overlay = document.querySelector('.sidebar-overlay');
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const backBtn = document.querySelector('.chat-back-btn');

    // Ouvrir la sidebar
    if (menuBtn && sidebar) {
        menuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
    }

    // Fermer la sidebar
    if (backBtn && sidebar) {
        backBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    if (overlay && sidebar) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // Fermer sidebar quand on sélectionne une conversation
    const convItems = document.querySelectorAll('.conversation-item');
    convItems.forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 767) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });

    // Activer le main chat quand une conversation est sélectionnée
    if (main && window.innerWidth <= 767) {
        main.classList.add('active');
    }
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
