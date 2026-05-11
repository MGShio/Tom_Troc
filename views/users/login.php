<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <div class="form-card">
        <h2>Connexion</h2>
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>?controller=user&action=login">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p style="margin-top: var(--spacing-md);">Pas de compte ? <a href="<?= BASE_URL ?>?controller=user&action=register">S'inscrire</a></p>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>