<?php /** @var Utilisateur $user */
/** @var string $error */ ?>
<section class="section">
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($user->getNom()) ?>" required>

        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>

        <label for="password">Nouveau mot de passe (laisser vide pour ne pas changer) :</label>
        <input type="password" id="password" name="password">

        <button type="submit">Mettre à jour</button>
    </form>
</section>