<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var Book[] $books */ ?>
<section class="section">
    <div class="profile-card">
        <div class="profile-summary">
            <div class="profile-details">
                <h2>Profil de <?= htmlspecialchars($user->getName()) ?></h2>
                <p><strong>Nom :</strong> <?= htmlspecialchars($user->getName()) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
            </div>
            <?php if ($user->getId() == $_SESSION['user_id']): ?>
                <div class="profile-actions">
                    <a href="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>" class="btn btn-secondary">Modifier mon profil</a>
                    <a href="<?= BASE_URL ?>?controller=book&action=create" class="btn btn-primary">Ajouter un livre</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <h2>Bibliothèque de <?= htmlspecialchars($user->getName()) ?></h2>
    <?php if (empty($books)): ?>
        <div class="card-panel">
            <p>Cet utilisateur n'a pas encore de livres.</p>
        </div>
    <?php else: ?>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <?php if (!empty($book->getImage())): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                <?php else: ?>
                    <div class="book-card-image"></div>
                <?php endif; ?>
                <div class="book-card-content">
                    <h3 class="book-card-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                    <p class="book-card-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
                    <div class="book-status"><?= htmlspecialchars($book->getStatut()) ?></div>
                    <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="btn btn-primary">Voir</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>