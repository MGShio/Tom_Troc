<?php 
require_once __DIR__ . '/../../includes/header.php';
if (!isset($book)) {
    header("HTTP/1.0 404 Not Found");
    echo '<p>Book non trouvé.</p>';
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}
?>
<section class="section">
    <div class="detail-card book-detail-grid">
        <div class="book-detail-image">
            <?php if ($book->getImage()): ?>
                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
            <?php else: ?>
                <div class="book-card-image"></div>
            <?php endif; ?>
        </div>
        <div class="book-detail-meta">
            <h2><?= htmlspecialchars($book->getTitle()) ?></h2>
            <div class="book-status">Statut : <?= htmlspecialchars($book->getStatut()) ?></div>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($book->getAuthor()) ?></p>
            <div style="margin-top: var(--spacing-lg);">
                <h3>Description</h3>
                <p><?= nl2br(htmlspecialchars($book->getDescription())) ?></p>
            </div>
            <div class="profile-actions" style="margin-top: var(--spacing-xl);">
                <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn btn-secondary">Retour à la liste</a>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>