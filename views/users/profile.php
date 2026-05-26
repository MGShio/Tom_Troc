<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var Book[] $books */
/** @var string|null $error */?>

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
                    <label class="edit-avatar-link" for="avatar-upload" style="cursor: pointer;">Modifier</label>
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
                    <h3 class="form-section-title">Modifier mon profil</h3>
                    <form method="POST" action="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $user->getId() ?>" enctype="multipart/form-data" class="edit-form-container">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        
                        <!-- Champ avatar caché mais dans le formulaire pour la soumission -->
                        <input type="file" id="avatar-upload" name="avatar" accept="image/png, image/jpeg, image/jpg" style="display: none;" onchange="previewAvatar(this);">

                        <?php if (isset($error)): ?>
                            <div class="form-error"><?= $error ?></div>
                        <?php endif; ?>

                        <?php if (isset($_GET['edit_success'])): ?>
                            <div class="form-success">Vos modifications ont été enregistrées !</div>
                        <?php endif; ?>

                        <div class="edit-forms">
                            <label for="pseudo" class="edit-label-blue">Pseudo *</label>
                            <input type="text" id="pseudo" name="pseudo" required value="<?= htmlspecialchars($user->getPseudo()) ?>">
                        </div>

                        <div class="edit-forms">
                            <label for="email" class="edit-label-blue">Email *</label>
                            <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user->getEmail()) ?>">
                        </div>

                        <div class="edit-forms">
                            <label for="password" class="edit-label-blue">Nouveau mot de passe</label>
                            <input type="password" id="password" name="password" autocomplete="new-password">
                        </div>

                        <div class="edit-forms">
                            <label for="password_confirm" class="edit-label-blue">Confirmer le nouveau mot de passe</label>
                            <input type="password" id="password_confirm" name="password_confirm" autocomplete="new-password">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-save-outline">Enregistrer les modifications</button>
                        </div>
                    </form>
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
                                    <br>
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

<script>
function previewAvatar(input) {
    const preview = document.getElementById('avatar-preview');
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
