<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<main class="edit-book-page-wrapper">
    <div class="edit-book-container">
        <div class="edit-back-navigation">
            <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="edit-btn-back">
                <img src="<?= BASE_URL ?>assets/images/line4.svg" alt="Retour" class="edit-back-icon">
                Retour
            </a>
        </div>
        
        <h1 class="edit-main-title">Ajouter un livre</h1>
        
        <div class="edit-card-white">
            <div class="edit-columns-flex">
                <!-- Colonne gauche - Image -->
                <div class="edit-col-left-photo">
                    <label class="edit-label-light">Image du livre</label>
                    <div class="edit-image-frame">
                        <img src="<?= BASE_URL ?>assets/images/Book_default.png" alt="Aperçu" id="image-preview">
                    </div>
                    <label class="edit-link-modify-photo" for="image">Modifier</label>
                    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg" class="hidden-input" onchange="previewImage(this);">
                    <small class="edit-image-hint">Format : PNG, JPG, JPEG. Max 2Mo.</small>
                </div>
                
                <!-- Colonne droite - Champs -->
                <div class="edit-col-right-inputs">
                    <form method="POST" action="<?= BASE_URL ?>?controller=book&action=create" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                        
                        <?php if (isset($error)): ?>
                            <div class="form-error"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <div class="edit-form-group">
                            <label class="edit-label-light" for="title">Titre *</label>
                            <input type="text" id="title" name="title" required value="<?= isset($title) ? htmlspecialchars($title) : '' ?>">
                        </div>
                        
                        <div class="edit-form-group">
                            <label class="edit-label-light" for="author">Auteur *</label>
                            <input type="text" id="author" name="author" required value="<?= isset($author) ? htmlspecialchars($author) : '' ?>">
                        </div>
                        
                        <div class="edit-form-group">
                            <label class="edit-label-light" for="description">Description *</label>
                            <textarea id="description" name="description" required><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>
                        
                        <div class="edit-form-group">
                            <label class="edit-label-light" for="disponibilite">Disponibilité</label>
                            <select id="disponibilite" name="disponibilite">
                                <option value="disponible" <?= (isset($disponibilite) && $disponibilite === 'disponible') ? 'selected' : '' ?>>Disponible</option>
                                <option value="non disponible" <?= (isset($disponibilite) && $disponibilite === 'non disponible') ? 'selected' : '' ?>>Non disponible</option>
                            </select>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="edit-btn-validate">Ajouter le livre</button>
                            <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="edit-btn-cancel">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
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
