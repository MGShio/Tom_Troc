<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$livre_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$livre = get_livre($livre_id);

if (!$livre) {
    header('Location: ' . BASE_URL . 'pages/livres.php');
    exit;
}

require_once '../includes/header.php';
?>

<section class="section">
    <div style="display: flex; gap: 2rem; align-items: flex-start;">
        <div style="flex: 1;">
            <?php if (!empty($livre['image'])): ?>
                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($livre['image']) ?>" alt="<?= htmlspecialchars($livre['titre']) ?>" style="max-width: 300px;">
            <?php else: ?>
                <div style="width: 300px; height: 400px; background-color: #eee; display: flex; align-items: center; justify-content: center;">
                    Pas d'image
                </div>
            <?php endif; ?>
        </div>
        <div style="flex: 2;">
            <h2><?= htmlspecialchars($livre['titre']) ?></h2>
            <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($livre['statut']) ?></p>
            <p><strong>Description :</strong></p>
            <p><?= nl2br(htmlspecialchars($livre['description'])) ?></p>
            <p><strong>Propriétaire :</strong> <a href="<?= BASE_URL ?>pages/profil.php?id=<?= $livre['utilisateur_id'] ?>"><?= htmlspecialchars($livre['nom']) ?></a></p>
            <?php if (is_logged_in() && $_SESSION['user_id'] != $livre['utilisateur_id']): ?>
                <a href="<?= BASE_URL ?>pages/messagerie.php?action=nouveau&destinataire=<?= $livre['utilisateur_id'] ?>&livre=<?= $livre['id'] ?>" class="btn">Contacter le propriétaire</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>