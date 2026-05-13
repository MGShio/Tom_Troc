<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<main class="form-page">
    <div class="container">
        <h1 class="page-title">Ajouter un livre</h1>
        
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?controller=book&action=create" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" required value="<?= isset($title) ? htmlspecialchars($title) : '' ?>">
            </div>

            <div class="form-group">
                <label for="author">Auteur *</label>
                <input type="text" id="author" name="author" required value="<?= isset($author) ? htmlspecialchars($author) : '' ?>">
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" required><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="disponibilite">Disponibilité</label>
                <select id="disponibilite" name="disponibilite">
                    <option value="disponible" <?= (isset($disponibilite) && $disponibilite === 'disponible') ? 'selected' : '' ?>>Disponible</option>
                    <option value="non disponible" <?= (isset($disponibilite) && $disponibilite === 'non disponible') ? 'selected' : '' ?>>Non disponible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image du livre</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg">
                <small>Format : PNG, JPG, JPEG. Max 2Mo.</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter le livre</button>
                <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
