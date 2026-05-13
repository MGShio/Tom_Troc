<?php require_once __DIR__ . '/../../includes/header.php'; /** @var Book[] $books */ ?>

<main class="books-page">
    <div class="container">
        <header class="books-header">
            <h1 class="page-title">Nos livres à l'échange</h1>
            <div class="search-bar">
                <form action="<?= BASE_URL ?>?controller=book&action=index" method="GET">
                    <div class="search-section">
                        <label for="search-input" class="visually-hidden">Rechercher un livre</label>
                        <input type="text" name="search" id="search-input" placeholder="Rechercher un livre" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </header>

        <section class="books-grid">
            <?php if (isset($books) && !empty($books)) : ?>
                <?php foreach ($books as $book) : ?>
                    <article class="book-card">
                        <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= htmlspecialchars($book->getId())  ?>">
                            <div class="book-image">
                                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage() ?: 'Book_default.png') ?>" alt="<?= htmlspecialchars($book->getTitle()) ?>">
                                <?php if ($book->getDisponibilite() === 'non disponible' || $book->getStatut() === 'non disponible') : ?>
                                    <span class="badge-non-disponible">non dispo.</span>
                                <?php endif; ?>
                            </div>
                            <div class="book-info">
                                <h2 class="book-title"><?= htmlspecialchars($book->getTitle()) ?></h2>
                                <p class="book-author">par <?= htmlspecialchars($book->getAuthor()) ?></p>
                                <p class="book-seller">Vendu par : <?= htmlspecialchars($book->getSeller() ?? 'Inconnu') ?></p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="no-results">
                    <?php if (!empty($_GET['search'])) : ?>
                        <p>Désolé, aucun livre ne correspond à votre recherche "<strong><?= htmlspecialchars($_GET['search']) ?></strong>".</p>
                        <a href="<?= BASE_URL ?>?controller=book&action=index" class="btn-back">Voir tous les livres</a>
                    <?php else : ?>
                        <p>Aucun livre disponible pour le moment.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
