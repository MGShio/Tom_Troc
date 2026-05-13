<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var Book[] $books */ ?>

<main class="profile-page-wrapper">
    <div class="container-profile-main-flex">

        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($user->getAvatar()) ?>"
                        alt="Avatar de <?= htmlspecialchars($user->getPseudo()) ?>">
                </div>

                <img src="<?= BASE_URL ?>assets/images/line3.png" alt="ligne separatrice" class="profile-separator-img">

                <h1 class="profile-name"><?= htmlspecialchars($user->getPseudo()) ?></h1>
                <p class="profile-member-since">Membre depuis <?= Utils::format($user->getCreatedAt()) ?></p>

                <div class="profile-library-info">
                    <p class="library-label">BIBLIOTHÈQUE</p>
                    <div class="library-count-box">
                        <img src="<?= BASE_URL ?>assets/images/livres.svg" alt="icon" class="livre-library">
                        <span class="library-count">
                            <?= count($books) ?> livre<?= count($books) > 1 ? 's' : '' ?>
                        </span>
                    </div>
                </div>

                <div class="profile-action">
                    <?php if (Utils::isUserConnected()): ?>
                        <?php if ($_SESSION['user_id'] != $user->getId()): ?>
                            <a href="<?= BASE_URL ?>?controller=message&action=index&create_chat_with=<?= htmlspecialchars($user->getId()) ?>" class="btn-write-message">
                                Écrire un message
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>?controller=user&action=login" class="btn-write-message">
                            Écrire un message
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="table-round-container">
                <table class="books-table">
                    <thead>
                        <tr>
                            <th class="col-photo">PHOTO</th>
                            <th class="col-title">TITRE</th>
                            <th class="col-author">AUTEUR</th>
                            <th class="col-desc">DESCRIPTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($books)): ?>
                            <tr>
                                <td colspan="4" class="empty-state">Cet utilisateur n'a pas encore de livres.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($books as $book): ?>
                                <tr>
                                    <td class="col-photo">
                                        <div class="book-img-wrapper">
                                            <?php if (!empty($book->getImage())): ?>
                                                <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>"
                                                    alt="Cover" class="book-cover-img-wrapper">
                                            <?php else: ?>
                                                <img src="<?= BASE_URL ?>assets/images/Book_default.png"
                                                    alt="Cover par défaut" class="book-cover-img-wrapper">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="col-title">
                                        <a href="<?= BASE_URL ?>?controller=book&action=show&id=<?= $book->getId() ?>">
                                            <?= htmlspecialchars($book->getTitle()) ?>
                                        </a>
                                    </td>
                                    <td class="col-author"><?= htmlspecialchars($book->getAuthor()) ?></td>
                                    <td class="col-desc">
                                        <?= htmlspecialchars(mb_substr($book->getDescription(), 0, 150)) ?>...
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
