<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>

    <!-- Chargement des polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/header.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
</head>

<body>
    <header class="site-header">
        <div class="header-inner">
            <nav class="nav">
                <div class="nav-left">
                    <a href="<?= BASE_URL ?>?controller=home" class="logo">
                        <img src="<?= BASE_URL ?>assets/images/logo.svg" alt="Logo Tom Troc">
                    </a>
                    <a href="<?= BASE_URL ?>?controller=home">Accueil</a>
                    <a href="<?= BASE_URL ?>?controller=book&action=index">Nos livres à échanger</a>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <img src="<?= BASE_URL ?>assets/images/line4.svg" alt="séparateur" class="separator">
                    <div class="nav-right">
                        <div class="nav-item">
                            <img src="<?= BASE_URL ?>assets/images/Icon messagerie.svg" alt="Messagerie">
                            <a href="<?= BASE_URL ?>?controller=message&action=index">Messagerie</a>
                            <?php if (isset($_SESSION['unread_count']) && $_SESSION['unread_count'] > 0): ?>
                                <div class="badge-wrapper">
                                    <span class="badge-number"><?= htmlspecialchars($_SESSION['unread_count']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="nav-item">
                            <img src="<?= BASE_URL ?>assets/images/Icon mon compte.svg" alt="Mon compte">
                            <a href="<?= BASE_URL ?>?controller=user&action=profile&id=<?= $_SESSION['user_id'] ?>">Mon compte</a>
                        </div>
                        <a href="<?= BASE_URL ?>?controller=user&action=logout" class="connexion">Déconnexion</a>
                    </div>
                <?php else: ?>
                    <div class="nav-right push-right">
                        <a href="<?= BASE_URL ?>?controller=user&action=login" class="connexion">Connexion</a>
                        <a href="<?= BASE_URL ?>?controller=user&action=register" class="connexion">Inscription</a>
                    </div>
                <?php endif; ?>
            </nav>
        </div>
    </header>
