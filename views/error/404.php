<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<main class="error-page">
    <div class="error-container">
        <h1 class="error-code">404</h1>
        <h2 class="error-title">Page non trouvée</h2>
        <p class="error-message">
            Désolé, la page que vous cherchez n'existe pas ou a été déplacée.
        </p>
        <div class="error-actions">
            <a href="<?= BASE_URL ?>?controller=home" class="btn">Retour à l'accueil</a>
            <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn">Voir les livres</a>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>