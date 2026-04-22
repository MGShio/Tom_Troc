<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1366, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="<?= BASE_URL ?>assets/images/logo.png" alt="Logo Tom Troc">
            <?= SITE_NAME ?>
        </div>
        <nav>
            <ul>
                <li><a href="<?= BASE_URL ?>">Accueil</a></li>
                <li><a href="<?= BASE_URL ?>livres.php">Nos livres à échanger</a></li>
                <li><a href="#">Messagerie</a></li>
                <li><a href="#">Mon compte</a></li>
                <li><a href="#">Connexion</a></li>
            </ul>
        </nav>
    </header>