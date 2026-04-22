<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$livres = get_livres_disponibles();
$terme_recherche = isset($_GET['recherche']) ? $_GET['recherche'] : '';

if (!empty($terme_recherche)) {
    $livres = rechercher_livres($terme_recherche);
}

require_once '../includes/header.php';
?>

<section class="section">
    <h2>Nos livres à l'échange</h2>
    <form method="get" style="margin-bottom: 2rem;">
        <input type="text" name="recherche" placeholder="Rechercher un livre..." value="<?= htmlspecialchars($terme_recherche) ?>">
        <button type="submit" class="btn">Rechercher</button>
    </form>
    <div class="books-grid">
        <?php foreach ($livres as $livre): ?>
            <div class="book-card">
                <?php if (!empty($livre['image'])): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre['image']) ?>" alt="<?= htmlspecialchars($livre['titre']) ?>">
                <?php else: ?>
                    <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                        Pas d'image
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($livre['titre']) ?></h3>
                <p>par <?= htmlspecialchars($livre['auteur']) ?></p>
                <a href="<?= BASE_URL ?>pages/detail_livre.php?id=<?= $livre['id'] ?>" class="btn">Voir le détail</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>