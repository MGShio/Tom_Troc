<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <h2>Ajouter un livre à ma bibliothèque</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>?controller=livre&action=create" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" value="<?= isset($titre) ? htmlspecialchars($titre) : '' ?>" required maxlength="255">
        
        <label for="auteur">Auteur :</label>
        <input type="text" id="auteur" name="auteur" value="<?= isset($auteur) ? htmlspecialchars($auteur) : '' ?>" required maxlength="255">
        
        <label for="description">Description :</label>
        <textarea id="description" name="description" maxlength="1000"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
        
        <label for="statut">Statut :</label>
        <select id="statut" name="statut" required>
            <option value="disponible" <?= isset($statut) && $statut === 'disponible' ? 'selected' : '' ?>>Disponible à l'échange</option>
            <option value="indisponible" <?= isset($statut) && $statut === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
            <option value="echange" <?= isset($statut) && $statut === 'echange' ? 'selected' : '' ?>>En cours d'échange</option>
        </select>
        
        <label for="image">Image du livre :</label>
        <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/gif">
        
        <button type="submit">Ajouter le livre</button>
    </form>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>