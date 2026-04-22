<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';
?>

    <section class="hero">
        <div class="hero-content">
            <h1>Rejoignez nos lecteurs passionnés</h1>
            <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Découvrez notre vaste sélection et partagez des connaissances et d'histoires à travers nos livres.</p>
            <a href="#" class="btn">Découvrir</a>
        </div>
        <div class="hero-image">
            <img src="<?= BASE_URL ?>assets/images/hero-image.jpg" alt="Pile de livres anciens">
        </div>
    </section>

    <section class="section">
        <h2>Les derniers livres ajoutés</h2>
        <div class="books-grid">
            <?php
            $livres = get_derniers_livres();
            foreach ($livres as $livre) {
                afficher_livre($livre);
            }
            ?>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?= BASE_URL ?>livres.php" class="btn">Voir tous les livres</a>
        </div>
    </section>

    <section class="how-it-works">
        <h2>Comment ça marche ?</h2>
        <p>Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :</p>
        <div class="steps">
            <div class="step">
                <div class="step-icon">
                    <img src="<?= BASE_URL ?>assets/images/icon1.png" alt="Inscription">
                </div>
                <h3>Inscrivez-vous gratuitement sur notre plateforme.</h3>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="<?= BASE_URL ?>assets/images/icon2.png" alt="Ajouter des livres">
                </div>
                <h3>Ajoutez les livres que vous souhaitez échanger à votre profil.</h3>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="<?= BASE_URL ?>assets/images/icon3.png" alt="Parcourir les livres">
                </div>
                <h3>Parcourez les livres disponibles et choisissez ceux qui vous intéressent.</h3>
            </div>
            <div class="step">
                <div class="step-icon">
                    <img src="<?= BASE_URL ?>assets/images/icon4.png" alt="Proposer un échange">
                </div>
                <h3>Proposez un échange et organisez la livraison des livres.</h3>
            </div>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?= BASE_URL ?>livres.php" class="btn">Voir tous les livres</a>
        </div>
    </section>

    <section class="section">
        <img src="<?= BASE_URL ?>assets/images/bookshelf.jpg" alt="Bibliothèque de livres" style="width: 100%; max-width: 1200px; margin-bottom: 2rem; border-radius: 8px;">
    </section>

    <section class="values">
        <h2>Nos valeurs</h2>
        <div class="values-content">
            <p>Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer un espace où les lecteurs peuvent se connecter et partager leurs histoires préférées. Nous croyons que le partage de livres enrichit nos vies et crée des conversations enrichissantes.</p>
            <p>Notre association a été fondée avec une conviction simple : le partage de livres peut transformer des vies. Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, de partager leurs livres préférés et de découvrir de nouveaux trésors littéraires. En échangeant des livres qui attendaient patiemment sur les étagères, nous encourageons une culture de la lecture et du partage.</p>
        </div>
        <div class="signature">Illustration Tom Troc</div>
    </section>

<?php
require_once 'includes/footer.php';
?>