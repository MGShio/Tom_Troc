<section class="section">
    <h2>Connexion</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="?controller=user&action=login">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas de compte ? <a href="?controller=user&action=register">S'inscrire</a></p>
</section>