<?php 
require_once __DIR__ . '/../../includes/header.php';
if (!isset($livre)) {
    header("HTTP/1.0 404 Not Found");
    echo '<p>Livre non trouvé.</p>';
    require_once __DIR__ . '/../../includes/footer.php';
    exit;
}
?>
<section class="section">
    <div style="display: flex; gap: 2rem; align-items: flex-start;">
        <div style="flex: 1;">
            <?php if ($livre->getImage()): ?>
                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre->getImage()) ?>" alt="<?= htmlspecialchars($livre->getTitre()) ?>" style="max-width: 300px;">
            <?php else: ?>
                <div style="width: 300px; height: 400px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                    Pas d'image
                </div>
            <?php endif; ?>
        </div>
        <div style="flex: 2;">
            <h2><?= htmlspecialchars($livre->getTitre()) ?></h2>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($livre->getAuteur()) ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($livre->getStatut()) ?></p>
            <p><strong>Description :</strong></p>
            <p><?= nl2br(htmlspecialchars($livre->getDescription())) ?></p>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>