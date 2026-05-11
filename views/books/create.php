<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <div class="form-card">
        <h2>Ajouter un livre à ma bibliothèque</h2>
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>?controller=book&action=create" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" required maxlength="255">
            </div>
            <div class="form-group">
                <label for="author">Auteur</label>
                <input type="text" id="author" name="author" value="<?= isset($author) ? htmlspecialchars($author) : '' ?>" required maxlength="255">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" maxlength="1000"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" required>
                    <option value="disponible" <?= isset($statut) && $statut === 'disponible' ? 'selected' : '' ?>>Disponible à l'échange</option>
                    <option value="indisponible" <?= isset($statut) && $statut === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                    <option value="echange" <?= isset($statut) && $statut === 'echange' ? 'selected' : '' ?>>En cours d'échange</option>
                </select>
            </div>
            <div class="form-group">
                <label for="image">Image du livre</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le livre</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>