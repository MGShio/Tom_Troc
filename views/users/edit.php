<?php require_once __DIR__ . '/../../includes/header.php'; /** @var User $user */
/** @var string $error */ ?>
<section class="section">
    <div class="form-card">
        <h2>Modifier mon profil</h2>
        <?php if (isset($error)): ?>
            <div class="form-error"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>?controller=user&action=edit&id=<?= $user->getId() ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->getName()) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Nouveau mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Laisser vide pour ne pas changer">
            </div>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>