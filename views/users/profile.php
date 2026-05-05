<?php require_once __DIR__ . '/../../includes/header.php'; /** @var Utilisateur $user */
/** @var Livre[] $livres */ ?>
<section class="section">
    <?php if ($user->getId() == $_SESSION['user_id']): ?>
        <a href="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>" class="btn">Modifier mon profil</a>
        <a href="<?= BASE_URL ?>?controller=livre&action=create" class="btn">Ajouter un livre</a>
    <?php endif; ?>
    <p><strong>Nom :</strong> <?= htmlspecialchars($user->getNom()) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
</section>

<section class="section">
    <h2>Bibliothèque de <?= htmlspecialchars($user->getNom()) ?></h2>
    <?php if (empty($livres)): ?>
        <p>Cet utilisateur n'a pas encore de livres.</p>
    <?php else: ?>
    <div class="books-grid">
        <?php foreach ($livres as $livre): ?>
            <div class="book-card">
                <?php if (!empty($livre->getImage())): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre->getImage()) ?>" alt="<?= htmlspecialchars($livre->getTitre()) ?>">
                <?php else: ?>
                    <div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                        Pas d'image
                    </div>
                <?php endif; ?>
                <h3><?= htmlspecialchars($livre->getTitre()) ?></h3>
                <p>par <?= htmlspecialchars($livre->getAuteur()) ?></p>
                <p>Statut : <?= htmlspecialchars($livre->getStatut()) ?></p>
                <a href="<?= BASE_URL ?>?controller=livre&action=show&id=<?= $livre->getId() ?>" class="btn">Voir</a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>