<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';

if (!is_logged_in()) {
    header('Location: ' . BASE_URL . 'pages/connexion.php');
    exit;
}

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : $_SESSION['user_id'];
$user = get_user($user_id);

if (!$user) {
    header('Location: ' . BASE_URL . 'pages/bibliotheque.php');
    exit;
}

require_once '../includes/header.php';
?>

<section class="section">
    <h2>Profil de <?= htmlspecialchars($user['nom']) ?></h2>
    <?php if ($user_id == $_SESSION['user_id']): ?>
        <a href="<?= BASE_URL ?>pages/profil.php?action=edit" class="btn">Modifier mon profil</a>
    <?php endif; ?>
    <p>Email : <?= htmlspecialchars($user['email']) ?></p>
</section>

<section class="section">
    <h2>Bibliothèque de <?= htmlspecialchars($user['nom']) ?></h2>
    <div class="books-grid">
        <?php
        $livres = get_livres_utilisateur($user_id);
        foreach ($livres as $livre) {
            echo '<div class="book-card">';
            if (!empty($livre['image'])) {
                echo '<img src="' . BASE_URL . 'assets/images/' . htmlspecialchars($livre['image']) . '" alt="' . htmlspecialchars($livre['titre']) . '">';
            } else {
                echo '<div style="width: 150px; height: 200px; background-color: #eee; display: flex; align-items: center; justify-content: center;">';
                echo 'Pas d\'image';
                echo '</div>';
            }
            echo '<h3>' . htmlspecialchars($livre['titre']) . '</h3>';
            echo '<p>par ' . htmlspecialchars($livre['auteur']) . '</p>';
            echo '<p>Statut : ' . htmlspecialchars($livre['statut']) . '</p>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php
require_once '../includes/footer.php';
?>