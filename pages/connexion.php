<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';

if (is_logged_in()) {
    header('Location: ' . BASE_URL . 'pages/bibliotheque.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (login($email, $password)) {
        header('Location: ' . BASE_URL . 'pages/bibliotheque.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

require_once '../includes/header.php';
?>

<section class="section">
    <h2>Connexion</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="post">
        <div>
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Se connecter</button>
    </form>
</section>

<?php
require_once '../includes/footer.php';
?>