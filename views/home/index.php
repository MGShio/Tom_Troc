<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Section Hero -->
<section class="hero">
    <div class="hero-content">
        <p class="eyebrow">Tom Troc</p>
        <h1>Rejoignez nos lecteurs passionnés</h1>
        <p>
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
            Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn btn-primary">Découvrir</a>
    </div>
    <div class="hero-image">
        <img src="<?= BASE_URL ?>assets/images/hamza-nouasria-KXrvPthkmYQ-unsplash%201@2x.png" alt="Livres anciens empilés">
    </div>
</section>

<!-- Section "Les derniers livres ajoutés" -->
<section class="section section-white">
    <div class="section-head">
        <h2>Les derniers livres ajoutés</h2>
    </div>
    <div class="books-grid">
        <?php $books = $books ?? []; foreach ($books as $book): ?>
            <article class="book-card">
                <?php if (!empty($book->getImage())): ?>
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                <?php else: ?>
                    <div class="book-card-image"></div>
                <?php endif; ?>
                <div class="book-card-content">
                    <h3 class="book-card-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                    <p class="book-card-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
                    <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="btn btn-outline">Voir le détail</a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="section-center">
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn btn-primary">Voir tous les livres</a>
    </div>
</section>

<!-- Section "Comment ça marche ?" -->
<section class="how-it-works section section-background">
    <div class="section-head">
        <h2>Comment ça marche ?</h2>
    </div>
    <p class="section-subtitle">
        Échanger des livres avec Tom Troc, c'est simple et amusant ! Suivez ces étapes pour commencer :
    </p>
    <div class="info-cards">
        <a href="<?= BASE_URL ?>?controller=user&action=register" class="info-card">
            <h3>Inscrivez-vous gratuitement</h3>
            <p>Créez votre compte et rejoignez la communauté Tom Troc en quelques clics.</p>
        </a>
        <a href="<?= isset($_SESSION['user_id']) ? BASE_URL . '?controller=book&action=create' : BASE_URL . '?controller=user&action=login' ?>" class="info-card">
            <h3>Ajoutez vos livres</h3>
            <p>Décrivez les livres que vous souhaitez échanger pour les rendre visibles aux autres lecteurs.</p>
        </a>
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="info-card">
            <h3>Parcourez la bibliothèque</h3>
            <p>Explorez les livres disponibles chez d'autres membres et trouvez de nouvelles lectures.</p>
        </a>
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="info-card">
            <h3>Proposez un échange</h3>
            <p>Contactez facilement un lecteur et organisez l'échange de votre prochain livre préféré.</p>
        </a>
    </div>
    <div class="section-center">
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn btn-outline">Voir tous les livres</a>
    </div>
</section>

<!-- Section image de bibliothèque -->
<section class="feature-image-section">
    <div class="feature-image">
        <img src="<?= BASE_URL ?>assets/images/Mask%20group.png" alt="Bibliothèque de livres">
    </div>
</section>

<!-- Section "Nos valeurs" -->
<section class="values section section-white">
    <div class="section-head">
        <h2>Nos valeurs</h2>
    </div>
    <div class="values-content">
        <p>
            Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté.
            Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer un espace où les lecteurs
            peuvent se connecter et partager leurs livres préférés. Nous croyons que le partage de livres enrichit nos vies
            et crée des conversations enrichissantes.
        </p>
        <p>
            Notre association a été fondée avec une conviction simple : le partage de livres peut transformer des vies.
            Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter,
            de partager leurs livres préférés et de découvrir de nouveaux trésors littéraires.
        </p>
    </div>
    <div class="signature">L'équipe Tom Troc</div>
</section>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>