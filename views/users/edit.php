<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */ ?>

<main class="form-page">
    <div class="container">
        <h1 class="page-title">Modifier mon profil</h1>
        
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>" enctype="multipart/form-data" class="form-container">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="profile-edit-avatar">
                <div class="avatar-preview">
                    <img src="<?= BASE_URL ?>assets/images/<?= htmlspecialchars($user->getAvatar() ?: 'Avatar_default.png') ?>" alt="Avatar actuel" id="avatar-preview-edit">
                </div>
                <div class="avatar-upload">
                    <label for="avatar">Changer d'avatar</label>
                    <input type="file" id="avatar" name="avatar" accept="image/png, image/jpeg, image/jpg">
                    <small>Format : PNG, JPG, JPEG. Max 2Mo.</small>
                </div>
            </div>

            <div class="form-group">
                <label for="pseudo">Pseudo *</label>
                <input type="text" id="pseudo" name="pseudo" required value="<?= htmlspecialchars($user->getPseudo()) ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user->getEmail()) ?>">
            </div>

            <div class="form-group">
                <label for="password">Nouveau mot de passe (laisser vide pour conserver l'actuel)</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirmer le nouveau mot de passe</label>
                <input type="password" id="password_confirm" name="password_confirm">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $user->getId() ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
