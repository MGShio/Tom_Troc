<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var Book[] $books */ ?>

<main class="my-account-wrapper">
    <div class="profile-container">

        <div class="profile-header-flex">
            <h1 class="profile-main-title">Mon compte</h1>
            <a href="<?= BASE_URL ?>?controller=book&action=create" class="btn-add-book">Ajouter un livre</a>
        </div>

        <div class="profile-top-section">
            <div class="profile-columns-flex">

                <div class="profile-col-left">
                    <div class="profile-avatar-frame">
                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($user->getAvatar() ?? 'Avatar_default.png') ?>" alt="Avatar de profil" id="avatar-preview">
                    </div>

                    <h2 class="profile-pseudo"><?= htmlspecialchars($user->getPseudo()) ?></h2>
                    <p class="profile-member-date">Membre depuis <?= Utils::format($user->getCreatedAt()) ?></p>

                    <div class="profile-library-stats">
                        <span class="library-label">BIBLIOTHÈQUE</span>
                        <div class="library-count-flex">
                            <img src="<?= BASE_URL ?>assets/images/livres.svg" alt="Icon livre" class="icon-book">
                            <span class="library-count-text"><?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?></span>
                        </div>
                    </div>
                </div>

                <div class="profile-col-right">
                    <div class="profile-actions">
                        <a href="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Modifier mon profil
                        </a>
                        <a href="<?= BASE_URL ?>?controller=user&action=deleteAccount&id=<?= $user->getId() ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible !')">
                            <i class="fas fa-trash"></i> Supprimer mon compte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-table-wrapper">
            <table class="my-books-table">
                <thead>
                    <tr>
                        <th>PHOTO</th>
                        <th>TITRE</th>
                        <th>AUTEUR</th>
                        <th>DESCRIPTION</th>
                        <th>DISPONIBILITE</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($books)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Votre bibliothèque est vide.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($books as $book): ?>
                            <tr>
                                <td>
                                    <div class="table-img-frame">
                                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage() ?: 'Book_default.png') ?>" alt="Cover">
                                    </div>
                                </td>
                                <td class="table-text-bold"><?= htmlspecialchars($book->getTitle()) ?></td>
                                <td class="table-text-light"><?= htmlspecialchars($book->getAuthor()) ?></td>
                                <td class="table-text-desc">
                                    <div class="text-truncate-wrapper">
                                        <?= htmlspecialchars($book->getDescription() ?? '') ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($book->getDisponibilite() === 'non disponible' || $book->getStatut() === 'non disponible'): ?>
                                        <span class="badge-not-avalaible">non dispo.</span>
                                    <?php else: ?>
                                        <span class="badge-disponible">disponible</span>
                                    <?php endif; ?>
                                </td>
                                <td class="table-actions">
                                    <a href="<?= BASE_URL ?>?controller=book&action=edit&id=<?= $book->getId() ?>" class="action-edit">
                                        <i class="fas fa-edit"></i> Éditer
                                    </a>
                                    <a href="<?= BASE_URL ?>?controller=book&action=delete&id=<?= $book->getId() ?>" 
                                       class="action-delete" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
