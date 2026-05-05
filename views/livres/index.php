<?php 
/** @var Livre[] $livres */
require_once __DIR__ . '/../../includes/header.php'; 
?>
<section class="section">
    <h2>Nos livres à l'échange</h2>
    <div class="books-grid">
        <?php foreach ($livres as $livre): ?>
            <div class="book-card">
                <?php if ($livre->getImage()): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre->getImage()) ?>" alt="<?= htmlspecialchars($livre->getTitre()) ?>">
                <?php else: ?>
                    <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                        Pas d'image
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($livre->getTitre()) ?></h3>
                <p>par <?= htmlspecialchars($livre->getAuteur()) ?></p>
                <a href="<?= BASE_URL ?>?controller=livre&action=show&id=<?= $livre->getId() ?>" class="btn">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; 
?>