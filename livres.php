<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

    <section class="section">
        <h2>Tous nos livres</h2>
        <div class="books-grid">
            <?php
            $livres = get_derniers_livres();
            foreach ($livres as $livre) {
                afficher_livre($livre);
            }
            ?>
        </div>
    </section>

<?php
require_once 'includes/footer.php';
?>