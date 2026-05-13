<?php
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Section Bannière -->
<section class="banner">
    <div class="banner-content">
        <h1 class="title">Rejoignez nos lecteurs passionnés</h1>
        <p class="text">
            Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture.
            Nous croyons en la magie du partage de connaissances et d'histoires à travers les livres.
        </p>
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn">Découvrir</a>
    </div>
    <div class="banner-image">
        <img src="<?= BASE_URL ?>assets/images/hamza-nouasria-KXrvPthkmYQ-unsplash%201.png" alt="Livres anciens" class="image">
        <div class="card">
            <span class="name">Hamza</span>
        </div>
    </div>
</section>

<!-- Section Livres -->
<section class="books">
    <h2 class="section-title">Les derniers livres ajoutés</h2>
    <div class="books-list">
        <?php $books = $books ?? [];
        foreach ($books as $book): ?>
            <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>" class="book">
                <div class="book-cover">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage() ?: 'Book_default.png') ?>"
                         alt="<?= htmlspecialchars($book->getTitle()) ?>"
                         class="image">
                </div>
                <div class="book-details">
                    <h3 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h3>
                    <p class="book-author"><?= htmlspecialchars($book->getAuthor()) ?></p>
                    <p class="book-seller">Vendu par : <?= htmlspecialchars($book->getSeller() ?? 'Inconnu') ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="center-btn">
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn-outline">Voir tous les livres</a>
    </div>
</section>

<!-- Section Étapes -->
<section class="steps">
    <h2 class="section-title">Comment ça marche ?</h2>
    <p class="subtitle">Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :</p>
    <div class="steps-list">
        <div class="step">Inscrivez-vous gratuitement sur notre plateforme.</div>
        <div class="step">Ajoutez les livres que vous souhaitez échanger à votre profil.</div>
        <div class="step">Parcourez les livres disponibles chez d'autres membres.</div>
        <div class="step">Proposez un échange et discutez avec d'autres passionnés de lecture.</div>
    </div>
    <div class="center-btn">
        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn-outline">Voir tous les livres</a>
    </div>
</section>

<!-- Section Bannière valeurs -->
<section class="values">
    <div class="values-banner">
        <img src="<?= BASE_URL ?>assets/images/Mask group.png" alt="Nos valeurs">
    </div>
    <div class="values-content">
        <h2 class="title-left">Nos valeurs</h2>
        <p>
            Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. 
            Nos valeurs sont ancrées dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. 
            Nous croyons en la puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes. 
            Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé. 
            Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter, 
            de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les étagères.
        </p>
        <div class="signature-box">
            <span class="team-name">L'équipe de Tom Troc</span>
            <img src="<?= BASE_URL ?>assets/images/Vector 2.svg" alt="heart" class="heart">
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../../includes/footer.php';
?>
