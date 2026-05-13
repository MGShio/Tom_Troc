<?php require_once __DIR__ . '/../../includes/header.php'; ?>

<div class="auth">
    <div class="auth-left">
        <h1 class="sign-title">Connexion</h1>
        
        <?php if (isset($error)): ?>
            <div class="field">
                <p style="color: #dc3545; margin: 10px 0;"><?= $error ?></p>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?= BASE_URL ?>?controller=user&action=login">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            </div>
            
            <div class="field">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="sign-submit-btn">Se connecter</button>
        </form>
        
        <p class="sign-ask">Pas encore de compte ? <a href="<?= BASE_URL ?>?controller=user&action=register">S'inscrire</a></p>
    </div>
    <div class="auth-img">
        <img src="<?= BASE_URL ?>assets/images/hamza-nouasria-KXrvPthkmYQ-unsplash%201.png" alt="Livres">
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
