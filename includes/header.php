<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1366, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Logo Tom Troc">
            <?= SITE_NAME ?>
        </div>
        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>?controller=home">Accueil</a></li>
                <li><a href="<?= BASE_URL ?>?controller=livre&action=index">Nos livres à échanger</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?= BASE_URL ?>?controller=message&action=index">Messagerie</a></li>
                    <li><a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>">Mon compte</a></li>
                    <li><a href="<?= BASE_URL ?>?controller=user&action=logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>?controller=user&action=login">Connexion</a></li>
                    <li><a href="<?= BASE_URL ?>?controller=user&action=register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>