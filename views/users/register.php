<section class="section">
    <h2>Inscription</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>?controller=user&action=register">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà un compte ? <a href="<?= BASE_URL ?>?controller=user&action=login">Se connecter</a></p>
</section>