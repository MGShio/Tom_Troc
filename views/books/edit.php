<?php require_once __DIR__ . '/../../includes/header.php'; /** @var Book $book */ ?>

<main class="form-page">
    <div class="container">
        <h1 class="page-title">Éditer le livre : <?= htmlspecialchars($book->getTitle()) ?></h1>
        
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?controller=book&action=edit&id=<?= $book->getId() ?>" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-group">
                <label for="title">Titre *</label>
                <input type="text" id="title" name="title" required value="<?= htmlspecialchars($book->getTitle()) ?>">
            </div>

            <div class="form-group">
                <label for="author">Auteur *</label>
                <input type="text" id="author" name="author" required value="<?= htmlspecialchars($book->getAuthor()) ?>">
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" required><?= htmlspecialchars($book->getDescription()) ?></textarea>
            </div>

            <div class="form-group">
                <label for="disponibilite">Disponibilité</label>
                <select id="disponibilite" name="disponibilite">
                    <option value="disponible" <?= ($book->getDisponibilite() === 'disponible' || $book->getStatut() === 'disponible') ? 'selected' : '' ?>>Disponible</option>
                    <option value="non disponible" <?= ($book->getDisponibilite() === 'non disponible' || $book->getStatut() === 'non disponible') ? 'selected' : '' ?>>Non disponible</option>
                </select>
            </div>

            <div class="form-group">
                <label for="image">Image du livre (actuelle : <?= htmlspecialchars($book->getImage() ?: 'Aucune') ?>)</label>
                <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/jpg">
                <small>Format : PNG, JPG, JPEG. Max 2Mo. Laissez vide pour conserver l'image actuelle.</small>
                <?php if ($book->getImage() && $book->getImage() !== 'Book_default.png'): ?>
                    <div class="current-image-preview">
                        <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($book->getImage()) ?>" alt="Image actuelle" class="img-thumbnail">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
