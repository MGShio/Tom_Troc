<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<section class="section">
    <h2>Inscription</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>?controller=user&action=register">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <label for="name">Nom :</label>
        <input type="text" id="name" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="<?= BASE_URL ?>?controller=user&action=login">Se connecter</a></p>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>