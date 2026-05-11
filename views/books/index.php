<?php 
/** @var Book[] $books */
require_once __DIR__ . '/../../includes/header.php'; 
?>
<section class="section">
    <div class="section-head">
        <h2>Nos livres à l'échange</h2>
        <form class="search-form" method="get" action="<?= BASE_URL ?>?controller=book&action=index">
            <input type="text" name="q" placeholder="Rechercher un livre" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </form>
    </div>
    <div class="books-grid">
        <?php if (empty($books)): ?>
            <p>Aucun livre trouvé pour cette recherche.</p>
        <?php else: ?>
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <?php if ($book->getImage()): ?>
                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                    <?php else: ?>
                        <div class="book-card-image"></div>
                    <?php endif; ?>
                    <div class="book-card-content">
                        <h3 class="book-card-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                        <p class="book-card-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
                        <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="btn btn-primary">Voir le détail</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; 
?>