<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <div class="form-card">
        <h2>Inscription</h2>
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>?controller=user&action=register">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p style="margin-top: var(--spacing-md);">Déjà un compte ? <a href="<?= BASE_URL ?>?controller=user&action=login">Se connecter</a></p>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>