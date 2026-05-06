<?php 
/** @var Book[] $books */
require_once __DIR__ . '/../../includes/header.php'; 
?>
<section class="section">
    <h2>Nos books à l'échange</h2>
    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <div class="book-card">
                <?php if ($book->getImage()): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                <?php else: ?>
                    <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                        Pas d'image
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($book->getTitle()) ?></h3>
                <p>par <?= htmlspecialchars($book->getAuthor()) ?></p>
                <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="btn">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; 
?>