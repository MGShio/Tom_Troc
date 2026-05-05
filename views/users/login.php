<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <h2>Connexion</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>?controller=user&action=login">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas de compte ? <a href="<?= BASE_URL ?>?controller=user&action=register">S'inscrire</a></p>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>