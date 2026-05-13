<?php require_once __DIR__ . '/../../includes/header.php'; /** @var Book $book */
/** @var User $seller */ ?>

<main class="book-page-wrapper">
    <div class="container">

        <nav class="chemin-navigation">
            <a href="<?= BASE_URL ?>?controller=book&action=index">Nos livres</a>
            <span class="separator">&gt;</span>
            <span class="current-page"><?= htmlspecialchars($book->getTitle()) ?></span>
        </nav>

        <div class="book-content-container">

            <div class="book-cover-section">
                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage() ?: 'Book_default.png') ?>"
                    alt="Couverture"
                    class="book-image-detail">
                    <?php if ($book->getDisponibilite() === 'non disponible' || $book->getStatut() === 'non disponible') : ?>
                        <span class="badge-non-disponible-detail">non disponible</span>
                    <?php else : ?>
                        <span class="badge-disponible-detail">disponible</span>
                    <?php endif; ?>
            </div>

            <div class="book-info-section">

                <h1 class="book-main-title"><?= htmlspecialchars($book->getTitle()) ?></h1>
                <p class="book-main-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>

                <hr class="detail-separator">

                <div class="description-bloc">
                    <h3 class="section-main-title">Description</h3>
                    <p class="book-main-description">
                        <?= nl2br(htmlspecialchars($book->getDescription())) ?>
                    </p>
                </div>

                <div class="owner-bloc">
                    <h3 class="section-owner">PROPRIÉTAIRE</h3>

                    <div class="owner-card">
                        <div class="avatar-wrapper">
                            <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($seller->getAvatar() ?: 'Avatar_default.png') ?>" alt="Avatar" class="owner-avatar-img">
                        </div>

                        <div class="owner-name-container">
                            <a href="<?= BASE_URL ?>?controller=user&action=public_profile&id=<?= htmlspecialchars($book->getUserId()) ?>">
                                <?= htmlspecialchars($seller->getPseudo() ?? $seller->getName()) ?>
                            </a>
                        </div>
                    </div>

                    <div class="action-wrapper">
                        <?php if (Utils::isUserConnected()): ?>
                            <?php if ($_SESSION['user_id'] != $book->getUserId()): ?>
                                <a href="<?= BASE_URL ?>?controller=message&action=create_chat_with&id=<?= htmlspecialchars($book->getUserId()) ?>" class="btn-send-message">
                                    Envoyer un message
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>?controller=user&action=login" class="btn-send-message">
                                Envoyer un message
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
