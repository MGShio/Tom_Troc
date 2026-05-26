<?php require_once __DIR__ . '/../../includes/header.php'; /** @var Book $book */ ?>

<main class="edit-book-page-wrapper">
    <div class="edit-book-container">
        <div class="edit-back-navigation">
            <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="edit-btn-back">
                <img src="<?= BASE_URL ?>assets/images/line4.svg" alt="Retour" class="edit-back-icon">
                Retour
            </a>
        </div>

        <h1 class="edit-main-title">Éditer le livre : <?= htmlspecialchars($book->getTitle()) ?></h1>

        <div class="edit-card-white">
            <form method="POST" action="<?= BASE_URL ?>?controller=book&action=edit&id=<?= $book->getId() ?>" enctype="multipart/form-data">
                <div class="edit-columns-flex">
                    <div class="edit-col-left-photo">
                        <label class="edit-label-light">Image du livre</label>
                        <div class="edit-image-frame">
                            <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage() ?: 'Book_default.png') ?>" alt="Aperçu" id="image-preview">
                        </div>
                        <label class="edit-link-modify-photo" for="image">Modifier</label>
                        <input type="file" id="image" name="image" accept="image/png,image/jpeg,image/jpg" class="hidden-input" onchange="previewImage(this);">
                        <small class="edit-image-hint">Format : PNG, JPG, JPEG. Max 2Mo. Laissez vide pour conserver l'image actuelle.</small>
                    </div>

                    <div class="edit-col-right-inputs">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                        <?php if (isset($error)): ?>
                            <div class="form-error">Veuillez remplir tous les champs obligatoires.</div>
                        <?php endif; ?>

                        <div class="edit-form-group">
                            <label class="edit-label-light" for="title">Titre *</label>
                            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($book->getTitle()) ?>">
                        </div>

                        <div class="edit-form-group">
                            <label class="edit-label-light" for="author">Auteur *</label>
                            <input type="text" id="author" name="author" required value="<?= htmlspecialchars($book->getAuthor()) ?>">
                        </div>

                        <div class="edit-form-group">
                            <label class="edit-label-light" for="description">Description *</label>
                            <textarea id="description" name="description" required><?= htmlspecialchars($book->getDescription()) ?></textarea>
                        </div>

                        <div class="edit-form-group">
                            <label class="edit-label-light" for="disponibilite">Disponibilité</label>
                            <select id="disponibilite" name="disponibilite">
                                <option value="disponible" <?= ($book->getDisponibilite() === 'disponible' || $book->getStatut() === 'disponible') ? 'selected' : '' ?>>Disponible</option>
                                <option value="non disponible" <?= ($book->getDisponibilite() === 'non disponible' || $book->getStatut() === 'non disponible') ? 'selected' : '' ?>>Non disponible</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="edit-btn-validate">Mettre à jour</button>
                            <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="edit-btn-cancel">Annuler</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function previewImage(input) {
    const preview = document.getElementById('image-preview');
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