<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var Book[] $books */ ?>
<section class="section">
    <?php if ($user->getId() == $_SESSION['user_id']): ?>
        <a href="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>" class="btn">Modifier mon profil</a>
        <a href="<?= BASE_URL ?>?controller=book&action=create" class="btn">Ajouter un livre</a>
    <?php endif; ?>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user->getName()) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
</section>

<section class="section">
    <h2>Bibliothèque de <?= htmlspecialchars($user->getName()) ?></h2>
    <?php if (empty($books)): ?>
        <p>Cet utilisateur n'a pas encore de livres.</p>
    <?php else: ?>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <?php if (!empty($book->getImage())): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                <?php else: ?>
                    <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                        Pas d'image
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($book->getTitle()) ?></h3>
                <p>par <?= htmlspecialchars($book->getAuthor()) ?></p>
                <p>Statut : <?= htmlspecialchars($book->getStatut()) ?></p>
                <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="btn">Voir</a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>