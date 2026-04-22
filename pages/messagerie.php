<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    header('Location: ' . BASE_URL . 'pages/connexion.php');
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : 'liste';
$user_id = (int)$_SESSION['user_id'];

if ($action === 'nouveau' && isset($_GET['destinataire'], $_GET['livre'])) {
    $destinataire_id = (int)$_GET['destinataire'];
    $livre_id = (int)$_GET['livre'];
    $livre = get_livre($livre_id);
    $destinataire = get_user($destinataire_id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer_message'])) {
    $destinataire_id = (int)$_POST['destinataire_id'];
    $contenu = db_escape($_POST['contenu']);
    envoyer_message($user_id, $destinataire_id, $contenu);
    header('Location: ' . BASE_URL . 'pages/messagerie.php');
    exit;
}

require_once '../includes/header.php';
?>

<section class="section">
    <?php if ($action === 'liste'): ?>
        <h2>Ma messagerie</h2>
        <?php
        $messages = get_messages_utilisateur($user_id);
        $correspondants = [];
        foreach ($messages as $message) {
            $correspondant_id = ($message['expediteur_id'] == $user_id) ? $message['destinataire_id'] : $message['expediteur_id'];
            if (!isset($correspondants[$correspondant_id])) {
                $correspondants[$correspondant_id] = get_user($correspondant_id);
            }
        }
        ?>
        <ul>
            <?php foreach ($correspondants as $correspondant): ?>
                <li>
                    <a href="<?= BASE_URL ?>pages/messagerie.php?action=fil&correspondant=<?= $correspondant['id'] ?>">
                        <?= htmlspecialchars($correspondant['nom']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php elseif ($action === 'fil' && isset($_GET['correspondant'])): ?>
        <h2>Conversation</h2>
        <?php
        $correspondant_id = (int)$_GET['correspondant'];
        $correspondant = get_user($correspondant_id);
        $messages = get_fil_discussion($user_id, $correspondant_id);
        ?>
        <h3>Discussion avec <?= htmlspecialchars($correspondant['nom']) ?></h3>
        <div style="border: 1px solid #ddd; padding: 1rem; margin-bottom: 1rem; max-height: 400px; overflow-y: auto;">
            <?php foreach ($messages as $message): ?>
                <div style="margin-bottom: 1rem; text-align: <?= ($message['expediteur_id'] == $user_id) ? 'right' : 'left' ?>">
                    <p><strong><?= htmlspecialchars(get_user($message['expediteur_id'])['nom']) ?>:</strong></p>
                    <p><?= nl2br(htmlspecialchars($message['contenu'])) ?></p>
                    <p><small><?= $message['date_envoi'] ?></small></p>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="post">
            <input type="hidden" name="destinataire_id" value="<?= $correspondant_id ?>">
            <textarea name="contenu" rows="4" style="width: 100%; margin-bottom: 1rem;" required></textarea>
            <button type="submit" name="envoyer_message" class="btn">Envoyer</button>
        </form>

    <?php elseif ($action === 'nouveau' && isset($destinataire, $livre)): ?>
        <h2>Nouveau message à <?= htmlspecialchars($destinataire['nom']) ?></h2>
        <p>À propos du livre : <strong><?= htmlspecialchars($livre['titre']) ?></strong></p>
        <form method="post">
            <input type="hidden" name="destinataire_id" value="<?= $destinataire['id'] ?>">
            <textarea name="contenu" rows="4" style="width: 100%; margin-bottom: 1rem;" required></textarea>
            <button type="submit" name="envoyer_message" class="btn">Envoyer</button>
        </form>
    <?php endif; ?>
</section>

<?php
require_once '../includes/footer.php';
?>